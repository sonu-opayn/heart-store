<?php

namespace App\HeartStore\Services;

use App\HeartStore\Services\BaseService;
use App\Models\Category;

class CategoryService extends BaseService 
{
	/**
     * Category Listing
     */
    public function getCatListing()
    {
        $categories = Category::get();

        return $categories;
    }
}