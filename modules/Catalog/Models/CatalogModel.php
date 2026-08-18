<?php

namespace Modules\Catalog\Models;  
use CodeIgniter\Model;
use App\Libraries\Link;
use Modules\Tags\Models\TagsModel;

class CatalogModel extends Model{

    protected $table = 'catalog';
    
    protected $allowedFields = [
        'id_page_cont',
        'id_parent',
        'type',
        'website',
        'email',
        'phone',
        'cords',
        'template',
        'order',
        'publish',
        'comment',
        'edited_at',
        'created_at',
    ];
    
    public function getCatalogById($id, $id_lang) 
    {
        $catalog = $this->where('id', $id)->first();
        if(!empty($catalog)) {
            $catalog['lang'] = $this->getCatalogLang($id);
            if(!empty($catalog['lang']) && !empty($catalog['lang'][$id_lang]) && !empty($catalog['lang'][$id_lang]['name'])) {
                $catalog['name'] = $catalog['lang'][$id_lang]['name'];
            } else {
                $catalog['name'] = '';
            }
            $catalog['meta']['lang'] = $this->getCatalogMetaLang($id);
            $catalog['photo'] = $this->getCatalogFile($id, 'photo');
            $catalog['photos'] = $this->getCatalogFiles($id, 'photos');
        }
        return $catalog;
    }
    
    private function getCatalogLang($id_catalog) 
    {
        $linkClass = new Link();
        $langs = array();
        $data = $this->db->table('catalog_lang')->where('id_catalog', $id_catalog)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            $this->tagsModel = new TagsModel();
            foreach($data as $d) {	
                if(!empty($d['tags'])) {
                    $d['tags']=$this->tagsModel->GetTagsById($d['tags']);
                }
                $d['link'] = $linkClass->getLink($d['id_link'], $d['id_lang']);
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
    
    public function getCatalogMetaLang($id_catalog) 
    {
        $langs = array();
        $data = $this->db->table('catalog_meta_lang')->where('id_catalog', $id_catalog)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
    
    private function getCatalogFile($id_catalog, $field='') 
    {
        $file = $this->db->table('catalog_files')->select('id,name,basename,path,order,type,publish,ext')->where('id_catalog', $id_catalog)->where('field', $field)->orderBy('order', 'ASC')->get()->getRowArray();
        if(!empty($file)) {
            $file['lang'] = $this->getCatalogFileLang($file['id']);
        }
        return $file;
    }
    
    private function getCatalogFiles($id_catalog, $field='') 
    {
        $files = $this->db->table('catalog_files')->select('id,name,basename,path,order,type,publish,ext')->where('id_catalog', $id_catalog)->where('field', $field)->orderBy('order', 'ASC')->get()->getResultArray();
        if(!empty($files)) {
            foreach($files as $k=>$file) {
                $files[$k]['lang'] = $this->getCatalogFileLang($file['id']);
            }
        }
        return $files;
    }
    
    private function getCatalogFileLang($id_file) 
    {
        $langs = array();
        $data = $this->db->table('catalog_files_lang')->where('id_file', $id_file)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }

    public function saveCatalog($id, $id_content, $post) 
    {
        helper(['file', 'text']);
        if(empty($post)) return false;	 	
        $data = array(
            'id_page_cont' => $id_content,
            'type' => !empty($post['type']) ? $post['type'] : '',
            'website' => $post['website'],
            'email' => $post['email'],
            'phone' => $post['phone'],
            'cords' => !empty($post['cords']) ? $post['cords'] : null,
            'template' => $post['template'],
            'publish' => !empty($post['publish']) ? $post['publish'] : 0,
            'order' => 0,
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
        
        $this->saveCatalogLang($this->id, $post['lang'], $id_content, $data['type']);
        $this->saveCatalogMetaLang($this->id, $post['meta']['lang']);
        $this->saveCatalogFile($this->id, !empty($post['photo']) ? $post['photo'] : array(), 'photo', true);
        $this->saveCatalogFiles($this->id, !empty($post['photos']) ? $post['photos'] : array(), 'photos');
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    private function saveCatalogLang($id_catalog, $lang_data, $id_content, $type='') 
    {
        if(!empty($lang_data)) {
            $module = $this->db->table('module')->select('id')->where('slug', 'Catalog')->get()->getRowArray();
            $this->tagsModel = new TagsModel();
            $linkClass = new Link();
            foreach($lang_data as $id_lang=>$lang) {
                if(!empty($lang['tags'])) {	
                    $lang['tags']= $this->tagsModel->AddTags($lang['tags'],$id_lang, $id_content);
                }
                else {$lang['tags']='';}
                $data = array(
                    'id_catalog' => $id_catalog,
                    'id_lang' => $id_lang,
                    'id_link' => $type != 'nolink' ? $linkClass->saveLink($lang['link'], $id_lang, $lang['id_link'], !empty($module) ? $module['id'] : 0) : 0,
                    'name' => $lang['name'],
                    'content' => $lang['content'],
                    'address' => $lang['address'],
                    'open_hours' => $lang['open_hours'],
                    'tags'=>$lang['tags'],
                );
                $lang = $this->db->table('catalog_lang')->select('id,id_link')->where('id_catalog', $id_catalog)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    if($type == 'nolink' && !empty($lang['id_link'])) {
                        $this->db->table('links')->where('id', $lang['id_link'])->delete();
                    }
                    $result = $this->db->table('catalog_lang')->set($data)->where('id_catalog', $id_catalog)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('catalog_lang')->insert($data);
                }
            }
        }
    }
    
    private function saveCatalogMetaLang($id_catalog, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_catalog' => $id_catalog,
                    'id_lang' => $id_lang,
                    'title' => $lang['title'],
                    'description' => $lang['description'],
                    'keywords' => $lang['keywords'],
                );
                $lang = $this->db->table('catalog_meta_lang')->select('id')->where('id_catalog', $id_catalog)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('catalog_meta_lang')->set($data)->where('id_catalog', $id_catalog)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('catalog_meta_lang')->insert($data);
                }
            }
        }
    }
    
    private function saveCatalogFiles($id_catalog, $files, $field='') 
    {
        $ids = array();
        if(!empty($files)) {
            foreach($files as $file) {
                $ids[] = $this->saveCatalogFile($id_catalog, $file, $field);
            }
        }
        $query = $this->db->table('catalog_files')->select('id,path')->where('id_catalog', $id_catalog)->where('field', $field);
        if(!empty($ids)) {
            $query->whereNotIn('id', $ids);
        }
        $files_list = $query->get()->getResultArray();
        if(!empty($files_list)) {
            foreach($files_list as $f) {
                $this->removeCatalogFile($f);
            }
        }
    }
    
    private function saveCatalogFile($id_catalog, $file, $field='', $remove=false) 
    {
        if(!empty($file)) {
            if(!empty($file['id']) && !empty($this->db->table('catalog_files')->select('id')->where('id', $file['id'])->get()->getRowArray())) {
                $data = array(
                    'order' => !empty($file['order']) ? $file['order'] : 0,
                    'publish' => !empty($file['publish']) ? $file['publish'] : 0,
                );
                $result = $this->db->table('catalog_files')->set($data)->where('id', $file['id'])->update();
                $id_file = $file['id'];
            } else {
                $file_obj = new \CodeIgniter\Files\File(WRITEPATH . 'uploads/' . $file['path']);
                if(!is_dir(WRITEPATH . 'uploads/catalog')) {
                    mkdir(WRITEPATH . 'uploads/catalog');
                }
                if(!is_dir(WRITEPATH . 'uploads/catalog/' . date('Ymd'))) {
                    mkdir(WRITEPATH . 'uploads/catalog/' . date('Ymd'));
                }
                $r = $file_obj->move(WRITEPATH . 'uploads/catalog/' . date('Ymd') , $file['basename']);
                $file_path = 'catalog/' . date('Ymd') . '/' . $r->getFilename();
                $file_info = pathinfo(WRITEPATH . 'uploads/' . $file_path);
                $data = array(
                    'id_catalog' => $id_catalog,
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
                $result = $this->db->table('catalog_files')->insert($data);
                $id_file = $this->db->insertID();
            }
            $this->saveCatalogFileLang($id_file, $file['lang']);
        }
        if($remove) {
            $query = $this->db->table('catalog_files')->select('id,path')->where('id_catalog', $id_catalog)->where('field', $field);
            if(!empty($id_file)) {
                $query->where('id !=', $id_file);
            }
            $files_list = $query->get()->getResultArray();
            if(!empty($files_list)) {
                foreach($files_list as $f) {
                    $this->removeCatalogFile($f);
                }
            }
        }
        return !empty($id_file) ? $id_file : 0;
    }
    
    private function saveCatalogFileLang($id_file, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                
                $slug = mb_url_title(str_replace(array('/', ','), '-', !empty($lang['caption']) ? $lang['caption'] : random_string('alnum', 16)), '-', true);
                $oryginal_slug = $slug;
                $count = 1;
                do {
                    $query = $this->db->table('catalog_files_lang')->where('slug', $slug)->where('id_lang', $id_lang);
                    if(!empty($id)) {
                        $query->where('id !=', $id);
                    }
                    $is = $query->select('id')->get()->getRowArray();
                    if(!empty($is)) {
                        $slug = $oryginal_slug . '-' . $count;
                    }
                    ++$count;
                } while(!empty($is) && $count<=1000);
                
                $data = array(
                    'id_file' => $id_file,
                    'id_lang' => $id_lang,
                    'caption' => $lang['caption'],
                    'author' => $lang['author'],
                    'slug' => $slug,
                );
                if(isset($lang['content'])) {
                    $data['content'] = $lang['content'];
                }
                $lang = $this->db->table('catalog_files_lang')->select('id')->where('id_file', $id_file)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('catalog_files_lang')->set($data)->where('id_file', $id_file)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('catalog_files_lang')->insert($data);
                }
            }
        }
    }
    
    private function removeCatalogFile($file) 
    {
        if(file_exists(WRITEPATH . 'uploads/' . $file['path'])) {
            @unlink(WRITEPATH . 'uploads/' . $file['path']);
        }
        $this->db->table('catalog_files_lang')->where('id_file', $file['id'])->delete();
        $this->db->table('catalog_files')->where('id', $file['id'])->delete();
    }
    
    public function deleteCatalog($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $langs = $this->db->table('catalog_lang')->select('id_link')->where('id_catalog', $id)->get()->getResultArray();
        if(!empty($langs)) {
            foreach($langs as $l) {
                $this->db->table('links')->where('id', $l['id_link'])->delete();
            }
        }
        $files_list = $this->db->table('catalog_files')->select('id,path')->where('id_catalog', $id)->get()->getResultArray();
        if(!empty($files_list)) {
            foreach($files_list as $f) {
                $this->removeCatalogFile($f);
            }
        }
        //$catalog = $this->select('order')->where('id', $id)->first();
        $this->db->table('catalog_lang')->where('id_catalog', $id)->delete();
        $this->db->table('catalog_meta_lang')->where('id_catalog', $id)->delete();
        $this->where('id', $id)->delete();
        //$this->set('order', '`order`-1', FALSE)->where('order >', $catalog['order'])->update();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
}