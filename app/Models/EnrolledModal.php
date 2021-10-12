<?php namespace App\Models;

use CodeIgniter\Model;
class EnrolledModal extends Model
{
    protected $table      = 'enrolled_test';
    protected $primaryKey = 'sl';
    protected $returnType     = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['user_id','test_id','time_left','attendance','ip','tabchange','login_attempt','submitted','endtime','starttime','enrolled_on','total_marks','sharingID'];
    protected $useTimestamps = false;
    protected $createdField  = 'enrolled_on';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}