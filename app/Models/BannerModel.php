<?php

namespace App\Models;

use CodeIgniter\Model;

class BannerModel extends Model
{
    protected $table            = 'banners';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title',
        'subtitle',
        'description',
        'image',
        'button_text',
        'button_link',
        'button_text_2',
        'button_link_2',
        'background_color',
        'text_color',
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
        'title' => 'required|min_length[2]|max_length[255]',
        'background_color' => 'permit_empty|regex_match[/^#[0-9A-Fa-f]{6}$/]',
        'text_color' => 'permit_empty|regex_match[/^#[0-9A-Fa-f]{6}$/]',
        'sort_order' => 'permit_empty|integer',
    ];

    protected $validationMessages = [
        'background_color' => [
            'regex_match' => 'Background color must be a valid hex color code (e.g., #ff6b35).'
        ],
        'text_color' => [
            'regex_match' => 'Text color must be a valid hex color code (e.g., #ffffff).'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    public function getActiveBanners($limit = null)
    {
        $builder = $this->where('is_active', 1)
                       ->orderBy('sort_order', 'ASC')
                       ->orderBy('created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    public function getMainBanner()
    {
        return $this->where('is_active', 1)
                   ->orderBy('sort_order', 'ASC')
                   ->orderBy('created_at', 'DESC')
                   ->first();
    }

    public function getSliderBanners($limit = 5)
    {
        return $this->where('is_active', 1)
                   ->orderBy('sort_order', 'ASC')
                   ->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
}
