<?php

namespace Modules\Foto\Models;  
use CodeIgniter\Model;
use App\Libraries\Link;

class FotoCategoryModel extends Model{

   
	protected $table = 'foto_category';
	
	protected $allowedFields = [
        'id_page_cont',
		'oder',
		'publish',
		're_id'
		
	];	
	
	public function saveCategory($id, $id_content, $post) {
		if(empty($post)) return false;	
        $data = array(
            'id_page_cont' => $id_content,
			're_id'=>!empty($post['re_id']) ? $post['re_id'] : 0,
            'publish' => !empty($post['publish']) ? $post['publish'] : 0,
        );
		$this->db->transStart();
        if($id) {
            $result = $this->db->table('foto_category')->set($data)->where('id', $id)->update();
            $this->id = $id;
        } else {
            $result = $this->db->table('foto_category')->set('order', '`order`+1', FALSE)->Where('order >=',0)->Where('re_id',$data['re_id'])->update();
            $result = $this->db->table('foto_category')->insert($data);
            $this->id = $this->db->insertID();
        }
		
	    $this->saveCategoryLang($this->id, $post['lang'], $id_content);
        $this->saveCategoryMetaLang($this->id, $post['meta']['lang']);
	    $this->db->transComplete();
		return $this->db->transStatus();
	}
	
	    private function saveCategoryLang($id_category, $lang_data, $id_content) 
    {
        if(!empty($lang_data)) {
            $module = $this->db->table('module')->select('id')->where('slug', 'Foto')->get()->getRowArray();
            foreach($lang_data as $id_lang=>$lang) {
					$linkClass = new Link();
					$data = array(
						'id_category' =>$id_category,
						'id_lang' => $id_lang,
						'id_link' => $linkClass->saveLink($lang['link'], $id_lang, $lang['id_link'], !empty($module) ? $module['id'] : 0,false,'category'),
						'name' => $lang['name']
					);
                $lang = $this->db->table('foto_category_lang')->select('id')->where('id_category', $id_category)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('foto_category_lang')->set($data)->where('id_category', $id_category)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('foto_category_lang')->insert($data);
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
                $lang = $this->db->table('foto_category_meta_lang')->select('id')->where('id_category', $id_category)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('foto_category_meta_lang')->set($data)->where('id_category', $id_category)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('foto_category_meta_lang')->insert($data);
                }
            }
        }
    }
	
	    public function getCategoryById($id, $id_lang) 
    {
        $category = $this->where('id', $id)->first();
        if(!empty($category)) {
            $category['lang'] = $this->getCategoryLang($id);
            if(!empty($category['lang']) && !empty($category['lang'][$id_lang]) && !empty($category['lang'][$id_lang]['name'])) {
                $category['name'] = $category['lang'][$id_lang]['name'];
            } else {
                $category['name'] = '';
            }
            $category['meta']['lang'] = $this->getCategoryMetaLang($id);
        }
        return $category;
    }
    
    public function getCategoryLang($id_category) 
    {
        $linkClass = new Link();
        $langs = array();
        $data = $this->db->table('foto_category_lang')->where('id_category', $id_category)->orderBy('id_lang')->get()->getResultArray();
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
        $data = $this->db->table('foto_category_meta_lang')->where('id_category', $id_category)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
    
	    public function getCategoryStructure($id_lang, $re_id = 0, $exclude_ids = array(), $level = 0,$count=false) {
        $db = $this->db->table('foto_category p')
                ->join('foto_category_lang pl', 'p.id = pl.id_category')
				->join('links l', 'l.id = pl.id_link')
                ->select('p.id,p.re_id,p.publish,pl.name,p.order,p.id_page_cont,l.link')
                ->where('pl.id_lang', $id_lang)
				->where('l.id_lang', $id_lang)
                ->where('p.re_id', $re_id);
        if (!empty($exclude_ids)) {
            $db->whereNotIn('p.id', $exclude_ids);
        }
        $pages = $db->orderBy('p.order', 'ASC')->get()->getResultArray();
        if (!empty($pages)) {
            foreach ($pages as $k => $page) {
                $pages[$k]['level'] = $level;
				if(!empty($count)) {
				  $cnt_photo=$this->db->table('foto_files')->Select('id')->Where('publish',1)->Where('id_category',$page['id'])->countAllResults();	
				  $cnt_galleries=$this->db->table('foto_gallery')->Select('SUM(number_of_photo) as total')->Where('publish',1)->Where('id_category',$page['id'])->get()->getRowArray();
                  $pages[$k]['count']=$cnt_photo+$cnt_galleries['total'];				
				}
				
                $pages[$k]['list'] = $this->getCategoryStructure($id_lang,$page['id'], $exclude_ids, $level + 1,$count);
            }
        }
        return $pages;
    }
	
	
	 public function deleteCategory($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $langs = $this->db->table('foto_category_lang')->select('id_link')->where('id_category', $id)->get()->getResultArray();
        if(!empty($langs)) {
            foreach($langs as $l) {
                $this->db->table('links')->where('id', $l['id_link'])->delete();
            }
        }
        $this->db->table('foto_category_lang')->where('id_category', $id)->delete();
        $this->db->table('foto_category_meta_lang')->where('id_category', $id)->delete();
		$cat = $this->select('order,re_id')->where('id', $id)->first();
        $this->where('id', $id)->delete();
		$check_order=$this->select('id')->where('re_id', $cat['re_id'])->where('order >',$cat['order'])->first();
		if(!empty($check_order['id'])) {
			$this->set('order', '`order`-1', FALSE)->where('order >',$cat['order'])->where('re_id',$cat['re_id'])->update();
		}
        $this->db->transComplete();
        return $this->db->transStatus();
    }
	
	function saveCategoryOrder($data,$id_content) {
		$this->db->transStart();
		if(!empty($data['cat_order'])) {
            foreach($data['cat_order'] as $order_data) {
                  foreach($order_data as $order=>$cat_id) {
					$this->db->table('foto_category')->set('order', $order, FALSE)->where('id',$cat_id)->where('id_page_cont',$id_content)->update();
                  }
			}
		}
		$this->db->transComplete();
        return $this->db->transStatus();
	}
	
	function showPhotoCatList($cat_id,$id_lang) {
		$user_l=$this->db->table('links')->Select('link')->Where('slug','user_photo')->get()->getRowArray();
		$cats = $this->db->table('foto_category c')
		->join('foto_category_lang pl', 'c.id = pl.id_category')
		->join('links l', 'l.id = pl.id_link')
        ->select('c.id,pl.name,l.link')
        ->where('pl.id_lang', $id_lang)
		->where('l.id_lang', $id_lang)
        ->where('c.re_id', $cat_id)->OrderBy('order','ASC')->get()->getResultArray();
		if(!empty($cats)) {
		    foreach($cats as $k=>$v) {
				  $cnt_photo=$this->db->table('foto_files')->Select('id')->Where('publish',1)->Where('id_category',$v['id'])->countAllResults();	
				  $cnt_galleries=$this->db->table('foto_gallery')->Select('SUM(number_of_photo) as total')->Where('publish',1)->Where('id_category',$v['id'])->get()->getRowArray();
				  $cats[$k]['number_photo']=$cnt_photo+$cnt_galleries['total']; 
		          $q1=$this->db->table('foto_files f')->join('foto_files_lang fl', 'fl.id_file = f.id')->Select('f.id,fl.name,f.path,fl.name as caption,id_user,f.created_at')->Where('f.publish',1)->Where('f.id_category',$v['id'])->where('fl.id_lang', $id_lang)->OrderBy('f.created_at','DESC')->limit(5);
				  $q2=$this->db->table('foto_gallery f')->join('foto_gallery_lang fl', 'fl.id_gallery = f.id')->join('foto_gallery_files fs', 'fs.id_gallery = f.id')->join('foto_gallery_files_lang fsl', 'fsl.id_file = fs.id')->Select('f.id,fl.name,fs.path,fsl.caption,id_user,f.created_at')->where('fl.id_lang', $id_lang)->where('fsl.id_lang', $id_lang)->Where('f.id_category',$v['id'])->Where('fs.main',1)->OrderBy('f.created_at','DESC')->GroupBy('fs.id_gallery')->limit(5)->union($q1);
				  $list=$this->db->newQuery()->fromSubquery($q2, 'q')->orderBy('created_at', 'DESC')->get()->getResultArray();
				  if(!empty($list)) {
				     $cats[$k]['photos']=array_slice($list, 0,5);
					 if(!empty($cats[$k]['photos'])) {
						foreach($cats[$k]['photos'] as $c=>$photo) { 
						   $cats[$k]['photos'][$c]['user_info']=$this->db->table('users')->select('id,nick,name,surname')->where('id', $photo['id_user'])->get()->getRowArray();
						   if(!empty($cats[$k]['photos'][$c]['user_info']['id'])) {
							    $cats[$k]['photos'][$c]['user_info']['user_link']='foto/g/user/'.crc32($cats[$k]['photos'][$c]['user_info']['id']); 
						   }	    
						} 
					 }	 
				  
				  }
		    }
		}	
		return $cats;
	}

    public function getSinglePhotoCategories($re_id,$id_lang) {
		global $cats;
		 $cat = $this->db->table('foto_category c')
		->join('foto_category_lang pl', 'c.id = pl.id_category')
		->join('links l', 'l.id = pl.id_link')
        ->select('c.id,pl.name,l.link,c.re_id')
        ->where('pl.id_lang', $id_lang)
		->where('l.id_lang', $id_lang)
		->where('c.publish', 1)
        ->where('c.id', $re_id)->OrderBy('order','ASC')->get()->getRowArray();
		if(!empty($cat['re_id'])) {
		  $cat['main_cat']=$this->getSinglePhotoCategories($cat['re_id'],$id_lang);	
		}	
       return $cat;
    }		
}
?>	