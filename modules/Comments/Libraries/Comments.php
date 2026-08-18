<?php

namespace Modules\Comments\Libraries;

use Modules\Comments\Models\CommentsModel;
use App\Libraries\Link;
use App\Libraries\Page;

class Comments {

    public function __construct() {
        $this->pageClass = new Page();
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->commentsModel = new CommentsModel();
    }
    
    public function initController() {
        
    }

    public function index($content, $id_lang, $slug='', $link=array()) {
        
    }
    
    public function commentsActions($action='', $id=0) {
        $session = session(); 
        $session_user = $session->get('user');
        $language = $this->pageClass->checkLanguage();
        if(empty($language)) {
            $lang = $this->pageClass->getPrimaryLang();
            $this->request->setLocale($lang['lang_code']);
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            exit();
        }
        $post = $this->request->getPost();
        $linkClass = new Link();
        $path_info = parse_url($this->request->getUserAgent()->getReferrer());
        $link = $linkClass->getLinkByUrl(substr($path_info['path'], 1), $language);
        $global_links = $linkClass->getGlobalLinks($language['id'], $language['slug']);
        $global_links = array_merge($global_links, $this->pageClass->getSpecialWebsites($language['id'], $language['slug']));
        
        switch($action) {
            case 'form':
                $html = view('\Modules\Comments\Views\user/form', array('id_lang' => $language['id'], 'locale' => $link['slug'], 'action' => $action, 'session_user' => $session_user, 'parent' => $id, 'global_links' => $global_links));
                $response = array(
                    'html' => base64_encode(urlencode($html)),
                    'action' => $action,
                    'lang' => array(
                        'title' => lang('Comments.form.AddComment'),
                        'close' => lang('Comments.form.Close'),
                    )
                );
                break;
            case 'list':
                $count = $this->commentsModel->countCommentsByLinkId($link['id']);
                $comments =  $this->commentsModel->getCommentsByLinkId($link['id'], 0, false, 5);
                $html = view('\Modules\Comments\Views\user/comments_list', array('id_lang' => $language['id'], 'locale' => $link['slug'], 'comments' => $comments, 'count' => $count, 'action' => $action));
                $response = array(
                    'html' => base64_encode(urlencode($html)),
                    'action' => $action,
                    'count' => $count,
                );
                break;
            case 'more':
                if(!empty($post['count'])) {
                    $count = $this->commentsModel->countCommentsByLinkId($link['id']);
                    $comments =  $this->commentsModel->getCommentsByLinkId($link['id'], 0, false, 5, $post['count']);
                    $html = view('\Modules\Comments\Views\user/comments_list', array('id_lang' => $language['id'], 'locale' => $link['slug'], 'comments' => $comments, 'count' => $count, 'action' => $action));
                    $response = array(
                        'html' => base64_encode(urlencode($html)),
                        'action' => $action,
                        'count' => $count,
                    );
                }
                break;
            default:
                $errors = array();
                $result = false;
                $validation = \Config\Services::validation();
                $validation->setRules([
                        'content' => 'required',
                        'field_h' => 'empty',
                    ],
                    [
                        'content' => [
                            'required' => lang('Comments.form.Required'),
                        ],
                        'field_h' => [
                            'empty' => 'field_h',
                        ],
                    ]
                );
                if ($validation->withRequest($this->request)->run()) {
                    if(!empty($link) && !empty($link['link'])) {
                        $tmp_link = explode('/', trim($link['link'], '/'));
                        if(!empty($tmp_link) && !empty($tmp_link[0])) {
                            $page = $this->commentsModel->db->table('links l')->join('page_lang pl', 'pl.id_link=l.id')->select('pl.id_page')->where('l.link', $tmp_link[0])->get()->getRowArray();
                        }
                    }
                    $result = $this->commentsModel->saveComment($post['content'], $language['id'], $link['id'], $link['id_module'], $this->request->getIPAddress(), $session_user, !empty($post['parent']) ? $post['parent'] : 0, $link['module_slug'], !empty($page) ? $page['id_page'] : 0);
                } else {
                    $errors = $validation->getErrors();
                }
                $response = array(
                    'result' => $result,
                    'errors' => $errors,
                    'msg' => $result ? lang('Comments.form.CommentAdded') : lang('Comments.form.ErrorWhileAddingComment'),
                    'action' => $action,
                );
                break;
        }
        
        return $this->response->setJSON($response);
    }
    
    public function showCommentsForm($id_lang, $locale, $id_link, $template='form.php') {
        $count = $this->commentsModel->countCommentsByLinkId($id_link);
        $comments =  $this->commentsModel->getCommentsByLinkId($id_link, 0, false, 5);
        $html = view('\Modules\Comments\Views\user/' . $template, array('id_lang' => $id_lang, 'locale' => $locale, 'comments' => $comments, 'count' => $count));
        return $html;
    }
}