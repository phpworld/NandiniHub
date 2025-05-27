<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'slug',
        'description',
        'image',
        'is_active',
        'sort_order'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[255]',
        'slug' => 'required|alpha_dash|is_unique[categories.slug,id,{id}]',
    ];

    protected $validationMessages = [
        'slug' => [
            'is_unique' => 'This category slug already exists.'
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

    public function getActiveCategories()
    {
        return $this->where('is_active', 1)
                   ->orderBy('sort_order', 'ASC')
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }

    public function getCategoryBySlug($slug)
    {
        return $this->where('slug', $slug)
                   ->where('is_active', 1)
                   ->first();
    }

    public function getCategoriesWithProductCount()
    {
        return $this->select('categories.*, COUNT(products.id) as product_count')
                   ->join('products', 'products.category_id = categories.id', 'left')
                   ->where('categories.is_active', 1)
                   ->where('products.is_active', 1)
                   ->groupBy('categories.id')
                   ->orderBy('categories.sort_order', 'ASC')
                   ->findAll();
    }
}
