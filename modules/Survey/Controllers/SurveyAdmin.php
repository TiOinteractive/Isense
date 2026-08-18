<?php

namespace Modules\Survey\Controllers;

use App\Controllers\BaseController;
use Modules\Survey\Models\SurveyModel;
use App\Libraries\Breadcrumb;

class SurveyAdmin extends BaseController {

    public function __construct() {
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->surveyModel = new SurveyModel();
    }

    public function index($action = '', $id = 0) {
        $survey = array();
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
        $this->breadcrumb->add(lang('Survey.SurveyList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/survey');

        switch ($action) {
            case 'edit':
                $survey = $this->surveyModel->getSurveyById($id, $this->id_lang);
            case 'add':
            case 'save':
                $post = $this->request->getPost();
                if (!empty($post)) {
                    $result = false;
                    $errors = array();
                    $validation = \Config\Services::validation();
                    if (!empty($post['lang'])) {
                        foreach ($post['lang'] as $id_lang => $lang) {
                            $validation->reset();
                            $lang_name = (!empty($this->languages[$id_lang]) ? '<b>[' . $this->languages[$id_lang]['short_name'] . ']</b> ' : '');
                            $validation->setRules([
                                'question' => [
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => $lang_name . lang('Survey.QuestionError')
                                    ],
                                ]
                            ]);
                            if (!$validation->run($lang)) {
                                $errors[] = array_merge($validation->getErrors());
                            }
                        }
                    }
                    if (!empty($post['options'])) {
                        foreach ($post['options'] as $no => $question) {
                            if (!empty($question['lang'])) {
                                foreach ($question['lang'] as $id_lang => $lang) {
                                    $validation->reset();
                                    $lang_name = (!empty($this->languages[$id_lang]) ? '<b>[' . $this->languages[$id_lang]['short_name'] . ']</b> ' : '');
                                    $validation->setRules([
                                        'option' => [
                                            'rules' => 'required',
                                            'errors' => [
                                                'required' => $lang_name . lang('Survey.option.OptionError', [$no])
                                            ],
                                        ]
                                    ]);
                                    if (!$validation->run($lang)) {
                                        $errors[] = array_merge($validation->getErrors());
                                    }
                                }
                            }
                        }
                    }
                    if (empty($errors)) {
                        $result = $this->surveyModel->saveSurvey($id, $post);
                    }
                    if ($result) {
                        $this->session->setFlashdata('survey', array(
                            'status' => true,
                            'msg' => ($id ? lang('Survey.EditSuccess') : lang('Survey.AddSuccess')) . '!'
                        ));
                        HistoryStat($id,'','survey','Survey',$id ? lang('Survey.EditSuccess') : lang('Survey.AddSuccess'));
                        return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/survey/edit/' . $this->surveyModel->id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Survey.EditError') : lang('Survey.AddError')) . '!',
                            'list' => $errors
                        );
                    }
                    $survey = array_merge($survey, $post);
                    $survey['id'] = $id;
                } else {
                    $flashdata = $this->session->getFlashdata('survey');
                }
                if ($id) {
                    $this->breadcrumb->add(lang('Survey.SurveyEdit') . (!empty($survey['name']) ? ': ' . $survey['name'] : ''), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/survey/edit/' . $id);
                } else {
                    $this->breadcrumb->add(lang('Survey.NewSurveyAdd'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/survey/add');
                }
                $this->survey = $survey;
                $breadcrumb = $this->breadcrumb->render();
                echo view('Modules\Survey\Views\admin\add', array('action' => $action, 'survey' => $survey, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            default :
                $breadcrumb = $this->breadcrumb->render();
                $get = $this->request->getGet();
                $query = $this->surveyModel->join('survey_lang sl', 'survey.id=sl.id_survey')->select('survey.id,survey.publish,survey.date_start,survey.date_end,sl.question')->where('sl.id_lang', $this->id_lang);
                if(!empty($get)) {
                    foreach($get as $name=>$value) {
                        switch($name) {
                            case 'question': 
                                if(!empty($value)) {
                                    $query->like('sl.question', $value);
                                }
                                break;
                            case 'date': 
                                if (!empty($value)) {
                                    $tmp = explode('-', $value);
                                    $date_start = !empty($tmp) && !empty($tmp[0]) ? date('Y-m-d', strtotime($tmp[0])) : '';
                                    $date_end = !empty($tmp) && !empty($tmp[1]) ? date('Y-m-d', strtotime($tmp[1])) : '';
                                    if(!empty($date_start)) {
                                        $query->groupStart();
                                            $query->groupStart()->where('survey.date_start >=', $date_start)->where('survey.date_end', NULL)->groupEnd();
                                            $query->orWhere('survey.date_end >=', $date_start);
                                        $query->groupEnd();
                                    }
                                    if(!empty($date_end)) {
                                        $query->where('survey.date_start <=', $date_end);
                                    }
                                }
                                break;
                            case 'publish':
                                if(in_array($value, array(0,1))) {
                                    $query->where('survey.publish', $value);
                                }
                                break;
                        }
                    }
                }
                if(empty($get['order'])) {
                    $get['order'] = 'id;asc';
                }
                switch($get['order']) {
                    case 'question;asc': $query->orderBy('sl.question', 'ASC');
                        break;
                    case 'question;desc': $query->orderBy('sl.question', 'DESC');
                        break;
                    default: $query->orderBy('survey.id', 'ASC');
                        break;
                }
                $surveys = $query->paginate(20);
                if(!empty($surveys)) {
                    foreach($surveys as $k=>$survey) {
                        $count = $this->surveyModel->db->table('survey_answer')->select('COUNT(id) as count')->where('id_survey', $survey['id'])->get()->getRowArray();
                        $surveys[$k]['result_count'] = $count['count'];
                        if (!empty($survey['date_start']) && !empty($survey['date_end'])) {
                            $surveys[$k]['date'] = date('d.m.Y', strtotime($survey['date_start'])) . ' - ' . date('d.m.Y', strtotime($survey['date_end']));
                        } else {
                            $surveys[$k]['date'] = '';
                        }
                    }
                }
                $order_list = array(
                    array('field' => '', 'name' => lang('Survey.sort.Default')),
                    array('field' => 'question;asc', 'name' => lang('Survey.sort.QuestionAsc')),
                    array('field' => 'question;desc', 'name' => lang('Survey.sort.QuestionDesc')),
                );
                echo view('Modules\Survey\Views\admin\list', array('surveys' => $surveys, 'breadcrumbs' => $breadcrumb, 'filters' => $get, 'order_list' => $order_list, 'pager' => $this->surveyModel->pager));
                break;
        }
        
    }
    
    public function assets($action='') {
        $assets = array(
            'js' => array(),
            'js_ready' => array(),
            'css' => array(),
            'css_footer' => array()
        );
        $assets['js'][] = '/adm/third-party/apexcharts/dist/apexcharts.min.js';
        switch ($action) {
            case 'edit':
            case 'add':
            case 'save':
                $assets['js'][] = '/adm/js/survey.js';
                $data = array();
                if(!empty($this->survey) && !empty($this->survey['options']) && !empty($this->survey['result'])) {
                    foreach($this->survey['options'] as $o) {
                        $data[] = array(
                            'x' => $o['name'],
                            'y' => !empty($this->survey['result'][$o['id']]) ? intval($this->survey['result'][$o['id']]) : 0
                        );
                    }
                }
                $assets['js_ready'][] = "new ApexCharts(document.querySelector('#survey-chart'), {
                    chart: {
                      type: 'bar'
                    },
                    series: [{
                        name: \"" . lang('Survey.Votes') . "\",
                        data: " . json_encode($data) . ",
                        color: \"#ffc800\",
                    }]
                  }).render();";
                break;
            default :
                break;
        }
        return $assets;
    }

    public function ajax($action = '', $id = 0) {
        $post = $this->request->getPost();
        if (!empty($action)) {
            switch ($action) {
                case 'option-add':
                    return $this->addOption($post);
                    break;
                case 'publish':
                    return $this->publishSurvey($id);
                    break;
                case 'delete':
                    return $this->deleteSurvey($id);
                    break;
                case 'result':
                    return $this->surveyResult($id);
                    break;
            }
        }
    }

    private function addOption($post = array()) {
        $html = view('Modules\Survey\Views\admin\add_option', array('no' => $post['no'], 'languages' => $this->languages, 'locale' => $this->locale));
        return $this->response->setJSON(array(
                'status' => true,
                'html' => base64_encode(urlencode($html))
        ));
    }

    private function deleteSurvey($id) {
        $result = $this->surveyModel->deleteSurvey($id);
        return $this->response->setJSON(array(
            'status' => true,
            'result' => $result,
            'id' => $id,
            'msg' => $result ? lang('Survey.Removed') : lang('Survey.Error')
        ));
		HistoryStat($id,'','survey','Survey',$result ? lang('Survey.Removed') : lang('Survey.Error'));
    }

    private function publishSurvey($id) {
        $survey = $this->surveyModel->select('id,publish')->where('id', $id)->first();
        if (!empty($survey)) {
            $r = $this->surveyModel->where('id', $id)->set('publish', $survey['publish'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $survey['publish'] ? 0 : 1,
                'msg' => $survey['publish'] ? lang('Survey.Republished') : lang('Survey.Published')
            );
            HistoryStat($id,'','survey','Survey',$survey['publish'] ? lang('Survey.Republished') : lang('Survey.Published'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $survey['publish'],
                'msg' => lang('Survey.Error')
            );
        }
        return $this->response->setJSON($response);
    }
    

    private function surveyResult($id) {
        $html = '<div class="chart-box"><div class="chart" id="survey-chart"></div></div>';
        $survey = $this->surveyModel->getSurveyById($id, $this->id_lang);
        $data = array(
            'labels' => array(),
            'series' => array(),
        );
        if(!empty($survey) && !empty($survey['options']) && !empty($survey['result'])) {
            foreach($survey['options'] as $o) {
                $data['labels'][] = $o['name'];
                $data['series'][] = !empty($survey['result'][$o['id']]) ? intval($survey['result'][$o['id']]) : 0;
            }
        }
        return $this->response->setJSON(array(
            'status' => true,
            'data' => $data,
            'target' => '#survey-chart',
            'title' => !empty($survey['name']) ? $survey['name'] : lang('Survey.Result'),
            'close' => lang('Survey.Close'),
            'total' => lang('Survey.Votes'),
            'html' => base64_encode(urlencode($html))
        ));
    }
    
    public function pageContent($id_content, $slug = '') {
        helper('filesystem');
        switch ($slug) {
            default:
                $templates = get_templates_by_dir('modules/Survey/Views/user');
                return array(
                    'pc_templates' => $templates,
                );
                break;
        }
        
    }
}