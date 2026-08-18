<?php

namespace Modules\Wyswig\Controllers;
use App\Controllers\BaseController;
use Modules\Wyswig\Models\WyswigModel;
use App\Libraries\Breadcrumb;


class WyswigAdmin extends BaseController
{
    public function __construct()
    {   
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->wyswigModel = new WyswigModel();
    }
    
    
    public function pageContent($id_content, $slug='') 
    {
        helper('filesystem');
        $wyswig = $this->wyswigModel->getWyswigByContentId($id_content);
        $templates = get_templates_by_dir('modules/Wyswig/Views/user');
        return array(
            'form_data' => $wyswig,
            'pc_templates' => $templates,
            'form_view' => 'Modules\Wyswig\Views\admin\wyswig'
        );
    }
    
    public function savePageContent($id_content, $post) 
    {
        $this->wyswigModel->saveWyswig($id_content, $post);
    }
	
	public function deletePageModule($data) 
	{
		$wyswig = $this->wyswigModel->select('id')->where('id_page_cont', $data['id'])->first();
		if(!empty($wyswig)) {
			$this->wyswigModel->db->table('wyswig_lang')->where('id_wyswig', $wyswig['id'])->delete();
			$this->wyswigModel->where('id', $wyswig['id'])->delete();
		}
	}
    
}