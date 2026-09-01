<?php

namespace Modules\Flavors\Models;  
use CodeIgniter\Model;
use App\Libraries\Link;
use \App\Validation\CustomRules;
use Modules\Flavors\Models\FlavorsRestaurantsModel;


class FlavorsCuisineModel extends Model{
	
			protected $table = 'flavors_cuisine';
			protected $allowedFields = [
				'publish',
				'edited_at',
				'order',
				'menu',
				'ico_svg',
				'id',
				'created_at',
			];
			
	 private function removeCuisineFile($file) 
    {
        if(file_exists(WRITEPATH . 'uploads/' . $file['path'])) {
            @unlink(WRITEPATH . 'uploads/' . $file['path']);
        }
        $this->db->table('flavors_cuisine_files_lang')->where('id_file', $file['id'])->delete();
        $this->db->table('flavors_cuisine_files')->where('id', $file['id'])->delete();
    }
    
    public function deleteCuisine($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $langs = $this->db->table('flavors_cuisine_lang')->select('id_link')->where('id_cuisine', $id)->get()->getResultArray();
        if(!empty($langs)) {
            foreach($langs as $l) {
                $this->db->table('links')->where('id', $l['id_link'])->delete();
            }
        }
        $files_list = $this->db->table('flavors_cuisine_files')->select('id,path')->where('id_cuisine', $id)->get()->getResultArray();
        if(!empty($files_list)) {
            foreach($files_list as $f) {
                $this->removeCuisineFile($f);
            }
        }
		$this->db->table('flavors_cuisine_meta_lang')->where('id_cuisine', $id)->delete();
        $this->db->table('flavors_cuisine_lang')->where('id_cuisine', $id)->delete();
        $this->where('id', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
			
			
   public function getCuisineById($id, $id_lang) 
    {
        $cuisine = $this->where('id', $id)->first();
        if(!empty($cuisine)) {
            
            $cuisine['lang'] = $this->getCuisineLang($id);
            if(!empty($cuisine['lang']) && !empty($cuisine['lang'][$id_lang]) && !empty($cuisine['lang'][$id_lang]['name'])) {
                $cuisine['name'] = $cuisine['lang'][$id_lang]['name'];
            } else {
                $cuisine['name'] = '';
            }
            $cuisine['photo'] = $this->getCuisineFile($id);
			$cuisine['meta']['lang'] = $this->getCuisineMetaLang($id);
        }
        return $cuisine;
    }
	
	public function getCuisineMetaLang($id_cuisine) 
    {
        $langs = array();
        $data = $this->db->table('flavors_cuisine_meta_lang')->where('id_cuisine', $id_cuisine)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
	
    
    public function getCuisineLang($id_news) 
    {
        $linkClass = new Link();
        $langs = array();
        $data = $this->db->table('flavors_cuisine_lang')->where('id_cuisine', $id_news)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $d['link'] = $linkClass->getLink($d['id_link'], $d['id_lang']);
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
    
  
    
    private function getCuisineFile($id_cuisine) 
    {
        $file = $this->db->table('flavors_cuisine_files')->select('id,name,basename,path,order,type,publish,ext')->where('id_cuisine', $id_cuisine)->orderBy('order', 'ASC')->get()->getRowArray();
        if(!empty($file)) {
            $file['lang'] = $this->getCuisineFileLang($file['id']);
        }
        return $file;
    }
    
  
    
    private function getCuisineFileLang($id_file) 
    {
        $langs = array();
        $data = $this->db->table('flavors_cuisine_files_lang')->where('id_file', $id_file)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }	
	
	
	
	    public function saveCuisine($id, $post) 
    {
        helper(['file']);
        if(empty($post)) return false;	 		
        $data = array(
            'publish' => !empty($post['publish']) ? $post['publish'] : 0,
			'ico_svg' => !empty($post['ico_svg']) ? $post['ico_svg'] : '',
        );
        $this->db->transStart();
        if($id) {
            $result = $this->set($data)->where('id', $id)->update();
            $this->id = $id;
        } else {
			$this->set('order', '`order`+1', FALSE)->where('id>', 0)->update();
            $result = $this->insert($data);
            $this->id = $this->getInsertID();
        }
        
        $this->saveCuisineLang($this->id, $post['lang']);
		$this->saveCuisineMetaLang($this->id, $post['meta']['lang']);		
        $this->saveCuisineFile($this->id, !empty($post['photo']) ? $post['photo'] : array(), 'photo', true);
        $this->db->transComplete();
        return $this->db->transStatus();
    }
	
	 private function saveCuisineMetaLang($id_cuisine, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_cuisine' => $id_cuisine,
                    'id_lang' => $id_lang,
                    'title' => $lang['title'],
                    'description' => $lang['description'],
                );
                $lang = $this->db->table('flavors_cuisine_meta_lang')->select('id')->where('id_cuisine', $id_cuisine)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('flavors_cuisine_meta_lang')->set($data)->where('id_cuisine', $id_cuisine)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('flavors_cuisine_meta_lang')->insert($data);
                }
            }
        }
    }
    
    private function saveCuisineLang($id_cuisine, $lang_data) 
    {
        if(!empty($lang_data)) {
            $module = $this->db->table('module')->select('id')->where('slug', 'Flavors')->get()->getRowArray();
            foreach($lang_data as $id_lang=>$lang) {
					$linkClass = new Link();
					$data = array(
						'id_cuisine' => $id_cuisine,
						'id_lang' => $id_lang,
						'id_link' => $linkClass->saveLink($lang['link'], $id_lang, $lang['id_link'], !empty($module) ? $module['id'] : 0,false,null,'cuisine'),
						'name' => $lang['name'],
						'denmark' => $lang['denmark'],
						'description' => $lang['description']
					);
                $lang = $this->db->table('flavors_cuisine_lang')->select('id')->where('id_cuisine', $id_cuisine)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('flavors_cuisine_lang')->set($data)->where('id_cuisine', $id_cuisine)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('flavors_cuisine_lang')->insert($data);
                }
            }
        }
    }
    

   
    private function saveCuisineFile($id_cuisine, $file, $field='', $remove=false) 
    {
        if(!empty($file)) {
            if(!empty($file['id']) && !empty($this->db->table('flavors_cuisine_files')->select('id')->where('id', $file['id'])->get()->getRowArray())) {
                $data = array(
                    'order' => !empty($file['order']) ? $file['order'] : 0,
                    'publish' => !empty($file['publish']) ? $file['publish'] : 0,
                );
                $result = $this->db->table('flavors_cuisine_files')->set($data)->where('id', $file['id'])->update();
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
                $data = array(
                    'id_cuisine' => $id_cuisine,
                    'field' => $field,
                    'name' => $file['name'],
                    'basename' => $file['basename'],
                    'path' => $file_path,
                    'mime' => $r->getMimeType(),
                    'type' => file_type($r->getMimeType()),
                    'ext' => $file_info['extension'],
                    'order' => !empty($file['order']) ? $file['order'] : 0,
                    'publish' => !empty($file['publish']) ? $file['publish'] : 0,
                );
                $result = $this->db->table('flavors_cuisine_files')->insert($data);
                $id_file = $this->db->insertID();
            }
            $this->saveCuisineFileLang($id_file, $file['lang']);
        }
        if($remove) {
            $query = $this->db->table('flavors_cuisine_files')->select('id,path')->where('id_cuisine', $id_cuisine)->where('field', $field);
            if(!empty($id_file)) {
                $query->where('id !=', $id_file);
            }
            $files_list = $query->get()->getResultArray();
            if(!empty($files_list)) {
                foreach($files_list as $f) {
                    $this->removeCuisineFile($f);
                }
            }
        }
        return !empty($id_file) ? $id_file : 0;
    }
    
    private function saveCuisineFileLang($id_file, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_file' => $id_file,
                    'id_lang' => $id_lang,
                    'caption' => $lang['caption'],
                    'author' => $lang['author'],
                );
                $lang = $this->db->table('flavors_cuisine_files_lang')->select('id')->where('id_file', $id_file)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('flavors_cuisine_files_lang')->set($data)->where('id_file', $id_file)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('flavors_cuisine_files_lang')->insert($data);
                }
            }
        }
    }
	
			
	 function getCuisinePatch($id,$id_lang,$join,$patch='') {
        global $db;
			$category=$this->db->table('flavors_categories c')
            ->join('flavors_categories_lang cl', 'c.id = cl.id_category')
			->select('c.id,c.re_id,cl.name')
            ->where('cl.id_lang', $id_lang)
			->where('c.id', $id)
			->get()->getRowArray();
			if(!empty($category['id'])) {
			  $patch=$category['name'].$join.$patch;	
			}	
			if(!empty($category['re_id'])) {
			  return $patch=$this->getCategoryPatch($category['re_id'],$id_lang,$join,$patch);
			}
            else {
               return trim($patch,$join);
            }				
    }		
	
	
	function saveCuisineOrder($data,$id_content) {
		$this->db->transStart();
		if(!empty($data['cuisine_order'])) {
            foreach($data['cuisine_order'] as $order=>$cuisine_id) {
					$this->db->table('flavors_cuisine')->set('order', $order, FALSE)->where('id',$cuisine_id)->update();
			}
		}
		$this->db->transComplete();
        return $this->db->transStatus();
	}

		

    function GetCuisineRestaurants($id_link,$id_lang) {
			$this->flavorsRestaurantsModel = new FlavorsRestaurantsModel();
			$this->request = \Config\Services::request();
			$cuisine=$this->db->table('flavors_cuisine c')
            ->join('flavors_cuisine_lang cl', 'c.id = cl.id_cuisine')
			 ->join('links l', 'l.id = cl.id_link','left') 
			->select('c.id,cl.name,cl.description,l.link,cl.denmark')
            ->where('cl.id_lang', $id_lang)
			->where('l.id_lang', $id_lang)
			->where('cl.id_link',$id_link)
			->get()->getRowArray();
			$get = $this->request->getGet();
		    $filters=$get;	
			$cuisine['meta']= $this->db->table('flavors_cuisine_meta_lang')->Select('title,description,keywords')->where('id_cuisine',$cuisine['id'])->orderBy('id_lang')->get()->getRowArray();
			$cuisine['parameters']=$this->flavorsRestaurantsModel->GetCuisineParameters($filters,$id_lang,$cuisine['id']);
			if(!empty($cuisine['parameters']) and !empty($filters['f'])) {
				foreach($cuisine['parameters'] as $param) {
				    if(!empty($param['value_list'])) {
				      foreach($param['value_list'] as $val) { 
						if(!empty($filters['f'][$param['id']][$val['id']])) {
								$choosen[]=array('id'=>$val['id'],'name'=>$val['value'],'param_id'=>$param['id'],'param_name'=>$param['name']);
						}  
				      }
				    }
				}
			}	
			if(!empty($choosen)) {
				$cuisine['choosen_parameters']=$choosen;
			}
			if(!empty($cuisine)) {
			if(!empty($filters['f'])) {
				  foreach($filters['f'] as $k=>$filter_values) {
					 foreach($filter_values as $c=>$filter) { 
					   $product_list=$this->db->table('flavors_restaurant_parameters p')->join('flavors_restaurant r', 'p.id_restaurant=r.id')->Select('p.id_restaurant')->Where('r.publish',1)->Like('cuisine_type',','.$cuisine['id'].',')->Where('p.id_value',$filter)->groupBy('p.id_restaurant')->get()->getResultArray();
					   if(!empty($product_list)) {
						   foreach($product_list as $prod) {
							  if(!empty($prod['id_restaurant'])) { $ids_p[$k][$c][]=$prod['id_restaurant'];}
						   }
					   }
					 }   
				  }
			   }
			$query=$this->db->table('flavors_restaurant')->join('flavors_restaurant_lang fl', 'flavors_restaurant.id = fl.id_restaurant')->Select('flavors_restaurant.id,fl.name,flavors_restaurant.awarded')->Like('cuisine_type',','.$cuisine['id'].',')->Where('publish',1)->Where('archives',0)->Where('fl.id_lang',$id_lang);
			  if(!empty($ids_p)) {
					$query->GroupStart();		
					foreach($ids_p as $parameter_list) {	
						$query->GroupStart();	
						foreach($parameter_list as $par) {
								if(!empty($par)) { $query->orWhereIn('flavors_restaurant.id',$par);}
						}
						$query->GroupEnd();	
					}	
					$query->GroupEnd();	
				}	 
				$query->OrderBy('awarded','DESC'); 
				
			   if(empty($filters['t'])) {
				 $filters['t']='desc';   
			   }
			   if(empty($filters['sort'])) {
				  $query->OrderBy('order','ASC'); 
				  $query->OrderBy('id','DESC'); 
			   }	
	           elseif($filters['sort']==1) {
				 $query=$query->OrderBy('id',$filters['t']);  
			   }	   
			   elseif($filters['sort']==2) {
				 $query=$query->OrderBy('fl.name',$filters['t']);  
			   }
			   elseif($filters['sort']==3) {
				   $query->Select('(SELECT AVG(rating) FROM tio_flavors_rating WHERE id_restaurant=tio_flavors_restaurant.id) as rating')->OrderBy('rating',$filters['t']);  
			   }	   
			   elseif($filters['sort']==4) {
			      $query->Select('(SELECT Count(id) FROM tio_flavors_comments WHERE id_restaurant=tio_flavors_restaurant.id) as comments_count')->OrderBy('comments_count',$filters['t']); 
			   }
			   elseif($filters['sort']==5) {
				 $query=$query->OrderBy('fl.views',$filters['t']); 
			   }
			   elseif($filters['sort']==6) {
			      $query->Select('(SELECT Count(rating) FROM tio_flavors_rating WHERE id_restaurant=tio_flavors_restaurant.id) as rating_count')->OrderBy('rating_count',$filters['t']); 
			   }
			   elseif($filters['sort']==7) {
			       $query=$query->join('flavors_rating r', 'flavors_restaurant.id = r.id_restaurant','left')->Where('r.type',1)->GroupBy('r.id_restaurant')->OrderBy('r.rating',$filters['t']);  
			   }
			   elseif($filters['sort']==8) {
			       $query=$query->join('flavors_rating r', 'flavors_restaurant.id = r.id_restaurant','left')->Where('r.type',2)->GroupBy('r.id_restaurant')->OrderBy('r.rating',$filters['t']);  
			   }
			   elseif($filters['sort']==9) {
			       $query=$query->join('flavors_rating r', 'flavors_restaurant.id = r.id_restaurant','left')->Where('r.type',3)->GroupBy('r.id_restaurant')->OrderBy('r.rating',$filters['t']);  
			   }
			   elseif($filters['sort']==10) {
			       $query=$query->join('flavors_rating r', 'flavors_restaurant.id = r.id_restaurant','left')->Where('r.type',4)->GroupBy('r.id_restaurant')->OrderBy('r.rating',$filters['t']);  
			   }
			if(empty($get['show'])) {$get['show']=36;}  
			$list = $query->get()->getResultArray();
			$letters_count=array('a'=>0,'b'=>0,'c'=>0,'d'=>0,'e'=>0,'f'=>0,'g'=>0,'h'=>0,'i'=>0,'j'=>0,'k'=>0,'l'=>0,'m'=>0,'n'=>0,'o'=>0,'p'=>0,'q'=>0,'r'=>0,'s'=>0,'t'=>0,'u'=>0,'v'=>0,'w'=>0,'x'=>0,'y'=>0,'z'=>0);
				if(!empty($list)) {
					foreach($list as $el){
						$letter=mb_strtolower(mb_substr(trim($el['name']),0,1));
						$letter=str_replace(array('ą', 'ć', 'ę', 'ł', 'ń', 'ó', 'ś', 'ź', 'ż'), array('a', 'c', 'e', 'l', 'n', 'o', 's', 'z', 'z'), $letter);
						$letters_count[$letter]=$letters_count[$letter]+1;
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
			if(!empty($list)) {
				  $page    = (int) ($this->request->getGet('page') ?? 1); 	
				  $total=count($list);
                  $list=array_slice($list,($page-1)*$get['show'],$get['show']); 
					foreach($list as $k=>$id_r) {
						$list[$k]=$this->flavorsRestaurantsModel->GetRestaurantListById($id_r['id'],$id_lang);	
					}
				$cuisine['restaurants']=$list;	
			}	
			$pager = service('pager');
				if(!empty($letters_count)) {
					$cuisine['letters']=$letters_count;    
				} 				
			 $cuisine['paginate']=$pager->makeLinks($page, $get['show'], $total, 'flavors_full');
	         $cuisine['filters']=$get;
			 return $cuisine; 
	
			}
            else {
				throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();	
			    exit();	
            }				
    }		
	
	function CuisineTypesList($id_lang) {
		
		$cuisine_list = $this->db->table('flavors_cuisine c')->join('flavors_cuisine_lang cl', 'c.id = cl.id_cuisine')->join('links l', 'l.id = cl.id_link')->Select('c.id,cl.name,cl.id_link,link')->Where('cl.id_lang',$id_lang)->Where('l.id_lang',$id_lang)->Where('c.publish',1)->OrderBy('c.order','ASC')->get()->getResultArray();
		if(!empty($cuisine_list)) {
		   foreach($cuisine_list as $k=>$v) {
			  $cuisine_list[$k]['photo']=$this->db->table('flavors_cuisine_files f')->join('flavors_cuisine_files_lang fl', 'f.id = fl.id_file')->Where('fl.id_lang',$id_lang)->Where('f.field','photo')->Where('f.id_cuisine',$v['id'])->get()->getRowArray(); 
		      $cuisine_list[$k]['count']=$this->db->table('flavors_restaurant')->Select('id')->like('cuisine_type', ','.$v['id'].',')->Where('publish',1)->countAllResults();
		   }
		}	
		return $cuisine_list;
	}	
	
	function CuisineTypesMenu($id_lang) {
		
		$cuisine_list = $this->db->table('flavors_cuisine c')->join('flavors_cuisine_lang cl', 'c.id = cl.id_cuisine')->join('links l', 'l.id = cl.id_link')->Select('c.id,cl.name,cl.id_link,link,ico_svg')->Where('cl.id_lang',$id_lang)->Where('l.id_lang',$id_lang)->Where('c.publish',1)->Where('c.menu',1)->OrderBy('c.order','ASC')->get()->getResultArray();
		if(!empty($cuisine_list)) {
		   foreach($cuisine_list as $k=>$v) {
		      $cuisine_list[$k]['count']=$this->db->table('flavors_restaurant')->Select('id')->like('cuisine_type', ','.$v['id'].',')->Where('publish',1)->countAllResults();
		   }
		}	
		return $cuisine_list;
	}	
	
	function GetCuisineList($id_lang,$config) {
		helper('text');
		$cuisine_list = $this->db->table('flavors_cuisine c')->join('flavors_cuisine_lang cl', 'c.id = cl.id_cuisine')->join('links l', 'l.id = cl.id_link')->Select('c.id,cl.name,cl.description,cl.id_link,link,ico_svg')->Where('cl.id_lang',$id_lang)->Where('l.id_lang',$id_lang)->Where('c.publish',1)->OrderBy('c.order','ASC')->get()->getResultArray();
		if(!empty($cuisine_list)) {
		   foreach($cuisine_list as $k=>$v) {
			  $cuisine_list[$k]['photo']=$this->db->table('flavors_cuisine_files f')->join('flavors_cuisine_files_lang fl', 'f.id = fl.id_file')->Where('fl.id_lang',$id_lang)->Where('f.field','photo')->Where('f.id_cuisine',$v['id'])->get()->getRowArray(); 
		      $cuisine_list[$k]['count']=$this->db->table('flavors_restaurant')->Select('id')->like('cuisine_type', ','.$v['id'].',')->Where('publish',1)->countAllResults();
			  //$cuisine_list[$k]['description']=word_limiter($v['description'],20);
		   }
		}	
		return $cuisine_list;
	}	
			
			
}			