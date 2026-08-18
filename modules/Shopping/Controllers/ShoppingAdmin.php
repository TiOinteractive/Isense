<?php

namespace Modules\Shopping\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Breadcrumb;

class ShoppingAdmin extends BaseController {

    public function __construct() {
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
        $this->db_shopping = \Config\Database::connect('shopping');
    }

    public function index($action = '', $id_content = 0, $id = 0) {
        helper('filesystem');
    }

    public function pageContent($id_content, $slug = '') {
        helper('filesystem');
        switch ($slug) {
            case 'products_list':
                $templates = get_templates_by_dir('modules/Shopping/Views/user/products_list');
                
                
                
                return array(
                    'pc_templates' => $templates,
                    'form_view' => 'Modules\Shopping\Views\admin\products_list_config'
                );
                break;
        }
    }

}
