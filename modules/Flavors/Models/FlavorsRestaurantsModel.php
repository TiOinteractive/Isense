<?php

namespace Modules\Flavors\Models;  
use CodeIgniter\Model;
use App\Libraries\Link;
use \App\Validation\CustomRules;


class FlavorsRestaurantsModel extends Model{
	
	protected $table = 'flavors_restaurant';
			protected $allowedFields = [
				'publish',
				'edited_at',
				'id',
				'created_at',
				'id_category',
				'coordinates',
				'additional_category',
				'dish_type',
				'cuisine_type',
				'recommended',
				'news',
				'awarded',
				'order',
				'archives',
				'paid_entry_from',
				'paid_entry_to',
				'publish'
			];
	
	
	public function saveRestaurant($id,$post) {
		 helper(['file']);
		 if(empty($post)) return false;
		 
		 if(!empty($post['paid_entry_range'])) {
			$paid_entry=explode(' - ',$post['paid_entry_range']);
            $paid_entry_from=date("Y-m-d",strtotime($paid_entry[0])); 			
			$paid_entry_to=date("Y-m-d",strtotime($paid_entry[1]));  
	     }		 
		 $data = array(
            'publish' => !empty($post['publish']) ? $post['publish'] : 0,
			'archives' => !empty($post['archives']) ? $post['archives'] : 0,
			'awarded' => !empty($post['awarded']) ? $post['awarded'] : 0,
			'recommended' => !empty($post['recommended']) ? $post['recommended'] : 0,
			'id_category' => !empty($post['id_category']) ? $post['id_category'] : 0,
			'additional_category' => !empty($post['categories']) ? ','.implode(',',$post['categories']).',' : '',
			'dish_type' => !empty($post['dish_type']) ? ','.implode(',',$post['dish_type']).',' : '',
			'news' => !empty($post['news']) ? ','.implode(',',array_unique($post['news'])).',' : '',
			'cuisine_type' => !empty($post['cuisine']) ? ','.implode(',',$post['cuisine']).',' : '',
			'coordinates' => !empty($post['coordinates']) ? str_replace(',','|',$post['coordinates']) : '',
			'paid_entry_from'=> !empty($paid_entry_from) ? $paid_entry_from : NULL,
			'paid_entry_to'=> !empty($paid_entry_to) ? $paid_entry_to : NULL,
        );
		 
		 $this->db->transStart();
		 if($id) {
            $result = $this->set($data)->where('id', $id)->update();
            $this->id = $id;
        } else {
            $result = $this->set('order', '`order`+1', FALSE)->Where('order >=',0)->Where('id_category',$post['id_category'])->update();
            $result = $this->insert($data);
            $this->id = $this->getInsertID();
        }
        
		 $this->saveRestaurantLang($this->id, $post['lang']); 
		 $this->saveRestaurantMetaLang($this->id, $post['meta']['lang']);
         $this->saveRestaurantFiles($this->id, !empty($post['menu']) ? $post['menu'] : array(), 'menu');		 
		 $this->saveRestaurantFile($this->id, !empty($post['logo']) ? $post['logo'] : array(), 'logo', true);
		 $this->saveRestaurantFile($this->id, !empty($post['photo']) ? $post['photo'] : array(), 'photo', true);
         $this->saveRestaurantFiles($this->id, !empty($post['photos']) ? $post['photos'] : array(), 'photos');
         $this->saveRestaurantParameters($this->id,$post['parameter'],$_POST['parameter_select']);
		 $this->db->transComplete();
         return $this->db->transStatus();
	}
	
	private function saveRestaurantLang($id_restaurant, $lang_data) 
    {
        if(!empty($lang_data)) {
			$module = $this->db->table('module')->select('id')->where('slug', 'Flavors')->get()->getRowArray();
			foreach($lang_data as $id_lang=>$lang) {
				if(!empty($lang['tags'])) {	
						$lang['tags']= ','.$this->AddTags($lang['tags'],$id_lang).',';
				}
				else {$lang['tags']='';}
				
				$linkClass = new Link();
				$data = array(
						'id_restaurant' => $id_restaurant,
						'id_lang' => $id_lang,
						'id_link' => $linkClass->saveLink($lang['link'], $id_lang, $lang['id_link'], !empty($module) ? $module['id'] : 0,false,null,'restaurant'),
						'name' => $lang['name'],
						'name2' => $lang['name2'],
						'introduction' => $lang['introduction'],
						'email'=> $lang['email'],
						'www' => $lang['www'],
						'social_link'=>$lang['social_link'],
						'phone'=>$lang['phone'],
						'address '=>$lang['address'],
						'city'=>$lang['city'],
						'reservation'=>$lang['reservation'],
						'working_hours'=>nl2br($lang['working_hours']),
						'chef'=>$lang['chef'],
						'opening_year'=>$lang['opening_year'],
						'table_numbers'=>$lang['table_numbers'],
						'speciality'=>$lang['speciality'],
						'tags'=>$lang['tags'],
						'introduction'=>$lang['introduction'],
						'description'=>$lang['description'],
						'delivery'=>$lang['delivery'],
						'menu'=>$lang['menu']
				);
				
				$lang = $this->db->table('flavors_restaurant_lang')->select('id')->where('id_restaurant', $id_restaurant)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('flavors_restaurant_lang')->set($data)->where('id_restaurant', $id_restaurant)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('flavors_restaurant_lang')->insert($data);
                }	
			}
	    }
	
	}
	
	
	public function AddTags($tags,$id_lang) {
		if(!empty($tags)) {
		  $tags=explode(',',$tags);
		  $tags_array=array();
          if(!empty($tags)) {
			  foreach($tags as $tag) {
			    $tag_info = $this->db->table('flavors_tags')->Select('id')->Where('value', $tag)->get()->getRowArray();
			    if(!empty($tag_info)) {
				  $tags_array[]=$tag_info['id'];	
				}
                else {
					 $data = array(
								'id_lang'=>$id_lang,
								'value'=>$tag
					);
					$result = $this->db->table('flavors_tags')->insert($data);
                    $tags_array[] = $this->db->insertID();
                }					
			  }
		  }	  
		if(!empty($tags_array)) {
             return implode(',',$tags_array);	
        } 		
		}
	}
	
	private function saveRestaurantFiles($id_restaurant, $files, $field='') 
    {
        $ids = array();
        if(!empty($files)) {
            foreach($files as $file) {
                $ids[] = $this->saveRestaurantFile($id_restaurant, $file, $field);
            }
        }
        $query = $this->db->table('flavors_restaurant_files')->select('id,path')->where('id_restaurant', $id_restaurant)->where('field', $field);
        if(!empty($ids)) {
            $query->whereNotIn('id', $ids);
        }
        $files_list = $query->get()->getResultArray();
        if(!empty($files_list)) {
            foreach($files_list as $f) {
                $this->removeRestaurantFile($f);
            }
        }
    }
    
    private function saveRestaurantFile($id_restaurant, $file, $field='', $remove=false) 
    {
        if(!empty($file)) {
            if(!empty($file['id']) && !empty($this->db->table('flavors_restaurant_files')->select('id')->where('id', $file['id'])->get()->getRowArray())) {
                $data = array(
                    'order' => !empty($file['order']) ? $file['order'] : 0,
                    'publish' => !empty($file['publish']) ? $file['publish'] : 0,
					'crop_dimension' => !empty($file['crop']) ? json_encode($file['crop']) : ''

                );
                $result = $this->db->table('flavors_restaurant_files')->set($data)->where('id', $file['id'])->update();
                $id_file = $file['id'];
            } else {
                $file_obj = new \CodeIgniter\Files\File(WRITEPATH . 'uploads/' . $file['path']);
                if(!is_dir(WRITEPATH . 'uploads/flavors')) {
                    mkdir(WRITEPATH . 'uploads/flavors');
                }
                if(!is_dir(WRITEPATH . 'uploads/flavors/' . date('Ymd'))) {
                    mkdir(WRITEPATH . 'uploads/flavors/' . date('Ymd'));
                }
                $r = $file_obj->move(WRITEPATH . 'uploads/flavors/' . date('Ymd') , $file['basename']);
                $file_path = 'flavors/' . date('Ymd') . '/' . $r->getFilename();
                $file_info = pathinfo(WRITEPATH . 'uploads/' . $file_path);
                if(!empty($file) && !empty($file['crop'])) {
                    $file['crop']['path'] = $file_path;
                }
                $data = array(
                    'id_restaurant' => $id_restaurant,
                    'field' => $field,
                    'name' => $file['name'],
                    'basename' => $file['basename'],
                    'path' => $file_path,
                    'mime' => $r->getMimeType(),
                    'type' => file_type($r->getMimeType()),
                    'ext' => $file_info['extension'],
                    'order' => !empty($file['order']) ? $file['order'] : 0,
                    'publish' => !empty($file['publish']) ? $file['publish'] : 0,
					'crop_dimension' => !empty($file['crop']) ? json_encode($file['crop']) : ''
                );
                $result = $this->db->table('flavors_restaurant_files')->insert($data);
                $id_file = $this->db->insertID();
            }
            $this->saveRestaurantFileLang($id_file, $file['lang']);
        }
        if($remove) {
            $query = $this->db->table('flavors_restaurant_files')->select('id,path')->where('id_restaurant', $id_restaurant)->where('field', $field);
            if(!empty($id_file)) {
                $query->where('id !=', $id_file);
            }
            $files_list = $query->get()->getResultArray();
            if(!empty($files_list)) {
                foreach($files_list as $f) {
                    $this->removeRestaurantFile($f);
                }
            }
        }
        return !empty($id_file) ? $id_file : 0;
    }
    
    private function saveRestaurantFileLang($id_file, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_file' => $id_file,
                    'id_lang' => $id_lang,
                    'caption' => $lang['caption'],
                    'author' => $lang['author'],
                );
                $lang = $this->db->table('flavors_restaurant_files_lang')->select('id')->where('id_file', $id_file)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('flavors_restaurant_files_lang')->set($data)->where('id_file', $id_file)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('flavors_restaurant_files_lang')->insert($data);
                }
            }
        }
    }
    
    private function removeRestaurantFile($file) 
    {
        if(file_exists(WRITEPATH . 'uploads/' . $file['path'])) {
            @unlink(WRITEPATH . 'uploads/' . $file['path']);
        }
        $this->db->table('flavors_restaurant_files_lang')->where('id_file', $file['id'])->delete();
        $this->db->table('flavors_restaurant_files')->where('id', $file['id'])->delete();
    }	
	
	private function saveRestaurantParameters($id_restaurant,$parameters,$parameters_select) {
		if(!empty($parameters)) {
		   foreach($parameters as $id_par=>$param) {
		       foreach($param as $id_val=>$value) {
		          if(!empty($parameters_select[$id_par][$id_val])) {
						$data = array(
							'id_restaurant' => $id_restaurant,
							'id_parameter' => $id_par,
							'id_value' => $parameters_select[$id_par][$id_val]
						);
						$check = $this->db->table('flavors_restaurant_parameters')->select('id')->where('id_restaurant', $id_restaurant)->where('id_parameter', $id_par)->where('id_value',$parameters_select[$id_par][$id_val])->get()->getRowArray();
						if(empty($check)) {
							$this->db->table('flavors_restaurant_parameters')->insert($data);
						} 
		          }
                  elseif(!empty($value)) {				  
		           $check = $this->db->table('flavors_parameters_value p')->join('flavors_parameters_value_lang pl', 'p.id = pl.id_value')->select('p.id')->where('p.id_parameter',$id_par)->where('pl.value', $value)->where('pl.id_lang',1)->get()->getRowArray();
		           if(!empty($check)) {
                      $id_value=$check;
				   }	   
		           else {
					   $data = array(
							'id_parameter' => $id_par
						);
					   $this->db->table('flavors_parameters_value')->insert($data);
                       $id_par_value=$this->db->insertID(); 
					   $data = array(
							'id_value' => $id_par_value,
							'id_lang' => 1,
							'value' => $value
						);
                        $this->db->table('flavors_parameters_value_lang')->insert($data);
                        $id_value=$this->db->insertID();  
				   }
                    if(!empty($id_value)) {
						$data = array(
							'id_restaurant' => $id_restaurant,
							'id_parameter' => $id_par,
							'id_value' => $id_value
						);
						$this->db->table('flavors_restaurant_parameters')->insert($data);
                    }
		          } 
			   }
		   }
		}
	}

    private function saveRestaurantMetaLang($id_restaurant, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_restaurant' => $id_restaurant,
                    'id_lang' => $id_lang,
                    'title' => $lang['title'],
                    'description' => $lang['description'],
                );
                $lang = $this->db->table('flavors_restaurant_meta_lang')->select('id')->where('id_restaurant', $id_restaurant)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('flavors_restaurant_meta_lang')->set($data)->where('id_restaurant', $id_restaurant)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('flavors_restaurant_meta_lang')->insert($data);
                }
            }
        }
    }	
	
	public function getRestaurantById($id_restaurant,$id_lang) {
		$restaurant = $this->where('id', $id_restaurant)->first();
        if(!empty($restaurant)) {
            $restaurant['lang'] = $this->getRestaurantLang($id_restaurant);
            if(!empty($restaurant['lang']) && !empty($restaurant['lang'][$id_lang]) && !empty($restaurant['lang'][$id_lang]['title'])) {
                $restaurant['name'] = $restaurant['lang'][$id_lang]['name'];
            } else {
                $restaurant['name'] = '';
            }
            $restaurant['meta']['lang'] = $this->getRestaurantMetaLang($id_restaurant);
			if(!empty($restaurant['paid_entry_from']) and !empty($restaurant['paid_entry_to'])) {
				$restaurant['paid_entry_range']=date("d.m.Y",strtotime($restaurant['paid_entry_from'])).' - '.date("d.m.Y",strtotime($restaurant['paid_entry_to']));
			}
			$restaurant['additional_category']=$this->getRestaurantAdditionalCategory($restaurant['additional_category'],$id_lang);
			$restaurant['cuisine_type']=$this->getRestaurantCuisineType($restaurant['cuisine_type'],$id_lang);
			$restaurant['menu'] = $this->getRestaurantFiles($id_restaurant, 'menu');
			$restaurant['news'] = $this->getRestaurantNews($restaurant['news'],$id_lang);
			$restaurant['logo'] = $this->getRestaurantFile($id_restaurant, 'logo');
			$restaurant['photo'] = $this->getRestaurantFile($id_restaurant, 'photo');
			$restaurant['photos'] = $this->getRestaurantFiles($id_restaurant, 'photos');
            $restaurant['parameters'] = $this->getRestaurantParameters($id_restaurant,$id_lang);
			if(!empty($restaurant['dish_type'])) {
				$restaurant['dish_type']=explode(',',trim($restaurant['dish_type'],','));
			}	
			if(!empty($restaurant['coordinates'])) {
				$restaurant['coordinates_array']=explode('|',$restaurant['coordinates']);
			}	
        }
        return $restaurant;
	}	
	
	 private function getRestaurantFile($id_restaurant, $field='') 
    {
        $file = $this->db->table('flavors_restaurant_files')->select('id,name,basename,path,order,type,publish,ext,crop_dimension')->where('id_restaurant', $id_restaurant)->where('field', $field)->orderBy('order', 'ASC')->get()->getRowArray();
        if(!empty($file)) {
            $file['lang'] = $this->getRestaurantFileLang($file['id']);
        }
		if(!empty($file['crop_dimension'])) {
			$file['crop_dimension']=json_decode($file['crop_dimension']);
		}
        return $file;
    }
	
	private function getRestaurantNews($news,$id_lang) {
	   if(!empty($news)) {
		  $news_id=array_unique(explode(',',$news)); 
		  $related_news=$this->db->table('news n')->join('news_lang nl', 'nl.id_news = n.id','left')->Select('n.id,title,n.created_at,n.id_page_cont')->Where('id_lang',$id_lang)->Where('publish',1)->OrderBy('n.created_at','DESC')->whereIn('n.id',$news_id)->get()->getResultArray();
			if(!empty($related_news)) {
			   foreach($related_news as $k=>$v) {	
				  $related_news[$k]['photo']=$this->db->table('news_files')->Select('path')->Where('field','photo')->Where('id_news',$v['id'])->get()->getRowArray();
			   } 
		   
		   return $related_news;
	   }	   
	}
	}
    
    private function getRestaurantFiles($id_restaurant, $field='') 
    {
        $files = $this->db->table('flavors_restaurant_files')->select('id,name,basename,path,order,type,publish,ext')->where('id_restaurant', $id_restaurant)->where('field', $field)->orderBy('order', 'ASC')->get()->getResultArray();
        if(!empty($files)) {
            foreach($files as $k=>$file) {
                $files[$k]['lang'] = $this->getRestaurantFileLang($file['id']);
            }
        }
        return $files;
    }
	
	  private function getRestaurantFileLang($id_file) 
    {
        $langs = array();
        $data = $this->db->table('flavors_restaurant_files_lang')->where('id_file', $id_file)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
	
	private function getRestaurantLang($id_restaurant) 
    {
        $linkClass = new Link();
        $langs = array();
        $data = $this->db->table('flavors_restaurant_lang')->where('id_restaurant', $id_restaurant)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
				if(!empty($d['tags'])) {
					$d['tags']=$this->GetRestaurantTagsById($d['tags']);
				}	
                $d['link'] = $linkClass->getLink($d['id_link'], $d['id_lang']);
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
	
	 public function getRestaurantMetaLang($id_restaurant) 
    {
        $langs = array();
        $data = $this->db->table('flavors_restaurant_meta_lang')->where('id_restaurant', $id_restaurant)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
	
	public function GetRestaurantTagsById($tags) {
		$tags=explode(',',$tags);
		$tags_array=array();
		$data = $this->db->table('flavors_tags')->Select('value')->whereIn('id', $tags)->orderBy('value')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $tags_array[]=$d['value'];
            }
        }
		if(!empty($tags_array)) {
		  $tags_array=implode(',',$tags_array);
          return 	$tags_array;	  
		}	
	}
	
	private function getRestaurantParameters($id_restaurant,$id_lang) {
		$parameters=$this->db->table("flavors_restaurant_parameters rp")->join('flavors_parameters_value_lang pl', 'rp.id_value = pl.id_value')->Select('rp.id_parameter,pl.id_value,pl.value')->Where('pl.id_lang',$id_lang)->Where('rp.id_restaurant',$id_restaurant)->orderBy('value', 'ASC')->get()->getResultArray();
		if(!empty($parameters)) {
		   foreach($parameters as $parameter) {
				$list_param[$parameter['id_parameter']][$parameter['id_value']]=$parameter['value'];
		   }
		   		return $list_param;
		}
	}	
	
	private function getRestaurantAdditionalCategory($kats,$id_lang) {
	   if(!empty($kats) and !empty(trim($kats,','))) {
		  $categories=$this->db->table("flavors_categories c")->join('flavors_categories_lang cl', 'c.id = cl.id_category')->Select('c.id,cl.name')->whereIn('c.id',explode(',',$kats))->Where('cl.id_lang',$id_lang)->OrderBy('name','ASC')->get()->getResultArray(); 
		   return $categories;
	   }	
	}	

	private function getRestaurantCuisineType($kats,$id_lang) {
	   if(!empty($kats) and !empty(trim($kats,','))) {
		  $categories=$this->db->table("flavors_cuisine c")->join('flavors_cuisine_lang cl', 'c.id = cl.id_cuisine')->Select('c.id,cl.name')->whereIn('c.id',explode(',',$kats))->Where('cl.id_lang',$id_lang)->OrderBy('name','ASC')->get()->getResultArray(); 
		   return $categories;
	   }	
	}	

    public function deleteRestaurant($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $langs = $this->db->table('flavors_restaurant_lang')->select('id_link')->where('id_restaurant', $id)->get()->getResultArray();
        if(!empty($langs)) {
            foreach($langs as $l) {
                $this->db->table('links')->where('id', $l['id_link'])->delete();
            }
        }
        $files_list = $this->db->table('flavors_restaurant_files')->select('id,path')->where('id_restaurant', $id)->get()->getResultArray();
        if(!empty($files_list)) {
            foreach($files_list as $f) {
                $this->removeRestaurantFile($f);
            }
        }
        $info = $this->select('order,id_category')->where('id', $id)->first();
        $this->db->table('flavors_restaurant_lang')->where('id_restaurant', $id)->delete();
		$this->db->table('flavors_restaurant_parameters')->where('id_restaurant', $id)->delete();
        $this->db->table('flavors_restaurant_meta_lang')->where('id_restaurant', $id)->delete();
		$this->db->table('flavors_comments')->where('id_restaurant', $id)->delete();
		$this->db->table('flavors_rating')->where('id_restaurant', $id)->delete();
        $this->where('id', $id)->delete();
        $this->set('order', '`order`-1', FALSE)->where('order >', $info['order'])->where('id_category', $info['id_category'])->update();
        $this->db->transComplete();
        return $this->db->transStatus();
    }


	function saveRestaurantOrder($data,$id_content) {
		$this->db->transStart();
		if(!empty($data['restaurant_order'])) {
            foreach($data['restaurant_order'] as $order=>$lokal_id) {
					$this->db->table('flavors_restaurant')->set('order', $order, FALSE)->where('id',$lokal_id)->update();
			}
		}
		$this->db->transComplete();
        return $this->db->transStatus();
	}

	
	function RestaurantListMenu($id_lang) {
		$cat_list=$this->db->table('flavors_categories c')->join('flavors_categories_lang cl', 'c.id = cl.id_category')->join('links l', 'l.id = cl.id_link')->Select('c.id,link,cl.name,c.svg')->Where('cl.id_lang',$id_lang)->Where('l.id_lang',$id_lang)->Where('c.publish',1)->OrderBy('c.order','ASC')->get()->getResultArray();
		if(!empty($cat_list)) {
			foreach($cat_list as $k=>$cat) {  
			   $list=$this->RestaurantListCategoryMenu($id_lang,$cat['id']);
			   $cnt=0;
			   if(!empty($list)) {
				   $cnt=count($list);
			      
			   }
			   $cat_list[$k]['count']=$cnt;
			   //$cat_list[$k]['menuicon']=$this->db->table('flavors_categories_files')->Select('id,path')->Where('id_category',$cat['id'])->Where('field','menu')->get()->getRowArray();
			}
		  return $cat_list;	
		}	
	}	
	
	function RestaurantListCategoryMenu($id_lang,$cat_id) {
		
		
		$restaurants_list=$this->db->table('flavors_restaurant r')->join('flavors_restaurant_lang rl', 'r.id = rl.id_restaurant')->join('links l', 'l.id = rl.id_link')->Select('r.id,link,rl.name,rl.address')->Where('rl.id_lang',$id_lang)->Where('l.id_lang',$id_lang)->Where('r.publish',1)->Where('r.archives',0)->GroupStart()->Where('r.id_category',$cat_id)->orLike('additional_category', ",".$cat_id.",")->GroupEnd()->OrderBy('rl.name','ASC')->get()->getResultArray();
		return $restaurants_list;
	}

   function RestaurantListCategoryAwardedMenu($id_lang,$cat_id) {
	   $restaurants_list=$this->db->table('flavors_restaurant r')->join('flavors_restaurant_lang rl', 'r.id = rl.id_restaurant')->join('links l', 'l.id = rl.id_link')->Select('r.id,link,rl.name,rl.address')->Where('rl.id_lang',$id_lang)->Where('l.id_lang',$id_lang)->Where('r.publish',1)->Where('r.archives',0)->Where('r.awarded',1)->GroupStart()->Where('r.id_category',$cat_id)->orLike('additional_category', ",".$cat_id.",")->GroupEnd()->OrderBy('rl.name','RANDOM')->limit(5)->get()->getResultArray();
	   if(!empty($restaurants_list)) {
	      foreach($restaurants_list as $k=>$rest){
			  $restaurants_list[$k]['photo']=$this->getRestaurantFile($rest['id'], 'photo');
	      }
		  return $restaurants_list;
       }
   }	
   
   function CountRestaurantsAll() {
     return $this->db->table('flavors_restaurant')->Select('id')->Where('publish',1)->Where('archives',0)->countAllResults();
   }



   function GetCuisineParameters($filters,$id_lang,$id_cuisine) { 
	  if(!empty($filters['f'])) {
	      foreach($filters['f'] as $k=>$filter_values) {
			foreach($filter_values as $c=>$filter) {  
			   $product_list=$this->db->table('flavors_restaurant_parameters p')->join('flavors_restaurant r', 'p.id_restaurant=r.id')->Select('p.id_restaurant')->Where('r.publish',1)->Like('r.cuisine_type',','.$id_cuisine.',')->Where('p.id_value',$filter)->Where('r.archives',0)->groupBy('p.id_restaurant')->get()->getResultArray();
			   if(!empty($product_list)) {
				   foreach($product_list as $prod) {
				      if(!empty($prod['id_restaurant'])) { $ids_p[$c][]=$prod['id_restaurant'];}
				   }
			   }	
            } 
	      }
	   }
	   $parameters_list=$this->db->table('flavors_parameters p')->join('flavors_parameters_lang pl', 'p.id = pl.id_parameter')->Select('p.id,pl.name,pl.filter_name')->Where('pl.id_lang',$id_lang)->Where('p.publish',1)->OrderBy('pl.name','ASC')->get()->getResultArray();
        if(!empty($parameters_list)) {
			foreach($parameters_list as $id_par=>$param) {
			   $value_list=$this->db->table('flavors_parameters_value v')->join('flavors_parameters_value_lang vl', 'v.id = vl.id_value')->Select('v.id,vl.value')->Where('vl.id_lang',$id_lang)->Where('v.id_parameter',$param['id'])->OrderBy('vl.value','ASC')->get()->getResultArray();
		       if(!empty($value_list)) {
				   $cnt=0;
		          foreach($value_list as $id_val=>$value) {
						$query=$this->db->table('flavors_restaurant_parameters p')->join('flavors_restaurant r', 'p.id_restaurant=r.id')->Select('p.id')->Where('r.publish',1)->Where('p.id_value',$value['id'])->Where('p.id_parameter',$param['id'])->Where('r.archives',0)->groupBy('p.id_restaurant');
						if(!empty($id_cuisine)) {
						   $query->Like('r.cuisine_type',','.$id_cuisine.',');
						}
						$value_list[$id_val]['count']=$query->countAllResults();
						if(!empty($ids_p)) {
						    $query2=$this->db->table('flavors_restaurant_parameters p')->join('flavors_restaurant r', 'p.id_restaurant=r.id')->Select('p.id')->Where('r.publish',1)->Where('p.id_value',$value['id'])->Where('p.id_parameter',$param['id'])->Where('r.archives',0)->groupBy('p.id_restaurant');
							if(!empty($id_cuisine)) {
							   $query2->Like('r.cuisine_type',','.$id_cuisine.',');
							}  
							if(!empty($filters['letter'])) {
                               $query2->join('flavors_restaurant_lang fl', 'r.id = fl.id_restaurant')->Where('fl.id_lang',$id_lang)->Like('name',$filters['letter'], 'after');
							}	
							$query2->GroupStart();
						     foreach($ids_p as $ids) {
								 if(!empty($ids)) {$query2->WhereIn('p.id_restaurant',$ids);}
							 }	 
						$query2->GroupEnd();
						  $value_list[$id_val]['count_filter']=$query2->countAllResults();
						}
						elseif(!empty($filters['letter'])) {
						  $query2=$this->db->table('flavors_restaurant_parameters p')->join('flavors_restaurant r', 'p.id_restaurant=r.id')->Select('p.id')->Where('r.publish',1)->Where('p.id_value',$value['id'])->Where('p.id_parameter',$param['id'])->Where('r.archives',0)->groupBy('p.id_restaurant');	
						  $query2->join('flavors_restaurant_lang fl', 'r.id = fl.id_restaurant')->Where('fl.id_lang',$id_lang)->Like('name',$filters['letter'], 'after');	
						  if(!empty($id_cuisine)) {
							   $query2->Like('r.cuisine_type',','.$id_cuisine.',');
							}  
							  $value_list[$id_val]['count_filter']=$query2->countAllResults();
						}	
						$cnt=$cnt+$value_list[$id_val]['count'];
		          }
				   $parameters_list[$id_par]['value_count']=$cnt;
				  $parameters_list[$id_par]['value_list']=$value_list;
		       }
			}
		}	
       return $parameters_list;
   }

		function GetRestaurantListById($id,$id_lang) {
		  $restaurant=$this->join('flavors_restaurant_lang rl', 'flavors_restaurant.id = rl.id_restaurant')->select('flavors_restaurant.id,flavors_restaurant.id_category,rl.name,rl.name2,l.link,rl.address,rl.city,awarded')->join('links l', 'l.id = rl.id_link')->where('flavors_restaurant.id', $id)->Where('rl.id_lang',$id_lang)->Where('l.id_lang',$id_lang)->get()->getRowArray();
		   if(!empty($restaurant)) {
			   $restaurant['photo']=$this->db->table('flavors_restaurant_files f')->join('flavors_restaurant_files_lang fl', 'fl.id_file = f.id','left')->Select('f.path,fl.caption')->Where('fl.id_lang',$id_lang)->Where('f.id_restaurant',$restaurant['id'])->Where('f.field','photo')->get()->getRowArray();
			   $restaurant['avg']=$this->db->table('flavors_rating')->Select('AVG(rating) as rating,COUNT(DISTINCT id_user) as cnt')->Where('id_restaurant',$restaurant['id'])->get()->getRowArray();
			   $restaurant['menu']=$this->db->table('flavors_restaurant_files')->Select('id')->Where('id_restaurant',$restaurant['id'])->Where('field','menu')->countAllResults();
			   $restaurant['telefon']=$this->db->table('flavors_restaurant_parameters')->Select('id')->Where('id_restaurant',$restaurant['id'])->Where('id_value',35)->countAllResults();
			   $restaurant['category']=$this->db->table('flavors_categories c')->join('flavors_categories_lang cl', 'cl.id_category = c.id','left')->join('links l', 'cl.id_link = l.id','left')->Select('c.id,cl.name,l.link')->Where('c.id',$restaurant['id_category'])->Where('cl.id_lang',$id_lang)->Where('c.publish',1)->get()->getRowArray();
		   }	 
           return $restaurant;
		}	
		
	function GetCategoryParameters($filters,$id_lang,$id_category) { 
	  if(!empty($filters['f'])) {
	      foreach($filters['f'] as $k=>$filter_values) {
			foreach($filter_values as $c=>$filter) {  
			   $product_list=$this->db->table('flavors_restaurant_parameters p')->join('flavors_restaurant r', 'p.id_restaurant=r.id')->Select('p.id_restaurant')->Where('r.publish',1)->GroupStart()->Where('r.id_category',$id_category)->orLike('r.additional_category',','.$id_category.',')->GroupEnd()->Where('p.id_value',$filter)->Where('r.archives',0)->groupBy('p.id_restaurant')->get()->getResultArray();
			   if(!empty($product_list)) {
				   foreach($product_list as $prod) {
				      if(!empty($prod['id_restaurant'])) { $ids_p[$c][]=$prod['id_restaurant'];}
				   }
			   }	
            } 
	      }
	   }
	  
	   $parameters_list=$this->db->table('flavors_parameters p')->join('flavors_categories_parameters cp', 'p.id = cp.id_parameter')->join('flavors_parameters_lang pl', 'p.id = pl.id_parameter')->Select('p.id,pl.name,pl.filter_name')->Where('pl.id_lang',$id_lang)->Where('cp.id_category',$id_category)->Where('p.publish',1)->OrderBy('pl.name','ASC')->get()->getResultArray();
        if(!empty($parameters_list)) {
			foreach($parameters_list as $id_par=>$param) {
			   $value_list=$this->db->table('flavors_parameters_value v')->join('flavors_parameters_value_lang vl', 'v.id = vl.id_value')->Select('v.id,vl.value')->Where('vl.id_lang',$id_lang)->Where('v.id_parameter',$param['id'])->OrderBy('vl.value','ASC')->get()->getResultArray();
		       if(!empty($value_list)) {
				   $cnt=0;
		          foreach($value_list as $id_val=>$value) {
						$query=$this->db->table('flavors_restaurant_parameters p')->join('flavors_restaurant r', 'p.id_restaurant=r.id')->Select('p.id')->Where('r.publish',1)->Where('p.id_value',$value['id'])->Where('p.id_parameter',$param['id'])->Where('r.archives',0)->GroupStart()->Where('r.id_category',$id_category)->orLike('r.additional_category',','.$id_category.',')->GroupEnd()->groupBy('p.id_restaurant');
						$value_list[$id_val]['count']=$query->countAllResults();
						if(!empty($ids_p)) {
						    $query2=$this->db->table('flavors_restaurant_parameters p')->join('flavors_restaurant r', 'p.id_restaurant=r.id')->Select('p.id')->Where('r.publish',1)->Where('p.id_value',$value['id'])->Where('p.id_parameter',$param['id'])->Where('r.archives',0)->groupBy('p.id_restaurant');
							$query2->GroupStart();
							if(!empty($id_category)) {
							   $query2->Where('r.id_category',$id_category);	
							   $query2->orLike('r.additional_category',','.$id_category.',');
							}  
							$query2->GroupEnd();
							if(!empty($filters['letter'])) {
                               $query2->join('flavors_restaurant_lang fl', 'r.id = fl.id_restaurant')->Where('fl.id_lang',$id_lang)->Like('name',$filters['letter'], 'after');
							}	
							$query2->GroupStart();
						     foreach($ids_p as $ids) {
								 if(!empty($ids)) {$query2->orWhereIn('p.id_restaurant',$ids);}
							 }	 
						$query2->GroupEnd();
						  $value_list[$id_val]['count_filter']=$query2->countAllResults();
						}
						elseif(!empty($filters['letter'])) {
						  $query2=$this->db->table('flavors_restaurant_parameters p')->join('flavors_restaurant r', 'p.id_restaurant=r.id')->Select('p.id')->Where('r.publish',1)->Where('p.id_value',$value['id'])->Where('p.id_parameter',$param['id'])->Where('r.archives',0)->groupBy('p.id_restaurant');	
						  $query2->join('flavors_restaurant_lang fl', 'r.id = fl.id_restaurant')->Where('fl.id_lang',$id_lang)->Like('name',$filters['letter'], 'after');	
						  $query2->GroupStart();
							if(!empty($id_category)) {
							   $query2->Where('r.id_category',$id_category);	
							   $query2->orLike('r.additional_category',','.$id_category.',');
							}  
							$query2->GroupEnd();
							  $value_list[$id_val]['count_filter']=$query2->countAllResults();
						}	
						$cnt=$cnt+$value_list[$id_val]['count'];
		          }
				   $parameters_list[$id_par]['value_count']=$cnt;
				  $parameters_list[$id_par]['value_list']=$value_list;
		       }
			}
		}	
       return $parameters_list;
   }
   
   function GetRestaurant($id_link,$id_lang) {
	   
	    $restaurant=$this->join('flavors_restaurant_lang rl', 'flavors_restaurant.id = rl.id_restaurant')->join('links l', 'l.id = rl.id_link')->join('flavors_restaurant_meta_lang rm', 'flavors_restaurant.id = rm.id_restaurant')->select('id_category,archives,additional_category,cuisine_type,rl.*,rm.title as meta_title,rm.description as meta_description,rm.keywords as meta_keywords,coordinates,link,dish_type,news,awarded,flavors_restaurant.id as id')->Where('rl.id_lang',$id_lang)->Where('rl.id_link',$id_link)->Where('rm.id_lang',$id_lang)->Where('publish',1)->get()->getRowArray();
		if(!empty($restaurant)) {
			
		if(!empty($restaurant['coordinates'])) {
			$restaurant['coordinates_array']=explode('|',$restaurant['coordinates']);
		}		
		if(!empty($restaurant['tags'])) {
			$tags=trim($restaurant['tags'],',');
			if(!empty($tags)) {
			  $restaurant['tags']=$this->db->table('flavors_tags')->Select('id,value')->Where('id_lang',$id_lang)->WhereIn('id',explode(',',$tags))->get()->getResultArray();
			}
		}
		 $cats=array();
		  if(!empty($restaurant['id_category'])) {
		     $cats[]=$restaurant['id_category'];
			 $restaurant['mapicon']=$this->db->table('flavors_categories_files f')->join('flavors_categories_files_lang fl', 'fl.id_file = f.id','left')->Select('f.path,fl.caption')->Where('fl.id_lang',$id_lang)->Where('f.id_category',$restaurant['id_category'])->Where('f.field','mapicon')->get()->getRowArray();
			  $restaurant['background']=$this->db->table('flavors_categories_files f')->join('flavors_categories_files_lang fl', 'fl.id_file = f.id','left')->Select('f.path,fl.caption')->Where('fl.id_lang',$id_lang)->Where('f.id_category',$restaurant['id_category'])->Where('f.field','background')->get()->getRowArray();
		  }	  
		  if(!empty(trim($restaurant['additional_category'],','))) {
			 $cats_additional=explode(',',trim($restaurant['additional_category'],','));
			 if(!empty($cats_additional)) {
				$cats=array_merge($cats,$cats_additional);
			 }
		  }	 
		  if(!empty(trim($restaurant['news'],','))) {
			 $restaurant['news']=explode(',',trim($restaurant['news'],',')); 
		  }	  
		  if(!empty(trim($restaurant['dish_type'],','))) {
			 $restaurant['dish_type']=explode(',',trim($restaurant['dish_type'],','));
		  }	 
          if(!empty($cats)) {
			$cats=array_unique($cats);  
			 $restaurant['categories']=$this->db->table('flavors_categories c')->join('flavors_categories_lang cl', 'cl.id_category = c.id','left')->join('links l', 'cl.id_link = l.id','left')->Select('c.id,cl.name,l.link')->WhereIn('c.id',$cats)->Where('cl.id_lang',$id_lang)->Where('c.publish',1)->orderBy("FIELD(c.id,".implode(',',$cats).")")->get()->getResultArray(); 
		  }	  
		  
		  if(!empty(trim($restaurant['cuisine_type'],','))) {
			$cats=explode(',',trim($restaurant['cuisine_type'],','));
			$cats=array_unique($cats);
			$restaurant['cuisine_type']=$this->db->table('flavors_cuisine c')->join('flavors_cuisine_lang cl', 'cl.id_cuisine = c.id','left')->join('links l', 'cl.id_link = l.id','left')->Select('c.id,cl.name,l.link')->WhereIn('c.id',$cats)->Where('cl.id_lang',$id_lang)->Where('c.publish',1)->orderBy("c.order",'asc')->get()->getResultArray(); 
		  }

		  $restaurant['parameters']=$this->getSingleRestaurantParameters($restaurant['id'],$id_lang);
		  $restaurant['photo']=$this->db->table('flavors_restaurant_files f')->join('flavors_restaurant_files_lang fl', 'fl.id_file = f.id','left')->Select('f.path,fl.caption')->Where('fl.id_lang',$id_lang)->Where('f.id_restaurant',$restaurant['id'])->Where('f.field','photo')->get()->getRowArray();
		   $restaurant['photos']=$this->db->table('flavors_restaurant_files f')->join('flavors_restaurant_files_lang fl', 'fl.id_file = f.id','left')->Select('f.path,fl.caption')->Where('fl.id_lang',$id_lang)->Where('f.id_restaurant',$restaurant['id'])->Where('f.field','photos')->get()->getResultArray();
		  $restaurant['logo']=$this->db->table('flavors_restaurant_files f')->join('flavors_restaurant_files_lang fl', 'fl.id_file = f.id','left')->Select('f.path,fl.caption')->Where('fl.id_lang',$id_lang)->Where('f.id_restaurant',$restaurant['id'])->Where('f.field','logo')->get()->getRowArray();
		  $restaurant['menu']=$this->db->table('flavors_restaurant_files f')->join('flavors_restaurant_files_lang fl', 'fl.id_file = f.id','left')->Select('f.path,fl.caption')->Where('fl.id_lang',$id_lang)->Where('f.id_restaurant',$restaurant['id'])->Where('f.field','menu')->get()->getResultArray();
		  $restaurant['avg']=$this->db->table('flavors_rating')->Select('AVG(rating) as rating,COUNT(DISTINCT id_user) as cnt')->Where('id_restaurant',$restaurant['id'])->get()->getRowArray();
		  $restaurant['comments']=$this->GetRestaurantComments($restaurant['id'],$id_lang);
		  $restaurant['avg']=$this->db->table('flavors_rating')->Select('AVG(rating) as rating,COUNT(DISTINCT id_user) as cnt')->Where('id_restaurant',$restaurant['id'])->get()->getRowArray();
		  $restaurant['user_rates']=$this->GetRestaurantRates($restaurant['id']);
		  
		 $restaurant['background']=$this->db->table('flavors_categories_files f')->join('flavors_categories_files_lang fl', 'f.id = fl.id_file')->Select('path,caption')->Where('field','background')->where('fl.id_lang', $id_lang)->where('f.id_category', $restaurant['id_category'])->get()->getRowArray();
		  return $restaurant;
		}	
	   
   }
   
   function GetSimilarLocationRestaurants ($id,$coordinates,$id_lang,$distance,$show) {
      if(!empty($coordinates) and !empty($id)) {
			$coordinates=explode('|',$coordinates);
			$lat=$coordinates[0];
			$long=$coordinates[1];
			$similar_list=$this->db->table("flavors_restaurant")->select("id,coordinates,SUBSTRING_INDEX(coordinates, '|', 1) as lat,SUBSTRING_INDEX(coordinates, '|', -1) as lng,ST_Distance_Sphere(POINT(".$lat.",".$long."), POINT(SUBSTRING_INDEX(coordinates, '|', 1),SUBSTRING_INDEX(coordinates, '|', -1))) as distance")->Where('coordinates!=','')->OrderBy('distance','ASC')->Where('id!=',$id)->Having('distance<',$distance)->Limit($show)->get()->getResultArray();
   
            foreach($similar_list as $k=>$list) {
				$similar_list[$k]=$this->GetRestaurantListById($list['id'],$id_lang);
				$similar_list[$k]['distance']=round($list['distance']);
			}	
			return $similar_list;
	  }
   }

    function getSingleRestaurantParameters($id_restaurant,$id_lang) {
		$parameters=$this->db->table("flavors_restaurant_parameters rp")->join('flavors_parameters p', 'p.id = rp.id_parameter')->join('flavors_parameters_lang pl', 'p.id = pl.id_parameter')->Where('pl.id_lang',$id_lang)->Where('p.publish',1)->Where('rp.id_restaurant',$id_restaurant)->Select('rp.id,rp.id_parameter,pl.name')->GroupBy('rp.id_parameter')->OrderBy('pl.name','asc')->get()->getResultArray();
		if(!empty($parameters)) {
		   foreach($parameters as $k=>$v) {	
			
			  $parameters[$k]['values']=$this->db->table("flavors_restaurant_parameters rp")->join('flavors_parameters_value_lang pl', 'rp.id_value = pl.id_value')->Where('rp.id_parameter',$v['id_parameter'])->Where('rp.id_restaurant',$id_restaurant)->Where('pl.id_lang',$id_lang)->Select('pl.value')->OrderBy('pl.value','asc')->get()->getResultArray();
		   }
		   return $parameters;
		}
    }		
	
	function GetRestaurantComments($id_restaurant,$id_lang,$re_id=0) {
		
		$comments=$this->db->table("flavors_comments c")->Select('c.id,c.id_user,c.nick,c.comment,c.created_at,c.re_id')->Where('c.id_lang',$id_lang)->Where('c.id_restaurant',$id_restaurant)->Where('c.re_id',$re_id)->Where('c.publish',1)->OrderBy('created_at','desc')->get()->getResultArray();
		if(!empty($comments)) {
		  foreach($comments as $k=>$v) {
            $comments[$k]['info_user']=$this->db->table("users")->Select('name,surname,nick')->Where('id',$v['id_user'])->Where('active',1)->get()->getRowArray();			  
			$comments[$k]['reply']=$this->GetRestaurantComments($id_restaurant,$id_lang,$v['id']);
		  }	
		}	
		return $comments;
	}	
	
	function GetRestaurantRates($id_restaurant) {
		
		$rates=$this->db->table("flavors_rating")->Select('rating,id_user,type')->Where('id_restaurant',$id_restaurant)->OrderBy('created_at','DESC')->get()->getResultArray();
		if(!empty($rates)) {
		foreach($rates as $rat){
            $rating[$rat['id_user']][$rat['type']]=$rat['rating'];
        }
		
		if($rating){
            foreach($rating as $k=>$ocena){
                $sum=0;
                foreach($ocena as $o){
                    $sum=$sum+$o;
                }
                $rating[$k]['main']=number_format($sum/count($rating[$k]),1,'.','');
				$rating[$k]['user_info']=$this->db->table("users")->Select('name,surname,nick')->Where('id',$k)->Where('active',1)->get()->getRowArray();
            }
			return $rating;
        }
		}
	}

	function GetHomeRestaurantRating($id_lang,$show) {
		$rates=$this->db->table("flavors_rating")->Select('id,AVG(rating) as rating_main,rating,id_user,id_restaurant')->GroupBy(['id_user', 'id_restaurant'])->OrderBy('flavors_rating.created_at','DESC')->Limit($show)->get()->getResultArray();
		if(!empty($rates)) {
			foreach($rates as $k=>$v){
				$rates[$k]['info_user']=$this->db->table("users")->Select('name,surname,nick')->Where('id',$v['id_user'])->Where('active',1)->get()->getRowArray();	
				$rates[$k]['info_restaurant']=$this->db->table("flavors_restaurant f")->Select('name,link')->join('flavors_restaurant_lang fl', 'fl.id_restaurant = f.id','left')->join('links l', 'fl.id_link = l.id','left')->Where('fl.id_lang',$id_lang)->Where('f.id',$v['id_restaurant'])->Where('f.publish',1)->get()->getRowArray();	
			}	
		}
        return $rates;
    }	
	
	function GetRestaurantRanking($id_lang,$config) {
	    $list=array();
		$this->request = \Config\Services::request();
		$list['get'] = $this->request->getGet();
		$subquery=$this->db->table("flavors_rating r")->Select('AVG(r.rating) as rate')->Where('r.id_restaurant=f.id');
		$subquery2=$this->db->table("flavors_rating r")->Select('COUNT(DISTINCT r.id_user)')->Where('r.id_restaurant=f.id');
		if(!empty($list['get']['type'])) {
			$subquery=$subquery->Where('type',$list['get']['type']);
			$subquery2=$subquery2->Where('type',$list['get']['type']);
		}	
		$subquery3=$this->db->table("flavors_comments c")->Select('COUNT(c.id)')->Where('c.id_restaurant=f.id')->Where('c.id_lang',$id_lang)->Where('c.publish',1)->Where('c.re_id',0);
		$query=$this->db->table('flavors_restaurant f')->join('flavors_restaurant_lang rl', 'f.id = rl.id_restaurant')->join('links l', 'l.id = rl.id_link')->selectSubquery($subquery,'rate')->selectSubquery($subquery2,'cnt')->selectSubquery($subquery3,'comments')->select('f.id as id,id_category,name,link')->Where('f.publish',1)->Where('archives',0)->Where('rl.id_lang',$id_lang)->Where('rl.id_lang',$id_lang);
		if(!empty($list['get']['cat'])) {
		  $query=$query->Where('id_category',$list['get']['cat']);	
		}	
		if(!empty($list['get']['order']) and $list['get']['order']=='rate_desc') {
			$query=$query->OrderBy('rate','DESC');	
			$list['order']='rate_desc';
		}
		elseif(!empty($list['get']['order']) and $list['get']['order']=='rate_asc') {
			$query=$query->OrderBy('rate','ASC');	
			$list['order']='rate_asc';
		}
		elseif(!empty($list['get']['order']) and $list['get']['order']=='cnt_desc') {
			$query=$query->OrderBy('cnt','DESC');	
			$list['order']='cnt_desc';
		}
		elseif(!empty($list['get']['order']) and $list['get']['order']=='cnt_asc') {
			$query=$query->OrderBy('cnt','ASC');	
			$list['order']='cnt_asc';
		}
		elseif(!empty($list['get']['order']) and $list['get']['order']=='comments_asc') {
			$query=$query->OrderBy('comments','ASC');	
			$list['order']='comments_asc';
		}
		elseif(!empty($list['get']['order']) and $list['get']['order']=='comments_desc') {
			$query=$query->OrderBy('comments','DESC');	
			$list['order']='comments_desc';
		}
		elseif(!empty($list['get']['order']) and $list['get']['order']=='cnt_asc') {
			$query=$query->OrderBy('cnt','ASC');	
			$list['order']='cnt_asc';
		}
		else {
			$query=$query->OrderBy('rate','DESC');	
			$list['order']='rate_desc';
		}	
		$list['ranking']=$query->limit($config['no'])->Having('cnt>=',10)->get()->getResultArray();
		if(!empty($list['ranking'])) {
		   foreach($list['ranking'] as $k=>$v) {
		      $list['ranking'][$k]['category']=$this->db->table('flavors_categories c')->join('flavors_categories_lang cl', 'cl.id_category = c.id','left')->join('links l', 'cl.id_link = l.id','left')->Select('c.id,cl.name,l.link')->Where('c.id',$v['id_category'])->Where('cl.id_lang',$id_lang)->Where('c.publish',1)->get()->getRowArray();
			  $list['ranking'][$k]['photo']=$this->db->table('flavors_restaurant_files f')->join('flavors_restaurant_files_lang fl', 'fl.id_file = f.id','left')->Select('f.path,fl.caption')->Where('fl.id_lang',$id_lang)->Where('f.id_restaurant',$v['id'])->Where('f.field','photo')->get()->getRowArray();
		   }
		}
	  $list['categories']=$this->db->table('flavors_categories c')->join('flavors_categories_lang cl', 'cl.id_category = c.id','left')->Select('c.id,cl.name')->Where('cl.id_lang',$id_lang)->OrderBy('cl.name','ASC')->get()->getResultArray();
	  return $list;
	}
	
	function GetVegetarianOther($id_restaurant,$id_lang,$show) {
		$list=$this->db->table('flavors_restaurant')->Select('id')->Where('id!=',$id_restaurant)->notLike('dish_type', ',1,')->groupStart()->Like('dish_type', ',2,')->orLike('dish_type', ',3,')->groupEnd()->Where('publish',1)->Where('archives',0)->OrderBy('created_at','RANDOM')->Limit($show)->get()->getResultArray();
		if(!empty($list)) {
		    foreach($list as $k=>$el) {
		        $list[$k]=$this->GetRestaurantListById($el['id'],$id_lang);
		    }
		} 
		return $list;
	}	
	
	function GetOthersRestaurants($id_restaurant,$id_cat,$id_lang,$show) {
		$list=$this->db->table('flavors_restaurant')->Select('id')->Where('id!=',$id_restaurant)->Where('id_category',$id_cat)->Where('publish',1)->Where('archives',0)->OrderBy('created_at','RANDOM')->Limit($show)->get()->getResultArray();
		if(!empty($list)) {
		    foreach($list as $k=>$el) {
		        $list[$k]=$this->GetRestaurantListById($el['id'],$id_lang);
		    }
		} 
		return $list;
	}	
	
	function GetRestaurantsRanking($id_restaurant,$id_lang,$limit) {
		$subquery=$this->db->table("flavors_rating r")->Select('AVG(r.rating) as rate')->Where('r.id_restaurant=f.id');
		$query=$this->db->table('flavors_restaurant f')->join('flavors_restaurant_lang rl', 'f.id = rl.id_restaurant')->selectSubquery($subquery,'rate')->select('f.id as id')->Where('f.publish',1)->Where('f.id!=',$id_restaurant)->Where('archives',0)->Where('rl.id_lang',$id_lang);
		$query=$query->OrderBy('rate','DESC');	
		$list=$query->limit($limit)->get()->getResultArray();
		if(!empty($list)) {
		    foreach($list as $k=>$el) {
		        $list[$k]=$this->GetRestaurantListById($el['id'],$id_lang);
		    }
		} 
		return $list;
	}	
	
	function GetRestaurantNewsList($news,$id_lang) {
		$news_list=array();
		
		if(!empty($news)) {
		     $news_list=$this->db->table('news n')->join('news_lang nl', 'nl.id_news = n.id','left')->Select('n.id,title,n.created_at,link,introduction')->join('links l', 'l.id = nl.id_link')->Where('nl.id_lang',$id_lang)->Where('publish',1)->OrderBy('n.created_at','DESC')->whereIn('n.id',$news)->get()->getResultArray();
			 if(!empty($news_list)) {
			   foreach($news_list as $k=>$v) {	
				  $news_list[$k]['photo']=$this->db->table('news_files')->Select('path')->Where('field','photo')->Where('id_news',$v['id'])->get()->getRowArray();
			   }
			}	
		
		}	
      return $news_list;
	}	
	
	function SearchRestaurantsByTag($tag,$id_lang) {
		$tag_s=$this->db->table('flavors_tags')->Select('id')->Where('value',$tag)->get()->getRowArray();
		$cat_search=$this->db->table('flavors_categories c')->join('flavors_categories_lang cl', 'cl.id_category = c.id','left')->Select('c.id')->Where('cl.id_lang',$id_lang)->Where('publish',1)->Like('cl.name',$tag)->get()->getRowArray();
		if(!empty($tag_s['id'])) {
			$list=$this->db->table('flavors_restaurant r')->join('flavors_restaurant_lang fl', 'fl.id_restaurant = r.id','left')->Select('r.id')->Where('fl.id_lang',$id_lang)->GroupStart()->Like('tags', ','.$tag_s['id'].',')->orLike('fl.name', $tag);
			if(!empty($cat_search['id'])) {
			   $list->orWhere('id_category',$cat_search['id'])->orLike('additional_category',','.$cat_search['id'].',');			   
			}
			$list_s=$list->GroupEnd()->Where('publish',1)->Where('archives',0)->OrderBy('awarded','DESC')->OrderBy('created_at','DESC')->get()->getResultArray();
			
		}	
		else {
			$list=$this->db->table('flavors_restaurant r')->join('flavors_restaurant_lang fl', 'fl.id_restaurant = r.id','left')->Select('r.id')->Where('fl.id_lang',$id_lang)->GroupStart()->Like('fl.name', $tag);
			if(!empty($cat_search['id'])) {
			   $list->orWhere('id_category',$cat_search['id'])->orLike('additional_category',','.$cat_search['id'].',');			   
			}	
			$list_s=$list->GroupEnd()->Where('publish',1)->Where('archives',0)->OrderBy('awarded','DESC')->OrderBy('created_at','DESC')->get()->getResultArray();	
		}
			if(!empty($list_s)) {
				foreach($list_s as $k=>$el) {
					$list_s[$k]=$this->GetRestaurantListById($el['id'],$id_lang);
				}
			 return $list_s;
			} 
	}	
	
	function SearchRestaurants($search,$id_lang) {
		if(!empty($search)) {
			$list=$this->db->table('flavors_restaurant r')->join('flavors_restaurant_lang fl', 'fl.id_restaurant = r.id','left')->Select('r.id')->Where('fl.id_lang',$id_lang)->GroupStart()->Like('fl.name', $search)->orLike('fl.address', $search)->orLike('fl.city', $search)->orLike('fl.speciality', $search)->GroupEnd()->Where('publish',1)->Where('archives',0)->OrderBy('awarded','DESC')->OrderBy('created_at','DESC')->get()->getResultArray();
			if(!empty($list)) {
				foreach($list as $k=>$el) {
					$list[$k]=$this->GetRestaurantListById($el['id'],$id_lang);
				}
			} 
		    return $list;
		}	
		
	}	
	
	function SearchNewsByTag($tag,$id_lang) {
		$tag=$this->db->table('tags')->Select('id')->Where('tag',$tag)->Where('id_page_cont',38)->get()->getRowArray();
		if(!empty($tag['id'])) {
			 $list= $this->db->table('news')
                        ->join('news_lang nl', 'news.id=nl.id_news')
                        ->join('links l', 'l.id=nl.id_link')
                        ->select('news.id,news.date,nl.title,nl.introduction,l.link')
                        ->where('news.publish', 1)
                        ->where('nl.id_lang', $id_lang)
                        ->where('l.id_lang', $id_lang)
                        ->where('news.id_page_cont',38)
						->Like('nl.tags',','.$tag['id'].',')
                        ->get()->getResultArray();
			if(!empty($list)) {
               foreach($list as $k=>$v) {
                   $list[$k]['photo']=$this->db->table('news_files nf')->join('news_files_lang nfl', 'nfl.id_file=nf.id', 'left')->select('nf.path,nf.crop_dimension,nfl.caption')->groupStart()->where('nfl.id_lang', $id_lang)->orWhere('nfl.id_lang', null)->groupEnd()->Where('nf.id_news',$v['id'])->groupStart()->where('nfl.id_lang', $id_lang)->orWhere('nfl.id_lang', null)->groupEnd()->groupStart()->where('nf.field', 'photo')->orWhere('nf.field', null)->groupEnd()->get()->getRowArray();
               }
            }				
			return $list;	
		}	
	}
	
	function SearchNews($search,$id_lang) {
		if(!empty($search)) {
			 $list= $this->db->table('news')
                        ->join('news_lang nl', 'news.id=nl.id_news')
                        ->join('links l', 'l.id=nl.id_link')
                        ->select('news.id,news.date,nl.title,nl.introduction,l.link')
                        ->where('news.publish', 1)
                        ->where('nl.id_lang', $id_lang)
                        ->where('l.id_lang', $id_lang)
                        ->where('news.id_page_cont',38)
						 ->groupStart()
						->Like('nl.title',$search)
						->groupEnd()->get()->getResultArray();
						if(!empty($list)) {
               foreach($list as $k=>$v) {
                     $list[$k]['photo']=$this->db->table('news_files nf')->join('news_files_lang nfl', 'nfl.id_file=nf.id', 'left')->select('nf.path,nf.crop_dimension,nfl.caption')->groupStart()->where('nfl.id_lang', $id_lang)->orWhere('nfl.id_lang', null)->groupEnd()->Where('nf.id_news',$v['id'])->groupStart()->where('nfl.id_lang', $id_lang)->orWhere('nfl.id_lang', null)->groupEnd()->groupStart()->where('nf.field', 'photo')->orWhere('nf.field', null)->groupEnd()->get()->getRowArray();
               }
            }	
			return $list;	
		}
		}	
		
		function GetRestaurantsMap($id_lang,$filters) {
			
			$cat_list=$this->db->table('flavors_categories f')->join('flavors_categories_lang fl', 'f.id = fl.id_category')->Select('fl.name,f.id')->OrderBy('name','asc')->Where('publish',1)->Where('fl.id_lang',$id_lang)->get()->getResultArray();
			if(!empty($cat_list)) {
			   foreach($cat_list as $k=>$v) {
			    $map_ico[$v['id']]['name']=$v['name'];
				$map_ico[$v['id']]['ico']=$this->db->table('flavors_categories_files f')->join('flavors_categories_files_lang fl', 'f.id = fl.id_file')->Select('path,caption')->Where('field','mapicon')->where('fl.id_lang', $id_lang)->where('f.id_category', $v['id'])->get()->getRowArray();
			   }
			}
			$query=$this->db->table('flavors_restaurant r')->join('flavors_restaurant_lang fl', 'fl.id_restaurant = r.id','left')->Select('r.id,fl.name,fl.address,fl.phone,r.coordinates,id_category,id_link')->Where('fl.id_lang',$id_lang)->Where('coordinates!=','')->Where('id_category>',0)->Where('publish',1)->Where('archives',0);
			if(!empty($filters['type'])) {
				$query=$query->GroupStart()->Where('id_category',$filters['type'])->orLike('additional_category',','.$filters['type'].',')->GroupEnd();
			}
			if(!empty($filters['cuisine'])) {
				$query=$query->Like('cuisine_type',','.$filters['cuisine'].',');
			}
			
			$list=$query->get()->getResultArray();
				$letters_count=array('a'=>0,'b'=>0,'c'=>0,'d'=>0,'e'=>0,'f'=>0,'g'=>0,'h'=>0,'i'=>0,'j'=>0,'k'=>0,'l'=>0,'m'=>0,'n'=>0,'o'=>0,'p'=>0,'q'=>0,'r'=>0,'s'=>0,'t'=>0,'u'=>0,'v'=>0,'w'=>0,'x'=>0,'y'=>0,'z'=>0);
			if(!empty($list)) {
			   foreach($list as $k=>$v) {	
			    $list[$k]['coordinates_array']=explode('|',$v['coordinates']);   
				$letter=mb_strtolower(mb_substr(trim($v['name']),0,1));
				$letter=str_replace(array('ą', 'ć', 'ę', 'ł', 'ń', 'ó', 'ś', 'ź', 'ż'), array('a', 'c', 'e', 'l', 'n', 'o', 's', 'z', 'z'), $letter);
				$letters_count[$letter]=$letters_count[$letter]+1;
				$link=$this->db->table('links')->Select('link')->Where('id',$v['id_link'])->get()->getRowArray();
				$list[$k]['link']=$link['link'];
            if(!empty($filters['type'])) {
			  $list[$k]['ico']=$map_ico[$filters['type']];
			}
            elseif(!empty($map_ico[$v['id_category']])) {
				$list[$k]['ico']=$map_ico[$v['id_category']];
			}
			   }
			   	if(!empty($filters['letter'])) {  
				   foreach($list as $k=>$v) {
				      $letter=mb_strtolower(mb_substr(trim($v['name']),0,1));
					  $letter=str_replace(array('ą', 'ć', 'ę', 'ł', 'ń', 'ó', 'ś', 'ź', 'ż'), array('a', 'c', 'e', 'l', 'n', 'o', 's', 'z', 'z'), $letter);
				      if($letter!=$filters['letter']) {
						unset($list[$k]);  
					  }	  
				   }
			    }
			 $map['legend']=$map_ico;	
			 $map['restaurants']=$list;
			 $map['letters']=$letters_count;
			 return $map;
			}	
		}

        function HomeRestaurantsMap($id_lang) {




        }			
}	