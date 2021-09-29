<?php

namespace App\HeartStore\Services;

use App\HeartStore\Services\BaseService;
use App\Models\Product;

class ProductService extends BaseService 
{
	/**
     * Category Listing
     */
    public function getProductListing(array $filters)
    {
        $eagerLoad = ['image'];

        $query = Product::query()->with($eagerLoad);
        
        if(ine($filters, 'category')) {
            $query->where('cat_id', $filters['category']);
        }
        if(ine($filters, 'price')) {
            $query->where('regular_price', $filters['price']);
        }
        
        if(ine($filters, 'availability')) {
            $query->where('stock', $filters['availability']);
        }

        if(ine($filters, 'sort')) {
            $query->orderBy('id', $filters['sort']);
        }

        $products = $query->get();

        return $products;
    }
}