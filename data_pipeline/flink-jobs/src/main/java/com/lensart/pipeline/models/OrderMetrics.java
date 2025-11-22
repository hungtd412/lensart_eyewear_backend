package com.lensart.pipeline.models;

import java.io.Serializable;
import java.math.BigDecimal;
import java.time.LocalDate;

/**
 * Order Metrics Model
 * 
 * Represents aggregated sales metrics for a specific date/hour
 */
public class OrderMetrics implements Serializable {
    
    private static final long serialVersionUID = 1L;
    
    public LocalDate metricDate;
    public Integer metricHour;  // NULL for daily, 0-23 for hourly
    public BigDecimal totalRevenue;
    public Integer totalTransactions;
    public BigDecimal avgTransactionValue;
    public String productSalesJson;  // JSON string of product sales

    /**
     * Default constructor
     */
    public OrderMetrics() {
        this.totalRevenue = BigDecimal.ZERO;
        this.totalTransactions = 0;
        this.avgTransactionValue = BigDecimal.ZERO;
        this.productSalesJson = "[]";
    }

    /**
     * Full constructor
     */
    public OrderMetrics(LocalDate metricDate, Integer metricHour, 
                       BigDecimal totalRevenue, Integer totalTransactions,
                       BigDecimal avgTransactionValue, String productSalesJson) {
        this.metricDate = metricDate;
        this.metricHour = metricHour;
        this.totalRevenue = totalRevenue;
        this.totalTransactions = totalTransactions;
        this.avgTransactionValue = avgTransactionValue;
        this.productSalesJson = productSalesJson;
    }

    // Getters and Setters
    public LocalDate getMetricDate() {
        return metricDate;
    }

    public void setMetricDate(LocalDate metricDate) {
        this.metricDate = metricDate;
    }

    public Integer getMetricHour() {
        return metricHour;
    }

    public void setMetricHour(Integer metricHour) {
        this.metricHour = metricHour;
    }

    public BigDecimal getTotalRevenue() {
        return totalRevenue;
    }

    public void setTotalRevenue(BigDecimal totalRevenue) {
        this.totalRevenue = totalRevenue;
    }

    public Integer getTotalTransactions() {
        return totalTransactions;
    }

    public void setTotalTransactions(Integer totalTransactions) {
        this.totalTransactions = totalTransactions;
    }

    public BigDecimal getAvgTransactionValue() {
        return avgTransactionValue;
    }

    public void setAvgTransactionValue(BigDecimal avgTransactionValue) {
        this.avgTransactionValue = avgTransactionValue;
    }

    public String getProductSalesJson() {
        return productSalesJson;
    }

    public void setProductSalesJson(String productSalesJson) {
        this.productSalesJson = productSalesJson;
    }

    @Override
    public String toString() {
        return String.format(
            "OrderMetrics{date=%s, hour=%s, revenue=%s, transactions=%d, avgValue=%s}",
            metricDate, metricHour, totalRevenue, totalTransactions, avgTransactionValue
        );
    }
}

