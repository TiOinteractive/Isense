<?php

namespace Modules\Flavors\Models;  
use CodeIgniter\Model;
use App\Libraries\Link;
use \App\Validation\CustomRules;



class FlavorsTagsModel extends Model{
	
			protected $table = 'flavors_tags';
			protected $allowedFields = [
				'id',
				'created_at',
				'value'
			];
			
	public function deleteTag($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $this->where('id', $id)->delete();
		$list = $this->db->table('flavors_restaurant_lang')->Select('id,tags')->Like('tags', ','.$id.',')->get()->getResultArray();
		if(!empty($list)) {
		  foreach($list as $el) {
			  $tag_array=array_filter(explode(',',$el['tags']));
			  if (($key = array_search($id, $tag_array)) !== false) {
					unset($tag_array[$key]);
			  }
			  if(!empty($tag_array)) {
				  $this->db->table('flavors_restaurant_lang')->set(array('tags'=>implode(',',$tag_array)))->where('id', $el['id'])->update();
			  }
              else {
                $this->db->table('flavors_restaurant_lang')->set(array('tags'=>''))->where('id', $el['id'])->update();
              }				  
		  }
		}
        $this->db->transComplete();
        return $this->db->transStatus();
    }			
}			