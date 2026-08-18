<?php

namespace Modules\Users\Libraries;

use Modules\Users\Models\UsersModel;
use App\Models\SettingsModel;

class Users
{
    public function __construct()
    {   
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->usersModel = new UsersModel();
    }
    
    public function index($content, $id_lang, $slug='') 
    {
        
    }
    
    public function slugs($slug, $id_lang, $link) {
        helper(['text', 'filesystem']);
        $session_user = $this->session->get('user');
        $get = $this->request->getGet();
        $post = $this->request->getPost();
        $errors = array();
        $msg = array();
        if (isset($get['log-out'])) {
            $this->session->remove('user');
            return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . $this->global_links['login']);
        } elseif(!empty($session_user) && $slug != 'client_account') {
            return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . $this->global_links['client_account']);
        }
        
        switch($slug) {
            case 'registration':
                if (!empty($post)) {
                    $validation = \Config\Services::validation();
                    $validation->setRules([
                            'email' => 'required|valid_email',
                            'password' => 'required|min_length[8]|regex_match[/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*?[0-9])(?=.*[!^(){}?\[\]<>~$%@#&*+=_-])/]',
                            'rules' => 'required',
                            'field_h' => 'empty',
                        ],
                        [
                            'email' => [
                                'required' => lang('Users.account.Required'),
                                'valid_email' => lang('Users.account.EmailIncorrect'),
                            ],
                            'password' => [
                                'min_length' => lang('Users.account.MinLength'),
                                'required' => lang('Users.account.Required'),
                                'regex_match' => lang('Users.account.RegexMatch'),
                            ],
                            'rules' => [
                                'required' => lang('Users.account.Required'),
                            ],
                            'field_h' => [
                                'empty' => 'field_h',
                            ],
                        ]
                    );
                    if ($validation->withRequest($this->request)->run()) {
                        $config = new \Config\Email();
                        $token = str_shuffle($post['email'] . random_string('alnum', 20));
                        $result = $this->usersModel->registerUser($post, $token);
                        if($result) {
                            $settingsModel = new SettingsModel();
                            $settings = $settingsModel->getSettings($id_lang);
                            $email = \Config\Services::email();
                            $url = base_url() . ($this->locale ? '/' . $this->locale : '') . $this->global_links['client_account'] . '/?token=' . $token;
                            $data = array(
                                'header' => str_replace('{SHOPNAME}', $settings['company_short_name'], lang('Users.account.EmailTopicActivate')), 
                                'post' => $post, 
                                'settings' => $settings, 
                                'url' => $url
                                
                            );
                            if (!empty($settings['logo']['path'])) {
                                $email->attach(base_url() . 'foto/r/300/100/' . $settings['logo']['path']);
                                $data['cid_logo'] = $email->setAttachmentCID(base_url() . ($this->locale ? '/' . $this->locale : '') . 'foto/r/300/100/' . $settings['logo']['path']);
                            }
                            $message = view('Modules\Users\Views\user/mails/account_cofirm', $data);
                            $email->setFrom($config->fromEmail, $settings['company_name']);
                            $email->setTo($post['email']);
                            $email->setSubject($data['header']);
                            $email->setMessage($message);
                            $mail_result = $email->send();
                        }
                        $msg['result'] = array(
                            'message' => lang('Users.account.CheckMailAndActivateAccount'),
                            'status' => 'success',
                        );
                        $this->session->setFlashdata('user_flashdata', $msg);
                        return redirect()->to('/' . $this->global_links['registration']);
                    } else {
                        $errors = $validation->getErrors();
                    }
                }
                return array(
                    'template' => 'registration.php',
                    'errors' => $errors,
                    'post' => $post,
                    'flashdata' => $this->session->getFlashdata('user_flashdata')
                );
                break;
            case 'login':
                if (!empty($post['signin'])) {
                    $validation = \Config\Services::validation();
                    $validation->setRules([
                            'email' => 'required|valid_email',
                            'password' => 'required',
                            'field_h' => 'empty',
                        ],
                        [
                            'email' => [
                                'required' => lang('Users.account.Required'),
                                'valid_email' => lang('Users.account.EmailIncorrect'),
                            ],
                            'password' => [
                                'required' => lang('Users.account.Required'),
                            ],
                            'field_h' => [
                                'empty' => 'field_h',
                            ],
                        ]
                    );
                    if ($validation->withRequest($this->request)->run()) {
                        $user = $this->usersModel->signIn($post['email'], $post['password']);
                        if (!empty($user)) {
                            $this->session->set('user', $user);
                            return redirect()->to(!empty($post['return']) ? $post['return'] : ($this->locale ? '/' . $this->locale : '') . '/' . $this->global_links['client_account']);
                        } else {
                            $errors['result'] = lang('Users.account.BadEmailOrPassword');
                        }
                    } else {
                        $errors = $validation->getErrors();
                    }
                } 
                $path_info = parse_url($this->request->getUserAgent()->getReferrer());
                return array(
                    'template' => 'login.php',
                    'errors' => $errors,
                    'flashdata' => $this->session->getFlashdata('user_flashdata'),
                    'return' => !empty($post) ? $post['return'] : (!empty($path_info) && !empty($path_info['path']) ? $path_info['path'] : ''),
                );
                break;
            case 'remind_password':
                if(!empty($get['token'])) {
                    $check_token = $this->usersModel->checkTokenRemindPassword($get['token']);
                    if(!empty($check_token)) {
                        if(!empty($post)) {
                            $validation = \Config\Services::validation();
                            $validation->setRules([
                                    'password' => 'required|matches[password2]',
                                    'password2' => 'required',
                                    'field_h' => 'empty',
                                ],
                                [
                                    'password' => [
                                        'required' => lang('Users.account.Required'),
                                        'matches' => lang('Users.account.PasswordNotMatch'),
                                    ],
                                    'password2' => [
                                        'required' => lang('Users.account.Required'),
                                    ],
                                    'field_h' => [
                                        'empty' => 'field_h',
                                    ],
                                ]
                            );
                            if ($validation->withRequest($this->request)->run()) {
                                $result = $this->usersModel->changePassword($check_token['id'], $post['password']);
                                if($result) {
                                    $msg['result'] = array(
                                        'message' => lang('Users.account.PasswordHasBeenChanged'),
                                        'status' => 'success',
                                    );
                                    $this->session->setFlashdata('user_flashdata', $msg);
                                    return redirect()->to('/' . $this->global_links['login']);
                                } else {
                                    $errors['result'] = lang('Users.account.ErrorWhilePasswordChange');
                                }
                            } else {
                                $errors = $validation->getErrors();
                            }
                        }
                    } else {
                        $errors['result'] = lang('Users.account.BadToken');
                    }
                    return array(
                        'template' => 'new_password.php',
                        'errors' => $errors,
                        'flashdata' => $this->session->getFlashdata('user_flashdata'),
                        'check_token' => $check_token,
                    );
                } elseif(!empty($post)) {
                    $validation = \Config\Services::validation();
                    $validation->setRules([
                            'email' => 'required|valid_email',
                            'field_h' => 'empty',
                        ],
                        [
                            'email' => [
                                'required' => lang('Users.account.Required'),
                                'valid_email' => lang('Users.account.EmailIncorrect'),
                            ],
                            'field_h' => [
                                'empty' => 'field_h',
                            ],
                        ]
                    );
                    if ($validation->withRequest($this->request)->run()) {
                        $user = $this->usersModel->checkUserByEmail($post['email']);
                        if (!empty($user)) {
                            $token = str_shuffle($user['id'] . random_string('alnum', 20));
                            $this->usersModel->updateUserSecret($user['id'], $token);
                            $settingsModel = new SettingsModel();
                            $settings = $settingsModel->getSettings($id_lang);
                            $email = \Config\Services::email();
                            $url = base_url() . ($this->locale ? '/' . $this->locale : '') . $this->global_links['remind_password'] . '/?token=' . $token;
                            $data = array(
                                'header' => str_replace('{SHOPNAME}', $settings['company_short_name'], lang('Users.account.EmailTopicPasswordRemind')), 
                                'post' => $post, 
                                'settings' => $settings, 
                                'url' => $url

                            );
                            if (!empty($settings['logo']['path'])) {
                                $email->attach(base_url() . 'foto/r/300/100/' . $settings['logo']['path']);
                                $data['cid_logo'] = $email->setAttachmentCID(base_url() . ($this->locale ? '/' . $this->locale : '') . 'foto/r/300/100/' . $settings['logo']['path']);
                            }
                            $message = view('Modules\Users\Views\user/mails/password_remind', $data);
                            $email->setTo($post['email']);
                            $email->setSubject($data['header']);
                            $email->setMessage($message);
                            $mail_result = $email->send();
                        }
                        $msg['result'] = array(
                            'message' => lang('Users.account.PasswordRemindSent'),
                            'status' => 'success',
                        );
                        $this->session->setFlashdata('user_flashdata', $msg);
                        return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . $this->global_links['remind_password']);
                    } else {
                        $errors = $validation->getErrors();
                    }
                    
                }
                return array(
                    'template' => 'password_reminder.php',
                    'flashdata' => $this->session->getFlashdata('user_flashdata'),
                    'errors' => $errors,
                );
                break;
            case 'client_account':
            default:
                if(!empty($get['token'])) {
                    $activate_user = $this->usersModel->activateUser($get['token']);
                    if ($activate_user) {
                        $msg['result'] = array(
                            'message' => lang('Users.account.AccountActivated'),
                            'status' => 'success',
                        );
                    } else {
                        $msg['result'] = array(
                            'message' => lang('Users.account.ActivateLinkIncorrect'),
                            'status' => 'error',
                        );
                    }
                    $this->session->setFlashdata('user_flashdata', $msg);
                    return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . $this->global_links['login']);
                } elseif (empty($session_user)) {
                    return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . $this->global_links['login']);
                } else {
                    $path = !empty($link['get']) ? implode('/', $link['get']) : '';
                    switch($path) {
                        case 'comments':
                            return array(
                                'template' => 'account_comments.php',
                                'link' => $path,
                                'flashdata' => $this->session->getFlashdata('user_flashdata'),
                                'errors' => $errors,
                            );
                            break;
                        case 'copy':
                            if(!empty($get['file']) && file_exists(WRITEPATH . 'uploads/users/' . $session_user['id'] . '/copy/' . $get['file'])) {
                                return $this->response->download(WRITEPATH . 'uploads/users/' . $session_user['id'] . '/copy/' . $get['file'], null);
                            }
                            $files = array();
                            if(is_dir(WRITEPATH . 'uploads/users/' . $session_user['id'] . '/copy')) {
                                $directory_map = directory_map(WRITEPATH . 'uploads/users/' . $session_user['id'] . '/copy');
                                if(!empty($directory_map)) {
                                    foreach($directory_map as $file) {
                                        $file_time = filemtime(WRITEPATH . 'uploads/users/' . $session_user['id'] . '/copy/' . $file);
                                        if($file_time < strtotime('- 7 days')) {
                                            unlink(WRITEPATH . 'uploads/users/' . $session_user['id'] . '/copy/' . $file);
                                        } else {
                                            $file_info = pathinfo(WRITEPATH . 'uploads/users/' . $session_user['id'] . '/copy/' . $file);
                                            $files[$file_time] = array(
                                                'name' => date('Y-m-d H:i:s', $file_time),
                                                'link' => ($this->locale ? '/' . $this->locale : '') . '/' . $this->global_links['client_account'] . '/g/copy?file=' . $file,
                                                'extension' => $file_info['extension']
                                            );
                                        }
                                    }
                                    ksort($files);
                                }
                            }
                            if(!empty($post)) {
                                $user = $this->usersModel->select('id,mail,name,surname,nick,google_id,fb_id,newsletter,edited_at,created_at')->where('id', $session_user['id'])->first();
                                $time = time();
                                if(!empty($post['type'])) {
                                    if(!is_dir(WRITEPATH . 'uploads/users')) {
                                        mkdir(WRITEPATH . 'uploads/users');
                                    }
                                    if(!is_dir(WRITEPATH . 'uploads/users/' . $session_user['id'])) {
                                        mkdir(WRITEPATH . 'uploads/users/' . $session_user['id']);
                                    }
                                    if(!is_dir(WRITEPATH . 'uploads/users/' . $session_user['id'] . '/copy')) {
                                        mkdir(WRITEPATH . 'uploads/users/' . $session_user['id'] . '/copy');
                                    }
                                    switch($post['type']) {
                                        case 'html':
                                            $file = 'users/' . $session_user['id'] . '/copy/' . $session_user['id'] . '_' . $time . '.' . $post['type'];
                                            $content = view('Modules\Users\Views\user\slugs\data_copy', array('user' => $user));
                                            file_put_contents(WRITEPATH . 'uploads/' . $file, $content);
                                            break;
                                        case 'csv':
                                            $file = 'users/' . $session_user['id'] . '/copy/' . $session_user['id'] . '_' . time() . '.zip';
                                            $zip = new \ZipArchive;
                                            $zip->open(WRITEPATH . 'uploads/' . $file, \ZIPARCHIVE::CREATE);
                                            $tmps = array();
                                            
                                            $tmp_plik = 'users/' . $session_user['id'] . '/copy/tmp_' . $session_user['id'] . '_' . $time . '_user.' . $post['type'];
                                            $tmps[] = $tmp_plik;
                                            $delimiter = ';';
                                            $f = fopen(WRITEPATH . 'uploads/' . $tmp_plik, 'w');
                                            fputcsv($f, array_keys($user), $delimiter);
                                            fputcsv($f, $user, $delimiter);
                                            fclose($f);
                                            if (file_exists(WRITEPATH . 'uploads/' . $tmp_plik)) {
                                                $zip->addFile(WRITEPATH . 'uploads/' . $tmp_plik, 'data_user.' . $post['type']);
                                            }
                                                
                                            $zip->close();
                                            if (!empty($tmps))
                                                foreach ($tmps as $t) {
                                                    if (file_exists(WRITEPATH . 'uploads/' . $t))
                                                        unlink(WRITEPATH . 'uploads/' . $t);
                                                }
                                            break;
                                    }
                                }
                                return redirect()->to('/' . $this->global_links['client_account'] . '/g/' . $path);
                            }
                            return array(
                                'template' => 'account_copy.php',
                                'link' => $path,
                                'flashdata' => $this->session->getFlashdata('user_flashdata'),
                                'errors' => $errors,
                                'files' => $files,
                            );
                            break;
                        case 'del':
                            if(!empty($post)) {
                                if($this->usersModel->checkPassword($session_user['id'], $post['password'])) {
                                    $validation = \Config\Services::validation();
                                    $validation->setRules(
                                        [
                                            'accept' => 'required',
                                        ],
                                        [
                                            'accept' => [
                                                'required' => lang('Users.account.Required'),
                                            ],
                                        ]
                                    );
                                    if ($validation->withRequest($this->request)->run()) {
                                        $result = $this->usersModel->deleteAccount($session_user['id']);
                                        if($result) {
                                            $db_newsletter = \Config\Database::connect('newsletter');
                                            $db_newsletter->table('adresy_mail')->where('mail', $session_user['mail'])->where('id_grupa', 8)->delete();
                                            $this->session->remove('user');
                                            $msg['result'] = array(
                                                'message' => lang('Users.account.AccountDeleted'),
                                                'status' => 'success',
                                            );
                                            $this->session->setFlashdata('user_flashdata', $msg);
                                            return redirect()->to('/' . $this->global_links['login']);
                                        } else {
                                            $errors['result'] = lang('Users.account.ErrorWhileDeletingAccount');
                                        }
                                    } else {
                                        $errors = $validation->getErrors();
                                    }
                                } else {
                                    $errors['password'] = lang('Users.account.BadPassword');
                                }
                            }
                            return array(
                                'template' => 'account_delete.php',
                                'link' => $path,
                                'flashdata' => $this->session->getFlashdata('user_flashdata'),
                                'errors' => $errors,
                            );
                            break;
                        case 'pass':
                            if(!empty($post)) {
                                if($this->usersModel->checkPassword($session_user['id'], $post['password'])) {
                                    $validation = \Config\Services::validation();
                                    $validation->setRules([
                                            'newpassword' => 'required|matches[newpassword2]',
                                            'newpassword2' => 'required',
                                        ],
                                        [
                                            'newpassword' => [
                                                'required' => lang('Users.account.Required'),
                                                'matches' => lang('Users.account.PasswordNotMatch'),
                                            ],
                                            'newpassword2' => [
                                                'required' => lang('Users.account.Required'),
                                            ],
                                        ]
                                    );
                                    if ($validation->withRequest($this->request)->run()) {
                                        $result = $this->usersModel->changePassword($session_user['id'], $post['newpassword']);
                                        if($result) {
                                            $msg['result'] = array(
                                                'message' => lang('Users.account.PasswordHasBeenChanged'),
                                                'status' => 'success',
                                            );
                                            $this->session->setFlashdata('user_flashdata', $msg);
                                            return redirect()->to('/' . $this->global_links['client_account'] . '/g/' . $path);
                                        } else {
                                            $errors['result'] = lang('Users.account.ErrorWhilePasswordChange');
                                        }
                                    } else {
                                        $errors = $validation->getErrors();
                                    }
                                } else {
                                    $errors['password'] = lang('Users.account.BadPassword');
                                }
                            }
                            return array(
                                'template' => 'account_password.php',
                                'link' => $path,
                                'flashdata' => $this->session->getFlashdata('user_flashdata'),
                                'errors' => $errors,
                            );
                            break;
                        default:
                            $result = false;
                            $user = $this->usersModel->getUser($session_user['id']);
                            /* external DB */$db_newsletter = \Config\Database::connect('newsletter');
                            /* external DB */$user['newsletter'] = !empty($db_newsletter->table('adresy_mail')->select('id')->where('mail', $user['mail'])->where('id_grupa', 8)->where('id_status', 1)->get()->getRowArray());
                            if(!empty($user)) {
                                if (!empty($post)) {
                                    $this->usersModel->db->transStart();
                                    $result = $this->usersModel->updateUser($session_user['id'], $post);
                                    if($result) {
                                        /* external DB start */
                                        $exists = $db_newsletter->table('adresy_mail')->select('id,id_status')->where('mail', $user['mail'])->where('id_grupa', 8)->get()->getRowArray();
                                        if(!empty($exists)) {
                                            if(!empty($post['newsletter']) && empty($user['newsletter'])) {
                                                $db_newsletter->table('adresy_mail')->set(array('id_status' => 1, 'source' => 'resinet_newsletter_account', 'data_statusu' => date('Y-m-d H:i:s')))->where('id', $exists['id'])->update();
                                            } elseif(empty($post['newsletter'])) {
                                                $db_newsletter->table('adresy_mail')->set(array('id_status' => 6, 'source' => 'resinet_newsletter_account', 'data_statusu' => date('Y-m-d H:i:s')))->where('id', $exists['id'])->update();
                                            }
                                        } elseif(!empty($post['newsletter'])) {
                                            $hash = random_string('sha1');
                                            $count = 1;
                                            do {
                                                $is = $db_newsletter->table('adresy_mail')->select('id')->where('token', $hash)->get()->getRowArray();
                                                if (!empty($is)) {
                                                    $hash = random_string('sha1');
                                                }
                                                ++$count;
                                            } while (!empty($is) && $count <= 1000);
                                            $data = array(
                                                'mail' => $user['mail'],
                                                'typ' => 'html',
                                                'id_grupa' => 8,
                                                'nazwa' => '',
                                                'komentarz' => '',
                                                'imie' => '',
                                                'nazwisko' => '',
                                                'stanowisko' => '',
                                                'firma' => '',
                                                'plec' => '',
                                                'miasto' => '',
                                                'token' => $hash,
                                                'token_expire' => null,
                                                'id_status' => 1,
                                                'data' => date('Y-m-d'),
                                                'urodzony' => '',
                                                'zmien_status' => '',
                                                'source' => 'resinet_newsletter_account',
                                                'data_statusu' => date('Y-m-d H:i:s')
                                            );
                                            $db_newsletter->table('adresy_mail')->insert($data);
                                        }
                                        /* external DB end */
                                        
                                        $exists = $this->usersModel->db->table('newsletter_email ne')->join('newsletter_group ng', 'ne.id_group=ng.id')->select('ne.id,ne.active,ne.agreement')->where('ne.email', $user['mail'])->where('ng.type', 'newsletter')->get()->getResultArray();
                                        if(!empty($exists)) {
                                            foreach($exists as $k=>$e) {
                                                if(!empty($post['newsletter']) && empty($user['newsletter'])) {
                                                    $this->usersModel->db->table('newsletter_email')->set(array('active' => 1, 'source' => 'resinet_newsletter_account'))->where('id', $e['id'])->update();
                                                } elseif(empty($post['newsletter'])) {
                                                    $this->usersModel->db->table('newsletter_email')->set(array('active' => 0, 'source' => 'resinet_newsletter_account',))->where('id', $e['id'])->update();
                                                }
                                            }
                                        } elseif(!empty($post['newsletter'])) {
                                            $groups = $this->usersModel->db->table('newsletter_group')->select('id')->where('type', 'newsletter')->get()->getResultArray();
                                            if(!empty($groups)) {
                                                foreach($groups as $g=>$group) {
                                                    $hash = random_string('sha1');
                                                    $count = 1;
                                                    do {
                                                        $is = $this->usersModel->db->table('newsletter_email')->select('id')->where('hash', $hash)->get()->getRowArray();
                                                        if (!empty($is)) {
                                                            $hash = random_string('sha1');
                                                        }
                                                        ++$count;
                                                    } while (!empty($is) && $count <= 1000);
                                                    $data = array(
                                                        'id_grup' => $group['id'],
                                                        'email' => $user['mail'],
                                                        'name' => '',
                                                        'surname' => '',
                                                        'agreement' => 1,
                                                        'hash' => $hash,
                                                        'hash_valid' => null,
                                                        'active' => 1,
                                                        'source' => 'resinet_newsletter_account'
                                                    );
                                                    $this->usersModel->db->table('newsletter_email')->insert($data);
                                                }
                                            }
                                        }
                                    }
                                    $this->usersModel->db->transComplete();
                                    $result = $this->usersModel->db->transStatus();
                                    if($result) {
                                        $msg['result'] = array(
                                            'message' => lang('Users.account.UserDataUpdated'),
                                            'status' => 'success',
                                        );
                                        $session_user['name'] = $post['name'];
                                        $session_user['surname'] = $post['surname'];
                                        $session_user['nick'] = $post['nick'];
                                        $this->session->set('user', $session_user);
                                        $this->session->setFlashdata('user_flashdata', $msg);
                                        return redirect()->to('/' . $this->global_links['client_account']);
                                    } else {
                                        $errors['result'] = lang('Users.account.ErrorWhileUpdatingUserData');
                                    }
                                }
                                
                            }
                            return array(
                                'template' => 'account.php',
                                'user' => $user,
                                'link' => $path,
                                'flashdata' => $this->session->getFlashdata('user_flashdata'),
                                'errors' => $errors,
                            );
                            break;
                    }
                }
                break;
        }
    }

    public function assets($slug = '', $template = '', $id_news=0, $data=array()) {
        $assets = array(
            'js' => array(),
            'css' => array(),
            'js_ready' => array(),
            'css_footer' => array(),
        );
        $assets['css_footer'][] = '/assets/css/jquery-confirm.min.css';
        $assets['js'][] = '/assets/js/jquery-confirm.min.js';
        $assets['js'][] = '/assets/js/users.js';
        $assets['js'][] = 'https://apis.google.com/js/platform.js';
        return $assets;
    }
    
    public function getBreadcrambs($locale, $id_lang, $slug, $link) {
        $breadrcrumbs = array(array(
            'name' => lang('Users.account.UserAccount'),
            'link' => ($locale ? '/' . $locale : '') . '/' . $this->global_links['client_account']
        ));
        $path = !empty($link['get']) ? implode('/', $link['get']) : '';
        switch($path) {
            case 'pass': $breadrcrumbs[] = array(
                    'name' => lang('Users.account.ChangePassword'),
                    'link' => ($locale ? '/' . $locale : '') . '/' . $this->global_links['client_account'] . '/g/' . $path
                );
                break;
            case 'del': $breadrcrumbs[] = array(
                    'name' => lang('Users.account.DeleteAccount'),
                    'link' => ($locale ? '/' . $locale : '') . '/' . $this->global_links['client_account'] . '/g/' . $path
                );
                break;
            case 'copy': $breadrcrumbs[] = array(
                    'name' => lang('Users.account.DataCopy'),
                    'link' => ($locale ? '/' . $locale : '') . '/' . $this->global_links['client_account'] . '/g/' . $path
                );
                break;
        }
        return $breadrcrumbs;
    }
    
    public function ajaxSlugs($post, $id_lang, $slug, $link) {
        $get = $this->request->getGet();
        $session_user = $this->session->get('user');
        if(empty($session_user)) {
            if(empty($post['action'])) {
                $post['action'] = '';
            }
            switch($post['action']) {
                case 'google':
                    $jwt0 = explode('.', $post['credential']);
                    $jwt = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $post['credential'])[1]))), true);
                    $arrContextOptions = array(
                        "ssl" => array(
                            "verify_peer" => false,
                            "verify_peer_name" => false,
                        ),
                    );
                    //var_dump('https://oauth2.googleapis.com/tokeninfo?id_token=' . $jwt0[0]);
                    //$response = file_get_contents('https://oauth2.googleapis.com/tokeninfo?id_token=' . $jwt0[0], false, stream_context_create($arrContextOptions));
                    //var_dump($response);
                    //$obj = json_decode($response, true);
                    

                    
                    
                    break;
                case 'facebook':
                    $result = false;
                    if(!empty($post) && !empty($post['access_token']) && !empty($post['user'])) {
                        $arrContextOptions = array(
                            "ssl" => array(
                                "verify_peer" => false,
                                "verify_peer_name" => false,
                            ),
                        );
                        $response = file_get_contents('https://graph.facebook.com/debug_token?input_token=' . $post['access_token'] . '&access_token=' . getenv('social.facebookAppId') . '|' . getenv('social.facebookAppSecret'), false, stream_context_create($arrContextOptions));
                        $obj = json_decode($response, true);
                        if(!empty($obj) && !empty($obj['data']) && !empty($obj['data']['is_valid'])) {
                            $user = $this->usersModel->checkUserByEmail($post['user']['email']);
                            if(empty($user)) {
                                $data = array(
                                    'email' => $post['user']['email'],
                                    'name' => $post['user']['first_name'],
                                    'surname' => $post['user']['last_name'],
                                    'password' => '',
                                    'newsletter' => false,
                                    'fb_id' => $post['user']['id'],
                                );
                                $result = $this->usersModel->registerUser($data, '', 1);
                                if($result) {
                                    $user = $this->usersModel->checkUserByEmail($data['email']);
                                }
                            }
                            if(!empty($user)) {
                                if(empty($user['name']) || empty($user['surname']) || empty($user['fb_id'])) {
                                    $data = array(
                                        'name' => $post['user']['first_name'],
                                        'surname' => $post['user']['last_name'],
                                        'nick' => $user['nick'],
                                        'newsletter' => $user['newsletter'],
                                        'fb_id' => $post['user']['id'],
                                    );
                                    $this->usersModel->updateUser($user['id'], $data);
                                    $user['name'] = $data['name'];
                                    $user['surname'] = $data['surname'];
                                }
                                $this->session->set('user', $user);
                                $result = true;
                            }
                        }
                    }
                    $response = array(
                        'status' => true,
                        'result' => $result,
                    );
                    return $this->response->setJSON($response);
                    break;
                case 'form':
                    $html = view('\Modules\Users\Views\user/slugs/login_form', array('id_lang' => $id_lang, 'locale' => $this->locale, 'global_links' => $this->global_links, 'data' => array()));
                    $response = array(
                        'status' => true,
                        'action' => $post['action'],
                        'html' => base64_encode(urlencode($html)),
                        'lang' => array(
                            'title' => lang('Users.account.SignIn'),
                            'close' => lang('Users.account.Close'),
                        )
                    );
                    return $this->response->setJSON($response);
                    break;
                default:
                    $result = false;
                    $errors = array();
                    if (!empty($post)) {
                        $validation = \Config\Services::validation();
                        $validation->setRules([
                                'email' => 'required|valid_email',
                                'password' => 'required',
                                'field_h' => 'empty',
                            ],
                            [
                                'email' => [
                                    'required' => lang('Users.account.Required'),
                                    'valid_email' => lang('Users.account.EmailIncorrect'),
                                ],
                                'password' => [
                                    'required' => lang('Users.account.Required'),
                                ],
                                'field_h' => [
                                    'empty' => 'field_h',
                                ],
                            ]
                        );
                        if ($validation->withRequest($this->request)->run()) {
                            $user = $this->usersModel->signIn($post['email'], $post['password']);
                            if (!empty($user)) {
                                $this->session->set('user', $user);
                                $result = true;
                            } else {
                                $errors['result'] = lang('Users.account.BadEmailOrPassword');
                            }
                        } else {
                            $errors = $validation->getErrors();
                        }
                    }
                    $response = array(
                        'status' => true,
                        'action' => $post['action'],
                        'errors' => $errors,
                        'result' => $result,
                    );
                    return $this->response->setJSON($response);
                    break;
            }
            
            
        } elseif(!empty($session_user) && !empty($get['file']) && file_exists(WRITEPATH . 'uploads/users/' . $session_user['id'] . '/copy/' . $get['file'])) {
            $response = array(
                'status' => true,
                'html' => base64_encode(urlencode(file_get_contents(WRITEPATH . 'uploads/users/' . $session_user['id'] . '/copy/' . $get['file'])))
            );
            return $this->response->setJSON($response);
        }
    }
    
    
}