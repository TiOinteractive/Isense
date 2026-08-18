<?php

namespace Modules\Survey\Libraries;

use Modules\Survey\Models\SurveyModel;

class Survey {

    public function __construct() {
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->surveyModel = new SurveyModel();
    }

    public function index($content, $id_lang, $slug = '') {
        $get = $this->request->getGet();
        $config = new \Config\Pager;
        switch ($slug) {
			case 'surveylist':
			$surveylist=array();
			$surveylist['active'] = $this->surveyModel->join('survey_lang sl', 'survey.id=sl.id_survey')->Select('survey.id,sl.question,sl.description,survey.date_start,survey.date_end,survey.single')->Where('publish', 1)->Where('date_end>=', date("Y-m-d H:i:s"))->Where('date_start<=', date("Y-m-d H:i:s"))->Where('sl.id_lang', $id_lang)->OrderBy('date_start', 'DESC')->get()->getResultArray();
			if(!empty($surveylist['active'])) {
			   foreach($surveylist['active'] as $k=>$v) {
			      $surveylist['active'][$k]['result']=$this->getResults($v['id'], $id_lang);
				  $surveylist['active'][$k]['options'] = $this->surveyModel->db->table('survey_option')->join('survey_option_lang sl', 'survey_option.id=sl.id_option')->Select('survey_option.id,sl.option')->Where('survey_option.id_survey', $v['id'])->Where('publish', 1)->Where('sl.id_lang', $id_lang)->OrderBy('order', 'ASC')->get()->getResultArray();
			   }
			}   
			$surveylist['archive'] = $this->surveyModel->join('survey_lang sl', 'survey.id=sl.id_survey')->Select('survey.id,sl.question,sl.description,survey.date_start,survey.date_end,survey.single')->Where('publish', 1)->Where('date_end<', date("Y-m-d H:i:s"))->Where('sl.id_lang', $id_lang)->OrderBy('date_start', 'DESC')->get()->getResultArray();
			if(!empty($surveylist['archive'])) {
			   foreach($surveylist['archive'] as $k=>$v) {
			      $surveylist['archive'][$k]['result']=$this->getResults($v['id'], $id_lang);
			   }
			}   
			return array(
				'views_dir' => 'list',
                'list' => $surveylist
            );
            break;
            default:
                $survey = $this->surveyModel->join('survey_lang sl', 'survey.id=sl.id_survey')->Select('survey.id,sl.question,sl.description,survey.single')->Where('publish', 1)->Where('date_end>=', date("Y-m-d H:i:s"))->Where('date_start<=', date("Y-m-d H:i:s"))->Where('sl.id_lang', $id_lang)->OrderBy('date_start', 'DESC')->get()->getRowArray();
                if(!empty($survey)) {
                    $survey['options'] = $this->surveyModel->db->table('survey_option')->join('survey_option_lang sl', 'survey_option.id=sl.id_option')->Select('survey_option.id,sl.option')->Where('survey_option.id_survey', $survey['id'])->Where('publish', 1)->Where('sl.id_lang', $id_lang)->OrderBy('order', 'ASC')->get()->getResultArray();
                }
                return array(
                    'survey' => $survey,
                );
                break;
        }
    }

    public function showSurvey($id_lang, $locale) {
        $survey = $this->surveyModel->join('survey_lang sl', 'survey.id=sl.id_survey')->Select('survey.id,sl.question,sl.description,survey.single')->Where('publish', 1)->Where('date_end>=', date("Y-m-d H:i:s"))->Where('date_start<=', date("Y-m-d H:i:s"))->Where('sl.id_lang', $id_lang)->OrderBy('date_start', 'DESC')->get()->getRowArray();
        if (!empty($survey)) {
            $survey['options'] = $this->surveyModel->db->table('survey_option')->join('survey_option_lang sl', 'survey_option.id=sl.id_option')->Select('survey_option.id,sl.option')->Where('survey_option.id_survey', $survey['id'])->Where('publish', 1)->Where('sl.id_lang', $id_lang)->OrderBy('order', 'ASC')->get()->getResultArray();
            return view('Modules\Survey\Views\user\single_survey', array('survey' => $survey));
        } else {
            return '';
        }
    }

    public function ajaxSlugs($post, $id_lang, $module_data, $link) {
        if (!empty($post['action'])) {
            switch ($post['action']) {
                case 'results_list':
				    $data = $this->getResults($post['id'], $id_lang);
                    $html = view('Modules\Survey\Views\user\survey_results_list', array('data' => $data,'id'=>$post['id']));
                    $response = array(
                        'status' => true,
                        'html' => base64_encode(urlencode($html))
                    );
                    return $this->response->setJSON($response);
                    break;
				case 'results':
                    $data = $this->getResults($post['id'], $id_lang);
                    $html = view('Modules\Survey\Views\user\survey_results', array('data' => $data));
                    $response = array(
                        'status' => true,
                        'html' => base64_encode(urlencode($html))
                    );
                    return $this->response->setJSON($response);
                    break;
                case 'vote':
                    parse_str($post['votes'], $votes);
                    $survey = $this->surveyModel->join('survey_lang sl', 'survey.id=sl.id_survey')->Select('survey.id,sl.question,sl.description,survey.single')->Where('survey.id', $post['id'])->Where('sl.id_lang', $id_lang)->OrderBy('date_start', 'DESC')->get()->getRowArray();
                        $results=array();
                    if(!empty($votes)) {
                        $results['status'] = $this->surveyModel->saveResults($post['id'], $id_lang,$votes);
                        $results['results'] = $this->getResults($post['id'], $id_lang);
                        }
                        $survey['options'] = $this->surveyModel->db->table('survey_option')->join('survey_option_lang sl', 'survey_option.id=sl.id_option')->Select('survey_option.id,sl.option')->Where('survey_option.id_survey',$post['id'])->Where('publish', 1)->Where('sl.id_lang', $id_lang)->OrderBy('order', 'ASC')->get()->getResultArray();
                    $html = view('Modules\Survey\Views\user\survey_vote', array('results' => $results,'survey'=>$survey));
                    $response = array(
                        'status' => true,
                        'html' => base64_encode(urlencode($html))
                    );
                    return $this->response->setJSON($response);
                break;
                case 'vote_list':
                    parse_str($post['votes'], $votes);
                    $survey = $this->surveyModel->join('survey_lang sl', 'survey.id=sl.id_survey')->Select('survey.id,sl.question,sl.description,survey.single')->Where('survey.id', $post['id'])->Where('sl.id_lang', $id_lang)->OrderBy('date_start', 'DESC')->get()->getRowArray();
                        $results=array();
                    if(!empty($votes)) {
                        $results['status'] = $this->surveyModel->saveResults($post['id'], $id_lang,$votes);
                        $results['results'] = $this->getResults($post['id'], $id_lang);
                        }
                        $survey['options'] = $this->surveyModel->db->table('survey_option')->join('survey_option_lang sl', 'survey_option.id=sl.id_option')->Select('survey_option.id,sl.option')->Where('survey_option.id_survey',$post['id'])->Where('publish', 1)->Where('sl.id_lang', $id_lang)->OrderBy('order', 'ASC')->get()->getResultArray();
                    $html = view('Modules\Survey\Views\user\survey_vote_list', array('results' => $results,'survey'=>$survey));
                    $response = array(
                        'status' => true,
                        'html' => base64_encode(urlencode($html))
                    );
                    return $this->response->setJSON($response);
                break;



				
            }
        }
    }

    public function getResults($id, $id_lang) {
        $list = $this->surveyModel->db->table('survey_answer a')->join('survey_option o', 'o.id=a.id_option')->join('survey_option_lang ol', 'ol.id_option=o.id')->select('a.id_option,COUNT(a.id_option) as count,ol.option')->where('a.id_survey', $id)->where('ol.id_lang', $id_lang)->groupBy('a.id_option')->get()->getResultArray();
        if (!empty($list)) {
            foreach ($list as $l) {
                $result[$l['id_option']] = $l;
            }
        }
        return $result;
    }

}
