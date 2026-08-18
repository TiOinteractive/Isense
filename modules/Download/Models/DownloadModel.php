<?php

namespace Modules\Download\Models;  
use CodeIgniter\Model;
use App\Libraries\Link;

class DownloadModel extends Model{
    protected $table = 'download';
	
	 protected $allowedFields = [
        'id_page_cont',
        'template',
        'publish',
        'edited_at',
        'created_at',
    ];
	
		public function saveDownloadByPageContent($id_content, $post) {	
			$download = $this->select('id')->where('id_page_cont', $id_content)->first();
			$this->saveDownload(!empty($download) ? $download['id'] : 0, $id_content, $post);
		}
	
	  public function saveDownload($id, $id_content, $post) 
    {
        helper(['file']);
        if(empty($post)) return false;
			$data = array(
				'id_page_cont' => $id_content,
				'template' => $post['template'],
				'publish' => !empty($post['publish']) ? $post['publish'] : 0,
			);
	    $this->db->transStart();
        if($id) {
            $result = $this->set($data)->where('id', $id)->update();
            $this->id = $id;
        } else {
            $result = $this->insert($data);
            $this->id = $this->getInsertID();
        }
		HistoryStat($id,$id_content,'download','Download',$result ? lang('Admin.page.EditSuccess') : lang('Admin.page.AddSuccess'));
		if(isset($post['lang'])) {
		   foreach($post['lang'] as $id_lang=>$lang) {
            if(isset($lang['file'])) {
              foreach($lang['file'] as $id_file=>$file) {
					$data = array(
						'id_download' => $this->id,
						'caption' => $file['caption'],
						'id_file' => $file['id'],
						'id_lang' => $id_lang,
						'order' => !empty($file['order']) ? $file['order'] : 0,
						'publish' => !empty($file['publish']) ? $file['publish'] : 0,
					);
				  $check = $this->db->table('download_files_lang')->select('id')->where('id_file', $file['id'])->where('id_lang', $id_lang)->get()->getRowArray();  
				  if(!empty($check) && !empty($check['id'])) {
					$this->db->table('download_files_lang')->set($data)->where('id', $check['id'])->where('id_lang', $id_lang)->update(); 
					$ids[]=$check['id'];
				  }
				  else {
                   $result = $this->db->table('download_files_lang')->insert($data);
				   $ids[]= $this->db->insertID();
				  }				  
			  }	
            }
			
			if(isset($ids) and !empty($ids)) {
				$query = $this->db->table('download_files_lang')->select('id')->where('id_download', $id);
				if(!empty($id_file)) {
					$query->whereNotIn('id',$ids);
				}
				$files_list = $query->get()->getResultArray();
				if(!empty($files_list)) {
						foreach($files_list as $f) {
							$this->db->table('download_files_lang')->where('id', $f['id'])->delete();
						}
				}
			}		
		   }	   	
		}
       else {
		 $this->db->table('download_files_lang')->where('id_download', $id)->delete();
       }		   
	   $this->db->transComplete();
       return $this->db->transStatus();
	}
	
	public function getDownloadByContentId($id_content) 
    {
        $download = $this->where('id_page_cont', $id_content)->first();
        if(!empty($download)) {
            $download['files']=$this->getDownloadFiles($download['id']);
        }
        return $download;
    }
	
	
	  private function getDownloadFiles($id_download) 
    {
		
       $files_list = $this->db->table('download_files_lang')->select('id,id_lang,caption,id_file,order,publish,downloads')->where('id_download', $id_download)->orderBy('id_lang', 'ASC')->orderBy('order', 'ASC')->get()->getResultArray();
       $files=array();  
	   if(!empty($files_list)) {
            foreach($files_list as $k=>$file) {
				
			   $file['file']=$this->db->table('download_files')->where('id', $file['id_file'])->get()->getRowArray();	
               $files[$file['id_lang']][$file['id']]=$file;
            }
        }
        return $files;
    }
	
	public function saveCategory($id, $id_content, $post) {
		helper(['file']);
        if(empty($post)) return false;
        $data = array(
            'id_page_cont' => $id_content,
            'publish' => !empty($post['publish']) ? $post['publish'] : 0
        );
        $this->db->transStart();
        if($id) {
            $result = $this->db->table('download_cat')->set($data)->where('id', $id)->update();
            $this->id = $id;
        } else {
            $result = $this->db->table('download_cat')->set('order', '`order`+1', FALSE)->where('id_page_cont', $id_content)->update();
            $result = $this->db->table('download_cat')->insert($data);
            $this->id = $this->db->insertID();
        }
		$this->saveCategoryLang($this->id, $post['lang']);
		if(isset($post['files']['lang'])) { $this->saveFilesLang($this->id, $post['files']['lang']); }
		
        $this->db->transComplete();
		return $this->db->transStatus();	  
	}	


 public function saveCategoryLang($id_cat, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_cat' => $id_cat,
                    'name' => $lang['name'],
					'id_lang'=>$id_lang
                );
                $lang = $this->db->table('download_cat_lang')->select('id')->where('id_cat', $id_cat)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('download_cat_lang')->set($data)->where('id_cat', $id_cat)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('download_cat_lang')->insert($data);
                }
            }
        }
    }

public function getCategoryById ($id, $id_lang) {
	$category=$this->db->table('download_cat')->where('id', $id)->get()->getRowArray();
	
	$data = $this->db->table('download_cat_lang')->where('id_cat', $id)->orderBy('id_lang')->get()->getResultArray();
       if(!empty($data)) {
            foreach($data as $d) {
                $category['lang'][$d['id_lang']] = $d;
       }	
       if(!empty($category['lang']) && !empty($category['lang'][$id_lang]) && !empty($category['lang'][$id_lang]['name'])) {
                $category['name'] = $category['lang'][$id_lang]['name'];
            } else {
                $category['name'] = '';
            }
       
	   $files_list = $this->db->table('download_files_lang')->select('id,id_lang,caption,id_file,order,publish,downloads')->where('id_cat', $id)->orderBy('id_lang', 'ASC')->orderBy('order', 'ASC')->get()->getResultArray();
       $files=array();  
	   if(!empty($files_list)) {
            foreach($files_list as $k=>$file) {
			   $file['file']=$this->db->table('download_files')->where('id', $file['id_file'])->get()->getRowArray();	
               $category['files'][$file['id_lang']][$file['id']]=$file;
            }
        }		
      }
	
     return $category;
}	

public function getCategories($id_content,$lang_prefix) {
	$defaultLang = $this->db->table('language')->select('id')->where('slug', $lang_prefix)->where('publish', 1)->get()->getRow();
	$data = $this->db->table('download_cat')->select('download_cat.id,order,name,publish')->where('id_page_cont', $id_content)->join('download_cat_lang', 'download_cat_lang.id_cat = download_cat.id and download_cat_lang.id_lang='.$defaultLang->id.'', 'left')->orderBy('order')->get()->getResultArray();
	if(isset($data)) {
	  foreach($data as $k=>$v) {
          $data[$k]['count']=$this->db->table('download_files_lang')->where('id_cat', $v['id'])->countAllResults();
	  }	  	
	}
	return $data;
}	

public function saveFilesLang($id,$files) {
       if(isset($files)) {
		   foreach($files as $id_lang=>$lang) {
            if(isset($lang['file'])) {
              foreach($lang['file'] as $id_file=>$file) {
					$data = array(
						'id_cat' => $id,
						'caption' => $file['caption'],
						'id_file' => $file['id'],
						'id_lang' => $id_lang,
						'order' => !empty($file['order']) ? $file['order'] : 0,
						'publish' => !empty($file['publish']) ? $file['publish'] : 0,
					);
				  $check = $this->db->table('download_files_lang')->select('id')->where('id_file', $file['id'])->where('id_cat', $id)->where('id_lang', $id_lang)->get()->getRowArray();  
				  if(!empty($check) && !empty($check['id'])) {
					$this->db->table('download_files_lang')->set($data)->where('id', $check['id'])->where('id_lang', $id_lang)->update(); 
					$ids[]=$check['id'];
				  }
				  else {
                   $result = $this->db->table('download_files_lang')->insert($data);
				   $ids[]= $this->db->insertID();
				  }				  
			  }	
            }
			if(isset($ids) and !empty($ids)) {
				$query = $this->db->table('download_files_lang')->select('id')->where('id_cat', $id);
				if(!empty($ids)) {
					$query->whereNotIn('id',$ids);
				}
				$files_list = $query->get()->getResultArray();
				if(!empty($files_list)) {
						foreach($files_list as $f) {
							$this->db->table('download_files_lang')->where('id', $f['id'])->delete();
						}
				}
			}	
		   }	   	
		}
        else {
          $this->db->table('download_files_lang')->where('id_cat', $id)->delete();
        }			
}	


 public function deleteCat($id) 
    {
        if(empty($id)) return false;
			$this->db->table('download_files_lang')->where('id_cat', $id)->delete();
			$this->db->table('download_cat_lang')->where('id_cat', $id)->delete();
			$this->db->table('download_cat')->where('id', $id)->delete();
		return true;
    }
   
}
?> 