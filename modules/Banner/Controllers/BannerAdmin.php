<?php

namespace Modules\Banner\Controllers;

use App\Controllers\BaseController;
use Modules\Banner\Models\BannerModel;
use App\Libraries\Breadcrumb;

class BannerAdmin extends BaseController {

    public function __construct() {
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->BannerModel = new BannerModel();
        helper("history");
    }

    public function index($action = '', $id = 0) {
        $BannerModel = new BannerModel();
        $banner['banner'] = array();
        $zone = array();
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
        $this->breadcrumb->add(lang('Banner.ZoneList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/banner');
        $zoneList = $BannerModel->getZones($action, $id, $this->locale);
        switch ($action) {
            case 'edit_zone':
                $zone = $BannerModel->getZoneById($id, $this->id_lang);
            case 'add_zone':
                $post = $this->request->getPost();
                if (!empty($post)) {
                    $result = false;
                    $errors = array();
                    $validation = \Config\Services::validation();
                    if ($post['zone']['lang']) {
                        foreach ($post['zone']['lang'] as $lang) {
                            $validation->setRules([
                                'name' => [
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => lang('Banner.NameError')
                                    ],
                                ]
                            ]);
                            if (!$validation->run($lang)) {
                                $errors[] = array_merge($validation->getErrors());
                            }
                        }
                    }

                    if (empty($errors)) {
                        $result = $BannerModel->saveZone($id, $action, $post['zone']);
                    }
                    if ($result) {
                        $this->session->setFlashdata('page', array(
                            'status' => true,
                            'msg' => ($id ? lang('Admin.page.EditSuccess') : lang('Admin.page.AddSuccess')) . '!'
                        ));
                        return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/banner/edit_zone/' . $BannerModel->id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Admin.page.EditError') : lang('Admin.page.AddError')) . '!',
                            'list' => $errors
                        );
                    }
                    $zone = $post['zone'];
                    $zone['id'] = $id;
                } else {
                    $flashdata = $this->session->getFlashdata('page');
                }

                if ($id) {
                    $this->breadcrumb->add(lang('Banner.ZoneEdit') . (!empty($zone['name']) ? ': ' . $zone['name'] : ''), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/banner/edit_zone/' . $id);
                } else {
                    $this->breadcrumb->add(lang('Banner.NewZoneAdd'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/banner/add_zone');
                }
                $breadcrumb = $this->breadcrumb->render();
                echo view('Modules\Banner\Views\admin\add-zone', array('action' => $action, 'zone' => $zone, 'id' => $id, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            case 'edit':
                $banner['banner'] = $BannerModel->getBannerById($id, $this->id_lang);
            case 'add':
            case 'add-zone':
            case 'save':
                $post = $this->request->getPost();
                if (!empty($post)) {
                    $result = false;
                    $errors = array();
                    $validation = \Config\Services::validation();
                    if ($post['banner']['lang']) {
                        foreach ($post['banner']['lang'] as $lang) {
                            $validation->setRules([
                                'name' => [
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => lang('Banner.NameError')
                                    ],
                                ]
                            ]);
                            if (!$validation->run($lang)) {
                                $errors[] = array_merge($validation->getErrors());
                            }
                        }
                    }

                    if (empty($errors)) {
                        $result = $BannerModel->saveBanner($id, $action, $post);
                    }
                    if ($result) {
                        $this->session->setFlashdata('page', array(
                            'status' => true,
                            'msg' => ($id ? lang('Admin.page.EditSuccess') : lang('Admin.page.AddSuccess')) . '!'
                        ));
						HistoryStat($id, '', 'baner_baner', 'Banner', $id ? lang('Admin.page.EditSuccess') : lang('Admin.page.AddSuccess'));
                        return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/banner/edit/' . $BannerModel->id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Admin.page.EditError') : lang('Admin.page.AddError')) . '!',
                            'list' => $errors
                        );
                    }
                    $banner = $post;
                    $banner['banner']['id'] = $id;
                    if (isset($post['banner']['dates']) and $post['banner']['dates'] == 1 and isset($post['banner']['data']) and!isset($post['banner']['date_single'])) {
                        $dat = explode('-', $post['banner']['data']);
                        $banner['banner']['date_start'] = date('Y-m-d', strtotime(str_replace('/', '-', $dat[0])));
                        $banner['banner']['date_end'] = date('Y-m-d', strtotime(str_replace('/', '-', $dat[1])));
                    } elseif (isset($post['banner']['date_single']) and $post['banner']['date_single'] == 1 and $post['banner']['dates'] == 1 and isset($post['banner']['data'])) {
                        $banner['banner']['date_start'] = date('Y-m-d', strtotime(str_replace('/', '-', $post['banner']['data'])));
                        $banner['banner']['date_end'] = NULL;
                    } else {
                        $banner['banner']['date_start'] = NULL;
                        $banner['banner']['date_end'] = NULL;
                    }
                } else {
                    $flashdata = $this->session->getFlashdata('page');
                }
                if ($action == "add-zone") {
                    $this->breadcrumb->add(lang('Banner.Zone') . (!empty($zone['name']) ? ': ' . $zone['name'] : ''), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/banner/zone/' . $id);
                } elseif ($id) {
                    if (isset($banner['banner']['strefa_info'])) {
                        $this->breadcrumb->add(lang('Banner.Zone') . ': ' . $banner['banner']['strefa_info']['name'], ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/banner/zone/' . $banner['banner']['strefa_info']['id']);
                    }
                    $this->breadcrumb->add(lang('Banner.BanerEdit') . (!empty($banner['banner']['name']) ? ': ' . $banner['banner']['name'] : ''), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/banner/edit/' . $id);
                } else {
                    $this->breadcrumb->add(lang('Banner.NewBanerAdd'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/banner/add');
                }
                $breadcrumb = $this->breadcrumb->render();
                echo view('Modules\Banner\Views\admin\add', array('action' => $action, 'banner' => $banner['banner'], 'id' => $id, 'zones' => $zoneList, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            case 'zone':
                $defaultLang = $BannerModel->db->table('language')->select('id')->where('slug', $this->locale)->where('publish', 1)->get()->getRow();
                $zone = $BannerModel->getZoneById($id, $this->id_lang);
                $zone['defaultLang'] = $defaultLang->id;
                $sort = $zone['order_sort'];
                $BannersList = array();
                if (isset($BannersList['list'])) {
                    $zone['list'] = $BannersList;
                }
                $BannersList = $BannerModel->getBannersZone($id, $sort);
                if (!isset($BannersList['pager'])) {
                    $BannersList['pager'] = '';
                }
                if (!isset($BannersList['filters'])) {
                    $BannersList['filters'] = '';
                }
                if (isset($BannersList['list'])) {
                    $zone['list'] = $BannersList['list'];
                }
                $this->breadcrumb->add(lang('Banner.Zone') . (!empty($zone['name']) ? ': ' . $zone['name'] : ''), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/banner/zone/' . $id);
                $breadcrumb = $this->breadcrumb->render();
                $flashdata = $this->session->getFlashdata('page');
                $order_list = array(
                    array('field' => 'order;asc', 'name' => lang('Banner.sort.OrderAsc')),
                    array('field' => 'order;desc', 'name' => lang('Banner.sort.OrderDesc')),
                    array('field' => 'name;asc', 'name' => lang('Banner.sort.NameAsc')),
                    array('field' => 'name;desc', 'name' => lang('Banner.sort.NameDesc')),
                    array('field' => 'date;desc', 'name' => lang('Banner.sort.DateDesc')),
                    array('field' => 'date;asc', 'name' => lang('Banner.sort.DateAsc')),
                );

                echo view('Modules\Banner\Views\admin\zone', array('action' => $action, 'flashdata' => $flashdata, 'order_list' => $order_list, 'pager' => $BannersList['pager'], 'filters' => $BannersList['filters'], 'zone' => $zone, 'breadcrumbs' => $breadcrumb));
                break;
            default :
                $breadcrumb = $this->breadcrumb->render();
                $zones = array();
                $zones = $BannerModel->db->table('baner_zone')->orderBy('id', 'ASC')->get()->getResultArray();
                $defaultLang = $BannerModel->db->table('language')->where('slug', $this->locale)->where('publish', 1)->get()->getRow();
                if (isset($zones)) {
                    foreach ($zones as $k => $zone) {
                        $zones[$k]['number'] = $BannerModel->db->table('baner_baner')->where(['id_zone' => $zone['id']])->countAllResults();
                        $zones[$k]['lang'] = $BannerModel->db->table('baner_zone_lang')->select('name')->where('id_zone', $zone['id'])->where('id_lang', $defaultLang->id)->orderBy('id_lang', 'ASC')->get()->getRow();
                    }
                }
                echo view('Modules\Banner\Views\admin\list', array('breadcrumbs' => $breadcrumb, 'zones' => $zones));
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
        $assets['js'][] = '/adm/js/banner.js';
        return $assets;
    }

    public function ajax($action = '', $id = 0) {
        $post = $this->request->getPost();
        if (!empty($action)) {
            switch ($action) {
                case 'publish_zone':
                    return $this->publishZone($id);
                    break;
                case 'publish':
                    return $this->publishBaner($id);
                    break;
                case 'delete_zone':
                    return $this->deleteZone($id);
                    break;
                case 'delete':
                    return $this->deleteBaner($id);
                    break;
                case 'order':
                    return $this->orderBaner($id);
                    break;
            }
        }
    }

    private function orderBaner($id) {
        $post = $this->request->getPost();
        $this->BannerModel->transStart();
        if (isset($post['old_pos']) && isset($post['new_pos']) && $post['old_pos'] != $post['new_pos']) {
            $ban = $this->BannerModel->select('id,order,id_zone')->where('order', $post['old_pos'])->first();
            if ($post['new_pos'] > $post['old_pos']) {
                $this->BannerModel->set('order', '`order`-1', FALSE)->where('id_zone', $ban['id_zone'])->where('order<=', $post['new_pos'])->where('order>', $post['old_pos'])->update();
            } elseif ($post['new_pos'] < $post['old_pos']) {
                $r = $this->BannerModel->set('order', '`order`+1', FALSE)->where('id_zone', $ban['id_zone'])->where('order>=', $post['new_pos'])->where('order<', $post['old_pos'])->update();
            }
            $this->BannerModel->where('id', $ban['id'])->set('order', $post['new_pos'])->update();
        }
        $this->BannerModel->transComplete();
        $r = $this->BannerModel->transStatus();
        $response = array(
            'status' => true,
            'result' => $r,
            'msg' => $r ? lang('Banner.OrderChanged') : lang('Banner.Error'),
            'new_pos' => $post['new_pos'],
            'old_pos' => $post['old_pos'],
        );
        return $this->response->setJSON($response);
    }

    private function publishZone($id) {
        $baner = $this->BannerModel->db->table('baner_zone')->select('id,publish')->where('id', $id)->get()->getRowArray();
        if (!empty($baner)) {
            $r = $this->BannerModel->db->table('baner_zone')->where('id', $id)->set('publish', $baner['publish'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $baner['publish'] ? 0 : 1,
                'msg' => $baner['publish'] ? lang('Banner.Republished') : lang('Banner.Published')
            );
            HistoryStat($id, '', 'baner_zone', 'Banner', $baner['publish'] ? lang('Banner.Republished') : lang('Banner.Published'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $baner['publish'],
                'msg' => lang('Banner.Error')
            );
        }
        return $this->response->setJSON($response);
    }

    private function publishBaner($id) {
        $baner = $this->BannerModel->db->table('baner_baner')->select('id,publish')->where('id', $id)->get()->getRowArray();
        if (!empty($baner)) {
            $r = $this->BannerModel->db->table('baner_baner')->where('id', $id)->set('publish', $baner['publish'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $baner['publish'] ? 0 : 1,
                'msg' => $baner['publish'] ? lang('Banner.Republished') : lang('Banner.Published')
            );
            HistoryStat($id, '', 'baner_baner', 'Banner', $baner['publish'] ? lang('Banner.Republished') : lang('Banner.Published'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $baner['publish'],
                'msg' => lang('Banner.Error')
            );
        }
        return $this->response->setJSON($response);
    }

    private function addSlide($post = array()) {
        $html = view('Modules\Banner\Views\admin\add_banner', array('no' => $post['no'], 'languages' => $this->languages));
        return $this->response->setJSON(array(
                    'status' => true,
                    'html' => base64_encode(urlencode($html))
        ));
    }

    private function deleteZone($id) {
        $BannerModel = new BannerModel();
        $result = $BannerModel->deleteZone($id);
        return $this->response->setJSON(array(
                    'status' => true,
                    'result' => $result,
                    'id' => $id,
                    'msg' => $result ? lang('Banner.Removed') : lang('Banner.Error')
        ));
        HistoryStat($id, '', 'baner_zone', 'Banner', $result ? lang('Banner.Removed') : lang('Banner.Error'));
    }

    public function deleteBaner($id) {

        $BannerModel = new BannerModel();
        $result = $BannerModel->deleteBaner($id);
        return $this->response->setJSON(array(
                    'status' => true,
                    'result' => $result,
                    'id' => $id,
                    'msg' => $result ? lang('Banner.Removed') : lang('Banner.Error')
        ));
    }

    public function pageContent($id_content, $slug = '') {
        helper('filesystem');
        $BannerModel = new BannerModel();
        $zones = array();
        $list = $BannerModel->db->table('baner_zone z')->select('z.id,nl.name')->where('z.publish', 1)
                        ->join('baner_zone_lang nl', 'z.id=nl.id_zone')
                        ->join('language l', 'l.id=nl.id_lang')
                        ->where('l.slug', $this->locale)
                        ->get()->getResultArray();
        if(!empty($list)) {
            foreach($list as $l) {
                $l['link'] = ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/banner/zone/' . $l['id'];
                $zones[$l['id']] = $l;
            }
        }
        $templates = get_templates_by_dir('modules/Banner/Views/user');
        return array(
            'elements' => $zones,
            'pc_templates' => $templates,
            'view' => 'modules\Banner\Views\admin\page_content'
        );
    }

}
