<?php

namespace App\Services;

use App\Models\Product;
use App\Http\Resources\ProductResource;

class ProductService {

    public function __construct(
        public Product $product
        ) {}

    public function getAllProducts() {
        return ProductResource::collection(Product::with(['category'])->orderBy("id", "desc")->get());
    }

    public function createProduct($data) {
        $this->product->fill($data)->save();
        return new ProductResource($this->getProductDetails($this->product->id));
    }

    public function updateProduct($data, $product) {
        $product->fill($data)->save();
        return new ProductResource($this->getProductDetails($product->id));
    }

    public function deleteProduct($product) {
        $product->delete();
        return true;
    }

    public function getProductDetails($id) {
        return Product::with(['category'])->whereId($id)->first();
    }

}
