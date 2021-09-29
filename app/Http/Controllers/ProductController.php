<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HeartStore\Services\ProductService;
use App\HeartStore\ApiResponse;

class ProductController extends Controller
{
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    /**
     * Category Listing
     */
    public function getListing(Request $request)
    {
        try {
            $filters = $request->only('category', 'price', 'sort', 'availability');
            $products = $this->productService->getProductListing($filters);
            
            return ApiResponse::success($products, 'Categories Listing');
        } catch (\Throwable $th) {
            return ApiResponse::errorInternal(['message'=>'something went wrong']);
        }
    }
}
