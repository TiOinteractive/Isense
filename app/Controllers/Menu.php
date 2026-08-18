<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Controllers\BaseController;
use App\Models\MenuModel;
use App\Libraries\Breadcrumb;

class Menu extends Controller
{
    

    public function __construct() {
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->menuModel = new MenuModel();
    }
    
    public function pageContent($id_content, $slug = '') {
        helper('filesystem');
        $menus = array();
        $list = $this->menuModel->join('menu_lang ml', 'menu.id=ml.id_menu')->select('menu.id,ml.name')->where('ml.id_lang', $this->id_lang)->orderBy('ml.name', 'ASC')->findAll();
        if(!empty($list)) {
            foreach($list as $l) {
                $l['link'] = ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/menu/edit/' . $l['id'];
                $menus[$l['id']] = $l;
            }
        }
		
        $templates = get_templates_by_dir('app/Views/user/menu');
        return array(
            'elements' => $menus,
            'pc_templates' => $templates
        );
    }

}