<?php

namespace Modules\Form\Models;  
use CodeIgniter\Model;

class FormModel extends Model{

    protected $table = 'form';
    
    protected $allowedFields = [
        'id_page_cont',
        'template',
        'addressee',
        'addressee_dw',
        'addressee_udw',
        'edited_at',
        'created_at',
        'captcha'
    ];
    
    
    public function getFormByContentId($id_content) 
    {
        $form = $this->where('id_page_cont', $id_content)->first();
        if(!empty($form)) {
            $form['lang'] = $this->getFromLang($form['id']);
            $form['fields'] = $this->getFormFields($form['id']);
        }
        return $form;
    }
    
    private function getFromLang($id) 
    {
        $langs = array();
        $data = $this->db->table('form_lang')->where('id_form', $id)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
    
    private function getFormFields($id_form) 
    {
        $fields = array();
        $fields = $this->db->table('form_field')->where('id_form', $id_form)->orderBy('order', 'ASC')->get()->getResultArray();
        if(!empty($fields)) {
            foreach($fields as $k=>$f) {
                $fields[$k]['lang'] = $this->getFormFieldLang($f['id']);
            }
        }
        return $fields;
    }
    
    private function getFormFieldLang($id_field) 
    {
        $langs = array();
        $data = $this->db->table('form_field_lang')->where('id_field', $id_field)->orderBy('id_lang', 'ASC')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
    
    public function saveForm($id_content, $post)
    {
        $data = array(
            'id_page_cont' => $id_content,
            'template' => $post['template'],
            'addressee' => $post['addressee'],
            'captcha' => !empty($post['captcha']) ? $post['captcha'] : 0,
        );
        $form = $this->where('id_page_cont', $id_content)->first();
        if(!empty($form)) {
            $this->set($data)->where('id_page_cont', $id_content)->update();
            $id = $form['id'];
			HistoryStat($id,$id_content,'form','Form',lang('Admin.page.EditSuccess'));
        } else {
            $this->insert($data);
            $id = $this->getInsertID();
			HistoryStat($id,$id_content,'form','Form',lang('Admin.page.AddSuccess'));
        }
        $this->saveFormLang($id, $post['lang']);
        $this->saveFormFields($id, !empty($post['field']) ? $post['field'] : array());
    }
    
    private function saveFormLang($id_form, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_form' => $id_form,
                    'id_lang' => $id_lang,
                    'name' => $lang['name'],
                    'description' => $lang['description'],
                    'success_msg' => $lang['success_msg'],
                    'error_msg' => $lang['error_msg'],
                );
                $lang = $this->db->table('form_lang')->select('id')->where('id_form', $id_form)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('form_lang')->set($data)->where('id_form', $id_form)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('form_lang')->insert($data);
                }
            }
        }
    }
    
    private function saveFormFields($id_form, $fields) 
    {
        $ids = array();
        if(!empty($fields)) {
            foreach($fields as $field) {
                $data = array(
                    'id_form' => $id_form,
                    'validation' => $field['validation'],
                    'type' => $field['type'],
                    'order' => $field['order'],
                    'required' => !empty($field['required']) ? $field['required'] : 0,
                    'publish' => !empty($field['publish']) ? $field['publish'] : 0,
                );
                if($field['id'] && !empty($this->db->table('form_field')->select('id')->where('id', $field['id'])->get()->getResultArray())) {
                    $result = $this->db->table('form_field')->set($data)->where('id', $field['id'])->update();
                    $id_field = $field['id'];
                } else {
                    $result = $this->db->table('form_field')->insert($data);
                    $id_field = $this->db->insertID();
                }
                $ids[] = $id_field;
                $this->saveFormFieldLang($id_field, $field['lang']);
            }
        }
        $query = $this->db->table('form_field')->select('id')->where('id_form', $id_form);
        if(!empty($ids)) {
            $query->whereNotIn('id', $ids);
        }
        $fields_list = $query->get()->getResultArray();
        if(!empty($fields_list)) {
            foreach($fields_list as $field) {
                $this->db->table('form_field_lang')->where('id_field', $field['id'])->delete();
                $this->db->table('form_field')->where('id', $field['id'])->delete();
            }
        }
    }
    
    private function saveFormFieldLang($id_field, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_field' => $id_field,
                    'id_lang' => $id_lang,
                    'name' => $lang['name'],
					'description'=>$lang['description']
                );
                $field = $this->db->table('form_field_lang')->select('id')->where('id_field', $id_field)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($field) && !empty($field['id'])) {
                    $result = $this->db->table('form_field_lang')->set($data)->where('id_field', $id_field)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('form_field_lang')->insert($data);
                }
            }
        }
    }
}