<?php namespace App\Models\Academic;

use CodeIgniter\Model;

class AchievementCategoryModel extends Model
{
    protected $table      = 'ac_achievements_category';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['name', 'parent_category_id', 'description', 'type', 'assigned_ids', 'color', 'icon'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}