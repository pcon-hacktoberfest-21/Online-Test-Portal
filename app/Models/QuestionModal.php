<?php namespace App\Models;

use CodeIgniter\Model;
class QuestionModal extends Model
{
    protected $table      = 'questions';
    protected $primaryKey = 'sl';
    protected $returnType     = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['question_id','test_id','question','image','option_a','option_b','option_c','option_d','answer','negativeMarking','positiveMarking','section'];
    protected $useTimestamps = false;
    protected $createdField  = 'created';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}