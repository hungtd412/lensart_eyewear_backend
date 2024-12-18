<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model {
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'brand_id',
        'category_id',
        'shape_id',
        'material_id',
        'gender',
        'price',
        'offer_price',
        'created_time',
        'status'
    ];

    protected $casts = [
        'created_time' => 'datetime:Y-m-d H:i:s',
        'price' => 'float',
        'offer_price' => 'float',
    ];


    // Thiết lập mối quan hệ với bảng Category
    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Thiết lập mối quan hệ với bảng Brand
     */
    public function brand() {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    /**
     * Thiết lập mối quan hệ với bảng Shape
     */
    public function shape() {
        return $this->belongsTo(Shape::class, 'shape_id');
    }

    /**
     * Thiết lập mối quan hệ với bảng Material
     */
    public function material() {
        return $this->belongsTo(Material::class, 'material_id');
    }

    /**
     * Thiết lập mối quan hệ với bảng ProductFeature
     */
    public function features() {
        return $this->belongsToMany(Feature::class, 'product_features', 'product_id', 'feature_id');
    }

    /**
     * Thiết lập mối quan hệ với bảng ProductDetail
     */
    public function details() {
        return $this->hasMany(ProductDetail::class, 'product_id');
    }

    /**
     * Thiết lập mối quan hệ với bảng ProductImage
     */
    public function images() {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    /**
     * Thiết lập mối quan hệ với bảng Reviews (nếu có)
     */
    public function reviews() {
        return $this->hasMany(ProductReview::class, 'product_id')
            ->join('users', 'product_reviews.user_id', '=', 'users.id')
            ->select('product_reviews.*', 'users.firstname', 'users.lastname');
    }

    // Định nghĩa mối quan hệ với bảng product_details
    public function productDetails() {
        return $this->hasMany(ProductDetail::class, 'product_id')
            ->join('branches', 'product_details.branch_id', '=', 'branches.id')
            ->select('product_details.*', 'branches.name as branch_name', 'branches.address', 'branches.index')
            ->withCasts([
                'index' => 'float'
            ]);
    }
}

use Illuminate\Support\Facades\DB;
