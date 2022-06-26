<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use App\Traits\OutputTrait;
use Exception;

class ProductController extends Controller
{
    use OutputTrait;

    public function __construct(
        public ProductService $productService
    ){}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->sendSuccess("Products List", $this->productService->getAllProducts());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $product = $this->productService->createProduct($request->validated());
            return $this->sendSuccess("Product added successfully", $product);
        } catch (Exception $exp) {
            return $this->sendError($exp);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            $product = $this->productService->updateProduct($request->validated(), $product);
            return $this->sendSuccess("Product updated successfully.", $product);
        } catch (Exception $exp) {
            return $this->sendError($exp);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try {
            $this->productService->deleteProduct($product);
            return $this->sendSuccess("Product deleted successfully");
        } catch (Exception $exp) {
            return $this->sendError($exp);
        }
    }
}
