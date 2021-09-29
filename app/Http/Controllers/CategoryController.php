<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HeartStore\ApiResponse;
use App\HeartStore\Services\CategoryService;

class CategoryController extends Controller
{
    public function __construct(CategoryService $catService)
    {
        $this->catService = $catService;
    }
    /**
     * Category Listing
     */
    public function getListing()
    {
        try {
            $categories = $this->catService->getCatListing();
            
            return ApiResponse::success($categories, 'Categories Listing');
        } catch (\Throwable $th) {
            return ApiResponse::errorInternal(['message'=>'something went wrong']);
        }
    }
}
