<?php

namespace App\Models;  
use CodeIgniter\Model;

class SidebarModel extends Model {

    protected $table = 'sidebar';
    
    protected $allowedFields = [
        'publish',
        'edited_at',
        'created_at'
    ];
    
    public function getSidebarById($id, $id_lang) 
    {
        $sidebar = $this->where('id', $id)->first();
        if(!empty($sidebar)) {
            $sidebar['lang'] = $this->getSidebarLang($id);
            if(!empty($sidebar['lang']) && !empty($sidebar['lang'][$id_lang]) && !empty($sidebar['lang'][$id_lang]['name'])) {
                $sidebar['name'] = $sidebar['lang'][$id_lang]['name'];
            } else {
                $sidebar['name'] = '';
            }
            $sidebar['content'] = $this->db->table('sidebar_content sc')->join('module_element me', 'me.id=sc.id_module_element')->join('module m', 'm.id=me.id_module')->select('sc.*')->where('sc.id_sidebar', $id)->where('m.publish', 1)->where('me.sidebar', 1)->orderBy('sc.order', 'ASC')->get()->getResultArray();
        }
        return $sidebar;
    }
    
    private function getSidebarLang($id_sidebar) 
    {
        $langs = array();
        $data = $this->db->table('sidebar_lang')->where('id_sidebar', $id_sidebar)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }

    public function saveSidebar($id, $post) 
    {
        if(empty($post)) return false;
        $data = array(
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
        $this->saveSidebarLang($this->id, $post['lang']);
        $this->saveSidebarContent($this->id, $post['content']);
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    private function saveSidebarLang($id_sidebar, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_sidebar' => $id_sidebar,
                    'id_lang' => $id_lang,
                    'name' => !empty($lang['name']) ? $lang['name'] : '',
                );
                $lang = $this->db->table('sidebar_lang')->select('id')->where('id_sidebar', $id_sidebar)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('sidebar_lang')->set($data)->where('id_sidebar', $id_sidebar)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('sidebar_lang')->insert($data);
                }
            }
        }
    }

    private function saveSidebarContent($id_sidebar, $content) {
        $ids = array();
        if (!empty($content)) {
            foreach ($content as $cont) {
                $data = array(
                    'id_sidebar' => $id_sidebar,
                    'id_module_element' => $cont['id_element'],
                    'order' => $cont['order'],
                    'publish' => !empty($cont['publish']) ? $cont['publish'] : 0,
                );
                if ($cont['id'] && !empty($this->db->table('sidebar_content')->select('id')->where('id', $cont['id'])->get()->getRowArray())) {
                    $result = $this->db->table('sidebar_content')->set($data)->where('id', $cont['id'])->update();
                    $id_cont = $cont['id'];
                } else {
                    $result = $this->db->table('sidebar_content')->insert($data);
                    $id_cont = $this->db->insertID();
                }
                $ids[] = $id_cont;
            }
        }
        $query = $this->db->table('sidebar_content')->select('id')->where('id_sidebar', $id_sidebar);
        if (!empty($ids)) {
            $query->whereNotIn('id', $ids);
        }
        $content_list = $query->get()->getResultArray();
        if (!empty($content_list)) {
            foreach ($content_list as $c) {
                $this->db->table('sidebar_content')->where('id', $c['id'])->delete();
            }
        }
    }
    
    public function getSidebarContentById($id) 
    {
        $content = $this->db->table('sidebar_content sc')->join('module_element me', 'me.id=sc.id_module_element', 'left')->select('sc.*,me.slug')->where('sc.id', $id)->get()->getRowArray();
        if(!empty($content)) {
            $content['config'] = unserialize($content['config']);
            $content['lang'] = $this->getSidebarContentLang($id);
        }
        return $content;
    }
    
    public function getSidebarContentLang($id) 
    {
        $langs = array();
        $data = $this->db->table('sidebar_content_lang')->where('id_sidebar_cont', $id)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
    
    public function getSidebarContentElements($id, $id_lang) 
    {
        $elements = array();
        $data = $this->db->table('sidebar_content sc')->join('sidebar_content_lang scl', 'sc.id=scl.id_sidebar_cont', 'left')->join('module_element me', 'me.id=sc.id_module_element')->join('module m', 'm.id=me.id_module')->join('module_element_lang mel', 'me.id=mel.id_module_el')->select('sc.id,sc.id_sidebar,sc.publish,mel.name,m.slug,scl.name as name2')->where('sc.id_sidebar', $id)->where('m.publish', 1)->where('mel.id_lang', $id_lang)->groupStart()->where('scl.id_lang', $id_lang)->orWhere('scl.id', null)->groupEnd()->orderBy('sc.order', 'ASC')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $elements[$d['id']] = $d;
            }
        }
        return $elements;
    }

    public function getSidebarInfo($id, $id_lang) {
        $page = $this->join('sidebar_lang sl', 'sidebar.id=sl.id_sidebar')->join('language l', 'l.id=sl.id_lang')->select('sidebar.id,sl.name')->where('sidebar.id', $id)->where('l.default', 1)->first();
        return $page;
    }

    public function deleteSidebar($id) {
        $this->db->transStart();
        $content = $this->db->table('sidebar_content')->select('id')->where('id_sidebar', $id)->get()->getResultArray();
        if (!empty($content)) {
            foreach ($content as $c) {
                $this->db->table('sidebar_content_lang')->where('id_sidebar_cont', $c['id'])->delete();
            }
        }
        $this->db->table('sidebar_content')->where('id_sidebar', $id)->delete();
        $this->db->table('sidebar_lang')->where('id_sidebar', $id)->delete();
        $this->db->table('sidebar')->where('id', $id)->delete();
        HistoryStat($id, '', 'sidebar', 'Sidebar', lang('Admin.sidebar.Removed'));
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    public function saveSidebarContentPost($id_content, $post) 
    {
         $data = array(
            'id_element' => !empty($post['id_element']) ? $post['id_element'] : '',
            'template' => !empty($post['template']) ? $post['template'] : '',
            'config' => !empty($post['config']) ? serialize($post['config']) : '',
        );
        $this->db->transStart();
        if(!empty($id_content) && !empty($this->db->table('sidebar_content')->select('id')->where('id', $id_content)->get()->getRowArray())) {
            $result = $this->db->table('sidebar_content')->set($data)->where('id', $id_content)->update();
            $this->saveSidebarContentLang($id_content, $post['lang']);
        }
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    private function saveSidebarContentLang($id_content, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_sidebar_cont' => $id_content,
                    'id_lang' => $id_lang,
                    'title' => $lang['title'],
                    'subtitle' => $lang['subtitle'],
                    'url' => $lang['url'],
                );
                $lang = $this->db->table('sidebar_content_lang')->select('id')->where('id_sidebar_cont', $id_content)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('sidebar_content_lang')->set($data)->where('id_sidebar_cont', $id_content)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('sidebar_content_lang')->insert($data);
                }
            }
        }
    }
    
    public function getSidebarContentForHomeById($id_sidebar, $id_lang) 
    {
        if(empty($id_sidebar)) return array();
        $content = array();
        $data = $this->db->table('sidebar_content sc')
                ->join('sidebar s', 's.id=sc.id_sidebar')
                ->join('sidebar_content_lang scl', 'sc.id=scl.id_sidebar_cont', 'left')
                ->select('sc.id,sc.id_element,sc.template,sc.id_module_element,sc.config,scl.title,scl.subtitle,scl.url')
                ->where('sc.id_sidebar', $id_sidebar)
                ->where('s.publish', 1)
                ->where('sc.publish', 1)
                ->where('scl.id_lang', $id_lang)
                ->orderBy('sc.order', 'ASC')
                ->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $d['config'] = unserialize($d['config']);
                $content['cont_' . $d['id']] = $d;
            }
        }
        return $content;
    }
    
    public function getSidebarsForSelect($id_lang) {
        $sidebars = array();
        $list = $this->join('sidebar_lang sl', 'sidebar.id=sl.id_sidebar')->select('sidebar.id,sl.name')->where('sidebar.publish', 1)->where('sl.id_lang', $id_lang)->find();
        if(!empty($list)) {
            foreach($list as $l) {
                $l['link'] = '/' . env('ADMIN_PANEL_SLUG') . '/sidebar/content/' . $l['id'];
                $sidebars[$l['id']] = $l;
            }
        }
        return $sidebars;
    }
}