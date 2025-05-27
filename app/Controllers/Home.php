<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CategoryModel;

class Home extends BaseController
{
    protected $productModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index(): string
    {
        $data = [
            'title' => 'Nandini Hub - Premium Puja Samagri Online',
            'featuredProducts' => $this->productModel->getFeaturedProducts(8),
            'categories' => $this->categoryModel->getActiveCategories(),
            'latestProducts' => $this->productModel->getActiveProducts(12)
        ];

        return view('home/index', $data);
    }
}
