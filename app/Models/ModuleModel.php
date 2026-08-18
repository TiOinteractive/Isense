<?php

namespace App\Models;  
use CodeIgniter\Model;

class ModuleModel extends Model {

    protected $table = 'module';
    
    protected $allowedFields = [
        'name',
        'slug',
        'main',
        'publish',
        'edited_at',
        'created_at'
    ];
    
    public function getModuleElements($id_lang, $sidebar=false) 
    {
        $query = $this->db->table('module_element me')->join('module m', 'm.id=me.id_module')->join('module_element_lang mel', 'me.id=mel.id_module_el')->join('module_lang ml', 'm.id=ml.id_module')->select('me.id,me.id_module,mel.name,ml.name as module_name')->where('m.publish', 1)->where('me.publish', 1)->where('mel.id_lang', $id_lang)->where('ml.id_lang', $id_lang);
        if($sidebar) {
            $query->where('me.sidebar', 1);
        }
        $elements = $query->orderBy('ml.name,me.id_module,mel.name')->get()->getResultArray();
        return $elements;
    }
    
    public function getModulesForMainMenu($id_lang) 
    {
        $modules = $this->db->table('module m')->join('module_lang ml', 'm.id=ml.id_module')->select('m.id,m.slug,m.main,ml.name')->where('m.publish', 1)->where('m.publish', 1)->where('m.separate', 1)->where('ml.id_lang', $id_lang)->get()->getResultArray();
        return $modules;
    }
	
    public function getSubModulesStructure($page, $slug, $id_lang, $id=0, $id2=0) {
        $elements = array();
        if (empty($slug)) {
            $slug = '';
        }
        $slug = str_replace('/' . env('ADMIN_PANEL_SLUG') . '/', '', $slug);
        if (!empty($slug) and $slug == "settings") {
            $elements = $this->db->table('module_subelement me')->join('module m', 'me.id_module=m.id', 'left')->join('module_subelement_lang mel', 'me.id=mel.id_submodule', 'left')->select('me.id,me.id_module,mel.name,me.slug,m.slug as slug_module')->where('me.publish', 1)->where('me.config_show', 1)->where('mel.id_lang', $id_lang)->orderBy('me.id_module,me.order,mel.name')->get()->getResultArray();
        } elseif($page == 'page'){
            $query = $this->db->table('page_content pc')->join('module_element me', 'me.id=pc.id_module_element')->select('pc.id,pc.id_module_element,me.id_module');
            if(!empty($id2)) {
                $query->where('pc.id', $id2);
            } else {
                $query->where('pc.id_page', $id)->orderBy('pc.order', 'ASC');
            }
            $content = $query->get()->getRowArray();
            if(!empty($content)) {
               $elements = $this->db->table('module_subelement me')->join('module m', 'me.id_module=m.id', 'left')->join('module_subelement_lang mel', 'me.id=mel.id_submodule', 'left')->select('me.id,me.id_module,mel.name,me.slug,m.slug as slug_module')->where('me.publish', 1)->groupStart()->where('me.show_module', $content['id_module'])->orWhere('me.id_module', $content['id_module'])->groupEnd()->where('mel.id_lang', $id_lang)->orderBy('me.id_module,me.order,mel.name')->get()->getResultArray();
            }
        } else {
            $id_module = $this->db->table('module')->where('publish', 1)->select('id')->like('slug', $page)->get()->getRowArray();
            if (!empty($id_module['id'])) {
                $elements = $this->db->table('module_subelement me')->join('module m', 'me.id_module=m.id', 'left')->join('module_subelement_lang mel', 'me.id=mel.id_submodule', 'left')->select('me.id,me.id_module,mel.name,me.slug,m.slug as slug_module')->where('me.publish', 1)->groupStart()->where('me.show_module', $id_module['id'])->orWhere('me.id_module', $id_module['id'])->groupEnd()->where('mel.id_lang', $id_lang)->orderBy('me.id_module,me.order,mel.name')->get()->getResultArray();
            }
        }
        return $elements;
    }

}