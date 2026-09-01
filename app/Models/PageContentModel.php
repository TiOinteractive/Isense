<?php

namespace App\Models;  
use CodeIgniter\Model;

class PageContentModel extends Model {

    protected $table = 'page_content';
    
    protected $allowedFields = [
        'id_page',
        'id_module_element',
        'id_element',
        'id_sidebar',
        'template',
        'config',
        'order',
        'publish',
        'edited_at',
        'created_at'
    ];
    
    public function getPageContentById($id) 
    {
        $content = $this->join('module_element me', 'me.id=page_content.id_module_element', 'left')->select('page_content.*,me.slug')->where('page_content.id', $id)->first();
        if(!empty($content)) {
            $content['config'] = unserialize($content['config']);
            $content['lang'] = $this->getPageContentLang($id);
        }
        return $content;
    }
    
    public function getPageContentLang($id) 
    {
        $langs = array();
        $data = $this->db->table('page_content_lang')->where('id_page_cont', $id)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
    
    public function getPageContentElements($id, $id_lang) 
    {
        $elements = array();
        $data = $this->db->table('page_content pc')->join('page_content_lang pcl', 'pc.id=pcl.id_page_cont', 'left')->join('module_element me', 'me.id=pc.id_module_element')->join('module m', 'm.id=me.id_module')->join('module_element_lang mel', 'me.id=mel.id_module_el')->select('pc.id,pc.id_page,pc.publish,mel.name,m.slug,pcl.name as name2')->where('pc.id_page', $id)->where('m.publish', 1)->where('mel.id_lang', $id_lang)->groupStart()->where('pcl.id_lang', $id_lang)->orWhere('pcl.id', null)->groupEnd()->orderBy('pc.order', 'ASC')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $elements[$d['id']] = $d;
            }
        }
        return $elements;
    }
    
    public function savePageContent($id_content, $post) 
    {
         $data = array(
            'id_element' => !empty($post['id_element']) ? $post['id_element'] : '',
            'id_sidebar' => !empty($post['id_sidebar']) ? $post['id_sidebar'] : '',
            'template' => !empty($post['template']) ? $post['template'] : '',
            'config' => !empty($post['config']) ? serialize($post['config']) : '',
        );
        $this->db->transStart();
        if(!empty($id_content) && !empty($this->select('id')->where('id', $id_content)->find())) {
            $result = $this->set($data)->where('id', $id_content)->update();
            $this->savePageContentLang($id_content, $post['lang'] ?? array());
        }
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    private function savePageContentLang($id_content, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_page_cont' => $id_content,
                    'id_lang' => $id_lang,
                    'title' => $lang['title'],
                    'subtitle' => $lang['subtitle'],
                    'url' => $lang['url'],
                );
                $lang = $this->db->table('page_content_lang')->select('id')->where('id_page_cont', $id_content)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('page_content_lang')->set($data)->where('id_page_cont', $id_content)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('page_content_lang')->insert($data);
                }
            }
        }
    }
    
    public function getSettings($id_content) 
    {
        $content = $this->select('page_content.settings')->where('page_content.id', $id_content)->first();
        if(!empty($content) && !empty($content['settings'])) {
            return unserialize($content['settings']);
        }
        return null;
    }
    
    public function saveSettings($id_content, $post) 
    {
        $this->db->transStart();
        if(!empty($id_content) && !empty($this->select('id')->where('id', $id_content)->find())) {
            $result = $this->set('settings', !empty($post['settings']) ? serialize($post['settings']) : null)->where('id', $id_content)->update();
        }
        $this->db->transComplete();
        return $this->db->transStatus();
    }
}