<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table            = 'settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'setting_key',
        'setting_value',
        'setting_type',
        'description'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'setting_key'   => 'required|max_length[255]',
        'setting_value' => 'permit_empty',
        'setting_type'  => 'required|in_list[string,text,number,boolean,json]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get a setting value by key
     */
    public function getSetting(string $key, $default = null)
    {
        $setting = $this->where('setting_key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return $this->castValue($setting['setting_value'], $setting['setting_type']);
    }

    /**
     * Set a setting value
     */
    public function setSetting(string $key, $value, string $type = 'string'): bool
    {
        $stringValue = $this->valueToString($value, $type);
        
        $existing = $this->where('setting_key', $key)->first();
        
        if ($existing) {
            return $this->update($existing['id'], [
                'setting_value' => $stringValue,
                'setting_type'  => $type
            ]);
        } else {
            return $this->insert([
                'setting_key'   => $key,
                'setting_value' => $stringValue,
                'setting_type'  => $type
            ]) !== false;
        }
    }

    /**
     * Get all settings as key-value pairs
     */
    public function getAllSettings(): array
    {
        $settings = $this->findAll();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $this->castValue(
                $setting['setting_value'], 
                $setting['setting_type']
            );
        }
        
        return $result;
    }

    /**
     * Update multiple settings at once
     */
    public function updateSettings(array $settings): bool
    {
        $this->db->transStart();
        
        foreach ($settings as $key => $value) {
            $type = $this->determineType($value);
            $this->setSetting($key, $value, $type);
        }
        
        $this->db->transComplete();
        
        return $this->db->transStatus();
    }

    /**
     * Cast value based on type
     */
    private function castValue($value, string $type)
    {
        switch ($type) {
            case 'boolean':
                return (bool) $value;
            case 'number':
                return is_numeric($value) ? (float) $value : 0;
            case 'json':
                return json_decode($value, true) ?: [];
            case 'text':
            case 'string':
            default:
                return (string) $value;
        }
    }

    /**
     * Convert value to string for storage
     */
    private function valueToString($value, string $type): string
    {
        switch ($type) {
            case 'boolean':
                return $value ? '1' : '0';
            case 'number':
                return (string) $value;
            case 'json':
                return json_encode($value);
            case 'text':
            case 'string':
            default:
                return (string) $value;
        }
    }

    /**
     * Automatically determine type from value
     */
    private function determineType($value): string
    {
        if (is_bool($value)) {
            return 'boolean';
        } elseif (is_numeric($value)) {
            return 'number';
        } elseif (is_array($value) || is_object($value)) {
            return 'json';
        } elseif (strlen($value) > 255) {
            return 'text';
        } else {
            return 'string';
        }
    }
}
