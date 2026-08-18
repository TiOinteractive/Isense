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

    /**
     * Assety menedżera plików — wymagane przez pole „Grafika tła" w formularzu sekcji.
     */
    public function assets($action = '') {
        $assets = array(
            'js' => array(),
            'css' => array(),
            'css_footer' => array()
        );
        switch ($action) {
            case 'content':
            case 'edit':
            case 'add':
            case 'save':
                $assets['css_footer'][] = '/adm/third-party/jquery-file-upload-master/css/jquery.fileupload.css';
                $assets['css_footer'][] = '/adm/third-party/jquery-file-upload-master/css/jquery.fileupload-ui.css';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/tmpl.min.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/load-image.all.min.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/canvas-to-blob.min.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.blueimp-gallery.min.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.iframe-transport.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-process.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-image.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-audio.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-video.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-validate.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-ui.js';
                $assets['js'][] = '/adm/js/file-uploader.js';
                break;
            default :
                break;
        }
        return $assets;
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