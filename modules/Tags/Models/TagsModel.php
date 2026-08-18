<?php

namespace Modules\Tags\Models;

use CodeIgniter\Model;
use App\Libraries\Link;

class TagsModel extends Model {

    protected $table = 'tags';
    protected $allowedFields = [
        'id',
        'id_page_cont',
        'tag',
        'id_lang',
        'date'
    ];

    public function AddTags($tags, $id_lang, $id_content) {
        if (!empty($tags)) {
            $tags = explode(',', $tags);
            $tags_array = array();
            if (!empty($tags)) {
                foreach ($tags as $tag) {
                    $tag_info = $this->Select('id')->where('tag', $tag)->first();
                    if (!empty($tag_info)) {
                        $tags_array[] = $tag_info['id'];
                    } else {
                        $data = array(
                            'id_page_cont' => $id_content,
                            'id_lang' => $id_lang,
                            'tag' => $tag
                        );
                        $result = $this->insert($data);
                        $tags_array[] = $this->getInsertID();
                    }
                }
            }
            $tags_array = array_unique($tags_array);
            if (!empty($tags_array)) {
                return ',' . implode(',', $tags_array) . ',';
            }
        }
    }

    public function GetTagsById($tags) {
        $tags = explode(',', $tags);
        $tags_array = array();
        $data = $this->db->table('tags')->Select('tag')->whereIn('id', $tags)->orderBy('tag')->get()->getResultArray();
        if (!empty($data)) {
            foreach ($data as $d) {
                $tags_array[] = $d['tag'];
            }
        }
        if (!empty($tags_array)) {
            $tags_array = implode(',', $tags_array);
            return $tags_array;
        }
    }

    public function GetTagById($id, $id_lang) {

        $tag = $this->db->table('tags')->where('id', $id)->where('id_lang', $id_lang)->get()->getRowArray();
        return $tag;
    }

    public function deleteTag($id) {
        if (empty($id))
            return false;
        $this->db->transStart();
        $this->where('id', $id)->delete();
        $list = $this->db->table('news_lang')->Select('id,tags')->Like('tags', ',' . $id . ',')->get()->getResultArray();
        if (!empty($list)) {
            foreach ($list as $el) {
                $tag_array = array_filter(explode(',', $el['tags']));
                if (($key = array_search($id, $tag_array)) !== false) {
                    unset($tag_array[$key]);
                }
                if (!empty($tag_array)) {
                    $this->db->table('news_lang')->set(array('tags' => implode(',', $tag_array)))->where('id', $el['id'])->update();
                } else {
                    $this->db->table('news_lang')->set(array('tags' => ''))->where('id', $el['id'])->update();
                }
            }
        }

        $list = $this->db->table('event_lang')->Select('id,tags')->Like('tags', ',' . $id . ',')->get()->getResultArray();
        if (!empty($list)) {
            foreach ($list as $el) {
                $tag_array = array_filter(explode(',', $el['tags']));
                if (($key = array_search($id, $tag_array)) !== false) {
                    unset($tag_array[$key]);
                }
                if (!empty($tag_array)) {
                    $this->db->table('event_lang')->set(array('tags' => implode(',', $tag_array)))->where('id', $el['id'])->update();
                } else {
                    $this->db->table('event_lang')->set(array('tags' => ''))->where('id', $el['id'])->update();
                }
            }
        }
        $this->db->transComplete();
        return $this->db->transStatus();
    }

}
