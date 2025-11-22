package com.lensart.pipeline.models;

import java.io.Serializable;
import java.math.BigDecimal;
import java.time.LocalDateTime;

/**
 * Sales Transaction Model
 * 
 * Represents a single product sale transaction
 */
public class SalesTransaction implements Serializable {
    
    private static final long serialVersionUID = 1L;
    
    public Integer orderId;
    public Integer productId;
    public Integer quantity;
    public BigDecimal price;
    public LocalDateTime timestamp;
    public Integer customerId;

    /**
     * Default constructor for serialization
     */
    public SalesTransaction() {
    }

    /**
     * Full constructor
     */
    public SalesTransaction(Integer orderId, Integer productId, Integer quantity, 
                           BigDecimal price, LocalDateTime timestamp, Integer customerId) {
        this.orderId = orderId;
        this.productId = productId;
        this.quantity = quantity;
        this.price = price;
        this.timestamp = timestamp;
        this.customerId = customerId;
    }

    /**
     * Validate transaction data
     * 
     * @return true if all fields are valid
     */
    public boolean isValid() {
        return orderId != null && orderId > 0
            && productId != null && productId > 0
            && quantity != null && quantity > 0
            && price != null && price.compareTo(BigDecimal.ZERO) >= 0
            && timestamp != null
            && customerId != null && customerId >= 0; // Allow 0 for guest customers
    }

    /**
     * Get order ID
     */
    public Integer getOrderId() {
        return orderId;
    }

    /**
     * Set order ID
     */
    public void setOrderId(Integer orderId) {
        this.orderId = orderId;
    }

    /**
     * Get product ID
     */
    public Integer getProductId() {
        return productId;
    }

    /**
     * Set product ID
     */
    public void setProductId(Integer productId) {
        this.productId = productId;
    }

    /**
     * Get quantity
     */
    public Integer getQuantity() {
        return quantity;
    }

    /**
     * Set quantity
     */
    public void setQuantity(Integer quantity) {
        this.quantity = quantity;
    }

    /**
     * Get price
     */
    public BigDecimal getPrice() {
        return price;
    }

    /**
     * Set price
     */
    public void setPrice(BigDecimal price) {
        this.price = price;
    }

    /**
     * Get timestamp
     */
    public LocalDateTime getTimestamp() {
        return timestamp;
    }

    /**
     * Set timestamp
     */
    public void setTimestamp(LocalDateTime timestamp) {
        this.timestamp = timestamp;
    }

    /**
     * Get customer ID
     */
    public Integer getCustomerId() {
        return customerId;
    }

    /**
     * Set customer ID
     */
    public void setCustomerId(Integer customerId) {
        this.customerId = customerId;
    }

    @Override
    public String toString() {
        return String.format(
            "SalesTransaction{orderId=%d, productId=%d, quantity=%d, price=%s, timestamp=%s, customerId=%d}",
            orderId, productId, quantity, price, timestamp, customerId
        );
    }

    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        
        SalesTransaction that = (SalesTransaction) o;
        
        if (!orderId.equals(that.orderId)) return false;
        if (!productId.equals(that.productId)) return false;
        return timestamp.equals(that.timestamp);
    }

    @Override
    public int hashCode() {
        int result = orderId.hashCode();
        result = 31 * result + productId.hashCode();
        result = 31 * result + timestamp.hashCode();
        return result;
    }
}

