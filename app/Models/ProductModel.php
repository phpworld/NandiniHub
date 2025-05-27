<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'category_id',
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'sale_price',
        'sku',
        'stock_quantity',
        'weight',
        'dimensions',
        'image',
        'gallery',
        'is_featured',
        'is_active',
        'meta_title',
        'meta_description'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'category_id' => 'required|integer',
        'name'        => 'required|min_length[2]|max_length[255]',
        'slug'        => 'required|alpha_dash|is_unique[products.slug,id,{id}]',
        'price'       => 'required|decimal',
        'sku'         => 'required|is_unique[products.sku,id,{id}]',
    ];

    protected $validationMessages = [
        'slug' => [
            'is_unique' => 'This product slug already exists.'
        ],
        'sku' => [
            'is_unique' => 'This SKU already exists.'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generateSlug'];
    protected $beforeUpdate   = ['generateSlug'];

    protected function generateSlug(array $data)
    {
        if (isset($data['data']['name']) && empty($data['data']['slug'])) {
            $data['data']['slug'] = url_title($data['data']['name'], '-', true);
        }
        return $data;
    }

    public function getActiveProducts($limit = null)
    {
        $builder = $this->select('products.*, categories.name as category_name')
                       ->join('categories', 'categories.id = products.category_id')
                       ->where('products.is_active', 1)
                       ->where('categories.is_active', 1)
                       ->orderBy('products.created_at', 'DESC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->findAll();
    }

    public function getFeaturedProducts($limit = 8)
    {
        return $this->select('products.*, categories.name as category_name')
                   ->join('categories', 'categories.id = products.category_id')
                   ->where('products.is_featured', 1)
                   ->where('products.is_active', 1)
                   ->where('categories.is_active', 1)
                   ->limit($limit)
                   ->findAll();
    }

    public function getProductBySlug($slug)
    {
        return $this->select('products.*, categories.name as category_name, categories.slug as category_slug')
                   ->join('categories', 'categories.id = products.category_id')
                   ->where('products.slug', $slug)
                   ->where('products.is_active', 1)
                   ->where('categories.is_active', 1)
                   ->first();
    }

    public function getProductsByCategory($categoryId, $limit = null)
    {
        $builder = $this->select('products.*, categories.name as category_name')
                       ->join('categories', 'categories.id = products.category_id')
                       ->where('products.category_id', $categoryId)
                       ->where('products.is_active', 1)
                       ->where('categories.is_active', 1)
                       ->orderBy('products.name', 'ASC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->findAll();
    }

    public function searchProducts($keyword, $limit = null)
    {
        $builder = $this->select('products.*, categories.name as category_name')
                       ->join('categories', 'categories.id = products.category_id')
                       ->groupStart()
                           ->like('products.name', $keyword)
                           ->orLike('products.description', $keyword)
                           ->orLike('products.short_description', $keyword)
                           ->orLike('categories.name', $keyword)
                       ->groupEnd()
                       ->where('products.is_active', 1)
                       ->where('categories.is_active', 1)
                       ->orderBy('products.name', 'ASC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->findAll();
    }

    public function getDiscountedPrice($product)
    {
        return $product['sale_price'] ? $product['sale_price'] : $product['price'];
    }

    public function hasDiscount($product)
    {
        return !empty($product['sale_price']) && $product['sale_price'] < $product['price'];
    }

    public function getDiscountPercentage($product)
    {
        if (!$this->hasDiscount($product)) {
            return 0;
        }
        
        return round((($product['price'] - $product['sale_price']) / $product['price']) * 100);
    }
}
