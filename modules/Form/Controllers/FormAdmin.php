<?php

namespace Modules\Form\Controllers;
use App\Controllers\BaseController;
use Modules\Form\Models\FormModel;
use App\Libraries\Breadcrumb;

class FormAdmin extends BaseController
{
    public function __construct()
    {   
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->formModel = new FormModel();
    }
    
    public function pageContent($id_content, $slug='') 
    {
        helper('filesystem');
        $form = $this->formModel->getFormByContentId($id_content);
        $templates = get_templates_by_dir('modules/Form/Views/user');
        return array(
            'form_data' => $form,
            'templates' => $templates,
            'form_view' => 'Modules\Form\Views\admin\form'
        );
    }
    
    public function savePageContent($id_content, $post) 
    {
        $this->formModel->saveForm($id_content, $post);
    }
    
    public function ajax($action='', $id=0) 
    {
        $post = $this->request->getPost();
        if(!empty($action)) {
            switch($action) {
                case 'field-add': 
                    return $this->addField($post);
                    break;
            }
        }
    }
    
    private function addField($post=array()) 
    {
        $html = view('Modules\Form\Views\admin\add_field', array('no' => $post['no'], 'languages' => $this->languages));
        return $this->response->setJSON(array(
            'status' => true,
            'html' => base64_encode(urlencode($html))
        ));
    }
    
    public function assets($action='') {
        $assets = array(
            'js' => array('/adm/js/form.js')
        );
        return $assets;
    }
	
	public function deletePageModule($data) 
	{
		$form = $this->formModel->select('id')->where('id_page_cont', $data['id'])->first();
		if(!empty($form)) {
			$fields = $this->formModel->db->table('form_field')->select('id')->where('id_form', $form['id'])->get()->getResultArray();
			if(!empty($fields)) {
				foreach($fields as $f) {
					$this->formModel->db->table('form_field_lang')->where('id_field', $f['id'])->delete();
				}
			}
			$this->formModel->db->table('form_lang')->where('id_form', $form['id'])->delete();
			$this->formModel->db->table('form_field')->where('id_form', $form['id'])->delete();
			$this->formModel->where('id', $form['id'])->delete();
		}
	}
}