<?php

namespace Modules\Survey\Models;  
use CodeIgniter\Model;

class SurveyModel extends Model{

    protected $table = 'survey';
    
    protected $allowedFields = [
        'date_start',
        'date_end',
        'publish',
        'edited_at',
		'single',
        'created_at'
    ];
    
    public function getSurveyById($id, $id_lang) 
    {
        $survey = $this->where('id', $id)->first();
        if(!empty($survey)) {
            $survey['lang'] = $this->getSurveyLang($id);
            if(!empty($survey['lang']) && !empty($survey['lang'][$id_lang]) && !empty($survey['lang'][$id_lang]['question'])) {
                $survey['name'] = $survey['lang'][$id_lang]['question'];
            } else {
                $survey['name'] = '';
            }
            if (!empty($survey['date_start']) && !empty($survey['date_end'])) {
                $survey['date'] = date('d.m.Y', strtotime($survey['date_start'])) . ' - ' . date('d.m.Y', strtotime($survey['date_end']));
            } else {
                $survey['date'] = '';
            }
            $survey['options'] = $this->getSurveyOptions($id, $id_lang);
            $survey['result'] = $this->getSurveyResult($id);
        }
        return $survey;
    }
    
    public function getSurveyLang($id) 
    {
        $langs = array();
        $data = $this->db->table('survey_lang')->where('id_survey', $id)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = array(
                    'question' => $d['question'],
                    'description' => $d['description']
                );
            }
        }
        return $langs;
    }
    
    public function getSurveyOptions($id, $id_lang) 
    {
        $options = $this->db->table('survey_option')->where('id_survey', $id)->orderBy('order', 'ASC')->get()->getResultArray();
        if(!empty($options)) {
            foreach($options as $k=>$o) {
                $options[$k]['lang'] = $this->getSurveyOptionLang($o['id']);
                if(!empty($options[$k]['lang']) && !empty($options[$k]['lang'][$id_lang]) && !empty($options[$k]['lang'][$id_lang]['option'])) {
                    $options[$k]['name'] = $options[$k]['lang'][$id_lang]['option'];
                } else {
                    $options[$k]['name'] = '';
                }
            }
        }
        return $options;
    }
    
    public function getSurveyOptionLang($id_option) 
    {
        $langs = array();
        $data = $this->db->table('survey_option_lang')->where('id_option', $id_option)->orderBy('id_lang', 'ASC')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
    
    public function getSurveyResult($id_survey) {
        $result = array();
        $list = $this->db->table('survey_answer')->select('id_option,COUNT(id_option) as count')->where('id_survey', $id_survey)->groupBy('id_option')->get()->getResultArray();
        if(!empty($list)) {
            foreach($list as $l) {
                $result[$l['id_option']] = $l['count'];
            }
        }
        return $result;
    }
    
    public function saveSurvey($id, $post) 
    {
        if(empty($post)) return false;
        $this->db->transStart();
        
        if (!empty($post['date'])) {
            $tmp = explode('-', $post['date']);
            $post['date_start'] = !empty($tmp) && !empty($tmp[0]) ? date('Y-m-d', strtotime($tmp[0])) : '';
            $post['date_end'] = !empty($tmp) && !empty($tmp[1]) ? date('Y-m-d', strtotime($tmp[1])) : '';
        }
        if(empty($post['date_end'])) $post['date_end'] = null;
        $data = array(
            'date_start' => !empty($post['date_start']) ? $post['date_start'] : null,
            'date_end' => !empty($post['date_end']) ? $post['date_end'] : null,
            'publish' => !empty($post['publish']) ? $post['publish'] : 0,
			'single' => !empty($post['single']) ? $post['single'] : 0,
        );
        if($id) {
            $result = $this->set($data)->where('id', $id)->update();
            $this->id = $id;
        } else {
            $result = $this->insert($data);
            $this->id = $this->getInsertID();
        }
        $this->saveSurveyLang($this->id, $post['lang']);
        $this->saveSurveyOptions($this->id, $post['options']);
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    
    private function saveSurveyLang($id_survey, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_survey' => $id_survey,
                    'id_lang' => $id_lang,
                    'question' => !empty($lang['question']) ? $lang['question'] : '',
                    'description' => !empty($lang['description']) ? $lang['description'] : '',
                );
                $survey = $this->db->table('survey_lang')->select('id')->where('id_survey', $id_survey)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($survey) && !empty($survey['id'])) {
                    $result = $this->db->table('survey_lang')->set($data)->where('id_survey', $id_survey)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('survey_lang')->insert($data);
                }
            }
        }
    }
    
    private function saveSurveyOptions($id, $options) 
    {
        $ids = array();
        if(!empty($options)) {
            foreach($options as $option) {
                $data = array(
                    'id_survey' => $id,
                    'order' => $option['order'],
                    'publish' => !empty($option['publish']) ? $option['publish'] : 0,
                );
                if($option['id'] && !empty($this->db->table('survey_option')->select('id')->where('id', $option['id'])->get()->getRowArray())) {
                    $result = $this->db->table('survey_option')->set($data)->where('id', $option['id'])->update();
                    $id_option = $option['id'];
                } else {
                    $result = $this->db->table('survey_option')->insert($data);
                    $id_option = $this->db->insertID();
                }
                $ids[] = $id_option;
                $this->saveSurveyOptionLang($id_option, $option['lang']);
            }
        }
        $query = $this->db->table('survey_option')->select('id')->where('id_survey', $id);
        if(!empty($ids)) {
            $query->whereNotIn('id', $ids);
        }
        $option_list = $query->get()->getResultArray();
        if(!empty($option_list)) {
            foreach($option_list as $option) {
                $this->db->table('survey_option_lang')->where('id_option', $option['id'])->delete();
                $this->db->table('survey_option')->where('id', $option['id'])->delete();
            }
        }
    }
    
    private function saveSurveyOptionLang($id_option, $lang_data) 
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_option' => $id_option,
                    'id_lang' => $id_lang,
                    'option' => !empty($lang['option']) ? $lang['option'] : '',
                );
                $option = $this->db->table('survey_option_lang')->select('id')->where('id_option', $id_option)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($option) && !empty($option['id'])) {
                    $result = $this->db->table('survey_option_lang')->set($data)->where('id_option', $id_option)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('survey_option_lang')->insert($data);
                }
            }
        }
    }
    
    public function deleteSurvey($id) 
    {
        if(empty($id)) return false;
        $this->db->transStart();
        $option_list = $this->db->table('survey_option')->select('id')->where('id_survey', $id)->get()->getResultArray();
        if(!empty($option_list)) {
            foreach($option_list as $option) {
                $this->db->table('survey_option_lang')->where('id_option', $option['id'])->delete();
                $this->db->table('survey_option')->where('id', $option['id'])->delete();
            }
        }
        $this->db->table('survey_answer')->where('id_survey', $id)->delete();
        $this->db->table('survey_lang')->where('id_survey', $id)->delete();
        $this->where('id', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
	
	public function saveResults($id,$id_lang,$votes) {
		if(empty($votes)) return false;
		if(!empty(get_cookie('survey'.$id))) return false;
		$this->db->transStart();
		foreach($votes as $vote) {
			$data = array(
                    'id_survey' => $id,
                    'id_option' => $vote
            );
		  $this->db->table('survey_answer')->insert($data);
		}	
		helper('cookie');
		set_cookie('survey'.$id,1,86400);
		$this->db->transComplete();
        return $this->db->transStatus();
	}	 
}