<?php

namespace Modules\Newsletter\Libraries;

use Modules\Newsletter\Models\NewsletterModel;

class Newsletter
{
    
    public function __construct()
    {   
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->newsletterModel = new NewsletterModel();
    }
    
    public function index($content, $id_lang, $slug='') 
    {
        if(!empty($content['id_element'])) {
            $group = $this->newsletterModel->db
                    ->table('newsletter_group ng')
                    ->join('newsletter_group_lang ngl', 'ng.id=ngl.id_group')
                    ->select('ng.id,ngl.name')
                    ->where('ng.id', $content['id_element'])
                    ->where('ng.publish', 1)
                    ->where('ngl.id_lang', $id_lang)
                    ->get()->getRowArray();
            return $group;
        }
        return null;
    }
    
    public function assets($slug='', $template='', $id_group=0, $data=array()) {
        $assets = array(
            'js' => array(),
            'css' => array(),
            'css_footer' => array()
        );
        switch($slug) {
            default :
                $assets['css_footer'][] = '/assets/css/jquery-confirm.min.css';
                $assets['js'][] = '/assets/js/jquery-confirm.min.js';
                $assets['js'][] = '/assets/js/newsletter.js';
                break;
        }
        return $assets;
    }
    
    public function ajax($post, $id_lang, $slug='') 
    {
        helper('text');
        $result = false;
        $errors = array();
        $config = new \Config\Email();
        $group = $this->newsletterModel->db
                ->table('newsletter_group ng')
                ->join('newsletter_group_lang ngl', 'ng.id=ngl.id_group')
                ->join('page_content pc', 'pc.id_element=ng.id')
                ->select('ng.id,ngl.name,ngl.success_msg,ngl.error_msg,pc.template')
                ->where('pc.id', $post['content'])
                ->where('ngl.id_lang', $id_lang)
                ->get()->getRowArray();
        if(!empty($group) && file_exists(ROOTPATH . 'modules/Newsletter/Views/user/mails/' . $group['template'])) {
            $validation =  \Config\Services::validation();
            $validation_rules = array(
                'field_h' => array(
                    'label' => '',
                    'rules' => 'empty'
                ),
                'email' => [
                    'rules' => 'required|valid_email',
                    'errors' => [
                        'required' => lang('Newsletter.EmailError'),
                        'valid_email' => lang('Newsletter.EmailFormatError')
                    ],
                ]
            );
            $validation->setRules($validation_rules);
            if (!$validation->run($post)) {
                $errors = $validation->getErrors();
            } else {
                $settingsModel = new \App\Models\SettingsModel();
                $exists = $this->newsletterModel->db->table('newsletter_email')->select('id,agreement')->where('email', $post['email'])->where('id_group', $group['id'])->get()->getRowArray();
                if(empty($exists) || (!empty($exists) && !$exists['agreement'])) {
                    $hash = random_string('sha1');
                    $count = 1;
                    do {
                        $is = $this->newsletterModel->db->table('newsletter_email')->select('id')->where('hash', $hash)->get()->getRowArray();
                        if(!empty($is)) {
                            $hash = random_string('sha1');
                        }
                        ++$count;
                    } while(!empty($is) && $count<=1000);
                    $data = array(
                        'id_group' => $group['id'],
                        'email' => $post['email'],
                        'name' => !empty($post['name']) ? $post['name'] : '',
                        'surname' => !empty($post['surname']) ? $post['surname'] : '',
                        'hash' => $hash,
                        'hash_valid' => date('Y-m-d H:i:s', strtotime('+ 5 days'))
                    );
                    $this->newsletterModel->db->transStart();
                    if(!empty($exists)) {
                        $this->newsletterModel->db->table('newsletter_email')->set($data)->where('id', $exists['id'])->update();
                        $id = $exists['id'];
                    } else {
                        $this->newsletterModel->db->table('newsletter_email')->insert($data);
                        $id = $this->newsletterModel->db->insertID();
                    }
                    $this->newsletterModel->db->transComplete();
                    $result = $this->newsletterModel->db->transStatus();
                    if($result) {
                        $url = base_url() . 'newsletter-action/confirm/' . $hash;
                        $email = \Config\Services::email();
                        $email->attach(base_url() . 'adm/img/tio_logo_6-0-1.jpg');
                        $data = array(
                            'header' => lang('Newsletter.MailVerification'),
                            'settings' => $settingsModel->getSettings($id_lang),
                            'cid_logo' => $email->setAttachmentCID(base_url() . 'adm/img/tio_logo_6-0-1.jpg')
                        );
                        $email->setFrom($config->fromEmail, $config->fromName);
                        $email->setTo($post['email']);
                        $email->setSubject($group['name']);
                        $message = view('Modules\Newsletter\Views\user/mails/' . $group['template'], array('newsletter' => $group, 'post' => $post, 'exists' => $exists, 'url' => $url, 'data' => $data));
                        $email->setMessage($message);
                        $result = $email->send();
                         
                    }
                } else {
                    $result = true;
                }
            }
        }
        $response = array(
            'content' => $post['content'],
            'newsletter' => !empty($group) ? $group['id'] : 0,
            'result' => $result,
            'errors' => $errors,
            'callback' => 'newsletterCallback',
            'msg' => $result ? (!empty($group['success_msg']) ? $group['success_msg'] : lang('Newsletter.SuccessMsg')) : (!empty($group['error_msg']) ? $group['error_msg'] : lang('Newsletter.ErrorMsg'))
        );
        return $this->response->setJSON($response);
    }
    
    public function checkGet($id_content) 
    {
        helper('text');
        $get = $this->request->getGet();
        if(!empty($get)) {
            $result = false;
            if(!empty($get['newsletter'])) {
                $email = $this->newsletterModel->db->table('newsletter_email')->select('id,id_group,email')->where('hash', $get['newsletter'])->where('agreement', 0)->where('hash_valid >=', date('Y-m-d H:i:s'))->get()->getRowArray();
                if(!empty($email)) {
                    $hash = random_string('sha1');
                    $count = 1;
                    do {
                        $is = $this->newsletterModel->db->table('newsletter_email')->select('id')->where('hash', $hash)->get()->getRowArray();
                        if(!empty($is)) {
                            $hash = random_string('sha1');
                        }
                        ++$count;
                    } while(!empty($is) && $count<=1000);
                    $data = array(
                        'agreement' => 1,
                        'hash' => $hash,
                        'hash_valid' => null
                    );
                    $result = $this->newsletterModel->db->table('newsletter_email')->set($data)->where('id', $email['id'])->update();
                }
                return view('\Modules\Newsletter\Views\user/popups/popup_confirm', array('result' => $result));
            }
        }
        return '';
    }
}