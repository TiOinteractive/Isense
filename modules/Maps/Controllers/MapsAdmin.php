<?php

namespace Modules\Maps\Controllers;

use App\Controllers\BaseController;
use Modules\Maps\Models\MapsModel;
use App\Libraries\Breadcrumb;

class MapsAdmin extends BaseController {

    public function __construct() {
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->mapsModel = new MapsModel();
    }

    public function index($action = '', $id = 0) {
        $map = array();
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
        $this->breadcrumb->add(lang('Maps.MapsList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/maps');
        switch ($action) {
            case 'edit':
                $map = $this->mapsModel->getMapById($id, $this->id_lang);
            case 'add':
            case 'save':
                $post = $this->request->getPost();
                if (!empty($post)) {
                    $result = false;
                    $errors = array();
                    $validation = \Config\Services::validation();
                    if (!empty($post['lang'])) {
                        foreach ($post['lang'] as $id_lang => $lang) {
                            $validation->reset();
                            $lang_name = (!empty($this->languages[$id_lang]) ? '<b>[' . $this->languages[$id_lang]['short_name'] . ']</b> ' : '');
                            $validation->setRules([
                                'name' => [
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => $lang_name . lang('Maps.NameError')
                                    ],
                                ],
                            ]);
                            if (!$validation->run($lang)) {
                                $errors[] = array_merge($validation->getErrors());
                            }
                        }
                    }
                    $validation->reset();
                    $validation->setRules([
                        'center' => [
                            'rules' => 'required',
                            'errors' => [
                                'required' => lang('Maps.CenterPosError')
                            ],
                        ]
                    ]);
                    if (!$validation->run($post)) {
                        $errors = array_merge($errors, $validation->getErrors());
                    }
                    if (empty($errors)) {
                        $result = $this->mapsModel->saveMap($id, $post);
                    }
                    if ($result) {
                        $this->session->setFlashdata('maps', array(
                            'status' => true,
                            'msg' => ($id ? lang('Maps.EditSuccess') : lang('Maps.AddSuccess')) . '!'
                        ));
                        HistoryStat($id, '', 'slider', 'Slider', $id ? lang('Maps.EditSuccess') : lang('Maps.AddSuccess'));
                        return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/maps/edit/' . $this->mapsModel->id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Maps.EditError') : lang('Maps.AddError')) . '!',
                            'list' => $errors
                        );
                    }
                    $map = $post;
                    $map['id'] = $id;
                } else {
                    $flashdata = $this->session->getFlashdata('maps');
                }
                if ($id) {
                    $this->breadcrumb->add(lang('Maps.MapEdit') . (!empty($map['name']) ? ': ' . $map['name'] : ''), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/maps/edit/' . $id);
                } else {
                    $this->breadcrumb->add(lang('Maps.NewMapAdd'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/maps/add');
                }

                $breadcrumb = $this->breadcrumb->render();
                echo view('Modules\Maps\Views\admin\add', array('action' => $action, 'map' => $map, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            case 'configuration':
                $post = $this->request->getPost();
                $settings = $this->mapsModel->getMapsSettings();
                $flashdata = array();
                if (!empty($post)) {
                    $result = $this->mapsModel->saveSettings($post);
                    if ($result) {
                        $this->session->setFlashdata('maps', array(
                            'status' => true,
                            'msg' => lang('Maps.configuration.EditSuccess') . '!'
                        ));
                        HistoryStat('', '', 'maps', 'Maps', lang('Maps.configuration.EditSuccess'));
                        return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/maps/configuration');
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => lang('Maps.configuration.EditError') . '!',
                            'list' => $errors
                        );
                        $settings = $post;
                    }
                } else {
                    $flashdata = $this->session->getFlashdata('maps');
                }
                $this->breadcrumb->add(lang('Maps.Configuration'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/maps/configuration');
                $breadcrumb = $this->breadcrumb->render();
                echo view('Modules\Maps\Views\admin\settings', array('settings' => $settings, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            default :
                $breadcrumb = $this->breadcrumb->render();
                $get = $this->request->getGet();
                $query = $this->mapsModel->join('maps_lang ml', 'maps.id=ml.id_map')->select('maps.id,maps.publish,ml.name')->where('ml.id_lang', $this->id_lang);
                if (!empty($get)) {
                    foreach ($get as $name => $value) {
                        switch ($name) {
                            case 'name':
                                if (!empty($value)) {
                                    $query->like('sl.name', $value);
                                }
                                break;
                            case 'publish':
                                if(in_array($value, array(0,1))) {
                                    $query->where('maps.publish', $value);
                                }
                                break;
                        }
                    }
                }
                if (empty($get['order'])) {
                    $get['order'] = 'id;asc';
                }
                switch ($get['order']) {
                    case 'name;asc': $query->orderBy('ml.name', 'ASC');
                        break;
                    case 'name;desc': $query->orderBy('ml.name', 'DESC');
                        break;
                    default: $query->orderBy('maps.id', 'ASC');
                        break;
                }
                $maps = $query->paginate(20);
                $order_list = array(
                    array('field' => '', 'name' => lang('Maps.sort.Default')),
                    array('field' => 'name;asc', 'name' => lang('Maps.sort.NameAsc')),
                    array('field' => 'name;desc', 'name' => lang('Maps.sort.NameDesc')),
                );
                echo view('Modules\Maps\Views\admin\list', array('maps' => $maps, 'breadcrumbs' => $breadcrumb, 'filters' => $get, 'order_list' => $order_list, 'pager' => $this->mapsModel->pager));
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
                $assets['js'][] = '/adm/js/maps.js';
                $assets['js'][] = '/adm/js/file-uploader.js';
                break;
            default :
                break;
        }
        return $assets;
    }

    public function ajax($action = '', $id = 0) {
        $post = $this->request->getPost();
        if (!empty($action)) {
            switch ($action) {
                case 'marker-add':
                    return $this->addMarker($post);
                    break;
                case 'publish':
                    return $this->publishMap($id);
                    break;
                case 'delete':
                    return $this->deleteMap($id);
                    break;
            }
        }
    }

    private function addMarker($post = array()) {
        $html = view('Modules\Maps\Views\admin\add_marker', array('no' => $post['no'], 'languages' => $this->languages, 'locale' => $this->locale));
        return $this->response->setJSON(array(
            'status' => true,
            'html' => base64_encode(urlencode($html))
        ));
    }

    private function deleteMap($id) {
        $result = $this->mapsModel->deleteMap($id);
        return $this->response->setJSON(array(
            'status' => true,
            'result' => $result,
            'id' => $id,
            'msg' => $result ? lang('Maps.Removed') : lang('Maps.Error')
        ));
        HistoryStat($id, '', 'maps', 'Maps', $result ? lang('Maps.Removed') : lang('Maps.Error'));
    }

    private function publishMap($id) {
        $map = $this->mapsModel->select('id,publish')->where('id', $id)->first();
        if (!empty($map)) {
            $r = $this->mapsModel->where('id', $id)->set('publish', $map['publish'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $map['publish'] ? 0 : 1,
                'msg' => $map['publish'] ? lang('Maps.Republished') : lang('Maps.Published')
            );
            HistoryStat($id, '', 'slider', 'Slider', $map['publish'] ? lang('Maps.Republished') : lang('Maps.Published'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $map['publish'],
                'msg' => lang('Maps.Error')
            );
        }
        return $this->response->setJSON($response);
    }

    public function pageContent($id_content, $slug = '') {
        helper('filesystem');
        $maps = array();
        $list = $this->mapsModel->join('maps_lang ml', 'maps.id=ml.id_map')->join('language l', 'l.id=ml.id_lang')->select('maps.id,ml.name')->where('l.default', 1)->where('maps.publish', 1)->orderBy('ml.name', 'ASC')->findAll();
        if(!empty($list)) {
            foreach($list as $l) {
                $l['link'] = ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/maps/edit/' . $l['id'];
                $maps[$l['id']] = $l;
            }
        }
        $templates = get_templates_by_dir('modules/Maps/Views/user');
        return array(
            'elements' => $maps,
            'pc_templates' => $templates
        );
    }

}
