<?php

namespace App\Services\Product;

use App\Repositories\Product\ProductDetailRepositoryInterface;

class ProductDetailService {
    protected $productDetailRepository;

    public function __construct(ProductDetailRepositoryInterface $productDetailRepository) {
        $this->productDetailRepository = $productDetailRepository;
    }

    public function store($data) {
        $productDetail = $this->productDetailRepository->store($data->toArray());

        return response()->json([
            'status' => 'success',
            'productDetail' => $productDetail
        ], 200);
    }

    public function storeForAllBranch($data, $idAllBranches) {
        foreach ($idAllBranches as $id) {
            $data['branch_id'] = $id;
            $this->productDetailRepository->store($data);
        }

        return response()->json([
            'status' => 'success'
        ], 200);
    }

    public function getAll() {
        $productDetails = $this->productDetailRepository->getAll();

        return response()->json([
            'status' => 'success',
            'productDetails' => $productDetails
        ], 200);
    }

    public function getById($id) {
        $productDetail = $this->productDetailRepository->getById($id);

        if ($productDetail === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'productDetail' => $productDetail,
        ], 200);
    }

    public function getByProductId($id) {
        $productDetails = $this->productDetailRepository->getByProductId($id);

        if ($productDetails === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'productDetails' => $productDetails,
        ], 200);
    }

    public function getByBranchId($id) {
        $productDetails = $this->productDetailRepository->getByBranchId($id);

        if ($productDetails === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'productDetails' => $productDetails,
        ], 200);
    }

    public function getByProductAndBranchId($productId, $branchId) {
        $productDetails = $this->productDetailRepository->getByProductAndBranchId($productId, $branchId);

        if ($productDetails === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'productDetails' => $productDetails,
        ], 200);
    }

    public function update($data, $id) {
        $productDetail = $this->productDetailRepository->getById($id);

        $this->productDetailRepository->update($data->toArray(), $productDetail);

        return response()->json([
            'message' => 'success',
            'productDetail' => $productDetail
        ], 200);
    }

    public function delete($id) {
        $this->productDetailRepository->delete($id);

        return response()->json([
            'message' => 'success',
        ], 200);
    }
}
