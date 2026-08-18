<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Libraries\Page;
use Modules\Newsletter\Models\NewsletterModel;
use Modules\Newsletter\Models\NewsletterEmailModel;

class Newsletter extends BaseController {

    public function __construct() 
    {
        $this->session = session();    
        $this->pageClass = new Page();
        $this->newsletterModel = new NewsletterModel();
        $this->newsletterEmailModel = new NewsletterEmailModel();
        $this->language = $this->pageClass->checkLanguage();
        if(empty($this->language)) {
            $lang = $this->pageClass->getPrimaryLang();
            $this->request->setLocale($lang['lang_code']);
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            exit();
        }
    }
    
    public function newsletterActions($action, $hash='', $hash2='') {
        helper('text');
        $this->db_newsletter = \Config\Database::connect('newsletter');
        $post = $this->request->getPost();
        switch($action) {
            case 'form':
                $html = view('Modules\Newsletter\Views\user/form.php', array('locale' => $this->language['slug']));
                $response = array(
                    'status' => true,
                    'html' => base64_encode(urlencode($html))
                );
                return $this->response->setJSON($response);
                break;
            case 'add-email':
                $result = false;
                $errors = array();
                $config = new \Config\Email();
                $post = $this->request->getPost();
                $settings = $this->pageClass->getSettings($this->language['id']);
                
                $validation = \Config\Services::validation();
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
                    ],
                    'newsletter' => [
                        'rules' => 'required',
                        'errors' => [
                            'required' => lang('Newsletter.Required'),
                        ],
                    ]
                );
                $validation->setRules($validation_rules);
                if (!$validation->run($post)) {
                    $errors = $validation->getErrors();
                } else {
                    $msg_success = lang('Newsletter.SuccessMsg');
                    $groups = $this->newsletterModel->db->table('newsletter_group')->select('id')->where('type', 'newsletter')->get()->getResultArray();
                    if(!empty($groups)) {
                        $this->newsletterModel->db->transStart();
                        foreach($groups as $group) {
                            $exist = $this->newsletterModel->db->table('newsletter_email')->select('id,active,agreement')->where('email', $post['email'])->where('id_group', $group['id'])->get()->getResultArray();
                            if (empty($exist) || (!empty($exist) && $exist['active'] == 0)) {
                                $hash = random_string('sha1');
                                $count = 1;
                                do {
                                    $is = $this->newsletterModel->db->table('newsletter_email')->select('id')->where('hash', $hash)->get()->getRowArray();
                                    if (!empty($is)) {
                                        $hash = random_string('sha1');
                                    }
                                    ++$count;
                                } while (!empty($is) && $count <= 1000);
                                $data = array(
                                    'id_group' => $group['id'],
                                    'email' => $post['email'],
                                    'name' => !empty($post['name']) ? $post['name'] : '',
                                    'surname' => !empty($post['surname']) ? $post['surname'] : '',
                                    'agreement' => 1,
                                    'hash' => $hash,
                                    'hash_valid' => date('Y-m-d H:i:s', strtotime('+ 5 days')),
                                    'active' => 0,
                                    'source' => 'resinet_newsletter_form'
                                );
                                if (!empty($exist)) {
                                    $this->newsletterModel->db->table('newsletter_email')->set($data)->where('id', $exist['id'])->update();
                                    $id = $exist['id'];
                                } else {
                                    $this->newsletterModel->db->table('newsletter_email')->insert($data);
                                    $id = $this->newsletterModel->db->insertID();
                                }
                            }
                        }
                        $this->newsletterModel->db->transComplete();
                        $result = $this->newsletterModel->db->transStatus();
                    }
                    if ($result) {
                        $url = base_url() . ($this->language['slug'] ? '/' . $this->language['slug'] : '') . 'newsletter-action/confirm/' . $hash;
                        $email = \Config\Services::email();
                        $email->attach(WRITEPATH . 'uploads/' . $settings['logo']['path']);
                        $cid_logo = $email->setAttachmentCID(WRITEPATH . 'uploads/' . $settings['logo']['path']);
                        $email->setFrom($config->fromEmail, $settings['company_name']);
                        $email->setTo($post['email']);
                        $email->setReplyTo($settings['email'], $settings['company_name']);
                        $email->setSubject(lang('Newsletter.NewsletterSubscribeSubject'));
                        $message = view('Modules\Newsletter\Views\user/mails/tpl.php', array('post' => $post, 'exists' => $exist, 'url' => $url, 'settings' => $settings, 'cid_logo' => $cid_logo));
                        $email->setMessage($message);
                        $result = $email->send();
                    }
                    
                    $response = array(
                        'result' => $result,
                        'errors' => $errors,
                        'callback' => 'newsletterCallback',
                        'msg' => $result ? (!empty($group['success_msg']) ? $group['success_msg'] : $msg_success) : (!empty($group['error_msg']) ? $group['error_msg'] : lang('Newsletter.ErrorMsg'))
                    );
                    
                    /* external DB start */
                    $exists = $this->db_newsletter->table('adresy_mail')->select('id,id_status')->where('mail', $post['email'])->where('id_grupa', 8)->get()->getRowArray();
                    if (empty($exists) || (!empty($exists) && $exists['id_status'] != 1)) {
                        $hash = random_string('sha1');
                        $count = 1;
                        do {
                            $is = $this->db_newsletter->table('adresy_mail')->select('id')->where('token', $hash)->get()->getRowArray();
                            if (!empty($is)) {
                                $hash = random_string('sha1');
                            }
                            ++$count;
                        } while (!empty($is) && $count <= 1000);
                        
                        $data = array(
                            'mail' => $post['email'],
                            'typ' => 'html',
                            'id_grupa' => 8,
                            'nazwa' => '',
                            'komentarz' => '',
                            'imie' => !empty($post['name']) ? $post['name'] : '',
                            'nazwisko' => !empty($post['surname']) ? $post['surname'] : '',
                            'stanowisko' => '',
                            'firma' => '',
                            'plec' => '',
                            'miasto' => '',
                            'token' => $hash,
                            'token_expire' => date('Y-m-d H:i:s', strtotime('+ 5 days')),
                            'id_status' => 3,
                            'data' => date('Y-m-d'),
                            'urodzony' => '',
                            'zmien_status' => '',
                            'source' => 'resinet_newsletter_form'
                        );
                        $this->db_newsletter->transStart();
                        if (!empty($exists)) {
                            $this->db_newsletter->table('adresy_mail')->set($data)->where('id', $exists['id'])->update();
                            $id = $exists['id'];
                        } else {
                            $this->db_newsletter->table('adresy_mail')->insert($data);
                            $id = $this->db_newsletter->insertID();
                        }
                            
                        $this->db_newsletter->transComplete();
                        $result = $this->db_newsletter->transStatus();
                        
                    } elseif(!empty($exists) && $exists['id_status'] == 1) {
                        $result = true;
                        $msg_success = lang('Newsletter.SuccessMsgExists');
                    }
                    /* external DB end */
                }
                return $this->response->setJSON($response);
                break;
            case 'confirm': 
                $new_hash = random_string('sha1');
                $count = 1;
                do {
                    $is = $this->newsletterModel->table('newsletter_email')->select('id')->where('hash', $new_hash)->get()->getRowArray();
                    if (!empty($is)) {
                        $new_hash = random_string('sha1');
                    }
                    ++$count;
                } while (!empty($is) && $count <= 1000);
                $data = array(
                    'active' => 1,
                    'hash_valid' => null,
                    'hash' => $new_hash
                );
                $result = $this->newsletterModel->db->table('newsletter_email')->set($data)->where('hash', $hash)->where('hash_valid >=', date('Y-m-d H:i:s'))->update();
                
                /* external DB start */
                $new_hash = random_string('sha1');
                $data = array(
                    'id_status' => 1,
                    'token_expire' => null,
                    'token' => $new_hash,
                    'data_statusu' => date('Y-m-d H:i:s')
                );
                $count = 1;
                do {
                    $is = $this->db_newsletter->table('adresy_mail')->select('id')->where('token', $new_hash)->get()->getRowArray();
                    if (!empty($is)) {
                        $new_hash = random_string('sha1');
                    }
                    ++$count;
                } while (!empty($is) && $count <= 1000);
                $result2 = $this->db_newsletter->table('adresy_mail')->set($data)->where('token', $hash)->where('token_expire >=', date('Y-m-d H:i:s'))->update();
                /* external DB end */
                
                $this->session->setFlashdata('newsletter_action', array(
                    'status' => $result,
                    'title' => lang('Newsletter.popup.Newsletter'),
                    'msg' => $result ? lang('Newsletter.action.EmailConfirmedInfo') : lang('Newsletter.action.Error'),
                    'close' => lang('Newsletter.popup.Close'),
                ));
                return redirect()->to('/' . ($this->language['slug'] ? $this->language['slug'] : ''));
                break;
            case 'unsubscribe':
                $result = $this->newsletterEmailModel->unsubscribeEmailByHash($hash, $this->language['id']);
                /* external DB start */
                // $this->db_newsletter->table('adresy_mail')->set(array('id_status' => 6, 'source' => 'resinet_newsletter_mail', 'data_statusu' => date('Y-m-d H:i:s')))->where('id_grupa', 8)->where('token', $hash)->update();
                /* external DB end */
                $this->session->setFlashdata('newsletter_action', array(
                    'status' => $result,
                    'title' => lang('Newsletter.popup.Newsletter'),
                    'msg' => $result ? lang('Newsletter.action.EmailUnsubscribeDInfo') : lang('Newsletter.action.Error'),
                    'close' => lang('Newsletter.popup.Close'),
                ));
                return redirect()->to('/' . ($this->language['slug'] ? $this->language['slug'] : ''));
                break;
            case 'readed':
                $result = $this->newsletterModel->markAsReaded($hash, $hash2);
                break;
        }
    }
    
    public function sendAt() {
        helper('filesystem');
        $history = $this->newsletterModel->db->table('newsletter_history')->select("id,id_newsletter,groups,subject,from_name,from_email,reply_to,html_text,hash,file,utm")->where('status', 'set')->orderBy('send_time', 'ASC')->get()->getRowArray();
        if(!empty($history) && !empty($history['groups'])) {
            $html_text = $history['html_text'];
            if(!empty($history['file']) && file_exists(WRITEPATH . 'newsletter/' . $history['file'])) {
                $html_text = file_get_contents(WRITEPATH . 'newsletter/' . $history['file']);
            }
            $status = 'sending';
            $this->newsletterModel->db->table('newsletter_history')->set('status', $status)->where('id', $history['id'])->update();
            if(!empty($html_text)) {
                $emails = $this->newsletterModel->db->table('newsletter_email')->select('id,email,hash')->where('id_group', explode(',', $history['groups']))->where('active', 1)->where('agreement', 1)->get()->getResultArray();
                if(!empty($emails)) {
                    $config = new \Config\Email();
                    $email_service = \Config\Services::email();
                    $file = 'history-' . $history['id'] . '-' . date('Y-m-d-H-i-s') . '.log';
                    foreach($emails as $e=>$email) {
                        if(($e + 1) % 10 == 0) {
                            $h = $this->newsletterModel->db->table('newsletter_history')->select('status')->where('id', $history['id'])->get()->getRowArray();
                            $status = $h['status'];
                        }
                        if($status == 'cancelled') {
                            break;
                        }
                        $unsubscribe_link = base_url() . 'newsletter-action/unsubscribe/' . $email['hash'];
                        $readed = '<img src="' . base_url() . 'newsletter-action/readed/' . $history['hash'] . '/' . $email['hash'] . '" alt="" />';
                        $tmp_html = $readed . str_replace('{UNSUBSCRIBE_URL}', $unsubscribe_link, $html_text);
                        $email_service->clear();
                        $email_service->protocol = !empty($config->protocolNewsletter) ? $config->protocolNewsletter : 'mail';
                        $email_service->setFrom($history['from_email'], $history['from_name']);
                        $email_service->setReplyTo($history['reply_to'], $history['from_name']);
                        $email_service->setSubject($history['subject']);
                        $email_service->setMessage($tmp_html);
                        $email_service->setTo($email['email']);
                        $result = $email_service->send();
                        write_file(WRITEPATH . 'logs/newsletter/' . $file, $email['email'] . ': ' . ($result ? 1 : 0) . "\n", 'a');
                        $data = array(
                            'id_newsletter' => $history['id_newsletter'],
                            'id_history' => $history['id'],
                            'id_email' => $email['id'],
                            'status' => $result ? 'sent' : 'error',
                        );
                        $this->newsletterModel->db->table('newsletter_stats')->insert($data);
                    }
                }
            }
            if($status != 'cancelled') {
                $this->newsletterModel->db->table('newsletter_history')->set('status', 'sent')->where('id', $history['id'])->update();
            }
        }
    }
}