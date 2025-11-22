-- ============================================
-- Simplified Schema - Only Essential Tables
-- ============================================

\c lensart_events;

-- Drop unused tables
DROP TABLE IF EXISTS orders_raw CASCADE;
DROP TABLE IF EXISTS orders_processed CASCADE;
DROP TABLE IF EXISTS order_status_history CASCADE;
DROP TABLE IF EXISTS order_items_analytics CASCADE;

-- Drop old views
DROP VIEW IF EXISTS v_daily_order_summary CASCADE;
DROP VIEW IF EXISTS v_branch_performance CASCADE;
DROP VIEW IF EXISTS v_product_popularity CASCADE;
DROP VIEW IF EXISTS v_recent_orders CASCADE;

-- ============================================
-- Table 1: sales_transactions (Keep - Raw data)
-- ============================================
-- Already exists, keep as is for raw transaction data

-- ============================================
-- Table 2: order_metrics (Simplified)
-- For aggregated metrics
-- ============================================
DROP TABLE IF EXISTS order_metrics CASCADE;

CREATE TABLE order_metrics (
    id SERIAL PRIMARY KEY,
    metric_date DATE NOT NULL,
    metric_hour INTEGER,  -- NULL for daily aggregates, 0-23 for hourly
    
    -- Revenue metrics
    total_revenue DECIMAL(15, 2) DEFAULT 0,
    total_transactions INTEGER DEFAULT 0,
    avg_transaction_value DECIMAL(15, 2),
    
    -- Product metrics (will store top products as JSONB)
    product_sales JSONB,  -- Format: [{"product_id": 1, "qty": 10, "revenue": 5000}, ...]
    
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Unique constraint
    CONSTRAINT order_metrics_unique_key UNIQUE(metric_date, metric_hour)
);

CREATE INDEX idx_order_metrics_date ON order_metrics(metric_date);
CREATE INDEX idx_order_metrics_date_hour ON order_metrics(metric_date, metric_hour);
CREATE INDEX idx_order_metrics_updated ON order_metrics(updated_at);
CREATE INDEX idx_order_metrics_product_sales ON order_metrics USING GIN (product_sales);

COMMENT ON TABLE order_metrics IS 'Aggregated sales metrics by date/hour';
COMMENT ON COLUMN order_metrics.product_sales IS 'JSON array of product sales: [{"product_id": int, "quantity": int, "revenue": decimal}]';

-- ============================================
-- Views for Easy Querying
-- ============================================

-- Daily revenue summary
CREATE OR REPLACE VIEW v_daily_revenue AS
SELECT 
    metric_date,
    SUM(total_revenue) as daily_revenue,
    SUM(total_transactions) as daily_transactions,
    AVG(avg_transaction_value) as avg_transaction_value
FROM order_metrics
WHERE metric_hour IS NOT NULL
GROUP BY metric_date
ORDER BY metric_date DESC;

-- Hourly revenue (today)
CREATE OR REPLACE VIEW v_today_hourly_revenue AS
SELECT 
    metric_hour as hour,
    total_revenue,
    total_transactions,
    avg_transaction_value
FROM order_metrics
WHERE metric_date = CURRENT_DATE
  AND metric_hour IS NOT NULL
ORDER BY metric_hour;

-- Top products from latest metrics
CREATE OR REPLACE VIEW v_top_products AS
SELECT 
    metric_date,
    jsonb_array_elements(product_sales) as product
FROM order_metrics
WHERE metric_date = CURRENT_DATE
  AND metric_hour IS NULL
ORDER BY metric_date DESC
LIMIT 1;

-- ============================================
-- Function: Upsert order metrics
-- ============================================
CREATE OR REPLACE FUNCTION upsert_order_metrics(
    p_metric_date DATE,
    p_metric_hour INTEGER,
    p_total_revenue DECIMAL(15, 2),
    p_total_transactions INTEGER,
    p_avg_transaction_value DECIMAL(15, 2),
    p_product_sales JSONB
)
RETURNS VOID AS $$
BEGIN
    INSERT INTO order_metrics (
        metric_date,
        metric_hour,
        total_revenue,
        total_transactions,
        avg_transaction_value,
        product_sales,
        updated_at
    )
    VALUES (
        p_metric_date,
        p_metric_hour,
        p_total_revenue,
        p_total_transactions,
        p_avg_transaction_value,
        p_product_sales,
        CURRENT_TIMESTAMP
    )
    ON CONFLICT (metric_date, metric_hour)
    DO UPDATE SET
        total_revenue = EXCLUDED.total_revenue,
        total_transactions = EXCLUDED.total_transactions,
        avg_transaction_value = EXCLUDED.avg_transaction_value,
        product_sales = EXCLUDED.product_sales,
        updated_at = CURRENT_TIMESTAMP;
END;
$$ LANGUAGE plpgsql;

-- ============================================
-- Sample Queries
-- ============================================

-- Query daily revenue
-- SELECT * FROM v_daily_revenue LIMIT 7;

-- Query today's hourly revenue
-- SELECT * FROM v_today_hourly_revenue;

-- Query top products
-- SELECT 
--     product->>'product_id' as product_id,
--     product->>'quantity' as total_quantity,
--     product->>'revenue' as total_revenue
-- FROM v_top_products
-- ORDER BY (product->>'revenue')::decimal DESC
-- LIMIT 10;

COMMIT;

SELECT 'Simplified schema created successfully!' as status;

