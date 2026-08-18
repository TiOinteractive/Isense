<?php

namespace App\Models;  
use CodeIgniter\Model;

class LanguageModel extends Model{

    protected $table = 'language';
    
    protected $allowedFields = [
        'name',
        'short_name',
        'slug',
        'default',
        'publish'
    ];
}