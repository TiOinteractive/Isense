<?php

namespace Modules\Newsletter\Models;  
use CodeIgniter\Model;

class NewsletterModel extends Model{

    protected $table = 'newsletter';
    
    protected $allowedFields = [
        'subject',
        'from_name',
        'from_email',
        'reply_to',
        'plain_text',
        'html_text',
        'edited_at',
        'created_at'
    ];
    
    public function getGroupById($id, $id_lang) 
    {
        $group = $this->db->table('newsletter_group')->where('id', $id)->get()->getRowArray();
        if(!empty($group)) {
            $group['lang'] = $this->getGroupLang($id);
            if(!empty($group['lang']) && !empty($group['lang'][$id_lang]) && !empty($group['lang'][$id_lang]['name'])) {
                $group['name'] = $group['lang'][$id_lang]['name'];
            } else {
                $group['name'] = '';
            }
        }
        return $group;
    }
    
    public function getGroupLang($id) 
    {
        $langs = array();
        $data = $this->db->table('newsletter_group_lang')->where('id_group', $id)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = array(
                    'name' => $d['name'],
                    'description' => $d['description'],
                    'success_msg' => $d['success_msg'],
                    'error_msg' => $d['error_msg'],
                );
            }
        }
        return $langs;
    }
    
    public function saveGroup($id, $post) 
    {
        if(empty($post)) return false;
        $data = array(
            'type' => $post['type'],
            'publish' => !empty($post['publish']) ? $post['publish'] : 0
        );
        $this->db->transStart();
        if($id) {
            $result = $this->db->table('newsletter_group')->set($data)->where('id', $id)->update();
            $this->id = $id;
        } else {
            $result = $this->db->table('newsletter_group')->insert($data);
            $this->id = $this->db->insertID();
        }
        $this->saveGroupLang($this->id, $post['lang']);
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    private function saveGroupLang($id_group, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_group' => $id_group,
                    'id_lang' => $id_lang,
                    'name' => $lang['name'],
                    'description' => $lang['description'],
                    'success_msg' => $lang['success_msg'],
                    'error_msg' => $lang['error_msg'],
                );
                $slider = $this->db->table('newsletter_group_lang')->select('id')->where('id_group', $id_group)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($slider) && !empty($slider['id'])) {
                    $result = $this->db->table('newsletter_group_lang')->set($data)->where('id_group', $id_group)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('newsletter_group_lang')->insert($data);
                }
            }
        }
    }
    
    public function deleteGroup($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $this->db->table('newsletter_group_lang')->where('id_group', $id)->delete();
        $this->db->table('newsletter_group')->where('id', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    public function getEmailById($id) 
    {
        $email = $this->db->table('newsletter_email')->where('id', $id)->get()->getRowArray();
        return $email;
    }
    
    public function saveEmail($id, $post) 
    {
        helper('text');
        if(empty($post)) return false;
        $data = array(
            'id_group' => $post['id_group'],
            'email' => $post['email'],
            'name' => $post['name'],
            'surname' => $post['surname'],
            'agreement' => !empty($post['agreement']) ? $post['agreement'] : 0,
            'active' => !empty($post['active']) ? $post['active'] : 0
        );
        $this->db->transStart();
        if($id) {
            $result = $this->db->table('newsletter_email')->set($data)->where('id', $id)->update();
            $this->id = $id;
        } else {
            $hash = random_string('sha1');
            $count = 1;
            do {
                $is = $this->db->table('newsletter_email')->select('id')->where('hash', $hash)->get()->getRowArray();
                if(!empty($is)) {
                    $hash = random_string('sha1');
                }
                ++$count;
            } while(!empty($is) && $count<=1000);
            $data['hash'] = $hash;
            $data['hash_valid'] = null;
            $result = $this->db->table('newsletter_email')->insert($data);
            $this->id = $this->db->insertID();
        }
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    public function deleteEmail($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $this->db->table('newsletter_email')->where('id', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    public function getNewsletterById($id) 
    {
        $newsletter = $this->db->table('newsletter')->where('id', $id)->get()->getRowArray();
        $newsletter['name'] = $newsletter['subject'];
        return $newsletter;
    }
    
    public function getNewsletterHistroyById($id) 
    {
        $subquery1 = $this->db->table('newsletter_stats ns')->where('ns.id_history', 'nh.id', false)->where('ns.status', 'sent')->selectCount('ns.id');
        $subquery2 = $this->db->table('newsletter_stats ns')->where('ns.id_history', 'nh.id', false)->where('ns.status', 'readed')->selectCount('ns.id');
        $subquery3 = $this->db->table('newsletter_stats ns')->where('ns.id_history', 'nh.id', false)->where('ns.status', 'error')->selectCount('ns.id');
        $list = $this->db->table('newsletter_history nh')->select('nh.id,nh.groups,nh.subject,nh.from_name,nh.from_email,nh.reply_to,nh.emails,nh.status,nh.hash,nh.created_at,nh.send_time')->selectSubquery($subquery1, 'emails_sent')->selectSubquery($subquery2, 'emails_readed')->selectSubquery($subquery3, 'emails_error')->where('nh.id_newsletter', $id)->orderBy('nh.created_at', 'DESC')->get()->getResultArray();
        if(!empty($list)) {
            foreach($list as $k=>$l) {
                $list[$k]['emails_all'] = $l['emails_sent'] + $l['emails_readed'] + $l['emails_error'];
                if(!empty($l['groups'])) {
                    $list[$k]['groups'] = $this->db->table('newsletter_group ng')->join('newsletter_group_lang ngl', 'ng.id=ngl.id_group')->join('language l', 'l.id=ngl.id_lang')->select('ng.id,ngl.name')->where('l.default', 1)->whereIn('ng.id', explode(',', $l['groups']))->get()->getResultArray();
                }
            }
        }
        return $list;
    }
    
    public function getHistroyById($id) 
    {
        $history = $this->db->table('newsletter_history nh')->select('nh.id,nh.id_newsletter,nh.groups,nh.subject,nh.from_name,nh.from_email,nh.reply_to,nh.emails,nh.status,nh.created_at,nh.html_text')->where('nh.id', $id)->get()->getRowArray();
        return $history;
    }
    
    public function getHistroyStatisticsById($id, $get = array()) {
        $query = $this->db->table('newsletter_stats ns')->join('newsletter_email ne', 'ne.id=ns.id_email')->select('ns.id,ns.status,ns.edited_at,ns.created_at,ne.email')->where('ns.id_history', $id);
        if(!empty($get)) {
            if(!empty($get['email'])) {
                $query->like('ne.email', $get['email']);
            }
            if(!empty($get['status'])) {
                $query->where('ns.status', $get['status']);
            }
        }
        $statistics = $query->get()->getResultArray();
        return $statistics;
    }
    
    public function markAsReaded($newsletter_hash, $email_hash) 
    {
        $email = $this->db->table('newsletter_email')->select('id')->where('hash', $email_hash)->get()->getRowArray();
        $history = $this->db->table('newsletter_history')->select('id')->where('hash', $newsletter_hash)->get()->getRowArray();
        if(!empty($email) && !empty($history)) {
            $this->db->table('newsletter_stats')->set('status', 'readed')->where('id_history', $history['id'])->where('id_email', $email['id'])->update();
        }
    }
    
    public function deleteNewsletter($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $this->db->table('newsletter')->where('id', $id)->delete();
        $this->db->table('newsletter_history')->where('id_newsletter', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
	public function getNewsletterTemplates() {
		$templates = $this->db->table('newsletter_tpls')->select('id,name,modules')->orderBy('name', 'ASC')->get()->getResultArray();
		if(!empty($templates)) {
			foreach($templates as $k=>$t) {
				$templates[$k]['modules'] = json_decode($t['modules'], true);
			}
		}
		return $templates;
	}
}