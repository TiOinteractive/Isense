<?php

namespace Modules\Users\Models;

use CodeIgniter\Model;
use App\Libraries\Link;
use \App\Validation\CustomRules;

class UsersModel extends Model {

    protected $table = 'users';
    protected $allowedFields = [
        'mail',
        'password',
        'id',
        'created_at',
        'name',
        'surname',
        'nick',
        'city',
        'phone',
        'google_id',
        'fb_id',
        'active',
        'secret',
        'secret_valid_to',
    ];

    public function deleteUser($id) {
        if (empty($id))
            return false;
        $this->db->transStart();
        $this->where('id', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }

    public function signIn($login, $password) {
        $data = array();
        $user = $this->select('id,mail,name,surname,nick,password,comments')->where('mail', $login)->where('active', 1)->first();
        if ($user) {
            if (password_verify($password, $user['password'])) {
                unset($user['password']);
                return $user;
            }
        }
        return null;
    }

    public function registerUser($post, $token, $active=0) {
        if (!empty($post)) {
            $check_email = $this->where('mail', $post['email'])->countAllResults();
            if (!empty($check_email)) {
                return false;
            } else {
                $data = array(
                    'mail' => $post['email'],
                    'name' => $post['name'],
                    'surname' => $post['surname'],
                    'nick' => !empty($post['nick']) ? $post['nick'] : substr($post['email'], 0, 1) . '***' . substr($post['email'], -1),
                    'secret' => $token,
                    'secret_valid_to' => $token ? date("Y-m-d H:i:s", strtotime("+3 hours")) : null,
                    'password' => !empty($post['password']) ? password_hash($post['password'], PASSWORD_BCRYPT) : '',
                    'active' => $active,
                    'fb_id' => !empty($post['fb_id']) ? $post['fb_id'] : null,
                );
                $result = $this->db->table('users')->insert($data);
                if($result && !empty($post['newsletter'])) {
                    $db_newsletter = \Config\Database::connect('newsletter');
                    $exists = $db_newsletter->table('adresy_mail')->select('id,id_status')->where('mail', $post['email'])->where('id_grupa', 8)->get()->getRowArray();
                    if (empty($exists) || (!empty($exists) && $exists['id_status'] != 1)) {
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
                            'source' => 'resinet_newsletter_registration',
                        );
                        if (!empty($exists)) {
                            $db_newsletter->table('adresy_mail')->set($data)->where('id', $exists['id'])->update();
                        } else {
                            $db_newsletter->table('adresy_mail')->insert($data);
                        }
                    }
                }
                return $result;
            }
        }
    }
    
    function activateUser($token) {
        $user = $this->select('id,mail')->where('secret', $token)->where('secret_valid_to >=', date("Y-m-d H:i:s"))->first();
        if (!empty($user)) {
            $data = array(
                'active' => 1,
                'secret' => '',
                'secret_valid_to' => null,
            );
            $result = $this->set($data)->where('id', $user['id'])->update();
            
            $db_newsletter = \Config\Database::connect('newsletter');
            $exists = $db_newsletter->table('adresy_mail')->select('id,id_status')->where('mail', $user['mail'])->where('id_grupa', 8)->get()->getRowArray();
            if(!empty($exists) && $exists['id_status'] != 1) {
                $db_newsletter->table('adresy_mail')->set(array('id_status' => 1, 'data_statusu' => date('Y-m-d H:i:s')))->where('id', $exists['id'])->update();
            }
            /*
            $newsletter_group = $this->db->table('newsletter_group')->select('id')->where('type', 'newsletter')->where('publish', 1)->get()->getRowArray();
            if (!empty($user['newsletter'])) {
                $check_email = $this->db->table('newsletter_email')->where('id_group', $newsletter_group['id'])->where('email', $user['mail'])->countAllResults();
                if (empty($check_email)) {
                    $hash = random_string('sha1');
                    $count = 1;
                    do {
                        $is = $this->db->table('newsletter_email')->select('id')->where('hash', $hash)->get()->getRowArray();
                        if (!empty($is)) {
                            $hash = random_string('sha1');
                        }
                        ++$count;
                    } while (!empty($is) && $count <= 1000);
                    $result = $this->db->table('newsletter_email')->insert(array('id_group' => $newsletter_group['id'], 'email' => $user['mail'], 'hash' => $hash, 'agreement' => 1, 'active' => 1));
                }
            }
            */
            return $result;
        } else {
            return false;
        }
    }
    
    public function checkUserByEmail($email) {
        $user = $this->select('id,mail,name,surname,nick,comments,fb_id')->where('mail', $email)->first();
        return $user;
    }
    
    public function updateUserSecret($id, $secret) {
        $data = array(
            'secret' => $secret,
            'secret_valid_to' => date("Y-m-d H:i:s", strtotime("+3 hours")),
        );
        return $this->set($data)->where('id', $id)->update();
    }
    
    public function changePassword($id, $password) {
        $data = array(
            'secret' => '',
            'secret_valid_to' => null,
            'password' => password_hash($password, PASSWORD_BCRYPT),
        );
        return $this->set($data)->where('id', $id)->update();
    }
    
    public function remindPassword($email, $token) {
        helper(['text']);
        $user = $this->select('id,mail,name,surname')->where('mail', $email)->first();
        if(!empty($user)) {
            $data = array(
                'secret' => $token,
                'secret_valid_to' => date("Y-m-d H:i:s", strtotime("+3 hours")),
            );
            $this->set($data)->where('id', $user['id'])->update();
        }
        return $user;
    }

    public function checkTokenRemindPassword($token) {
        $data = $this->select('id,secret_valid_to,secret')->where('secret', $token)->where('secret_valid_to >=', date("Y-m-d H:i:s"))->first();
        return $data;
    }
    
    public function getUser($id) {
        $user = $this->where('id', $id)->first();
        return $user;
    }
    
    public function checkPassword($id, $password) {
        $user = $this->select('id,password')->where('id', $id)->first();
        if ($user && password_verify($password, $user['password'])) {
            return true;
        }
        return false;
    }
    
    public function updateUser($id, $post) {
        $data = array(
            'name' => $post['name'],
            'surname' => $post['surname'],
            'nick' => !empty($post['nick']) ? $post['nick'] : substr($post['mail'], 0, 1) . '...' . substr($post['mail'], -1),
            'fb_id' => !empty($post['fb_id']) ? $post['fb_id'] : null,
        );
        return $this->set($data)->where('id', $id)->update();
    }
    
    public function deleteAccount($id) {
        if(empty($id)) return false;
        $this->db->transStart();
        $this->db->table('comments')->where('id_user', $id)->delete();
        $this->db->table('flavors_comments')->where('id_user', $id)->delete();
        $this->db->table('users')->where('id', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
}
