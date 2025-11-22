package com.lensart.pipeline.models;

import java.io.Serializable;
import java.math.BigDecimal;
import java.time.LocalDateTime;

/**
 * Product Sales Model
 * 
 * Represents aggregated sales for a product
 */
public class ProductSales implements Serializable {
    
    private static final long serialVersionUID = 1L;
    
    public Integer productId;
    public Integer totalQuantity;
    public BigDecimal totalRevenue;
    public LocalDateTime lastSaleDate;

    /**
     * Default constructor
     */
    public ProductSales() {
        this.totalQuantity = 0;
        this.totalRevenue = BigDecimal.ZERO;
    }

    /**
     * Full constructor
     */
    public ProductSales(Integer productId, Integer totalQuantity, BigDecimal totalRevenue, LocalDateTime lastSaleDate) {
        this.productId = productId;
        this.totalQuantity = totalQuantity;
        this.totalRevenue = totalRevenue;
        this.lastSaleDate = lastSaleDate;
    }

    // Getters and Setters
    public Integer getProductId() {
        return productId;
    }

    public void setProductId(Integer productId) {
        this.productId = productId;
    }

    public Integer getTotalQuantity() {
        return totalQuantity;
    }

    public void setTotalQuantity(Integer totalQuantity) {
        this.totalQuantity = totalQuantity;
    }

    public BigDecimal getTotalRevenue() {
        return totalRevenue;
    }

    public void setTotalRevenue(BigDecimal totalRevenue) {
        this.totalRevenue = totalRevenue;
    }

    public LocalDateTime getLastSaleDate() {
        return lastSaleDate;
    }

    public void setLastSaleDate(LocalDateTime lastSaleDate) {
        this.lastSaleDate = lastSaleDate;
    }

    @Override
    public String toString() {
        return String.format(
            "ProductSales{productId=%d, quantity=%d, revenue=%s}",
            productId, totalQuantity, totalRevenue
        );
    }
}

