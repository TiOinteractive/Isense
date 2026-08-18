<?php

namespace Modules\Download\Controllers;

use App\Controllers\BaseController;
use Modules\Download\Models\DownloadModel;
use App\Libraries\Breadcrumb;

class DownloadAdmin extends BaseController {

    public function __construct() {
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->downloadModel = new DownloadModel();
    }

    public function index($action = '', $id_content = 0, $id = 0) {
        $page = $this->downloadModel->db->table('page_content')->select('id_page')->where('id', $id_content)->get()->getRowArray();
        $page_info = $this->downloadModel->db->table('page p')->join('page_lang pl', 'p.id=pl.id_page')->join('language l', 'l.id=pl.id_lang')->select('p.id,pl.name')->where('p.id', $page['id_page'])->where('l.default', 1)->get()->getRowArray();
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
        $this->breadcrumb->add(lang('Admin.page.PagesList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/page');
        $this->breadcrumb->add(lang('Admin.page.PageContent') . ': ' . $page_info['name'], ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/page/content/' . $page['id_page'] . '/' . $id_content);
        $session = session();
        $category = array();
        switch ($action) {
            case 'edit-cat':
                $category = $this->downloadModel->getCategoryById($id, $this->id_lang);
            case 'add-cat':
            case 'save-cat':
                if ($id) {
                    $this->breadcrumb->add(lang('Download.CategoryEdit') . (!empty($category['name']) ? ': ' . $category['name'] : ''), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/download/edit-cat/' . $id_content . '/' . $id);
                } else {
                    $this->breadcrumb->add(lang('Download.CategoryAdd'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/download/add-cat/' . $id_content);
                }
                $breadcrumb = $this->breadcrumb->render();
                $post = $this->request->getPost();
				
				
	
				
                if (!empty($post)) {
                    $result = false;
                    $errors = array();
                    $validation = \Config\Services::validation();
                    foreach ($post['lang'] as $id_lang => $lang) {
                        $validation->reset();
                        $lang_name = (!empty($this->languages[$id_lang]) ? '<b>[' . $this->languages[$id_lang]['short_name'] . ']</b> ' : '');
                        $validation->setRules([
                            'name' => [
                                'rules' => 'required',
                                'errors' => [
                                    'required' => $lang_name . lang('Download.NameError')
                                ],
                            ],
                        ]);
                        if (!$validation->run($lang)) {
                            $errors[] = array_merge($validation->getErrors());
                        }
                    }
                    if (empty($errors)) {
                        $result = $this->downloadModel->saveCategory($id, $id_content, $post);
                    }
                    if ($result) {
                        $this->session->setFlashdata('download', array(
                            'status' => true,
                            'msg' => ($id ? lang('Download.EditSuccess') : lang('Download.AddSuccess')) . '!'
                        ));
						HistoryStat($id,$id_content,'download_cat','Download',$id ? lang('Download.EditSuccess') : lang('Download.AddSuccess'));
                        return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/download/edit-cat/' . $id_content . '/' . $this->downloadModel->id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Download.EditError') : lang('Download.AddError')) . '!',
                            'list' => $errors
                        );
                    }
                    $category = $post;
                    $category['id'] = $id;
                } else {
                    $flashdata = $this->session->getFlashdata('download');
                }
                echo view('Modules\Download\Views\admin\add_cat_form', array('action' => $action, 'id_content' => $id_content, 'category' => $category, 'page' => $page, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            default :
                $languageModel = new LanguageModel();
                $this->languages = $languageModel->select('id,name,short_name,slug,default')->where('publish', 1)->orderBy('default', 'DESC')->findAll();
                if ($this->request->isAJAX()) {
                    return $this->ajax($action, $module, $id);
                }
                break;
        }
    }
    
    public function assets($action='') {
        $assets = array(
            'js' => array(),
            'css' => array(),
            'css_footer' => array()
        );
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
        return $assets;
    }

    public function ajax($action = '', $id = 0) {
        if (!empty($action)) {
            switch ($action) {
                case 'open-menager': return $this->openModal();
                    break;
                case 'add-file': return $this->addFile();
                    break;
                case 'order':
                    return $this->orderCat($id);
                    break;
                case 'publish-cat':
                    return $this->publishCat($id);
                    break;
                case 'delete-cat':
                    return $this->deleteCat($id);
                    break;
            }
        }
    }

    private function openModal() {
        $post = $this->request->getPost();
        $files = $this->downloadModel->db->table('download_files')->orderBy('created_at', 'DESC')->get()->getResultArray();
        $html = view('Modules\Download\Views\admin\filemenager\modal', array('files' => $files, 'module' => 'download', 'locale' => $this->locale));
        return $this->response->setJSON(array(
                    'status' => true,
                    'html' => base64_encode(urlencode($html))
        ));
    }

    private function addFile() {
        $post = $this->request->getPost();
        $files = $this->downloadModel->db->table('download_files')->whereIn('id', $post['files'])->orderBy('created_at', 'DESC')->get()->getResultArray();
        $html = view('Modules\Download\Views\admin\filemenager\files_list', array('files' => $files, 'name' => $post['name'], 'key_name' => $post['key_name']));
        return $this->response->setJSON(array(
                    'status' => true,
                    'html' => base64_encode(urlencode($html))
        ));
    }

    public function pageContent($id_content, $slug = '') {
        helper('filesystem');
        switch ($slug) {
            case 'single':
                $download = $this->downloadModel->getDownloadByContentId($id_content);
                $templates = get_templates_by_dir('modules/Download/Views/user/single');
                return array(
                    'form_data' => $download,
                    'templates' => $templates,
                    'form_view' => 'Modules\Download\Views\admin\add_single'
                );
                break;
            case 'list':
                $download_cat = $this->downloadModel->getCategories($id_content, $this->locale);
                $templates = get_templates_by_dir('modules/Download/Views/user/list');
                return array(
                    'form_data' => $download_cat,
                    'pc_templates' => $templates,
                    'list_view' => 'Modules\Download\Views\admin\add_cat'
                );
                break;

                break;
        }
    }

    public function savePageContent($id_content, $post) {
        $this->downloadModel->saveDownloadByPageContent($id_content, $post);
    }

    private function publishCat($id) {
        $cat = $this->downloadModel->db->table('download_cat')->select('id,publish')->where('id', $id)->get()->getRowArray();
        if (!empty($cat)) {
            $r = $this->downloadModel->db->table('download_cat')->where('id', $id)->set('publish', $cat['publish'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $cat['publish'] ? 0 : 1,
                'msg' => $cat['publish'] ? lang('Download.Republished') : lang('Download.Published')
            );
			HistoryStat($id,'','download_cat','Download',$cat['publish'] ? lang('Download.Republished') : lang('Download.Published'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $cat['publish'],
                'msg' => lang('Download.Error')
            );
        }
        return $this->response->setJSON($response);
    }

    private function orderCat($id) {
        $post = $this->request->getPost();
        $this->downloadModel->transStart();
        if (isset($post['old_pos']) && isset($post['new_pos']) && $post['old_pos'] != $post['new_pos']) {
            $ban = $this->downloadModel->db->table('download_cat')->select('id,id_page_cont')->where('order', $post['old_pos'])->get()->getRowArray();
            if ($post['new_pos'] > $post['old_pos']) {
                $this->downloadModel->db->table('download_cat')->set('order', '`order`-1', FALSE)->where('id_page_cont', $ban['id_page_cont'])->where('order<=', $post['new_pos'])->where('order>', $post['old_pos'])->update();
            } elseif ($post['new_pos'] < $post['old_pos']) {
                $r = $this->downloadModel->db->table('download_cat')->set('order', '`order`+1', FALSE)->where('id_page_cont', $ban['id_page_cont'])->where('order>=', $post['new_pos'])->where('order<', $post['old_pos'])->update();
            }
            $this->downloadModel->db->table('download_cat')->where('id', $ban['id'])->set('order', $post['new_pos'])->update();
        }
        $this->downloadModel->transComplete();
        $r = $this->downloadModel->transStatus();
        $response = array(
            'status' => true,
            'result' => $r,
            'msg' => $r ? lang('Download.OrderChanged') : lang('Download.Error'),
            'new_pos' => $post['new_pos'],
            'old_pos' => $post['old_pos'],
        );
		HistoryStat($id,'','download_cat','Download',$r ? lang('Download.OrderChanged') : lang('Download.Error'));
        return $this->response->setJSON($response);
    }

    public function deleteCat($id) {
        $downloadModel = new downloadModel();
        $result = $downloadModel->deleteCat($id);
        return $this->response->setJSON(array(
                    'status' => true,
                    'result' => $result,
                    'id' => $id,
                    'msg' => $result ? lang('Download.Removed') : lang('Download.Error')
        ));
		HistoryStat($id,'','download_cat','Download',$result ? lang('Download.Removed') : lang('Download.Error'));
    }
	
    public function preDeletePageModule($data)
    {
        if(!empty($data['slug'])) {
            switch($data['slug']) {
                case 'list':
                    $count = $this->downloadModel->db->table('download_cat')->where('id_page_cont', $data['id'])->countAllResults();
                    return $count ? false : true;
                    break;
                default: return true;
                    break;
            }
        }
        return true;
    }
	
	public function deletePageModule($data) 
	{
		$download = $this->downloadModel->select('id')->where('id_page_cont', $data['id'])->first();
		if(!empty($download)) {
			$this->downloadModel->where('id', $download['id'])->delete();
		}
	}
}

?>