<?php namespace App\Models\Academic;

use CodeIgniter\Model;

class AchievementModel extends Model
{
    protected $table      = 'ac_achievements';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['name', 'category_id', 'header_field_id', 'row_code'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}