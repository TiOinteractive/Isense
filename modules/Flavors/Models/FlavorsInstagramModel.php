<?php

namespace Modules\Flavors\Models;  
use CodeIgniter\Model;
use App\Libraries\Link;




class FlavorsInstagramModel extends Model{
	
			protected $table = 'flavors_tags';
			protected $allowedFields = [
				'id',
				'created_at',
				'value'
			];
			
			
			
}			