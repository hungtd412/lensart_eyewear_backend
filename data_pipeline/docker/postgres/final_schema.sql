-- ============================================
-- Final Simplified Schema - Only 2 Tables
-- ============================================

\c lensart_events;

-- Drop all old tables
DROP TABLE IF EXISTS orders_raw CASCADE;
DROP TABLE IF EXISTS orders_processed CASCADE;
DROP TABLE IF EXISTS order_status_history CASCADE;
DROP TABLE IF EXISTS order_items_analytics CASCADE;
DROP TABLE IF EXISTS order_metrics CASCADE;
DROP TABLE IF EXISTS store_sales CASCADE;
DROP TABLE IF EXISTS total_revenue CASCADE;

-- Drop all views
DROP VIEW IF EXISTS v_daily_order_summary CASCADE;
DROP VIEW IF EXISTS v_branch_performance CASCADE;
DROP VIEW IF EXISTS v_product_popularity CASCADE;
DROP VIEW IF EXISTS v_recent_orders CASCADE;
DROP VIEW IF EXISTS v_daily_revenue CASCADE;
DROP VIEW IF EXISTS v_today_hourly_revenue CASCADE;
DROP VIEW IF EXISTS v_top_products CASCADE;

-- ============================================
-- Table 1: sales_transactions
-- Purpose: Lưu raw events từ Kafka
-- ============================================
-- Keep existing table, just ensure it exists

CREATE TABLE IF NOT EXISTS sales_transactions (
    id SERIAL PRIMARY KEY,
    order_id INTEGER NOT NULL,
    product_id INTEGER NOT NULL,
    quantity INTEGER NOT NULL,
    price DECIMAL(15, 2) NOT NULL,
    timestamp TIMESTAMP NOT NULL,
    customer_id INTEGER NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT sales_transactions_quantity_positive CHECK (quantity > 0),
    CONSTRAINT sales_transactions_price_positive CHECK (price >= 0)
);

CREATE INDEX IF NOT EXISTS idx_sales_transactions_order_id ON sales_transactions(order_id);
CREATE INDEX IF NOT EXISTS idx_sales_transactions_product_id ON sales_transactions(product_id);
CREATE INDEX IF NOT EXISTS idx_sales_transactions_customer_id ON sales_transactions(customer_id);
CREATE INDEX IF NOT EXISTS idx_sales_transactions_timestamp ON sales_transactions(timestamp);
CREATE INDEX IF NOT EXISTS idx_sales_transactions_created_at ON sales_transactions(created_at);

COMMENT ON TABLE sales_transactions IS 'Raw sales transaction events from Kafka - 1 row per product per order';

-- ============================================
-- Table 2: product_sales
-- Purpose: Thống kê tổng quantity và revenue theo product
-- ============================================
DROP TABLE IF EXISTS product_sales CASCADE;

CREATE TABLE product_sales (
    id SERIAL PRIMARY KEY,
    product_id INTEGER UNIQUE NOT NULL,
    total_quantity INTEGER DEFAULT 0,
    total_revenue DECIMAL(15, 2) DEFAULT 0,
    transaction_count INTEGER DEFAULT 0,  -- Số lần xuất hiện
    last_sale_date TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT product_sales_product_id_unique UNIQUE(product_id),
    CONSTRAINT product_sales_quantity_positive CHECK (total_quantity >= 0),
    CONSTRAINT product_sales_revenue_positive CHECK (total_revenue >= 0)
);

CREATE INDEX idx_product_sales_product_id ON product_sales(product_id);
CREATE INDEX idx_product_sales_revenue ON product_sales(total_revenue DESC);
CREATE INDEX idx_product_sales_quantity ON product_sales(total_quantity DESC);
CREATE INDEX idx_product_sales_updated ON product_sales(updated_at);

COMMENT ON TABLE product_sales IS 'Aggregated product sales: total quantity and revenue per product';
COMMENT ON COLUMN product_sales.total_quantity IS 'Tổng số lượng sản phẩm đã bán';
COMMENT ON COLUMN product_sales.total_revenue IS 'Tổng doanh thu từ sản phẩm này';
COMMENT ON COLUMN product_sales.transaction_count IS 'Số lần sản phẩm xuất hiện trong giao dịch';

-- ============================================
-- Views for Easy Querying
-- ============================================

-- Top selling products by quantity
CREATE OR REPLACE VIEW v_top_products_by_quantity AS
SELECT 
    product_id,
    total_quantity,
    total_revenue,
    transaction_count,
    CASE 
        WHEN transaction_count > 0 THEN total_quantity::decimal / transaction_count 
        ELSE 0 
    END as avg_quantity_per_transaction,
    last_sale_date,
    updated_at
FROM product_sales
ORDER BY total_quantity DESC;

-- Top selling products by revenue
CREATE OR REPLACE VIEW v_top_products_by_revenue AS
SELECT 
    product_id,
    total_revenue,
    total_quantity,
    transaction_count,
    CASE 
        WHEN total_quantity > 0 THEN total_revenue / total_quantity 
        ELSE 0 
    END as avg_price_per_unit,
    last_sale_date,
    updated_at
FROM product_sales
ORDER BY total_revenue DESC;

-- Sales summary
CREATE OR REPLACE VIEW v_sales_summary AS
SELECT 
    COUNT(*) as total_products,
    SUM(total_quantity) as total_items_sold,
    SUM(total_revenue) as total_revenue,
    AVG(total_revenue) as avg_revenue_per_product,
    MAX(total_revenue) as highest_product_revenue,
    MIN(total_revenue) as lowest_product_revenue
FROM product_sales;

-- Recent transactions
CREATE OR REPLACE VIEW v_recent_transactions AS
SELECT 
    id,
    order_id,
    product_id,
    quantity,
    price,
    timestamp,
    customer_id,
    created_at
FROM sales_transactions
ORDER BY created_at DESC
LIMIT 100;

-- ============================================
-- Function: UPSERT product sales
-- Called by Flink to update aggregates
-- ============================================
CREATE OR REPLACE FUNCTION upsert_product_sales(
    p_product_id INTEGER,
    p_quantity INTEGER,
    p_revenue DECIMAL(15, 2),
    p_last_sale_date TIMESTAMP
)
RETURNS VOID AS $$
BEGIN
    INSERT INTO product_sales (
        product_id,
        total_quantity,
        total_revenue,
        transaction_count,
        last_sale_date,
        updated_at
    )
    VALUES (
        p_product_id,
        p_quantity,
        p_revenue,
        1,
        p_last_sale_date,
        CURRENT_TIMESTAMP
    )
    ON CONFLICT (product_id)
    DO UPDATE SET
        total_quantity = product_sales.total_quantity + p_quantity,
        total_revenue = product_sales.total_revenue + p_revenue,
        transaction_count = product_sales.transaction_count + 1,
        last_sale_date = GREATEST(product_sales.last_sale_date, p_last_sale_date),
        updated_at = CURRENT_TIMESTAMP;
END;
$$ LANGUAGE plpgsql;

-- ============================================
-- Sample Queries (for reference)
-- ============================================

-- Top 10 products by quantity sold
-- SELECT * FROM v_top_products_by_quantity LIMIT 10;

-- Top 10 products by revenue
-- SELECT * FROM v_top_products_by_revenue LIMIT 10;

-- Overall sales summary
-- SELECT * FROM v_sales_summary;

-- Recent 20 transactions
-- SELECT * FROM v_recent_transactions LIMIT 20;

-- Product details
-- SELECT 
--     ps.product_id,
--     ps.total_quantity as sold,
--     ps.total_revenue as revenue,
--     ps.transaction_count as times_sold,
--     (ps.total_revenue / NULLIF(ps.total_quantity, 0)) as avg_unit_price
-- FROM product_sales ps
-- ORDER BY ps.total_revenue DESC
-- LIMIT 20;

COMMIT;

-- Show current tables
\dt

SELECT 'Final schema created successfully! Only 2 tables: sales_transactions, product_sales' as status;

