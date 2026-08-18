<?php

namespace Modules\Gallery\Models;  
use CodeIgniter\Model;
use App\Libraries\Link;

class GalleryModel extends Model{

    protected $table = 'gallery';
    
    protected $allowedFields = [
        'id_page_cont',
        'template',
        'order',
        'publish',
		'home',
        'edited_at',
        'created_at',
    ];
    
    public function getGalleryByContentId($id_content) 
    {
        $gallery = $this->where('id_page_cont', $id_content)->first();
        if(!empty($gallery)) {
            $gallery['lang'] = $this->getGalleryLang($gallery['id']);
            $gallery['photo'] = $this->getGalleryFile($gallery['id'], 'photo');
            $gallery['photos'] = $this->getGalleryFiles($gallery['id'], 'photos');
            $gallery['audio'] = $this->getGalleryFiles($gallery['id'], 'audio');
            $gallery['video'] = $this->getGalleryFiles($gallery['id'], 'video');
        }
        return $gallery;
    }
    
    public function getGalleryById($id, $id_lang) 
    {
        $gallery = $this->where('id', $id)->first();
        if(!empty($gallery)) {
            $gallery['lang'] = $this->getGalleryLang($id);
            if(!empty($gallery['lang']) && !empty($gallery['lang'][$id_lang]) && !empty($gallery['lang'][$id_lang]['name'])) {
                $gallery['name'] = $gallery['lang'][$id_lang]['name'];
            } else {
                $gallery['name'] = '';
            }
            $gallery['meta']['lang'] = $this->getGalleryMetaLang($id);
            $gallery['photo'] = $this->getGalleryFile($id, 'photo');
            $gallery['photos'] = $this->getGalleryFiles($id, 'photos');
            $gallery['audio'] = $this->getGalleryFiles($id, 'audio');
            $gallery['video'] = $this->getGalleryFiles($id, 'video');
        }
        return $gallery;
    }
    
    private function getGalleryLang($id_gallery) 
    {
        $linkClass = new Link();
        $langs = array();
        $data = $this->db->table('gallery_lang')->where('id_gallery', $id_gallery)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $d['link'] = $linkClass->getLink($d['id_link'], $d['id_lang']);
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
    
    public function getGalleryMetaLang($id_gallery) 
    {
        $langs = array();
        $data = $this->db->table('gallery_meta_lang')->where('id_gallery', $id_gallery)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
    
    private function getGalleryFile($id_gallery, $field='') 
    {
        $file = $this->db->table('gallery_files')->select('id,name,basename,path,order,type,publish,ext')->where('id_gallery', $id_gallery)->where('field', $field)->orderBy('order', 'ASC')->get()->getRowArray();
        if(!empty($file)) {
            $file['lang'] = $this->getGalleryFileLang($file['id']);
        }
        return $file;
    }
    
    private function getGalleryFiles($id_gallery, $field='') 
    {
        $files = $this->db->table('gallery_files')->select('id,name,basename,path,order,type,publish,ext')->where('id_gallery', $id_gallery)->where('field', $field)->orderBy('order', 'ASC')->get()->getResultArray();
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
        $data = $this->db->table('gallery_files_lang')->where('id_file', $id_file)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
    
    public function saveGalleryByPageContent($id_content, $post) 
    {
        $gallery = $this->select('id')->where('id_page_cont', $id_content)->first();
        $r = $this->saveGallery(!empty($gallery) ? $gallery['id'] : 0, $id_content, $post);
        if($r) {
            $this->db->table('page_content')->set('id_element', $this->id)->where('id', $id_content)->update();
        }
    }

    public function saveGallery($id, $id_content, $post) 
    {
        helper(['file']);
        if(empty($post)) return false;
        $data = array(
            'id_page_cont' => $id_content,
            'template' => $post['template'],
            'publish' => !empty($post['publish']) ? $post['publish'] : 0,
			'home' => !empty($post['home']) ? $post['home'] : 0,
        );
        $this->db->transStart();
        if($id) {
            $result = $this->set($data)->where('id', $id)->update();
            $this->id = $id;
        } else {
            $result = $this->set('order', '`order`+1', FALSE)->update();
            $result = $this->insert($data);
            $this->id = $this->getInsertID();
        }
        
        $this->saveGalleryLang($this->id, $post['lang']);
        if(!empty($post['meta'])) {
            $this->saveGalleryMetaLang($this->id, $post['meta']['lang']);
        }
        $this->saveGalleryFile($this->id, !empty($post['photo']) ? $post['photo'] : array(), 'photo', true);
        $this->saveGalleryFiles($this->id, !empty($post['photos']) ? $post['photos'] : array(), 'photos');
        $this->saveGalleryFiles($this->id, !empty($post['audio']) ? $post['audio'] : array(), 'audio');
        $this->saveGalleryFiles($this->id, !empty($post['video']) ? $post['video'] : array(), 'video');
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    private function saveGalleryLang($id_gallery, $lang_data) 
    {
        if(!empty($lang_data)) {
            $module = $this->db->table('module')->select('id')->where('slug', 'Gallery')->get()->getRowArray();
            foreach($lang_data as $id_lang=>$lang) {
                $linkClass = new Link();
                $id_link = 0;
                if(!empty($lang['link'])) {
                    $id_link = $linkClass->saveLink($lang['link'], $id_lang, $lang['id_link'], !empty($module) ? $module['id'] : 0);
                }
                $data = array(
                    'id_gallery' => $id_gallery,
                    'id_lang' => $id_lang,
                    'id_link' => $id_link,
                    'name' => $lang['name'],
                    'introduction' => $lang['introduction'],
                    'content' => $lang['content'],
                );
                $lang = $this->db->table('gallery_lang')->select('id')->where('id_gallery', $id_gallery)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('gallery_lang')->set($data)->where('id_gallery', $id_gallery)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('gallery_lang')->insert($data);
                }
            }
        }
    }
    
    private function saveGalleryMetaLang($id_gallery, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_gallery' => $id_gallery,
                    'id_lang' => $id_lang,
                    'title' => $lang['title'],
                    'description' => $lang['description'],
                    'keywords' => $lang['keywords'],
                );
                $lang = $this->db->table('gallery_meta_lang')->select('id')->where('id_gallery', $id_gallery)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('gallery_meta_lang')->set($data)->where('id_gallery', $id_gallery)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('gallery_meta_lang')->insert($data);
                }
            }
        }
    }
    
    private function saveGalleryFiles($id_gallery, $files, $field='') 
    {
        $ids = array();
        if(!empty($files)) {
            foreach($files as $file) {
                $ids[] = $this->saveGalleryFile($id_gallery, $file, $field);
            }
        }
        $query = $this->db->table('gallery_files')->select('id,path')->where('id_gallery', $id_gallery)->where('field', $field);
        if(!empty($ids)) {
            $query->whereNotIn('id', $ids);
        }
        $files_list = $query->get()->getResultArray();
        if(!empty($files_list)) {
            foreach($files_list as $f) {
                $this->removeGalleryFile($f);
            }
        }
    }
    
    private function saveGalleryFile($id_gallery, $file, $field='', $remove=false) 
    {
        if(!empty($file)) {
            if(!empty($file['id']) && !empty($this->db->table('gallery_files')->select('id')->where('id', $file['id'])->get()->getRowArray())) {
                $data = array(
                    'order' => !empty($file['order']) ? $file['order'] : 0,
                    'publish' => !empty($file['publish']) ? $file['publish'] : 0,
                );
                $result = $this->db->table('gallery_files')->set($data)->where('id', $file['id'])->update();
                $id_file = $file['id'];
            } else {
                $file_obj = new \CodeIgniter\Files\File(WRITEPATH . 'uploads/' . $file['path']);
                if(!is_dir(WRITEPATH . 'uploads/gallery')) {
                    mkdir(WRITEPATH . 'uploads/gallery');
                }
                if(!is_dir(WRITEPATH . 'uploads/gallery/' . date('Ymd'))) {
                    mkdir(WRITEPATH . 'uploads/gallery/' . date('Ymd'));
                }
                $r = $file_obj->move(WRITEPATH . 'uploads/gallery/' . date('Ymd') , $file['basename']);
                $file_path = 'gallery/' . date('Ymd') . '/' . $r->getFilename();
                $file_info = pathinfo(WRITEPATH . 'uploads/' . $file_path);
                $data = array(
                    'id_gallery' => $id_gallery,
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
                $result = $this->db->table('gallery_files')->insert($data);
                $id_file = $this->db->insertID();
            }
            $this->saveGalleryFileLang($id_file, $file['lang']);
        }
        if($remove) {
            $query = $this->db->table('gallery_files')->select('id,path')->where('id_gallery', $id_gallery)->where('field', $field);
            if(!empty($id_file)) {
                $query->where('id !=', $id_file);
            }
            $files_list = $query->get()->getResultArray();
            if(!empty($files_list)) {
                foreach($files_list as $f) {
                    $this->removeGalleryFile($f);
                }
            }
        }
        return !empty($id_file) ? $id_file : 0;
    }
    
    private function saveGalleryFileLang($id_file, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_file' => $id_file,
                    'id_lang' => $id_lang,
                    'caption' => $lang['caption'],
                    'author' => $lang['author'],
                );
                $lang = $this->db->table('gallery_files_lang')->select('id')->where('id_file', $id_file)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('gallery_files_lang')->set($data)->where('id_file', $id_file)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('gallery_files_lang')->insert($data);
                }
            }
        }
    }
    
    public function removeGalleryFile($file) 
    {
        if(file_exists(WRITEPATH . 'uploads/' . $file['path'])) {
            @unlink(WRITEPATH . 'uploads/' . $file['path']);
        }
        $this->db->table('gallery_files_lang')->where('id_file', $file['id'])->delete();
        $this->db->table('gallery_files')->where('id', $file['id'])->delete();
    }
    
    public function deleteGallery($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $langs = $this->db->table('gallery_lang')->select('id_link')->where('id_gallery', $id)->get()->getResultArray();
        if(!empty($langs)) {
            foreach($langs as $l) {
                $this->db->table('links')->where('id', $l['id_link'])->delete();
            }
        }
        $files_list = $this->db->table('gallery_files')->select('id,path')->where('id_gallery', $id)->get()->getResultArray();
        if(!empty($files_list)) {
            foreach($files_list as $f) {
                $this->removeGalleryFile($f);
            }
        }
        //$gallery = $this->select('order')->where('id', $id)->first();
        $this->db->table('gallery_lang')->where('id_gallery', $id)->delete();
        $this->db->table('gallery_meta_lang')->where('id_gallery', $id)->delete();
        $this->where('id', $id)->delete();
        //$this->set('order', '`order`-1', FALSE)->where('order >', $gallery['order'])->update();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    public function getGalleryList($id_lang) 
    {
        $galleries = $this->db->table('gallery g')
                ->join('gallery_lang gl', 'g.id = gl.id_gallery')
                ->select('g.id,g.publish,gl.name')
                ->where('gl.id_lang', $id_lang)
                ->where('g.publish', 1)
                ->where('gl.id_link !=', 0)
                ->orderBy('gl.name', 'ASC')
                ->get()
                ->getResultArray();
        return $galleries;
    }
    
    public function getGalleryForMenu($id, $id_lang) 
    {
        $galleries = $this->db->table('gallery g')
                ->join('gallery_lang gl', 'g.id = gl.id_gallery')
                ->select('g.id as id_target,g.publish,gl.name')
                ->where('gl.id_lang', $id_lang)
                ->where('g.id', $id)
                ->get()
                ->getRowArray();
        if(!empty($galleries)) {
            $galleries['lang'] = array();
            $lang_data = $this->db->table('gallery_lang gl')->join('links l', 'l.id = gl.id_link')->select('gl.id_lang,gl.name,l.link as url')->where('gl.id_gallery', $id)->orderBy('gl.id_lang', 'ASC')->get()->getResultArray();
            if(!empty($lang_data)) {
                foreach($lang_data as $l) {
                    $l['title'] = $l['name'];
                    if(!empty($l['url'])) $l['url'] = (!empty($languages) && !empty($languages[$l['id_lang']]) && $languages[$l['id_lang']]['slug'] ? '/' . $languages[$l['id_lang']]['slug'] : '') . '/' . $l['url'];
                    $galleries['lang'][$l['id_lang']] = $l;
                }
            }
        }
        return $galleries;
    }
    
    public function getElementPhotosForSitemap($id)
    {
        $photos = array();
        $gallery = $this->select('id')->where('id', $id)->where('publish', 1)->first();
        if(empty($gallery)) return array();
        $photos = $this->db->table('gallery_files gf')->join('gallery_files_lang gfl', 'gf.id=gfl.id_file')->join('language l', 'l.id=gfl.id_lang')->select('gf.name,gf.path,gfl.caption')->where('l.default', 1)->where('gf.id_gallery', $id)->whereIn('field', array('photo', 'photos'))->orderBy('gf.field', 'ASC')->get()->getResultArray();
        return $photos;
    }
}