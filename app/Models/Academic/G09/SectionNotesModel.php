<?php namespace App\Models\Academic\G09;

use CodeIgniter\Model;

class SectionNotesModel extends Model
{
    protected $table      = 'g09_sections_notes';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['class_id', 'report_id', 'section_id', 'notes'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}