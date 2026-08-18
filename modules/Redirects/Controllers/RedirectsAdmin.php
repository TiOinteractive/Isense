<?php

namespace Modules\Redirects\Controllers;
use App\Controllers\BaseController;
use Modules\Redirects\Models\RedirectsModel;
use App\Libraries\Breadcrumb;


class RedirectsAdmin extends BaseController
{
    public function __construct()
    {   
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->redirectsModel = new RedirectsModel();
    }
    
    public function index($action='', $id=0) 
    {
        $redirect = array();
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
        $this->breadcrumb->add(lang('Redirects.RedirectsList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/redirects');
        switch ($action) {
            case 'edit':
                $redirect = $this->redirectsModel->getRedirectById($id);
            case 'add':
            case 'save':
                $post = $this->request->getPost();
                if (!empty($post)) {
                    $result = false;
                    $errors = array();
                    $validation = \Config\Services::validation();
                    if (!empty($post)) {
                        $validation->reset();
                        $validation->setRules([
                            'from' => [
                                'rules' => 'required',
                                'errors' => [
                                    'required' => lang('Redirects.FromError')
                                ],
                            ],
                            'to' => [
                                'rules' => 'required',
                                'errors' => [
                                    'required' => lang('Redirects.ToError')
                                ],
                            ]
                        ]);
                        if (!$validation->run($post)) {
                            $errors = $validation->getErrors();
                        }
                    }
                    if (empty($errors)) {
                        $errors = $this->redirectsModel->checkRedirectExist($id, $post);
                        if(empty($errors)) {
                            $result = $this->redirectsModel->saveRedirect($id, $post);
                        }
                    }
                    if ($result) {
                        $this->session->setFlashdata('redirects', array(
                            'status' => true,
                            'msg' => ($id ? lang('Redirects.EditSuccess') : lang('Redirects.AddSuccess')) . '!'
                        ));
                        HistoryStat($id,'','redirects','Redirects',$id ? lang('Redirects.EditSuccess') : lang('Redirects.AddSuccess'));
                        return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/redirects/edit/' . $this->redirectsModel->id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Redirects.EditError') : lang('Redirects.AddError')) . '!',
                            'list' => $errors
                        );
                    }
                    $redirect = $post;
                    $redirect['id'] = $id;
                } else {
                    $flashdata = $this->session->getFlashdata('redirects');
                }
                if ($id) {
                    $this->breadcrumb->add(lang('Redirects.RedirectEdit') . (!empty($redirect['from']) ? ': ' . $redirect['from'] : ''), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/redirects/edit/' . $id);
                } else {
                    $this->breadcrumb->add(lang('Redirects.NewRedirectAdd'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/redirects/add');
                }

                $breadcrumb = $this->breadcrumb->render();
                echo view('Modules\Redirects\Views\admin\add', array('action' => $action, 'redirect' => $redirect, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            default :
                $breadcrumb = $this->breadcrumb->render();
                $get = $this->request->getGet();
                $query = $this->redirectsModel->select('id,from,to,type,publish');
                if(!empty($get)) {
                    foreach($get as $name=>$value) {
                        switch($name) {
                            case 'from': 
                                if(!empty($value)) {
                                    $query->like('from', $value);
                                }
                                break;
                            case 'to': 
                                if(!empty($value)) {
                                    $query->like('to', $value);
                                }
                                break;
                            case 'type': 
                                if(!empty($value)) {
                                    $query->where('type', $value);
                                }
                                break;
                            case 'publish': 
                                if(in_array($value, array(0,1))) {
                                    $query->where('publish', $value);
                                }
                                break;
                            case 'short':
                                if(in_array($value, array(0,1))) {
                                    $query->where('short', $value);
                                }
                                break;
                            case 'group':
                                if(!empty($value)) {
                                    $query->where('group', $value);
                                }
                                break;
                        }
                    }
                }
                if(empty($get['order'])) {
                    $get['order'] = 'created_at;desc';
                }
                switch($get['order']) {
                    case 'from;asc': $query->orderBy('from', 'ASC')->orderBy('to', 'ASC');
                        break;
                    case 'from;desc': $query->orderBy('from', 'DESC')->orderBy('to', 'DESC');
                        break;
                    case 'to;asc': $query->orderBy('to', 'ASC')->orderBy('from', 'ASC');
                        break;
                    case 'to;desc': $query->orderBy('to', 'DESC')->orderBy('from', 'DESC');
                        break;
                    default: $query->orderBy('created_at', 'DESC');
                        break;
                }
                $redirects = $query->paginate(20);
                
                $order_list = array(
                    array('field' => '', 'name' => lang('Redirects.sort.Default')),
                    array('field' => 'from;asc', 'name' => lang('Redirects.sort.FromAsc')),
                    array('field' => 'from;desc', 'name' => lang('Redirects.sort.FromDesc')),
                    array('field' => 'to;asc', 'name' => lang('Redirects.sort.ToAsc')),
                    array('field' => 'to;desc', 'name' => lang('Redirects.sort.ToDesc')),
                );
                echo view('Modules\Redirects\Views\admin\list', array('redirects' => $redirects, 'breadcrumbs' => $breadcrumb, 'filters' => $get, 'order_list' => $order_list, 'pager' => $this->redirectsModel->pager));
                break;
        }
    }

    public function ajax($action = '', $id = 0) {
        $post = $this->request->getPost();
        if (!empty($action)) {
            switch ($action) {
                case 'publish':
                    return $this->publishRedirect($id);
                    break;
                case 'delete':
                    return $this->deleteRedirect($id);
                    break;
            }
        }
    }

    private function publishRedirect($id) {
        $redirect = $this->redirectsModel->select('id,publish')->where('id', $id)->first();
        if (!empty($redirect)) {
            $r = $this->redirectsModel->where('id', $id)->set('publish', $redirect['publish'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $redirect['publish'] ? 0 : 1,
                'msg' => $redirect['publish'] ? lang('Redirects.Republished') : lang('Redirects.Published')
            );
            HistoryStat($id,'','slider','Slider', $redirect['publish'] ? lang('Redirects.Republished') : lang('Redirects.Published'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $redirect['publish'],
                'msg' => lang('Redirects.Error')
            );
        }
        return $this->response->setJSON($response);
    }

    private function deleteRedirect($id) {
        $result = $this->redirectsModel->deleteRedirect($id);
        return $this->response->setJSON(array(
            'status' => true,
            'result' => $result,
            'id' => $id,
            'msg' => $result ? lang('Redirects.Removed') : lang('Redirects.Error')
        ));
        HistoryStat($id,'','redirects','Redirects',$result ? lang('Redirects.Removed') : lang('Redirects.Error'));
    }
}