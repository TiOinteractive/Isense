<?php

namespace Modules\Cinema\Models;  
use CodeIgniter\Model;
use App\Libraries\Link;

class CinemaAnnouncementModel extends Model{

    protected $table = 'cinema_announcement';
    
    protected $allowedFields = [
        'id_movie',
        'id_place',
        'date',
        'edited_at',
        'created_at',
    ];
    
    public function saveAnnouncement($id, $post) 
    {
        $this->statistics = array(
            'added' => array(),
            'exists' => array(),
        );
        $this->db->transStart();
        if(!empty($post['date'])) {
            foreach($post['date'] as $date) {
                if(!empty($post['place'])) {
                    foreach($post['place'] as $id_place) {
                        $data = array(
                            'id_movie' => $post['id_movie'],
                            'id_place' => $id_place,
                            'date' => !empty($date) ? date('Y-m-d', strtotime($date)) : null
                        );
                        $exist = $this->db->table('cinema_announcement')->where($data)->get()->getRowArray();
                        if(empty($exist)) {
                            $this->db->table('cinema_announcement')->insert($data);
                            $this->statistics['added'][] = $data;
                        } else {
                            $this->statistics['exists'][] = $data;
                        }
                    }
                }
            }
        }
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    public function deleteAnnouncement($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $this->where('id', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
}