<?php

namespace Modules\Newsletter\Models;  
use CodeIgniter\Model;

class NewsletterEmailModel extends Model{

    protected $table = 'newsletter_email';
    
    protected $allowedFields = [
        'id_group',
        'email',
        'name',
        'surname',
        'agreement',
        'hash',
        'hash_valid',
        'active',
        'edited_at',
        'created_at'
    ];
    
    public function addNewsletterAddress($email, $id_lang, $types=array()) {
        helper('text');
        $groups = $this->db->table('newsletter_group')->select('id,type')->whereIn('type', $types)->get()->getResultArray();
        $count = 0;
        $this->db->transStart();
        if(!empty($groups)) {
            $hash = random_string('sha1');
            $count = 1;
            do {
                $is = $this->db->table('newsletter_email')->select('id')->where('hash', $hash)->get()->getRowArray();
                if(!empty($is)) {
                    $hash = random_string('sha1');
                }
                ++$count;
            } while(!empty($is) && $count<=1000);
            foreach($groups as $group) {
                $count += $this->addAddressToGroup($group['id'], $email, $hash);
            }
        }
        if($count) {
            $this->sendWerificatiomMail($email, $hash, $count, $id_lang);
        }
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    public function addAddressToGroup($id_group, $email, $hash) {
        $exists = $this->db->table('newsletter_email')->select('id,agreement')->where('id_group', $id_group)->where('email', $email)->get()->getRowArray();
        if(empty($exists) || (!empty($exists) && !$exists['agreement'])) {
            $data = array(
                'id_group' => $id_group,
                'email' => $email,
                'name' => '',
                'surname' => '',
                'agreement' => 1,
                'hash' => $hash,
                'hash_valid' => date('Y-m-d H:i:s', strtotime('+ 5 days')),
                'active' => 0
            );
            if(!empty($exists)) {
                $this->db->table('newsletter_email')->set($data)->where('id', $exists['id'])->update();
                $id = $exists['id'];
            } else {
                $this->db->table('newsletter_email')->insert($data);
                $id = $this->db->insertID();
            }
            return 1;
        }
        return 0;
    }
    
    public function sendWerificatiomMail($email_address, $hash, $count, $id_lang) {
        $config = new \Config\Email();
        $settingsModel = new \App\Models\SettingsModel();
        $data = array(
            'header' => lang('Newsletter.MailVerification'),
            'settings' => $settingsModel->getSettings($id_lang)
        );
        $url = base_url() . 'newsletter-action/confirm/' . $hash;
        $email = \Config\Services::email();
        $email->attach(base_url() . 'adm/img/tio_logo_6-0-1.jpg');
        $data['cid_logo'] = $email->setAttachmentCID(base_url() . 'adm/img/tio_logo_6-0-1.jpg');
        $email->setFrom($config->fromEmail, $config->fromName);
        $email->setTo($email_address);
        $email->setSubject(lang('Newsletter.MailVerification'));
        $message = view('Modules\Newsletter\Views\user/mails/_order_confirmation', array('url' => $url, 'count' => $count, 'data' => $data));
        $email->setMessage($message);
        $result = $email->send();
    }
    
    public function confirmEmailByHash($hash, $id_lang) {        
        helper('text');
        $result = $this->db->table('newsletter_email')->set('active', 1)->where('hash', $hash)->where('hash_valid >=', date('Y-m-d H:i:s'))->update();
        $mail = $this->db->table('newsletter_email')->select('email')->where('active', 0)->where('hash', $hash)->where('hash_valid <', date('Y-m-d H:i:s'))->get()->getRowArray();
        if(!empty($mail)) {
            $new_hash = random_string('sha1');
            $count = 1;
            do {
                $is = $this->db->table('newsletter_email')->select('id')->where('hash', $new_hash)->get()->getRowArray();
                if(!empty($is)) {
                    $new_hash = random_string('sha1');
                }
                ++$count;
            } while(!empty($is) && $count<=1000);
            $this->db->table('newsletter_email')->set('hash', $new_hash)->set('hash_valid', date('Y-m-d H:i:s', strtotime('+ 5 days')))->where('active', 0)->where('hash', $hash)->where('hash_valid <', date('Y-m-d H:i:s'))->update();
            $this->sendWerificatiomMail($mail['email'], $new_hash, 1, $id_lang);
        }
        $this->regenerateHashByHash($hash);
        return $result;
    }
    
    public function regenerateHashByHash($hash) {   
        helper('text');
        $mails = $this->db->table('newsletter_email')->select('id')->where('hash', $hash)->where('hash_valid >=', date('Y-m-d H:i:s'))->get()->getResultArray();
        if(!empty($mails)) {
            foreach($mails as $mail) {
                $new_hash = random_string('sha1');
                $count = 1;
                do {
                    $is = $this->db->table('newsletter_email')->select('id')->where('hash', $new_hash)->get()->getRowArray();
                    if(!empty($is)) {
                        $new_hash = random_string('sha1');
                    }
                    ++$count;
                } while(!empty($is) && $count<=1000);
                $this->db->table('newsletter_email')->set('hash', $new_hash)->set('hash_valid', null)->where('id', $mail['id'])->update();
            }
        }
    }
    
    public function unsubscribeEmailByHash($hash, $id_lang) {
        $result = $this->db->table('newsletter_email')->set('active', 0)->where('hash', $hash)->update();
        return $result;
    }
}