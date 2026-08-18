<?php

namespace Modules\Comments\Controllers;

use App\Controllers\BaseController;
use Modules\Comments\Models\CommentsModel;
use App\Libraries\Breadcrumb;

class CommentsAdmin extends BaseController {

    public function __construct() {
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->commentsModel = new CommentsModel();
    }

    public function index($action = '', $id_content = 0, $id = 0) {
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG'));
        $this->breadcrumb->add(lang('Comments.Comments'), ($this->locale ? $this->locale . '/' : '') . env('ADMIN_PANEL_SLUG') . '/comments');
        switch ($action) {
            default:
                $breadcrumb = $this->breadcrumb->render();
                $get = $this->request->getGet();
                $query = $this->commentsModel->select('comments.*,u.name,u.surname,u.mail,u.comments as user_comments,l.link')->join('links l', 'l.id=comments.id_link', 'left')->join('users u', 'u.id=comments.id_user');
                if(!empty($get)) {
                    foreach($get as $name=>$value) {
                        if(!empty($value)) {
                            switch($name) {
                                case 'search':
                                    $query->groupStart()->like('u.mail', $value, 'both')->orLike('u.nick', $value, 'both')->groupEnd();
                                    break;
                                case 'status':
                                    $query->where('comments.status', $value);
                                    break;
                                case 'date':
                                    if(!empty($value)) {
                                        $tmp = explode('-', $value);
                                        $date_start = !empty($tmp) && !empty($tmp[0]) ? date('Y-m-d', strtotime($tmp[0])) : '';
                                        $date_end = !empty($tmp) && !empty($tmp[1]) ? date('Y-m-d', strtotime($tmp[1] . ' +1 day')) : '';
                                        if(!empty($date_start)) {
                                            $query->where('comments.created_at >=', $date_start);
                                        }
                                        if(!empty($date_end)) {
                                            $query->where('comments.created_at <', $date_end);
                                        }
                                    }
                                    break;
                                case 'section':
                                    $query->where('comments.id_page', $value);
                                    break;
                                case 'module':
                                    $query->where('comments.id_module', $value);
                                    break;
                            }
                        }
                    }
                }
                if(empty($get['order'])) {
                    $get['order'] = 'created_at;desc';
                }
                switch($get['order']) {
                    case 'created_at;asc': $query->orderBy('comments.created_at', 'ASC');
                        break;
                    case 'created_at;desc': 
                    default: 
                        $query->orderBy('comments.created_at', 'DESC');
                        break;
                }
                $comments = $query->paginate(!empty($get['on_page']) ? $get['on_page'] : 20);
                if(!empty($comments)) {
                    foreach($comments as $c) {
                        if($c['status'] == 'new') {
                            $this->commentsModel->changeCommenStatus($c['id'], 'viewed');
                        }
                    }
                }
                $order_list = array(
                    array('field' => '', 'name' => lang('Comments.sort.Default')),
                    array('field' => 'created_at;asc', 'name' => lang('Comments.sort.AddDateAsc')),
                    array('field' => 'created_at;desc', 'name' => lang('Comments.sort.AddDateDesc')),
                );
                $on_page_list = array(
                    20 => 20,
                    40 => 40,
                    80 => 80,
                );
                $pages = $this->commentsModel->db->table('page p')->join('page_lang pl', 'p.id=pl.id_page')->join('links l', 'pl.id_link=l.id')->select('p.id,pl.name')->where('p.re_id', 0)->where('pl.id_lang', $this->id_lang)->where('l.link !=', '')->orderBy('pl.name', 'ASC')->get()->getResultArray();
                $modules = $this->commentsModel->db->table('module m')->join('module_lang ml', 'm.id=ml.id_module')->select('m.id,ml.name')->whereIn('m.id', array(2,4,13,14,16,17,18))->where('ml.id_lang', $this->id_lang)->orderBy('ml.name', 'ASC')->get()->getResultArray();
                echo view('Modules\Comments\Views\admin\list', array(
                    'comments' => $comments, 
                    'pages' => $pages, 
                    'modules' => $modules, 
                    'filters' => $get, 
                    'breadcrumbs' => $breadcrumb, 
                    'order_list' => $order_list, 
                    'on_page_list'=>$on_page_list,
                    'pager' => $this->commentsModel->pager
                ));
                break;
        }
    }
    
    public function ajax($action='', $id=0, $id2=0) 
    {
        $post = $this->request->getPost();
        if(!empty($action)) {
            switch($action) {
                case 'preview': 
                    return $this->previewComment($id, $id2);
                    break;
                case 'block-user': 
                    return $this->blockUser($id, $post);
                    break;
                case 'delete': 
                    return $this->deleteComment($id, $post);
                    break;
            }
        }
    }
    
    private function previewComment($id_link, $id) {
        $comments = $this->commentsModel->getCommentsByLinkId($id_link, 0, true);
        $html = view('Modules\Comments\Views\admin\preview', array('comments' => $comments, 'id' => $id, 'locale' => $this->locale));
        $response = array(
            'status' => true,
            'html' => base64_encode(urlencode($html))
        );
        return $this->response->setJSON($response);
    }
    
    private function blockUser($id_user, $post) 
    {
        $result = $this->commentsModel->blockUser($id_user);
        HistoryStat($id_user,'','users','Users',$result ? lang('Comments.Blocked') : lang('Comments.Error'));
        return $this->response->setJSON(array(
            'status' => true,
            'result' => $result,
            'id_user' => $id_user,
            'msg' => $result ? lang('Comments.Blocked') : lang('Comments.Error'),
            'preview' => !empty($post['preview'])
        ));
    }
    
    private function deleteComment($id, $post) 
    {
        $result = $this->commentsModel->deleteComment($id);
        HistoryStat($id,'','comments','Comments',$result ? lang('Comments.Removed') : lang('Comments.Error'));
        return $this->response->setJSON(array(
            'status' => true,
            'result' => $result,
            'id' => $id,
            'msg' => $result ? lang('Comments.Removed') : lang('Comments.Error'),
            'preview' => !empty($post['preview']),
            'change' => true
        ));
    }
    
}