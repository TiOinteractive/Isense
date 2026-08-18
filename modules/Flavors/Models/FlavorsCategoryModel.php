<?php

namespace Modules\Flavors\Models;  
use CodeIgniter\Model;
use App\Libraries\Link;

class FlavorsCategoryModel extends Model{
	
	protected $table = 'flavors_categories';
			protected $allowedFields = [
				'publish',
				'edited_at',
				'id',
				'order',
				'svg',
				're_id',
				'created_at',
			];
	
	
	public function saveCategory($id, $post) 
    {
        helper(['file']);
        if(empty($post)) return false;	
        $data = array(
            're_id' => !empty($post['re_id']) ? $post['re_id'] : 0,
            'publish' => !empty($post['publish']) ? $post['publish'] : 0,
			'svg' => !empty($post['svg']) ? $post['svg'] : NULL,
        );
        $this->db->transStart();
        if($id) {
            $result = $this->set($data)->where('id', $id)->update();
            $this->id = $id;
        } else {
            $result = $this->set('order', '`order`+1', FALSE)->Where('order >=',0)->update();
            $result = $this->insert($data);
            $this->id = $this->getInsertID();
        }
        $this->saveCategoryLang($this->id, $post['lang']);
        $this->saveCategoryMetaLang($this->id, $post['meta']['lang']);
		if(empty($post['cat_param'])) {$post['cat_param']=array();}
		$this->saveCategoryParameters($this->id, $post['cat_param']);
		$this->saveCategoryFile($this->id, !empty($post['menu']) ? $post['menu'] : array(), 'menu', true);
		$this->saveCategoryFile($this->id, !empty($post['mapicon']) ? $post['mapicon'] : array(), 'mapicon', true);
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    private function saveCategoryLang($id_category, $lang_data) 
    {
        if(!empty($lang_data)) {
            $module = $this->db->table('module')->select('id')->where('slug', 'Flavors')->get()->getRowArray();
            foreach($lang_data as $id_lang=>$lang) {
					$linkClass = new Link();
					$data = array(
						'id_category' => $id_category,
						'id_lang' => $id_lang,
						'id_link' => $linkClass->saveLink($lang['link'], $id_lang, $lang['id_link'], !empty($module) ? $module['id'] : 0,false,null,'category'),
						'name' => $lang['name'],
						'description' => $lang['description']
					);
                $lang = $this->db->table('flavors_categories_lang')->select('id')->where('id_category', $id_category)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('flavors_categories_lang')->set($data)->where('id_category', $id_category)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('flavors_categories_lang')->insert($data);
                }
            }
        }
    }
    
    private function saveCategoryMetaLang($id_category, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_category' => $id_category,
                    'id_lang' => $id_lang,
                    'title' => $lang['title'],
                    'description' => $lang['description'],
                    'keywords' => $lang['keywords'],
                );
                $lang = $this->db->table('flavors_categories_meta_lang')->select('id')->where('id_category', $id_category)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('flavors_categories_meta_lang')->set($data)->where('id_category', $id_category)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('flavors_categories_meta_lang')->insert($data);
                }
            }
        }
    }
	
	private function saveCategoryParameters($id_category,$cat_data) {
		

		if(!empty($cat_data)) {
			foreach($cat_data as $order=>$id_par) {
					$data = array(
							'id_category' => $id_category,
							'id_parameter' => $id_par,
							'order' => $order
					);
			     $par = $this->db->table('flavors_categories_parameters')->select('id')->where('id_category', $id_category)->where('id_parameter', $id_par)->get()->getRowArray();
				if(!empty($par) && !empty($par['id'])) {
                    $result = $this->db->table('flavors_categories_parameters')->set($data)->where('id_category', $id_category)->where('id_parameter', $id_par)->update();
                } else {
                    $result = $this->db->table('flavors_categories_parameters')->insert($data);
                }
			
			}
		}	
		
	}
	
	    private function saveCategoryFile($id_category, $file, $field='', $remove=false) 
    {
        if(!empty($file)) {
            if(!empty($file['id']) && !empty($this->db->table('flavors_categories_files')->select('id')->where('id', $file['id'])->get()->getRowArray())) {
                $data = array(
                    'order' => !empty($file['order']) ? $file['order'] : 0,
                    'publish' => !empty($file['publish']) ? $file['publish'] : 0,
                );
                $result = $this->db->table('flavors_categories_files')->set($data)->where('id', $file['id'])->update();
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
                    'id_category' => $id_category,
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
                $result = $this->db->table('flavors_categories_files')->insert($data);
                $id_file = $this->db->insertID();
            }
            $this->saveCategoryFileLang($id_file, $file['lang']);
        }
        if($remove) {
            $query = $this->db->table('flavors_categories_files')->select('id,path')->where('id_category', $id_category)->where('field', $field);
            if(!empty($id_file)) {
                $query->where('id !=', $id_file);
            }
            $files_list = $query->get()->getResultArray();
            if(!empty($files_list)) {
                foreach($files_list as $f) {
                    $this->removeCategoryFile($f);
                }
            }
        }
        return !empty($id_file) ? $id_file : 0;
    }
    
    private function saveCategoryFileLang($id_file, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_file' => $id_file,
                    'id_lang' => $id_lang,
                    'caption' => $lang['caption'],
                    'author' => $lang['author'],
                );
                $lang = $this->db->table('flavors_categories_files_lang')->select('id')->where('id_file', $id_file)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('flavors_categories_files_lang')->set($data)->where('id_file', $id_file)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('flavors_categories_files_lang')->insert($data);
                }
            }
        }
    }
	
	  private function removeCategoryFile($file) 
    {
        if(file_exists(WRITEPATH . 'uploads/' . $file['path'])) {
            @unlink(WRITEPATH . 'uploads/' . $file['path']);
        }
        $this->db->table('flavors_categories_files_lang')->where('id_file', $file['id'])->delete();
        $this->db->table('flavors_categories_files')->where('id', $file['id'])->delete();
    }	
	
	 public function getCategoryById($id, $id_lang) 
    {
        $category= $this->where('id', $id)->first();
        if(!empty($category)) {
            $category['lang'] = $this->getCategoryLang($id);
            if(!empty($category['lang']) && !empty($category['lang'][$id_lang]) && !empty($category['lang'][$id_lang]['name'])) {
               $category['name'] = $category['lang'][$id_lang]['name'];
            } else {
                $category['name'] = '';
            }
			if(!empty($category['lang']) && !empty($category['lang'][$id_lang]) && !empty($category['lang'][$id_lang]['filter_name'])) {
               $category['filter_name'] = $category['lang'][$id_lang]['filter_name'];
            } else {
                $category['filter_name'] = '';
            }
            $category['meta']['lang'] = $this->getCategoryMetaLang($id);
			$category['params'] = $this->getCategoryParams($id, $id_lang);
			$category['menu'] = $this->getCategoryFile($id, 'menu');
			$category['mapicon'] = $this->getCategoryFile($id, 'mapicon');
        }
        return $category;
    }
    
	
		 private function getCategoryFile($id_category, $field='') 
    {
        $file = $this->db->table('flavors_categories_files')->select('id,name,basename,path,order,type,publish,ext')->where('id_category', $id_category)->where('field', $field)->orderBy('order', 'ASC')->get()->getRowArray();
        if(!empty($file)) {
            $file['lang'] = $this->getCategoryFileLang($file['id']);
        }
        return $file;
    }
    
  
	
	  private function getCategoryFileLang($id_file) 
    {
        $langs = array();
        $data = $this->db->table('flavors_categories_files_lang')->where('id_file', $id_file)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
	
	
	
	
	
    private function getCategoryLang($id_category) 
    {
        $linkClass = new Link();
        $langs = array();
        $data = $this->db->table('flavors_categories_lang')->where('id_category', $id_category)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $d['link'] = $linkClass->getLink($d['id_link'], $d['id_lang']);
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
    
    public function getCategoryMetaLang($id_category) 
    {
        $langs = array();
        $data = $this->db->table('flavors_categories_meta_lang')->where('id_category', $id_category)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
    
	
	
	private function getCategoryParams($id_category, $id_lang) 
    {
		$this->flavorsParametersModel = new FlavorsParametersModel(); 
        $linkClass = new Link();
        $params = array();
        $data = $this->db->table('flavors_categories_parameters')->where('id_category', $id_category)->orderBy('order','ASC')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $k=>$d) {
                $params[$k]=$this->flavorsParametersModel->getParameterById($d['id_parameter'], $id_lang);	
				$params[$k]['values']=$this->flavorsParametersModel->valuesList($d['id_parameter'], $id_lang);
            }
        }
        return $params;
    }
	
	
	    public function getCategoryStructure($id_lang, $re_id = 0, $exclude_ids = array(), $level = 0) {
        $db = $this->db->table('flavors_categories p')
                ->join('flavors_categories_lang pl', 'p.id = pl.id_category')
                ->select('p.id,p.re_id,p.publish,pl.name,p.order')
                ->where('pl.id_lang', $id_lang)
                ->where('p.re_id', $re_id);
        if (!empty($exclude_ids)) {
            $db->whereNotIn('p.id', $exclude_ids);
        }
        $pages = $db->orderBy('p.order', 'ASC')->get()->getResultArray();
        if (!empty($pages)) {
            foreach ($pages as $k => $page) {
                $pages[$k]['level'] = $level;
                $pages[$k]['list'] = $this->getCategoryStructure($id_lang,$page['id'], $exclude_ids, $level + 1);
            }
        }
        return $pages;
    }
	
		function saveCategoryOrder($data,$id_content) {
		$this->db->transStart();
		if(!empty($data['cat_order'])) {
            foreach($data['cat_order'] as $order_data) {
                  foreach($order_data as $order=>$cat_id) {
					$this->db->table('flavors_categories')->set('order', $order, FALSE)->where('id',$cat_id)->update();
                  }
			}
		}
		$this->db->transComplete();
        return $this->db->transStatus();
	}
	
	 public function deleteCategory($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $langs = $this->db->table('flavors_categories_lang')->select('id_link')->where('id_category', $id)->get()->getResultArray();
        if(!empty($langs)) {
            foreach($langs as $l) {
                $this->db->table('links')->where('id', $l['id_link'])->delete();
            }
        }
        $this->db->table('flavors_categories_lang')->where('id_category', $id)->delete();
        $this->db->table('flavors_categories_meta_lang')->where('id_category', $id)->delete();
		$this->db->table('flavors_categories_parameters')->where('id_category', $id)->delete();
		$cat = $this->select('order,re_id')->where('id', $id)->first();
        $this->where('id', $id)->delete();
		$check_order=$this->select('id')->where('re_id', $cat['re_id'])->where('order >',$cat['order'])->first();
		if(!empty($check_order['id'])) {
			$this->set('order', '`order`-1', FALSE)->where('order >',$cat['order'])->where('re_id',$cat['re_id'])->update();
		}
        $this->db->transComplete();
        return $this->db->transStatus();
    }
	
	
		
	    function getCategoryPatch($id,$id_lang,$join,$patch='') {
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
	
	
	
	function GetCategoryRestaurants($id_link,$id_lang) {
		$this->flavorsRestaurantsModel = new FlavorsRestaurantsModel();
	    $this->request = \Config\Services::request();
		$category=$this->db->table('flavors_categories c')
            ->join('flavors_categories_lang cl', 'c.id = cl.id_category')
			 ->join('links l', 'l.id = cl.id_link','left')
			 ->join('flavors_categories_meta_lang fm', 'c.id = fm.id_category','left') 
			->select('c.id,cl.name,cl.description,l.link,fm.title as meta_title,fm.description as meta_desc,fm.keywords as meta_key')
            ->where('cl.id_lang', $id_lang)
			->where('l.id_lang', $id_lang)
			->where('fm.id_lang', $id_lang)
			->where('cl.id_link',$id_link)
			->get()->getRowArray();
			$get = $this->request->getGet();
		    $filters=$get;	
			$category['parameters']=$this->flavorsRestaurantsModel->GetCategoryParameters($filters,$id_lang,$category['id']);
			$category['background']=$this->db->table('flavors_categories_files f')->join('flavors_categories_files_lang fl', 'f.id = fl.id_file')->Select('path,caption')->Where('field','background')->where('fl.id_lang', $id_lang)->where('f.id_category', $category['id'])->get()->getRowArray();
			if(!empty($category['parameters']) and !empty($filters['f'])) {
				foreach($category['parameters'] as $param) {
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
				$category['choosen_parameters']=$choosen;
			}
	        if(!empty($category)) {
			if(!empty($filters['f'])) {
				  foreach($filters['f'] as $k=>$filter_values) {
					 foreach($filter_values as $c=>$filter) { 
					   $product_list=$this->db->table('flavors_restaurant_parameters p')->join('flavors_restaurant r', 'p.id_restaurant=r.id')->Select('p.id_restaurant')->Where('r.publish',1)->GroupStart()->Where('r.id_category',$category['id'])->orLike('r.additional_category',','.$category['id'].',')->GroupEnd()->Where('p.id_value',$filter)->groupBy('p.id_restaurant')->get()->getResultArray();
					   if(!empty($product_list)) {
						   foreach($product_list as $prod) {
							  if(!empty($prod['id_restaurant'])) { $ids_p[$k][$c][]=$prod['id_restaurant'];}
						   }
					   }
					 }   
				  }
			   }
			    $query=$this->db->table('flavors_restaurant')->join('flavors_restaurant_lang fl', 'flavors_restaurant.id = fl.id_restaurant')->Select('flavors_restaurant.id,fl.name,flavors_restaurant.awarded')->GroupStart()->Where('flavors_restaurant.id_category',$category['id'])->orLike('flavors_restaurant.additional_category',','.$category['id'].',')->GroupEnd()->Where('publish',1)->Where('fl.id_lang',$id_lang);
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
				$page    = (int) ($this->request->getGet('page') ?? 1);
				$total=0;				
				if(!empty($list)) {	
				  $total=count($list);
                  $list=array_slice($list,($page-1)*$get['show'],$get['show']); 
					foreach($list as $k=>$id_r) {
						$list[$k]=$this->flavorsRestaurantsModel->GetRestaurantListById($id_r['id'],$id_lang);	
					}
					$category['restaurants']=$list;	
				}	
			$pager = service('pager');
            if(!empty($letters_count)) {
               $category['letters']=$letters_count;    
            } 				
			$category['paginate']=$pager->makeLinks($page, $get['show'], $total, 'flavors_full');
			$category['filters']=$get;
			 return $category; 
			}
            else {
				throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();	
			    exit();	
            }
	}
	
		function GetAllRestaurants($id_lang) {
		$this->flavorsRestaurantsModel = new FlavorsRestaurantsModel();
	    $this->request = \Config\Services::request();
	
		$get = $this->request->getGet();
		$filters=$get;
	    $category=array();
			    $query=$this->db->table('flavors_restaurant')->join('flavors_restaurant_lang fl', 'flavors_restaurant.id = fl.id_restaurant')->Select('flavors_restaurant.id,fl.name,flavors_restaurant.awarded')->Where('publish',1)->Where('archives',0)->Where('fl.id_lang',$id_lang);
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
				$page    = (int) ($this->request->getGet('page') ?? 1);
				$total=0;				
				if(!empty($list)) {	
				  $total=count($list);
                  $list=array_slice($list,($page-1)*$get['show'],$get['show']); 
					foreach($list as $k=>$id_r) {
						$list[$k]=$this->flavorsRestaurantsModel->GetRestaurantListById($id_r['id'],$id_lang);	
					}
					$category['restaurants']=$list;	
				}	
			$pager = service('pager');
            if(!empty($letters_count)) {
               $category['letters']=$letters_count;    
            } 				
			$category['paginate']=$pager->makeLinks($page, $get['show'], $total, 'flavors_full');
			$category['filters']=$get;
			 return $category; 
	}
	
	public function getCatForMenu($id, $id_lang, $languages) 
    {
        $types = $this->db->table('flavors_categories c')
                ->join('flavors_categories_lang cl', 'c.id = cl.id_category')
                ->select('c.id as id_target,c.publish,cl.name')
                ->where('cl.id_lang', $id_lang)
                ->where('c.id', $id)
                ->get()
                ->getRowArray();
        if(!empty($types)) {
            $types['lang'] = array();
            $lang_data = $this->db->table('flavors_categories_lang cl')->join('links l', 'l.id = cl.id_link')->select('cl.id_lang,cl.name,l.link as url')->where('cl.id_category', $id)->orderBy('cl.id_lang', 'ASC')->get()->getResultArray();
            if(!empty($lang_data)) {
                foreach($lang_data as $l) {
                    $l['title'] = $l['name'];
                    if(!empty($l['url'])) $l['url'] = (!empty($languages) && !empty($languages[$l['id_lang']]) && $languages[$l['id_lang']]['slug'] ? '/' . $languages[$l['id_lang']]['slug'] : '') . '/' . $l['url'];
                    $types['lang'][$l['id_lang']] = $l;
                }
            }
        }
        return $types;
    }
	
}
?>	