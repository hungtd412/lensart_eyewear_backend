<?php

namespace App\Repositories\Product;

use App\Models\Product;
use Illuminate\Support\Facades\DB;


class ProductRepository implements ProductRepositoryInterface
{
    public function store(array $product): Product
    {
        return Product::create($product);
    }

    public function getAll()
    {
        return Product::all();
    }

    public function getById($id)
    {
        $product = Product::with(['brand', 'category', 'shape', 'material', 'productDetails', 'features', 'images', 'reviews'])->find($id);

        return $product;
    }

    public function getByCategoryId($categoryId)
    {
        return Product::where('category_id', $categoryId)
            ->with(['images'])
            ->get();
    }

    public function update(array $data, $product)
    {
        $product->update($data);
    }

    public function updateEach(array $data, $product, $attributeOfProduct)
    {
        $product->$attributeOfProduct = $data[$attributeOfProduct];
        $product->save();
    }

    public function switchStatus($product)
    {
        $product->status = $product->status == 'active' ? 'inactive' : 'active';
        $product->save();
    }

    public function getAllActive()
    {
        return Product::where('status', 'active')->with(['images', 'features:id'])->get();
    }

    public function getProductFeatures($productId)
    {
        $product = Product::find($productId);
        return $product ? $product->features()->get() : null;
    }

    public function getByIdActive($id)
    {
        return Product::where('id', $id)->where('status', 'active')
            ->with(['images'])
            ->first();
    }

    public function getByCategoryIdActive($categoryId)
    {
        return Product::where('category_id', $categoryId)
            ->where('status', 'active')
            ->with(['images'])
            ->get();
    }

    // Tìm kiếm sản phẩm

    public function searchProduct($keyword)
    {
        // Tách keyword thành mảng các từ khóa con
        $keywords = explode(' ', $keyword);

        return Product::where('status', 'active') // Kiểm tra sản phẩm có `status = 1`
            ->whereHas('productDetails', function ($query) {
                $query->where('product_details.status', 'active') // Kiểm tra `status` của product_details
                    ->where('quantity', '>', 0); // Kiểm tra `quantity > 0`
            })
            ->where(function ($query) use ($keywords) {
                foreach ($keywords as $word) {
                    $query->where(function ($subQuery) use ($word) {
                        $subQuery->where('name', 'LIKE', "%{$word}%") // Tìm trong tên sản phẩm
                            ->orWhere('description', 'LIKE', "%{$word}%") // Tìm trong mô tả sản phẩm
                            ->orWhereHas('brand', function ($subSubQuery) use ($word) {
                                $subSubQuery->where('name', 'LIKE', "%{$word}%"); // Tìm trong tên thương hiệu
                            })
                            ->orWhereHas('category', function ($subSubQuery) use ($word) {
                                $subSubQuery->where('name', 'LIKE', "%{$word}%"); // Tìm trong tên danh mục
                            })
                            ->orWhere('shape_id', $word) // Tìm theo shape_id
                            ->orWhere('material_id', $word) // Tìm theo material_id
                            ->orWhere('gender', 'LIKE', "%{$word}%") // Tìm theo giới tính
                            ->orWhere('price', $word); // Tìm theo giá
                    });
                }
            })
            ->with([
                'brand:id,name', // Lấy thông tin thương hiệu
                'category:id,name', // Lấy thông tin danh mục
                'productDetails' => function ($query) {
                    $query->where('product_details.status', 'active') // Chỉ lấy product_details đang hoạt động
                        ->where('quantity', '>', 0) // Lấy product_details có số lượng > 0
                        ->select('product_details.product_id', 'product_details.branch_id', 'product_details.color', 'product_details.quantity', 'product_details.status'); // Chọn các cột cần thiết
                },
                'mainImage:id,product_id,image_url' // Lấy thông tin hình ảnh chính
            ])
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'brand' => $product->brand->name ?? 'N/A',
                    'category' => $product->category->name ?? 'N/A',
                    'shape_id' => $product->shape_id,
                    'material_id' => $product->material_id,
                    'gender' => $product->gender,
                    'price' => $product->price,
                    'offer_price' => $product->offer_price,
                    'image_url' => $product->mainImage->image_url ?? 'N/A', // Trả về image_url
                    'product_details' => $product->productDetails
                ];
            });
    }

    // Lọc theo kiểu gọng
    public function filterByShape($query, $types)
    {
        if (!empty($types)) {
            $query->leftJoin('shapes as s1', 'products.shape_id', '=', 's1.id')
                ->whereIn('s1.name', $types);
        }
        return $query;
    }

    // Lọc theo giới tính
    public function filterByGender($query, $gender)
    {
        if (!empty($gender)) {
            $query->where('gender', $gender);
        }
        return $query;
    }

    // Lọc theo chất liệu
    public function filterByMaterial($query, $materials)
    {
        if (!empty($materials)) {
            $query->leftJoin('materials as m1', 'products.material_id', '=', 'm1.id')
                ->whereIn('m1.name', $materials);
        }
        return $query;
    }

    // Lọc theo giá
    public function filterByPriceRange($query, $priceRange)
    {
        if (!empty($priceRange)) {
            switch ($priceRange) {
                case 'Dưới 500000':
                    $query->where('price', '<', 500000);
                    break;
                case '500000-1500000':
                    $query->whereBetween('price', [500000, 1500000]);
                    break;
                case '1500000-3000000':
                    $query->whereBetween('price', [1500000, 3000000]);
                    break;
                case '3000000-5000000':
                    $query->whereBetween('price', [3000000, 5000000]);
                    break;
                case 'Trên 5000000':
                    $query->where('price', '>', 5000000);
                    break;
            }
        }
        return $query;
    }

    // Lọc theo thương hiệu
    public function filterByBrand($query, $brands)
    {
        if (!empty($brands)) {
            $query->leftJoin('brands as b1', 'products.brand_id', '=', 'b1.id')
                ->whereIn('b1.name', $brands);
        }
        return $query;
    }

    // Lọc theo tính năng
    public function filterByFeatures($query, $features)
    {
        if (!empty($features)) {

            $query->join('product_features as pf', 'products.id', '=', 'pf.product_id')
                ->join('features as f', 'pf.feature_id', '=', 'f.id')
                ->whereIn('f.name', $features);
        }
        return $query;
    }

    public function getBestSellingProducts($limit = 10)
    {
        return Product::select('products.*', DB::raw('SUM(product_details.quantity) as total_sold'))
            ->join('product_details', 'products.id', '=', 'product_details.product_id')
            ->where('products.status', 'active')
            ->where('product_details.status', 'active')
            ->groupBy('products.id')
            ->orderBy('total_sold', 'desc')
            ->with(['images'])
            ->take($limit)
            ->get();
    }

    public function getNewestProducts($limit = 10)
    {
        return Product::where('status', 'active')
            ->orderBy('id', 'desc')
            ->with(['images'])
            ->take($limit)
            ->get();
    }

    public function getProductCatetoryID($productId)
    {
        return Product::find($productId)->category_id;
    }
}
