<?php
 
namespace App\Models;
 
use CodeIgniter\Model;
 
class Article extends Model
{
    protected $table            = 'article';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
 
    // Tohle je klíčové – sloupce, které může model zapisovat
    protected $allowedFields = [
        'link',
        'title',
        'photo',
        'date',
        'top',
        'text'
    ];
 
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
 
    protected array $casts = [];
    protected array $castHandlers = [];
 
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'int';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
 
    // Validation
    protected $validationRules      = [];
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
}