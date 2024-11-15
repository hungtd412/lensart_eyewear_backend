<?php

namespace App\Repositories\Product;

use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface {
    public function store(array $product): Product {
        return Product::create($product);
    }

    public function getAll() {
        return Product::orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")->get();
    }

    public function getById($id) {
        return Product::find($id);
    }

    public function update(array $data, $product) {
        $product->update($data);
    }

    public function updateEach(array $data, $product, $attributeOfProduct) {
        $product->$attributeOfProduct = $data[$attributeOfProduct];
        $product->save();
    }

    public function switchStatus($product) {
        $product->status = $product->status == 'active' ? 'inactive' : 'active';
        $product->save();
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
}
