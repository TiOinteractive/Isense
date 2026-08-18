<?php

namespace App\Libraries;

use App\Models\PageModel;

class Breadcrumbs
{
    public $redirect_conflict;
    public $url_conflict;
    
    public function __construct() {
        $this->pageModel = new PageModel();
    }
    
    public function getPageBreadcrumbs($id_page, $id_parents, $home, $id_lang, $locale) 
    {
        if(empty($id_page) || $home) return array();
        $breadcrumbs = array();
        $ids = array();
        if(!empty($id_parents)) {
            $ids = array_merge($ids, array_reverse($id_parents));
        }
        $ids[] = $id_page;
        if(!empty($ids)) {
            $breadcrumbs = $this->pageModel->db->table('page p')->join('page_lang pl', 'p.id=pl.id_page')->join('links l', 'l.id=pl.id_link')->select('p.id,pl.name,l.link')->where('pl.id_lang', $id_lang)->where('p.publish', 1)->whereIn('p.id', $ids)->orderBy('FIELD(pl.id_page,' . implode(',', $ids) . ')')->get()->getResultArray();
            if(!empty($breadcrumbs)) {
                foreach($breadcrumbs as $k=>$bread) {
                    $breadcrumbs[$k]['link'] = ($locale ? '/' . $locale : '') . '/' . $bread['link'];
                }
            }
        }
        return $breadcrumbs;
    }
}