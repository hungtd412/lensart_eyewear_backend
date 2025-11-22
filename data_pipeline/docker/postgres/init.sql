-- ============================================
-- LensArt Data Pipeline - PostgreSQL Schema
-- Database: lensart_events
-- ============================================

\c lensart_events;

-- Drop tables if exist (for re-initialization)
DROP TABLE IF EXISTS order_items_analytics CASCADE;
DROP TABLE IF EXISTS order_metrics CASCADE;
DROP TABLE IF EXISTS order_status_history CASCADE;
DROP TABLE IF EXISTS orders_processed CASCADE;
DROP TABLE IF EXISTS orders_raw CASCADE;

-- ============================================
-- Table: orders_raw
-- Purpose: Store raw events from Kafka
-- ============================================
CREATE TABLE orders_raw (
    id SERIAL PRIMARY KEY,
    event_id VARCHAR(100) UNIQUE NOT NULL,
    event_type VARCHAR(50) NOT NULL,
    order_id INTEGER NOT NULL,
    user_id INTEGER,
    branch_id INTEGER,
    total_price DECIMAL(15, 2),
    order_status VARCHAR(50),
    payment_status VARCHAR(50),
    payment_method VARCHAR(50),
    event_data JSONB NOT NULL,
    event_timestamp TIMESTAMP NOT NULL,
    processed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Indexes for performance
    CONSTRAINT orders_raw_event_id_unique UNIQUE (event_id)
);

CREATE INDEX idx_orders_raw_order_id ON orders_raw(order_id);
CREATE INDEX idx_orders_raw_event_type ON orders_raw(event_type);
CREATE INDEX idx_orders_raw_event_timestamp ON orders_raw(event_timestamp);
CREATE INDEX idx_orders_raw_processed_at ON orders_raw(processed_at);
CREATE INDEX idx_orders_raw_event_data ON orders_raw USING GIN (event_data);

COMMENT ON TABLE orders_raw IS 'Raw events from Kafka - immutable log';
COMMENT ON COLUMN orders_raw.event_data IS 'Full JSON payload from Kafka event';

-- ============================================
-- Table: orders_processed
-- Purpose: Cleaned and processed order data
-- ============================================
CREATE TABLE orders_processed (
    id SERIAL PRIMARY KEY,
    order_id INTEGER UNIQUE NOT NULL,
    user_id INTEGER,
    user_name VARCHAR(255),
    user_email VARCHAR(255),
    branch_id INTEGER,
    branch_name VARCHAR(255),
    branch_address TEXT,
    order_date TIMESTAMP NOT NULL,
    address TEXT,
    note TEXT,
    coupon_id INTEGER,
    total_price DECIMAL(15, 2) NOT NULL,
    order_status VARCHAR(50) NOT NULL,
    payment_status VARCHAR(50) NOT NULL,
    payment_method VARCHAR(50),
    items_count INTEGER DEFAULT 0,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Constraints
    CONSTRAINT orders_processed_order_id_unique UNIQUE (order_id),
    CONSTRAINT orders_processed_total_price_positive CHECK (total_price >= 0),
    CONSTRAINT orders_processed_items_count_positive CHECK (items_count >= 0)
);

CREATE INDEX idx_orders_processed_user_id ON orders_processed(user_id);
CREATE INDEX idx_orders_processed_branch_id ON orders_processed(branch_id);
CREATE INDEX idx_orders_processed_order_status ON orders_processed(order_status);
CREATE INDEX idx_orders_processed_payment_status ON orders_processed(payment_status);
CREATE INDEX idx_orders_processed_order_date ON orders_processed(order_date);
CREATE INDEX idx_orders_processed_updated_at ON orders_processed(updated_at);

COMMENT ON TABLE orders_processed IS 'Processed and enriched order data';

-- ============================================
-- Table: order_status_history
-- Purpose: Track order status changes over time
-- ============================================
CREATE TABLE order_status_history (
    id SERIAL PRIMARY KEY,
    order_id INTEGER NOT NULL,
    old_status VARCHAR(50),
    new_status VARCHAR(50) NOT NULL,
    changed_at TIMESTAMP NOT NULL,
    processing_time_seconds INTEGER,
    event_id VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Foreign key to orders_processed
    CONSTRAINT fk_order_status_history_order 
        FOREIGN KEY (order_id) 
        REFERENCES orders_processed(order_id) 
        ON DELETE CASCADE
);

CREATE INDEX idx_order_status_history_order_id ON order_status_history(order_id);
CREATE INDEX idx_order_status_history_changed_at ON order_status_history(changed_at);
CREATE INDEX idx_order_status_history_new_status ON order_status_history(new_status);

COMMENT ON TABLE order_status_history IS 'History of order status transitions';
COMMENT ON COLUMN order_status_history.processing_time_seconds IS 'Time between status changes in seconds';

-- ============================================
-- Table: order_metrics
-- Purpose: Real-time aggregated metrics
-- ============================================
CREATE TABLE order_metrics (
    id SERIAL PRIMARY KEY,
    metric_date DATE NOT NULL,
    metric_hour INTEGER,
    branch_id INTEGER,
    total_orders INTEGER DEFAULT 0,
    total_revenue DECIMAL(15, 2) DEFAULT 0,
    avg_order_value DECIMAL(15, 2),
    pending_orders INTEGER DEFAULT 0,
    processing_orders INTEGER DEFAULT 0,
    completed_orders INTEGER DEFAULT 0,
    cancelled_orders INTEGER DEFAULT 0,
    cash_orders INTEGER DEFAULT 0,
    online_orders INTEGER DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Unique constraint for time-based aggregation
    CONSTRAINT order_metrics_unique_key 
        UNIQUE(metric_date, metric_hour, branch_id),
    
    -- Check constraints
    CONSTRAINT order_metrics_counts_positive CHECK (
        total_orders >= 0 AND
        pending_orders >= 0 AND
        processing_orders >= 0 AND
        completed_orders >= 0 AND
        cancelled_orders >= 0
    ),
    CONSTRAINT order_metrics_revenue_positive CHECK (total_revenue >= 0)
);

CREATE INDEX idx_order_metrics_date ON order_metrics(metric_date);
CREATE INDEX idx_order_metrics_branch ON order_metrics(branch_id);
CREATE INDEX idx_order_metrics_updated ON order_metrics(updated_at);
CREATE INDEX idx_order_metrics_date_hour ON order_metrics(metric_date, metric_hour);

COMMENT ON TABLE order_metrics IS 'Aggregated metrics by date, hour, and branch';
COMMENT ON COLUMN order_metrics.metric_hour IS 'Hour of day (0-23), NULL for daily aggregates';

-- ============================================
-- Table: order_items_analytics
-- Purpose: Product-level analytics
-- ============================================
CREATE TABLE order_items_analytics (
    id SERIAL PRIMARY KEY,
    order_id INTEGER NOT NULL,
    product_id INTEGER NOT NULL,
    product_name VARCHAR(255),
    color VARCHAR(50),
    quantity INTEGER NOT NULL,
    unit_price DECIMAL(15, 2),
    total_price DECIMAL(15, 2) NOT NULL,
    order_date DATE NOT NULL,
    branch_id INTEGER,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Foreign key
    CONSTRAINT fk_order_items_order 
        FOREIGN KEY (order_id) 
        REFERENCES orders_processed(order_id) 
        ON DELETE CASCADE,
    
    -- Check constraints
    CONSTRAINT order_items_quantity_positive CHECK (quantity > 0),
    CONSTRAINT order_items_prices_positive CHECK (
        unit_price >= 0 AND total_price >= 0
    )
);

CREATE INDEX idx_order_items_order_id ON order_items_analytics(order_id);
CREATE INDEX idx_order_items_product_id ON order_items_analytics(product_id);
CREATE INDEX idx_order_items_order_date ON order_items_analytics(order_date);
CREATE INDEX idx_order_items_branch_id ON order_items_analytics(branch_id);

COMMENT ON TABLE order_items_analytics IS 'Individual order items for product analytics';

-- ============================================
-- Views for Common Queries
-- ============================================

-- View: Daily order summary
CREATE OR REPLACE VIEW v_daily_order_summary AS
SELECT 
    metric_date,
    SUM(total_orders) as total_orders,
    SUM(total_revenue) as total_revenue,
    AVG(avg_order_value) as avg_order_value,
    SUM(completed_orders) as completed_orders,
    SUM(cancelled_orders) as cancelled_orders
FROM order_metrics
WHERE metric_hour IS NOT NULL
GROUP BY metric_date
ORDER BY metric_date DESC;

-- View: Branch performance
CREATE OR REPLACE VIEW v_branch_performance AS
SELECT 
    branch_id,
    COUNT(DISTINCT order_id) as total_orders,
    SUM(total_price) as total_revenue,
    AVG(total_price) as avg_order_value,
    COUNT(CASE WHEN order_status = 'Đã giao' THEN 1 END) as completed_orders,
    COUNT(CASE WHEN order_status = 'Đã hủy' THEN 1 END) as cancelled_orders
FROM orders_processed
GROUP BY branch_id
ORDER BY total_revenue DESC;

-- View: Product popularity
CREATE OR REPLACE VIEW v_product_popularity AS
SELECT 
    product_id,
    product_name,
    COUNT(DISTINCT order_id) as order_count,
    SUM(quantity) as total_quantity_sold,
    SUM(total_price) as total_revenue,
    AVG(unit_price) as avg_price
FROM order_items_analytics
GROUP BY product_id, product_name
ORDER BY total_quantity_sold DESC;

-- View: Recent orders with details
CREATE OR REPLACE VIEW v_recent_orders AS
SELECT 
    op.order_id,
    op.user_name,
    op.branch_name,
    op.order_date,
    op.total_price,
    op.order_status,
    op.payment_status,
    op.payment_method,
    op.items_count
FROM orders_processed op
ORDER BY op.order_date DESC
LIMIT 100;

-- ============================================
-- Functions
-- ============================================

-- Function: Update order metrics (upsert)
CREATE OR REPLACE FUNCTION upsert_order_metrics(
    p_metric_date DATE,
    p_metric_hour INTEGER,
    p_branch_id INTEGER,
    p_order_revenue DECIMAL(15, 2),
    p_order_status VARCHAR(50),
    p_payment_method VARCHAR(50)
)
RETURNS VOID AS $$
BEGIN
    INSERT INTO order_metrics (
        metric_date, 
        metric_hour, 
        branch_id, 
        total_orders, 
        total_revenue,
        pending_orders,
        processing_orders,
        completed_orders,
        cancelled_orders,
        cash_orders,
        online_orders
    )
    VALUES (
        p_metric_date,
        p_metric_hour,
        p_branch_id,
        1,
        p_order_revenue,
        CASE WHEN p_order_status = 'Đang xử lý' THEN 1 ELSE 0 END,
        CASE WHEN p_order_status IN ('Đã xử lý và sẵn sàng giao hàng', 'Đang giao hàng') THEN 1 ELSE 0 END,
        CASE WHEN p_order_status = 'Đã giao' THEN 1 ELSE 0 END,
        CASE WHEN p_order_status = 'Đã hủy' THEN 1 ELSE 0 END,
        CASE WHEN p_payment_method = 'cash' THEN 1 ELSE 0 END,
        CASE WHEN p_payment_method = 'payos' THEN 1 ELSE 0 END
    )
    ON CONFLICT (metric_date, metric_hour, branch_id)
    DO UPDATE SET
        total_orders = order_metrics.total_orders + 1,
        total_revenue = order_metrics.total_revenue + p_order_revenue,
        avg_order_value = (order_metrics.total_revenue + p_order_revenue) / (order_metrics.total_orders + 1),
        pending_orders = order_metrics.pending_orders + CASE WHEN p_order_status = 'Đang xử lý' THEN 1 ELSE 0 END,
        processing_orders = order_metrics.processing_orders + CASE WHEN p_order_status IN ('Đã xử lý và sẵn sàng giao hàng', 'Đang giao hàng') THEN 1 ELSE 0 END,
        completed_orders = order_metrics.completed_orders + CASE WHEN p_order_status = 'Đã giao' THEN 1 ELSE 0 END,
        cancelled_orders = order_metrics.cancelled_orders + CASE WHEN p_order_status = 'Đã hủy' THEN 1 ELSE 0 END,
        cash_orders = order_metrics.cash_orders + CASE WHEN p_payment_method = 'cash' THEN 1 ELSE 0 END,
        online_orders = order_metrics.online_orders + CASE WHEN p_payment_method = 'payos' THEN 1 ELSE 0 END,
        updated_at = CURRENT_TIMESTAMP;
END;
$$ LANGUAGE plpgsql;

-- ============================================
-- Sample Data (Optional - for testing)
-- ============================================

-- You can uncomment below to insert sample data for testing
/*
INSERT INTO orders_raw (event_id, event_type, order_id, user_id, branch_id, total_price, order_status, payment_status, payment_method, event_data, event_timestamp) VALUES
('evt_sample_1', 'order.created', 1, 1, 1, 1500000, 'Đang xử lý', 'Đã thanh toán', 'payos', '{"sample": "data"}'::jsonb, NOW());

INSERT INTO orders_processed (order_id, user_id, user_name, user_email, branch_id, branch_name, order_date, address, total_price, order_status, payment_status, payment_method, items_count, created_at) VALUES
(1, 1, 'Test User', 'test@example.com', 1, 'LensArt Q1', NOW(), '123 Test Street', 1500000, 'Đang xử lý', 'Đã thanh toán', 'payos', 1, NOW());
*/

-- ============================================
-- Grants (if needed for specific users)
-- ============================================

-- GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO flink_user;
-- GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO flink_user;

-- ============================================
-- Database Info
-- ============================================

SELECT 'LensArt Data Pipeline Database Initialized Successfully!' as status;

-- Show tables
\dt

-- Show views
\dv

-- Show functions
\df

COMMIT;

