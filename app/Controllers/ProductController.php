<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CategoryModel;

class ProductController extends BaseController
{
    protected $productModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $data = [
            'title' => 'All Products - Nandini Hub',
            'products' => $this->productModel->getActiveProducts(),
            'categories' => $this->categoryModel->getActiveCategories()
        ];

        return view('products/index', $data);
    }

    public function show($slug)
    {
        $product = $this->productModel->getProductBySlug($slug);
        
        if (!$product) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Product not found');
        }

        // Get related products from same category
        $relatedProducts = $this->productModel->getProductsByCategory($product['category_id'], 4);
        
        // Remove current product from related products
        $relatedProducts = array_filter($relatedProducts, function($p) use ($product) {
            return $p['id'] != $product['id'];
        });

        $data = [
            'title' => $product['name'] . ' - Nandini Hub',
            'product' => $product,
            'relatedProducts' => array_slice($relatedProducts, 0, 3)
        ];

        return view('products/show', $data);
    }

    public function category($slug)
    {
        $category = $this->categoryModel->getCategoryBySlug($slug);
        
        if (!$category) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Category not found');
        }

        $products = $this->productModel->getProductsByCategory($category['id']);

        $data = [
            'title' => $category['name'] . ' - Nandini Hub',
            'category' => $category,
            'products' => $products,
            'categories' => $this->categoryModel->getActiveCategories()
        ];

        return view('products/category', $data);
    }

    public function search()
    {
        $keyword = $this->request->getGet('q');
        
        if (empty($keyword)) {
            return redirect()->to('/products');
        }

        $products = $this->productModel->searchProducts($keyword);

        $data = [
            'title' => 'Search Results for "' . $keyword . '" - Nandini Hub',
            'keyword' => $keyword,
            'products' => $products,
            'categories' => $this->categoryModel->getActiveCategories()
        ];

        return view('products/search', $data);
    }
}
