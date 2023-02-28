<?php namespace App\Models\Academic;

use CodeIgniter\Model;

class CompletedAchievementModel extends Model
{
    protected $table      = 'ac_completed_achievements';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['achievement_id', 'user_id'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}