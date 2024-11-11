<?php

namespace App\Repositories\Product;

use App\Models\ProductDetail;
use Illuminate\Support\Facades\DB;

class ProductDetailRepository implements ProductDetailRepositoryInterface {
    public function store(array $productDetail): ProductDetail {
        return ProductDetail::create($productDetail);
    }

    public function getAll() {
        return ProductDetail::orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")->get();
    }

    public function getByProductId($productId) {
        return DB::table('product_details')
            ->join('branches', 'branches.id', '=', 'product_details.branch_id')
            ->where('product_id', $productId)
            ->select(
                'product_details.*',
                'branches.id',
                'branches.name as branch_name'
            )
            ->get();
    }

    public function getByBranchId($branchId) {
        return DB::table('product_details')
            ->join('branches', 'branches.id', '=', 'product_details.branch_id')
            ->where('branch_id', $branchId)
            ->select(
                'product_details.*',
                'branches.id',
                'branches.name as branch_name'
            )
            ->groupBy('branch_name')
            ->get();
    }

    public function getByProductAndBranchId($productId, $branchId) {
        return ProductDetail::where('product_id', $productId)
            ->where('branch_id', $branchId)
            ->get();
    }

    public function getByThreeIds($productId, $branchId, $colorId) {
        return ProductDetail::where('product_id', $productId)
            ->where('branch_id', $branchId)
            ->where('color_id', $colorId)
            ->first();
    }

    public function update(array $data, $productId, $branchId, $colorId) {
        ProductDetail::where('product_id', $productId)
            ->where('branch_id', $branchId)
            ->where('color_id', $colorId)
            ->update($data);
    }

    public function updateEach(array $data, $productDetail, $attributeOfProductDetail) {
        $productDetail->$attributeOfProductDetail = $data[$attributeOfProductDetail];
        $productDetail->save();
    }

    public function delete($id) {
        ProductDetail::destroy($id);
    }
}
