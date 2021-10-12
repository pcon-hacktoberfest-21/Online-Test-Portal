<?php namespace App\Models;

use CodeIgniter\Model;

class UserAccountModal extends Model
{
    protected $table      = 'user_account';
    protected $primaryKey = 'id';
    protected $returnType     = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['createdOn', 'email', 'isActive', 'modifiedOn', 'name','roll', 'password', 'branch', 'verified', 'picture', 'alternateEmail','sharingID'];
    protected $useTimestamps = false;
    protected $createdField  = 'createdOn';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}