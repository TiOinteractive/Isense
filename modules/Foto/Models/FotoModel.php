<?php

namespace Modules\Foto\Models;  
use CodeIgniter\Model;
use App\Libraries\Link;
use Modules\Foto\Models\FotoCategoryModel;

class FotoModel extends Model{

   
	protected $table = 'foto_files';
	
	protected $allowedFields = [
        'id_page_cont',
		'publish',
		'home',
		'id_category',
		'id_user',
		'name',
		'basename',
		'path',
		'mime',
		'type',
		'ext'
	];	
	
	
	 private function removeGalleryFile($file) 
    {
        if(file_exists(WRITEPATH . 'uploads/' . $file['path'])) {
            @unlink(WRITEPATH . 'uploads/' . $file['path']);
        }
        $this->db->table('foto_files_lang')->where('id_file', $file['id'])->delete();
    }
    
    public function deleteFile($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $files_list = $this->db->table('foto_files')->select('id,path')->where('id', $id)->get()->getResultArray();
        if(!empty($files_list)) {
            foreach($files_list as $f) {
                $this->removeGalleryFile($f);
            }
        } 
        $this->where('id', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
	
	public function getSinglePhoto($hash,$id_lang) {
		if(!empty($hash)) {
		   $session = \Config\Services::session();	
		   if(!empty($hash)) { 	
			 $file = $this->db->table('foto_files f')->join('foto_files_lang gfl', 'gfl.id_file=f.id', 'left')
			 ->join('users u', 'u.id=f.id_user', 'left')
			 ->select('f.id,path,id_page_cont,id_category,f.created_at,gfl.name,views,id_user,u.nick,u.name as user_name,u.surname as user_surname,keywords,description')->having('CRC32(f.id)', $hash)->where('f.publish', 1)
							->groupStart()
								->where('gfl.id_lang', $id_lang)
								->orWhere('gfl.id_lang', null)
							->groupEnd()->get()->getRowArray();
			
			if(!empty($file)) {
				
				if(!empty($file) and empty($session->get('galleryf_view_stats_'.$file['id']))) {
					$this->db->table('foto_files_lang')->set('views', 'views+1', false)->where('id_file', $file['id'])->update();
					$session->set('galleryf_view_stats_'.$file['id'], '1');
				 
				  }
				  $file['size']=$this->filesize_formatted(WRITEPATH . 'uploads/'.$file['path']);
				  $file['dimensions']=getimagesize(WRITEPATH . 'uploads/'.$file['path']);
				  if(!empty($file['id_category']) and $file['id_category']>0) {
					  $this->fotoCategoryModel = new FotoCategoryModel();
					  $file['category']=$this->fotoCategoryModel->getSinglePhotoCategories($file['id_category'],$id_lang);  
				  }	  
				  $file['caption'] = $file['name'] . ($file['category']['main_cat']['name'] ? ' - ' . $file['category']['main_cat']['name'] : '') . ($file['category']['name'] ? ', ' . $file['category']['name'] : '');
			}	
		   }
		}	
		return $file;
	}	
	
	function getUserInfo($hash,$id_lang) {
		    $session = \Config\Services::session();	
		    if(!empty($hash)) { 
		      $user = $this->db->table('users u')->select('id,name,surname,nick')->having('CRC32(id)', $hash)->where('active', 1)->get()->getRowArray();
			  if(!empty($user['id'])) {
				$user['cnt']['gallery']=$this->db->table('foto_gallery')->select('SUM(number_of_photo) as gallery_cnt_photo')->where('publish', 1)->where('id_user',$user['id'])->get()->getRowArray();
				$user['cnt']['gallery_count']=$this->db->table('foto_gallery')->select('id')->where('publish', 1)->where('id_user',$user['id'])->countAllResults();
				$user['cnt']['files']=$this->db->table('foto_files')->select('id')->where('publish', 1)->where('id_user',$user['id'])->countAllResults();
				return $user;
			  }
		    }
	}	
	
	

function filesize_formatted($path)
{
    $size = filesize($path);
    $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $power = $size > 0 ? floor(log($size, 1024)) : 0;
    return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}

	public function addPhoto($id, $id_content, $post) {
		   helper(['file']);
		  $this->db->transStart();
		  $file_obj = new \CodeIgniter\Files\File(WRITEPATH . 'uploads/' . $post['photo']['path']);
                if(!is_dir(WRITEPATH . 'uploads/foto')) {
                    mkdir(WRITEPATH . 'uploads/foto');
                }
                if(!is_dir(WRITEPATH . 'uploads/foto/' . date('Ymd'))) {
                    mkdir(WRITEPATH . 'uploads/foto/' . date('Ymd'));
                }
                $r = $file_obj->move(WRITEPATH . 'uploads/foto/' . date('Ymd') , $post['photo']['basename']);
                $file_path = 'foto/' . date('Ymd') . '/' . $r->getFilename();
                $file_info = pathinfo(WRITEPATH . 'uploads/' . $file_path);
                $data = array(
                    'id_page_cont' => $id_content,
                    'name' => $post['photo']['name'],
                    'basename' => $post['photo']['basename'],
                    'path' => $file_path,
                    'mime' => $r->getMimeType(),
                    'type' => file_type($r->getMimeType()),
                    'ext' => $file_info['extension'],
                    'publish' => !empty($post['publish']) ? $post['publish'] : 0,
                    'home' => !empty($post['home']) ? $post['home'] : 0,
					'id_category' => !empty($post['id_category']) ? $post['id_category'] : 0,
					'id_user' => !empty($post['id_user']) ? $post['id_user'] : 0,
                );
                $result = $this->db->table('foto_files')->insert($data);
                $id_file = $this->db->insertID();
				if(!empty($post['lang'])) {
					foreach($post['lang'] as $id_lang=>$lang) {
						$data = array(
							'id_file' => $id_file,
							'id_lang' => $id_lang,
							'name' => $lang['name'],
							'description' => $lang['description'],
							'keywords' => $lang['keywords'],
						);
					
					$result = $this->db->table('foto_files_lang')->insert($data);
						
					}
				}
		    $this->db->transComplete();
            return $this->db->transStatus();   
	}   
}
	