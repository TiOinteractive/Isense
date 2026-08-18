<?php

namespace Modules\Wyswig\Models;

use CodeIgniter\Model;

class WyswigModel extends Model {

    protected $table = 'wyswig';
    protected $allowedFields = [
        'id_page_cont',
        'id_photo',
        'edited_at',
        'created_at',
    ];

    public function getWyswigByContentId($id_content) {
        $wyswig = $this->where('id_page_cont', $id_content)->first();
        if (!empty($wyswig)) {
            $wyswig['lang'] = $this->getWyswigLang($wyswig['id']);
            // Grafika tła — wspólna dla wszystkich wersji językowych
            if (!empty($wyswig['id_photo'])) {
                $wyswig['photo'] = $this->db->table('tio_files')->where('id', $wyswig['id_photo'])->limit(1)->get()->getRowArray();
            }
        }
        return $wyswig;
    }

    private function getWyswigLang($id) {
        $langs = array();
        $data = $this->db->table('wyswig_lang')->where('id_wyswig', $id)->orderBy('id_lang')->get()->getResultArray();
        if (!empty($data)) {
            foreach ($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }

    public function saveWyswig($id_content, $post) {
        $data = array(
            'id_page_cont' => $id_content,
            'id_photo' => !empty($post['photo']) && !empty($post['photo']['id']) ? $post['photo']['id'] : 0,
        );
        $wyswig = $this->where('id_page_cont', $id_content)->first();
        if (!empty($wyswig)) {
            $this->set($data)->where('id_page_cont', $id_content)->update();
            $id = $wyswig['id'];
        } else {
            $this->insert($data);
            $id = $this->getInsertID();
        }
        HistoryStat($id, $id_content, 'wyswig', 'Wysywig', ($id ? lang('Admin.page.EditSuccess') : lang('Admin.page.AddSuccess')));
        $this->saveWyswigLang($id, $post['lang']);
    }

    private function saveWyswigLang($id_wyswig, $lang_data) {
        if (!empty($lang_data)) {
            foreach ($lang_data as $id_lang => $lang) {
                $data = array(
                    'id_wyswig' => $id_wyswig,
                    'id_lang' => $id_lang,
                    'content' => $lang['content'],
                );
                $lang = $this->db->table('wyswig_lang')->select('id')->where('id_wyswig', $id_wyswig)->where('id_lang', $id_lang)->get()->getRowArray();
                if (!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('wyswig_lang')->set($data)->where('id_wyswig', $id_wyswig)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('wyswig_lang')->insert($data);
                }
            }
        }
    }

}
