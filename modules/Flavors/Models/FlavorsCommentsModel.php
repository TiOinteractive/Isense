<?php

namespace Modules\Flavors\Models;  
use CodeIgniter\Model;
use App\Libraries\Link;
use \App\Validation\CustomRules;



class FlavorsCommentsModel extends Model{
	
			protected $table = 'flavors_comments';
			protected $allowedFields = [
				'id',
				'created_at',
				'id_restaurant',
				'comment',
				'publish',
				'status'
				
			];
			
	public function deleteComment($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $this->where('id', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }	
			
	public function saveComment($id) {



		return true;
    }		


     public function changeCommenStatus($id, $status) {
        if(empty($id) || empty($status)) return false;
        return $this->where('id', $id)->set('status', $status)->update();
    }


	
}			