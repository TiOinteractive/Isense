<?php

namespace Modules\Comments\Models;  
use CodeIgniter\Model;

class CommentsModel extends Model {

    protected $table = 'comments';
    
    protected $allowedFields = [
        'id_element',
        'id_parent',
        'id_lang',
        'id_user',
        'id_module',
        'id_link',
        'ip',
        'nick',
        'content',
        'publish',
        'slug',
        'status',
        'id_page',
        'edited_at',
        'created_at',
    ];
    
    
    public function saveComment($comment, $id_lang, $id_link, $id_module, $ip, $session_user, $id_parent=0, $slug='', $id_page=0) {
        
        $data = array(
            'id_element' => 0,
            'id_parent' => $id_parent,
            'id_lang' => $id_lang,
            'id_user' => !empty($session_user) ? $session_user['id'] : 0,
            'id_module' => $id_module,
            'id_link' => $id_link,
            'ip' => $ip,
            'nick' => !empty($session_user) ? $session_user['nick'] : '',
            'content' => $comment,
            'publish' => 1,
            'status' => 'new',
            'slug' => $slug,
            'id_page' => $id_page,
        );
        return $this->insert($data);
    }
    
    public function countCommentsByLinkId($id_link, $id_parent=0) {
        $count = $this->db->table('comments c')->where('c.id_link', $id_link)->where('c.id_parent', $id_parent)->countAllResults();
        return $count;
    }
    
    public function getCommentsByLinkId($id_link, $id_parent=0, $details=false, $limit=0, $count=0) {
        $query = $this->db->table('comments c')->select('c.id,c.id_parent,c.nick,c.content,c.status,c.created_at,c.id_user')->where('c.id_link', $id_link)->where('c.id_parent', $id_parent);
        if($details) {
            $query->join('users u', 'u.id=c.id_user', 'left')->select('u.name,u.surname,u.mail,u.comments as user_comments');
        }
        if($limit) {
            $query->limit($limit, $count);
        }
        $comments = $query->orderBy('c.created_at', 'DESC')->get()->getResultArray();
        if(!empty($comments)) {
            foreach($comments as $k=>$comment) {
                $comments[$k]['comments'] = $this->getCommentsByLinkId($id_link, $comment['id'], $details);
            }
        }
        return $comments;
    }
    
    public function deleteComment($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $this->where('id', $id)->set('status', 'removed')->update();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    public function blockUser($id_user) {
        if(empty($id_user)) return false;
        $this->db->transStart();
        $this->db->table('users')->set('comments', 0)->where('id', $id_user)->update();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    public function changeCommenStatus($id, $status) {
        if(empty($id) || empty($status)) return false;
        return $this->where('id', $id)->set('status', $status)->update();
    }
}