<?php

namespace App\Models;  
use CodeIgniter\Model;

class LinkModel extends Model{

    protected $table = 'links';
    
    protected $allowedFields = [
        'id_module',
        'module_slug',
        'id_lang',
        'link',
        'action',
        'slug',
        'blocked',
        'redirect',
        'sync',
        'edited_at',
        'created_at'
    ];
    
    
}