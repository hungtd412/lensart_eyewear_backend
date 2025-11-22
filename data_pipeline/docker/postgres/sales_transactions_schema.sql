-- ============================================
-- Sales Transactions Table (Simplified)
-- ============================================

-- Table để lưu transactions từ Kafka (via Flink)
-- Format đơn giản: chỉ 6 fields
CREATE TABLE IF NOT EXISTS sales_transactions (
    id SERIAL PRIMARY KEY,
    order_id INTEGER NOT NULL,
    product_id INTEGER NOT NULL,
    quantity INTEGER NOT NULL,
    price DECIMAL(15, 2) NOT NULL,
    timestamp TIMESTAMP NOT NULL,
    customer_id INTEGER NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Constraints
    CONSTRAINT sales_transactions_quantity_positive CHECK (quantity > 0),
    CONSTRAINT sales_transactions_price_positive CHECK (price >= 0)
);

-- Indexes for performance
CREATE INDEX idx_sales_transactions_order_id ON sales_transactions(order_id);
CREATE INDEX idx_sales_transactions_product_id ON sales_transactions(product_id);
CREATE INDEX idx_sales_transactions_customer_id ON sales_transactions(customer_id);
CREATE INDEX idx_sales_transactions_timestamp ON sales_transactions(timestamp);
CREATE INDEX idx_sales_transactions_created_at ON sales_transactions(created_at);

-- Composite index for analytics queries
CREATE INDEX idx_sales_transactions_product_timestamp 
    ON sales_transactions(product_id, timestamp);
CREATE INDEX idx_sales_transactions_customer_timestamp 
    ON sales_transactions(customer_id, timestamp);

COMMENT ON TABLE sales_transactions IS 'Sales transactions from Kafka - 1 product = 1 record';
COMMENT ON COLUMN sales_transactions.price IS 'Total price for this quantity (unit_price * quantity)';

-- ============================================
-- Useful Views
-- ============================================

-- View: Daily sales by product
CREATE OR REPLACE VIEW v_daily_sales_by_product AS
SELECT 
    DATE(timestamp) as sale_date,
    product_id,
    COUNT(*) as transaction_count,
    SUM(quantity) as total_quantity,
    SUM(price) as total_revenue,
    AVG(price / quantity) as avg_unit_price
FROM sales_transactions
GROUP BY DATE(timestamp), product_id
ORDER BY sale_date DESC, total_revenue DESC;

-- View: Customer purchase summary
CREATE OR REPLACE VIEW v_customer_purchases AS
SELECT 
    customer_id,
    COUNT(DISTINCT order_id) as total_orders,
    COUNT(*) as total_transactions,
    SUM(quantity) as total_items,
    SUM(price) as total_spent,
    AVG(price) as avg_transaction_value,
    MAX(timestamp) as last_purchase_date
FROM sales_transactions
GROUP BY customer_id
ORDER BY total_spent DESC;

-- View: Product performance
CREATE OR REPLACE VIEW v_product_performance AS
SELECT 
    product_id,
    COUNT(*) as sales_count,
    SUM(quantity) as total_sold,
    SUM(price) as revenue,
    AVG(price / quantity) as avg_price,
    MIN(timestamp) as first_sale,
    MAX(timestamp) as last_sale
FROM sales_transactions
GROUP BY product_id
ORDER BY revenue DESC;

-- View: Hourly sales metrics
CREATE OR REPLACE VIEW v_hourly_sales AS
SELECT 
    DATE(timestamp) as sale_date,
    EXTRACT(HOUR FROM timestamp) as hour,
    COUNT(*) as transaction_count,
    SUM(quantity) as items_sold,
    SUM(price) as revenue,
    COUNT(DISTINCT customer_id) as unique_customers
FROM sales_transactions
GROUP BY DATE(timestamp), EXTRACT(HOUR FROM timestamp)
ORDER BY sale_date DESC, hour ASC;

-- ============================================
-- Sample Analytics Queries
-- ============================================

-- Top 10 best-selling products (last 30 days)
-- SELECT product_id, SUM(quantity) as qty, SUM(price) as revenue
-- FROM sales_transactions
-- WHERE timestamp >= NOW() - INTERVAL '30 days'
-- GROUP BY product_id
-- ORDER BY qty DESC
-- LIMIT 10;

-- Revenue by day (last 7 days)
-- SELECT DATE(timestamp) as day, SUM(price) as revenue
-- FROM sales_transactions
-- WHERE timestamp >= NOW() - INTERVAL '7 days'
-- GROUP BY DATE(timestamp)
-- ORDER BY day DESC;

-- Customer purchase frequency
-- SELECT customer_id, COUNT(DISTINCT order_id) as orders,
--        MAX(timestamp) - MIN(timestamp) as customer_lifetime
-- FROM sales_transactions
-- GROUP BY customer_id
-- HAVING COUNT(DISTINCT order_id) > 1
-- ORDER BY orders DESC;

COMMIT;

