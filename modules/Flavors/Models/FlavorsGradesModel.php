<?php

namespace Modules\Flavors\Models;  
use CodeIgniter\Model;
use App\Libraries\Link;
use \App\Validation\CustomRules;



class FlavorsGradesModel extends Model{
	
			protected $table = 'flavors_rating';
			protected $allowedFields = [
				'id',
				'created_at',
				'id_restaurant'
			];
		
		
		
		
		
		function SaveRestaurantRate($post,$data_form,$id_lang,$ip,$session_user) {
		
			if(empty($session_user['id'])) {return false;}
			if(!empty($data_form) and empty($data_form['rate_1']) and empty($data_form['rate_2']) and empty($data_form['rate_3']) and empty($data_form['rate_4']) and empty($data_form['comment'])) {return false;}
				$this->db->transStart();
			     if(!empty($data_form['comment'])) {
					$data = array(
					'id_user' => $session_user['id'],
					'nick' => !empty($session_user['nick']) ? $session_user['nick'] : $session_user['name'].' '.$session_user['surname'],
					'comment' => $data_form['comment'],
					'id_lang' => $id_lang,
					'id_restaurant' => $post['id_restaurant'],
					're_id' => !empty($post['id_comment']) ? $post['id_comment'] : 0,
					'publish' => 0,
					'ip' => $ip);
					$this->db->table('flavors_comments')->insert($data);
		         } 	
				 
				if(!empty($data_form['rate_1'])) {
					$data=array(
					   'id_user'=>$session_user['id'],
					   'id_restaurant'=>$post['id_restaurant'],
					   'rating'=>$data_form['rate_1'],
					   'type'=>1
					);
					$check=$this->db->table('flavors_rating')->Select('id')->Where('id_restaurant',$post['id_restaurant'])->Where('id_user',$session_user['id'])->Where('type',1)->get()->getRowArray();
					if(!empty($check['id'])) {
						$this->db->table('flavors_rating')->set($data)->where('id', $check['id'])->where('id_user',$session_user['id'])->update();
					}
                    else {
                      $this->db->table('flavors_rating')->insert($data);
                    }						
				}
				if(!empty($data_form['rate_2'])) {
					$data=array(
					   'id_user'=>$session_user['id'],
					   'id_restaurant'=>$post['id_restaurant'],
					   'rating'=>$data_form['rate_2'],
					   'type'=>2
					);
					$check=$this->db->table('flavors_rating')->Select('id')->Where('id_restaurant',$post['id_restaurant'])->Where('id_user',$session_user['id'])->Where('type',2)->get()->getRowArray();
					if(!empty($check['id'])) {
						$this->db->table('flavors_rating')->set($data)->where('id', $check['id'])->where('id_user',$session_user['id'])->update();
					}
                    else {
                      $this->db->table('flavors_rating')->insert($data);
                    }						
				}
				if(!empty($data_form['rate_3'])) {
					$data=array(
					   'id_user'=>$session_user['id'],
					   'id_restaurant'=>$post['id_restaurant'],
					   'rating'=>$data_form['rate_3'],
					   'type'=>3
					);
					$check=$this->db->table('flavors_rating')->Select('id')->Where('id_restaurant',$post['id_restaurant'])->Where('id_user',$session_user['id'])->Where('type',3)->get()->getRowArray();
					if(!empty($check['id'])) {
						$this->db->table('flavors_rating')->set($data)->where('id', $check['id'])->where('id_user',$session_user['id'])->update();
					}
                    else {
                      $this->db->table('flavors_rating')->insert($data);
                    }						
				}
				if(!empty($data_form['rate_4'])) {
					$data=array(
					   'id_user'=>$session_user['id'],
					   'id_restaurant'=>$post['id_restaurant'],
					   'rating'=>$data_form['rate_4'],
					   'type'=>4
					);
					$check=$this->db->table('flavors_rating')->Select('id')->Where('id_restaurant',$post['id_restaurant'])->Where('id_user',$session_user['id'])->Where('type',4)->get()->getRowArray();
					if(!empty($check['id'])) {
						$this->db->table('flavors_rating')->set($data)->where('id', $check['id'])->where('id_user',$session_user['id'])->update();
					}
                    else {
                      $this->db->table('flavors_rating')->insert($data);
                    }						
				}
				$this->db->transComplete();
				return $this->db->transStatus();
		}	
		
		function GetRestaurantUserRate($id_restaurant,$id_lang,$session_user) {
			$grades=$this->db->table('flavors_rating')->Select('type,rating,id_user')->Where('id_restaurant',$id_restaurant)->Where('id_user',$session_user['id'])->get()->getResultArray();
			$cnt=0;
			if(!empty($grades)) {
			   foreach($grades as $grade) {
				   $user_grades['list'][$grade['type']]=round($grade['rating']);
				   $cnt=$cnt+round($grade['rating']);
			   }
			  $user_grades['main']=number_format(round(($cnt/count($user_grades['list']))),1, '.', ''); 
			  return $user_grades;
			}	
        }				
}			