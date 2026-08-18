<?php

namespace Modules\Catalog\Controllers;
use App\Controllers\BaseController;
use Modules\Catalog\Models\CatalogModel;
use App\Libraries\Breadcrumb;


class CatalogAdmin extends BaseController
{
    public function __construct()
    {   
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->catalogModel = new CatalogModel();
    }
    
    public function index($action='', $id_content=0, $id=0) 
    {
        helper('filesystem');
        $catalog = array();
        $page = $this->catalogModel->db->table('page_content')->select('id_page')->where('id', $id_content)->get()->getRowArray();
        $page_info = $this->catalogModel->db->table('page p')->join('page_lang pl', 'p.id=pl.id_page')->join('language l', 'l.id=pl.id_lang')->select('p.id,pl.name,p.re_id')->where('p.id', $page['id_page'])->where('l.default', 1)->get()->getRowArray();
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
        $this->breadcrumb->add(lang('Admin.page.PagesList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/page');
        $this->breadcrumb->add(lang('Admin.page.PageContent') . ': ' . $page_info['name'], ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/page/content/' . $page['id_page'] . '/' . $id_content);  
	
        switch ($action) {
            case 'edit': 
                $catalog = $this->catalogModel->getCatalogById($id, $this->id_lang);
            case 'add':
            case 'save':
                $post = $this->request->getPost();
                if(!empty($post)) {
                    $result = false;
                    $errors = array();
                    $validation =  \Config\Services::validation();
                        $validation->setRules([
                            'template' => [
                                'rules' => 'required',
                                'errors' => [
                                    'required' => lang('Catalog.TemplateError')
                                ],
                            ],
                        ]);
                    if (!$validation->run($post)) {
                        $errors[] = array_merge($validation->getErrors());
                    }
                    foreach ($post['lang'] as $id_lang=>$lang) {
                        $validation->reset();
                        $lang_name = (!empty($this->languages[$id_lang]) ? '<b>[' . $this->languages[$id_lang]['short_name'] . ']</b> ' : '');
                        $rules = [
                            'name' => [
                                'rules' => 'required',
                                'errors' => [
                                    'required' => $lang_name . lang('Catalog.NameError')
                                ],
                            ],
                        ];
                        if(!empty($post['type']) && $post['type'] != 'nolink') {
                            $rules['link'] = [
                                'rules' => 'required',
                                'errors' => [
                                    'required' => $lang_name . lang('Catalog.DirectLinkError')
                                ],
                            ];
                        }
                        $validation->setRules($rules);
                        if (!$validation->run($lang)) {
                            $errors[] = array_merge($validation->getErrors());
                        }
                    }
                    if(empty($errors)) {
                        $result = $this->catalogModel->saveCatalog($id, $id_content, $post);
                    }
                    if($result) {
                        $this->session->setFlashdata('catalog', array(
                            'status' => true,
                            'msg' => ($id ? lang('Catalog.EditSuccess') : lang('Catalog.AddSuccess')) . '!'
                        ));
                        HistoryStat($id, $id_content,'catalog','Catalog',$id ? lang('Catalog.EditSuccess') : lang('Catalog.AddSuccess'));
                        return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/catalog/edit/' . $id_content . '/' . $this->catalogModel->id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Catalog.EditError') : lang('Catalog.AddError')) . '!',
                            'list' => $errors
                        );
                    }
                    $catalog = $post;
                    $catalog['id'] = $id;
                } else {
                    $flashdata = $this->session->getFlashdata('catalog');
                }
                $templates = get_templates_by_dir('modules/Catalog/Views/user/single');
                if($id) {
                    $this->breadcrumb->add(lang('Catalog.CatalogEdit') . (!empty($catalog['name']) ? ': ' . $catalog['name'] : ''), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/catalog/edit/' . $id_content . '/' . $id);
                } else {
                    $this->breadcrumb->add(lang('Catalog.CatalogAdd'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/catalog/add/' . $id_content);
                }
                $breadcrumb = $this->breadcrumb->render();
                echo view('Modules\Catalog\Views\admin\add', array('action' => $action, 'id_content' => $id_content, 'catalog' => $catalog, 'page' => $page, 'templates' => $templates, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            default :
                
                break;
        }
    }
    
    public function assets($action='') {
        $assets = array(
            'js' => array(),
            'css' => array(),
            'css_footer' => array()
        );
        switch ($action) {
            case 'edit':
            case 'add':
            case 'save':
                $map_key = $this->catalogModel->db->table('settings')->select('value')->where('name', 'widget_gmjs')->get()->getRowArray();
                $assets['js'][] = 'https://maps.google.com/maps/api/js?key=' . (!empty($map_key) && !empty($map_key['value']) ? $map_key['value'] : '');
                $assets['css_footer'][] = '/adm/third-party/jquery-file-upload-master/css/jquery.fileupload.css';
                $assets['css_footer'][] = '/adm/third-party/jquery-file-upload-master/css/jquery.fileupload-ui.css';
                $assets['css_footer'][] = '/adm/third-party/tags/jquery.tagsinput.css';
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
                $assets['js'][] = '/adm/third-party/tags/jquery.tagsinput.js';
                $assets['js'][] = '/adm/js/file-uploader.js';
                $assets['js'][] = '/adm/js/catalog.js';
                break;
            default :
                break;
        }
        return $assets;
    }
    
    public function pageContent($id_content, $slug='') 
    {
        helper('filesystem');
        switch($slug) {
			case 'selected_category':
			  $templates = get_templates_by_dir('modules/Catalog/Views/user/categories');
                $lists = $this->catalogModel->db->table('page_content pc')
                        ->join('page_content_lang pcl', 'pc.id=pcl.id_page_cont', 'left')
                        ->join('page p', 'p.id=pc.id_page')
                        ->join('page_lang pl', 'pl.id_page=p.id')
                        ->select('pl.id_page,pl.name,pc.id as id_content,p.re_id,pcl.name as content_name,pcl.title,pc.order')
                        ->groupStart()
                            ->where('pcl.id_lang', $this->id_lang)
                            ->orWhere('pcl.id', null)
                        ->groupEnd()
                        ->where('pl.id_lang', $this->id_lang)
                        ->where('pc.id_module_element', 23)
						->orderBy('pl.name', 'ASC')
                        ->get()
                        ->getResultArray();
                return array(
                    'lists' => $lists,
                    'pc_templates' => $templates,
                    'form_view' => 'Modules\Catalog\Views\admin\selected_cat_config'
                );
                break;
			break;
            default:
                $get = $this->request->getGet();
                $query = $this->catalogModel
                        ->join('catalog_lang cl', 'catalog.id=cl.id_catalog')
                        ->select('catalog.id,catalog.publish,catalog.order,catalog.created_at,cl.name')
                        ->where('catalog.id_page_cont', $id_content)
                        ->where('cl.id_lang', $this->id_lang);
                if(!empty($get)) {
                    foreach($get as $name=>$value) {
                        switch($name) {
                            case 'name': 
                                if(!empty($value)) {
                                    $query->like('cl.name', $value);
                                }
                                break;
                            case 'publish': 
                                if(in_array($value, array(0,1))) {
                                    $query->where('catalog.publish', $value);
                                }
                                break;
                        }
                    }
                }

                if(empty($get['order'])) {
                    $get['order'] = 'order,asc';
                }
                if(!empty($get['order'])) {
                    $tmp = explode(',', $get['order']);
                    $get['order_array'][$tmp[0]] = $tmp[1] == 'asc' ? 'asc' : 'desc';
                    switch ($tmp[0]) {
                        case 'name': $query->orderBy('cl.name', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
                        case 'publish': $query->orderBy('catalog.publish', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
                        case 'order': $query->orderBy('catalog.order', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
                    }
                }
                $catalog_list = $query->paginate(!empty($get['on_page']) ? $get['on_page'] : 20);
                if(!empty($catalog_list)) {
                    foreach($catalog_list as $k=>$c) {
                        $count = $this->catalogModel->db->table('catalog_lang cl')->where('cl.id_catalog', $c['id'])->selectSum('cl.views')->get()->getRowArray();
                        $catalog_list[$k]['views'] = $count['views'];
                    }
                }
                $templates = get_templates_by_dir('modules/Catalog/Views/user/list');
                $order_list = array(
                    array('field' => 'order;asc', 'name' => lang('Catalog.sort.OrderAsc')),
                    array('field' => 'order;desc', 'name' => lang('Catalog.sort.OrderDesc')),
                    array('field' => 'title;asc', 'name' => lang('Catalog.sort.NameAsc')),
                    array('field' => 'title;desc', 'name' => lang('Catalog.sort.NameDesc')),
                );
                $on_page_list = array(
                    20 => 20,
                    40 => 40,
                    80 => 80,
                );
                return array(
                    'catalog_list' => $catalog_list,
                    'filters' => $get,
                    'order_list' => '',
                    'pager' => $this->catalogModel->pager,
                    'on_page_list' => $on_page_list,
                    'pc_templates' => $templates,
                    'list_view' => 'Modules\Catalog\Views\admin\list',
                    'form_view' => 'Modules\Catalog\Views\admin\list_config'
                );
                break;
        }
    }
    
    public function ajax($action='', $id=0, $id2=0) 
    {
        $post = $this->request->getPost();
        if(!empty($action)) {
            switch($action) {
                case 'publish': 
                    return $this->publishCatalog($id);
                    break;
                case 'delete': 
                    return $this->deleteCatalog($id);
                    break;
            }
        }
    }
    
    private function publishCatalog($id) 
    {
        $catalog = $this->catalogModel->select('id,publish')->where('id', $id)->first();
        if(!empty($catalog)) {
            $r = $this->catalogModel->where('id', $id)->set('publish', $catalog['publish'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $catalog['publish'] ? 0 : 1,
                'msg' => $catalog['publish'] ? lang('Catalog.Republished') : lang('Catalog.Published')
            );
            HistoryStat($id,'','catalog','Catalog',$catalog['publish'] ? lang('Catalog.Republished') : lang('Catalog.Published'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $catalog['publish'],
                'msg' => lang('Catalog.Error')
            );
        }
        return $this->response->setJSON($response);
    }

    private function deleteCatalog($id) {
        $result = $this->catalogModel->deleteCatalog($id);
        return $this->response->setJSON(array(
            'status' => true,
            'result' => $result,
            'id' => $id,
            'msg' => $result ? lang('Catalog.Removed') : lang('Catalog.Error')
        ));
        HistoryStat($id, '', 'catalog', 'Catalog', $result ? lang('Catalog.Removed') : lang('Catalog.Error'));
    }
}