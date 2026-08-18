<?php

namespace Modules\Foto\Models;  
use CodeIgniter\Model;
use App\Libraries\Link;

class FotoGalleryModel extends Model{

   
	protected $table = 'foto_gallery';
	
	protected $allowedFields = [
        'id_page_cont',
		'oder',
		'publish',
		'home',
		'investments',
		'id_category',
		'number_of_photo',
		'id_user',
		'user_name',
		're_id'
	];	
	
	
	 private function removeGalleryFile($file) 
    {
        if(file_exists(WRITEPATH . 'uploads/' . $file['path'])) {
            @unlink(WRITEPATH . 'uploads/' . $file['path']);
        }
        $this->db->table('foto_gallery_files_lang')->where('id_file', $file['id'])->delete();
        $this->db->table('foto_gallery_files')->where('id', $file['id'])->delete();
    }
    
    public function deleteGallery($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $langs = $this->db->table('foto_gallery_lang')->select('id_link')->where('id_gallery', $id)->get()->getResultArray();
        if(!empty($langs)) {
            foreach($langs as $l) {
                $this->db->table('links')->where('id', $l['id_link'])->delete();
            }
        }
		$files_list = $this->db->table('foto_gallery_files')->select('id,path')->where('id_gallery', $id)->get()->getResultArray();
        if(!empty($files_list)) {
            foreach($files_list as $f) {
                $this->removeGalleryFile($f);
            }
        }
		$this->db->table('foto_gallery_lang')->where('id_gallery', $id)->delete();
		$this->db->table('foto_gallery_related')->where('id_gallery', $id)->delete();
		$this->db->table('foto_gallery_related')->where('id_related', $id)->delete();
        $this->where('id', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
	
	function getGalleryById($id) {
		$gallery = $this->where('id', $id)->first();
		$gallery['lang'] = $this->getGalleryLang($id);
		$gallery['photos'] = $this->getGalleryFiles($id);
		return $gallery;
	}	
	
	private function getGalleryLang($id_gal) 
    {
        $linkClass = new Link();
        $langs = array();
        $data = $this->db->table('foto_gallery_lang')->where('id_gallery', $id_gal)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $d['link'] = $linkClass->getLink($d['id_link'], $d['id_lang']);
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
	
	 private function getGalleryFiles($id_gallery) 
    {
        $files = $this->db->table('foto_gallery_files')->select('id,name,basename,path,order,type,publish,ext,main')->where('id_gallery', $id_gallery)->orderBy('main DESC','order ASC','id ASC')->get()->getResultArray();
        if(!empty($files)) {
            foreach($files as $k=>$file) {
                $files[$k]['lang'] = $this->getGalleryFileLang($file['id']);
            }
        }
        return $files;
    }
    private function getGalleryFileLang($id_file) 
    {
        $langs = array();
        $data = $this->db->table('foto_gallery_files_lang')->where('id_file', $id_file)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
	
	public function saveGallery($id, $id_content, $post) {
	       $data = array(
            'home' => !empty($post['home']) ? $post['home'] : 0,
            'publish' => !empty($post['publish']) ? $post['publish'] : 0,
			'id_category'=> !empty($post['id_category']) ? $post['id_category'] : 0,
			'investments' => !empty($post['investments']) ? $post['investments'] : 0,
			'number_of_photo'=> !empty($post['photo']) ? count($post['photo']) : 0,
        );
	   $this->db->transStart();
        if($id) {
            $result = $this->set($data)->where('id', $id)->update();
            $this->id = $id;
        }
		$this->saveGalleryLang($this->id, $post['lang'], $id_content);
	    $this->saveGalleryPhotos($this->id, $post, $id_content);
		$this->saveGalleryRelated($this->id, $post);
	    $this->db->transComplete();
        return $this->db->transStatus();
	
	}
	
	public function addGallery($id, $id_content, $post) {
	   helper(['file']);
	   $data = array(
            'home' => !empty($post['home']) ? $post['home'] : 0,
            'publish' => !empty($post['publish']) ? $post['publish'] : 0,
			'id_category'=> !empty($post['id_category']) ? $post['id_category'] : 0,
			'investments' => !empty($post['investments']) ? $post['investments'] : 0,
			'number_of_photo'=> !empty($post['photos']) ? count($post['photos']) : 0,
			'id_page_cont'=>$id_content,
			'id_user'=>!empty($post['id_user']) ? $post['id_user'] : 0
        );
	   $this->db->transStart();
       $result = $this->insert($data);
       $this->id = $this->getInsertID();
	    $this->saveGalleryLang($this->id, $post['lang'], $id_content);
	    $this->addGalleryPhotos($this->id,!empty($post['photos']) ? $post['photos'] : array(), $id_content,!empty($post['photo_main']) ? $post['photo_main'] :'');
		$this->saveGalleryRelated($this->id, $post);
	    $this->db->transComplete();
        return $this->db->transStatus();
	}
	
	private function addGalleryPhotos($id_gallery, $files,$id_content,$main_photo) 
    {
        $ids = array();
		if(!empty($files)) {
            foreach($files as $k=>$file) {
				if(!empty($main_photo) and $main_photo==$k) {$mphoto=1;} else {$mphoto=0;}
                $ids[] = $this->addGalleryFile($id_gallery, $file, $id_content,$mphoto);
            }
        }
    }
	
	    private function addGalleryFile($id_gallery, $file, $id_content,$mphoto) 
    {
        if(!empty($file)) {
                $file_obj = new \CodeIgniter\Files\File(WRITEPATH . 'uploads/' . $file['path']);
                if(!is_dir(WRITEPATH . 'uploads/foto')) {
                    mkdir(WRITEPATH . 'uploads/foto');
                }
                if(!is_dir(WRITEPATH . 'uploads/foto/' . date('Ymd'))) {
                    mkdir(WRITEPATH . 'uploads/foto/' . date('Ymd'));
                }
                $r = $file_obj->move(WRITEPATH . 'uploads/foto/' . date('Ymd') , $file['basename']);
                $file_path = 'foto/' . date('Ymd') . '/' . $r->getFilename();
                $file_info = pathinfo(WRITEPATH . 'uploads/' . $file_path);
                $data = array(
                    'id_gallery' => $id_gallery,
                    'name' => $file['name'],
                    'basename' => $file['basename'],
                    'path' => $file_path,
                    'mime' => $r->getMimeType(),
                    'type' => file_type($r->getMimeType()),
                    'ext' => $file_info['extension'],
                    'order' => !empty($file['order']) ? $file['order'] : 0,
					'main' => !empty($mphoto) ? $mphoto : 0,
                    'publish' => !empty($file['publish']) ? $file['publish'] : 0,
                );
                $result = $this->db->table('foto_gallery_files')->insert($data);
                $id_file = $this->db->insertID();
            
            $this->addGalleryFileLang($id_file, $file['lang']);
        } 
        return !empty($id_file) ? $id_file : 0;
    }
	
	
	private function addGalleryFileLang($id_file, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_file' => $id_file,
                    'id_lang' => $id_lang,
                    'caption' => $lang['caption'],
                    'author' => $lang['author'],
                );
                    $result = $this->db->table('foto_gallery_files_lang')->insert($data);
            }
        }
    }
	
	
	private function saveGalleryLang($id_gallery, $lang_data, $id_content) 
    {
        if(!empty($lang_data)) {
            $module = $this->db->table('module')->select('id')->where('slug', 'Foto')->get()->getRowArray();
            foreach($lang_data as $id_lang=>$lang) {
					$linkClass = new Link();
					$data = array(
						'id_gallery' => $id_gallery,
						'id_lang' => $id_lang,
						'id_link' => $linkClass->saveLink($lang['link'], $id_lang, $lang['id_link'], !empty($module) ? $module['id'] : 0,false,'gallery'),
						'name' => $lang['name'],
						'description' => $lang['description'],
						'keywords'=> $lang['keywords']
					);
                $lang = $this->db->table('foto_gallery_lang')->select('id')->where('id_gallery', $id_gallery)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('foto_gallery_lang')->set($data)->where('id_gallery', $id_gallery)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('foto_gallery_lang')->insert($data);
                }
            }
        }
    }
	
	private function saveGalleryPhotos($id_gallery, $post, $id_content) {

		if(!empty($post['photo'])) {
		    foreach($post['photo'] as $id_file=>$photo) {
				$this->db->table('foto_gallery_files')->set(array('publish'=>!empty($photo['publish']) ? $photo['publish'] : 0))->where('id', $id_file)->update();
		       if(!empty($photo['lang'])) {
		          foreach($photo['lang'] as $id_lang=>$lang) {
					$this->db->table('foto_gallery_files_lang')->set(array('caption'=>$lang['caption'],'author'=>$lang['author']))->where('id_lang', $id_lang)->where('id_file', $id_file)->update();  
				  }
			   }
			   $id_files[]=$id_file;
		    }
		}
		$this->db->table('foto_gallery_files')->set(array('main'=>0))->where('id_gallery', $id_gallery)->update();
		if(!empty($post['photo_main'])) {
			 $this->db->table('foto_gallery_files')->set(array('main'=>1))->where('id_gallery', $id_gallery)->where('id', $post['photo_main'])->update();
		}
		
		if(!empty($post['photo_order'])) {
		   foreach($post['photo_order'] as $order=>$id_file) {
		        $this->db->table('foto_gallery_files')->set(array('order'=>$order))->where('id_gallery', $id_gallery)->where('id', $id_file)->update();
		   }
		}
		
		if(!empty($id_files)) {
			$files_list_remove = $this->db->table('foto_gallery_files')->select('id,path')->where('id_gallery', $id_gallery)->whereNotIn('id',$id_files)->get()->getResultArray();
        }
		else {
			$files_list_remove = $this->db->table('foto_gallery_files')->select('id,path')->where('id_gallery', $id_gallery)->get()->getResultArray();
		}	
		if(!empty($files_list_remove)) {
            foreach($files_list_remove as $f) {
                $this->removeGalleryFile($f);
            }
        }
		
		$number_photo=$this->db->table('foto_gallery_files')->where('id_gallery', $id_gallery)->countAllResults();
		$this->db->table('foto_gallery')->set(array('number_of_photo'=>$number_photo))->where('id', $id_gallery)->update();
	}	
	
	public function saveGalleryRelated($id_gallery,$post) {
		$added=array();
		if(empty($post['related'])) {
			$this->db->table('foto_gallery_related')->where('id_gallery', $id_gallery)->delete();
			$this->db->table('foto_gallery_related')->where('id_related', $id_gallery)->delete();
		}
		else {
			foreach($post['related'] as $rel) {
				$check = $this->db->table('foto_gallery_related')->Select('id')->where('id_gallery', $id_gallery)->where('id_related', $rel)->get()->getRowArray();
				print_r($check);
				if(empty($check['id'])) {
				   $data = array(
						'id_gallery' => $id_gallery,
						'id_related' => $rel
					);
					$result = $this->db->table('foto_gallery_related')->insert($data);
					$added[] = $this->db->insertID();
				}
				else {
					$added[]=$check['id'];
				}	
			}
			if(!empty($added)) {
			   $files_list_remove = $this->db->table('foto_gallery_related')->select('id')->where('id_gallery', $id_gallery)->whereNotIn('id',$added)->get()->getResultArray();
			   if(!empty($files_list_remove)) {
					foreach($files_list_remove as $f) {
						$this->db->table('foto_gallery_related')->where('id', $f['id'])->delete();
					}
				}
			}
		}	
	}	
	
	public function getGalleryRelatedById($id_gallery,$id_lang) {
		$query = $this->db->table('foto_gallery_related r')
		->join('foto_gallery g', 'g.id=r.id_related')
		->join('foto_gallery_lang pl', 'g.id=pl.id_gallery')
		->join('foto_gallery_files pf', 'pf.id_gallery=pl.id_gallery', 'left')
		->select('g.id,pl.name,pf.path,g.created_at,g.id_page_cont')
		 ->where('pl.id_lang', $id_lang)
		 ->where('r.id_gallery', $id_gallery)
		 ->where('pf.main',1)->GroupBy('g.id');
         $query->orderBy('g.created_at', 'DESC');
         $products = $query->get()->getResultArray();
		return $products;
	}	
	
	public function GetRelatedGalleryList($id_gallery,$id_lang) {
		$linkClass = new Link();
		$query = $this->db->table('foto_gallery_related r')
		->join('foto_gallery g', 'g.id=r.id_related')
		->join('foto_gallery_lang pl', 'g.id=pl.id_gallery')
		->join('foto_gallery_files pf', 'pf.id_gallery=pl.id_gallery', 'left')
		->join('users u', 'u.id=g.id_user')
		->select('g.id,pl.name,pf.path as photo,pl.id_link,u.name as user_name,u.surname as user_surname,u.nick,u.id as user_id,number_of_photo')
		 ->where('pl.id_lang', $id_lang)
		 ->where('r.id_gallery', $id_gallery)
		 ->where('pf.main',1)->GroupBy('g.id');
         $query->orderBy('g.created_at', 'DESC');
         $galleries = $query->get()->getResultArray();
	if(!empty($galleries)) {
	   foreach($galleries as $k=>$v) {	
		 $galleries[$k]['link']=$linkClass->getLink($v['id_link'],$id_lang);
		 $galleries[$k]['user_link']='foto/g/user/'.crc32($v['user_id']);
	   }
	}	
		
		return $galleries;
	}	
}	