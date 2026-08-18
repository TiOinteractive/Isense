<?php

namespace Modules\Newsletter\Controllers;

use App\Controllers\BaseController;
use Modules\Newsletter\Models\NewsletterModel;
use Modules\Newsletter\Models\NewsletterEmailModel;
use App\Libraries\Breadcrumb;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class NewsletterAdmin extends BaseController {

    public function __construct() {
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->newsletterModel = new NewsletterModel();
        $this->newsletterEmailModel = new NewsletterEmailModel();
    }

    public function index($action = '', $id = 0) {
        $group = array();
        $email = array();
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
        $this->breadcrumb->add(lang('Newsletter.Newsletter'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/newsletter');

        switch ($action) {
            case 'mail-edit':
                $email = $this->newsletterModel->getEmailById($id);
            case 'mail-add':
            case 'mail-save':
                $post = $this->request->getPost();
                if (!empty($post)) {
                    $result = false;
                    $errors = array();
                    $validation = \Config\Services::validation();
                    $validation->setRules([
                        'email' => [
                            'rules' => 'required|valid_email',
                            'errors' => [
                                'required' => lang('Newsletter.EmailError'),
                                'valid_email' => lang('Newsletter.EmailFormatError')
                            ],
                        ]
                    ]);
                    if (!$validation->run($post)) {
                        $errors[] = array_merge($validation->getErrors());
                    }
                    if (empty($errors)) {
                        $result = $this->newsletterModel->saveEmail($id, $post);
                    }
                    if ($result) {
                        $this->session->setFlashdata('newsletter', array(
                            'status' => true,
                            'msg' => ($id ? lang('Newsletter.EditEmailSuccess') : lang('Newsletter.AddEmailSuccess')) . '!'
                        ));
                        HistoryStat($id,'','newsletter','Newsletter',$id ? lang('Newsletter.EditEmailSuccess') : lang('Newsletter.AddEmailSuccess'));
                        return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/newsletter/mail-edit/' . $this->newsletterModel->id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Newsletter.EditEmailError') : lang('Newsletter.AddEmailError')) . '!',
                            'list' => $errors
                        );
                    }
                    $email = $post;
                    $email['id'] = $id;
                } else {
                    $flashdata = $this->session->getFlashdata('newsletter');
                }
                $this->breadcrumb->add(lang('Newsletter.EmailList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/newsletter/mails');
                if ($id) {
                    $this->breadcrumb->add(lang('Newsletter.EmailEdit'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/newsletter/mail-edit/' . $id);
                } else {
                    $this->breadcrumb->add(lang('Newsletter.EmailAdd'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/newsletter/mail-add');
                }
                $breadcrumb = $this->breadcrumb->render();
                $groups = $query = $this->newsletterModel->db->table('newsletter_group ng')->join('newsletter_group_lang ngl', 'ng.id=ngl.id_group')->select('ng.id,ngl.name')->where('ngl.id_lang', $this->id_lang)->orderBy('ngl.name', 'ASC')->get()->getResultArray();
                echo view('Modules\Newsletter\Views\admin\email_add', array('action' => $action, 'email' => $email, 'groups' => $groups, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            case 'mails':
                $this->breadcrumb->add(lang('Newsletter.EmailList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/newsletter/mails');
                $breadcrumb = $this->breadcrumb->render();
                $get = $this->request->getGet();
                $query = $this->newsletterEmailModel->select('id,agreement,email,name,surname');
                if(!empty($get)) {
                    foreach($get as $name=>$value) {
                        switch($name) {
                            case 'name': 
                                if(!empty($value)) {
                                    $query->groupStart();
                                    $query->like('name', $value);
                                    $query->orLike('surname', $value);
                                    $query->orLike('email', $value);
                                    $query->groupEnd();
                                }
                                break;
                            case 'group':
                                if(!empty($value)) {
                                    $query->where('id_group', $value);
                                }
                                break;
                            case 'agreement':
                                if(in_array($value, array(0,1))) {
                                    $query->where('agreement', $value);
                                }
                                break;
                        }
                    }
                }
                if(empty($get['order'])) {
                    $get['order'] = 'id;asc';
                }
                switch($get['order']) {
                    case 'name;asc': $query->orderBy('name', 'ASC');
                        break;
                    case 'name;desc': $query->orderBy('name', 'DESC');
                        break;
                    default: $query->orderBy('id', 'ASC');
                        break;
                }
                
                $emails = $query->paginate(20);
                if(!empty($get) && !empty($get['export'])) {
                    switch($get['export']) {
                        case 'xls': 
                        case 'xlsx': 
                            $name = 'newsletter_' . date('Ymd') . '.xlsx';
                            $spreadsheet = new Spreadsheet();
                            $sheet = $spreadsheet->getActiveSheet();
                            $sheet->setCellValue('A1', lang('Newsletter.Email'));
                            $sheet->setCellValue('B1', lang('Newsletter.FirstName'));
                            $sheet->setCellValue('C1', lang('Newsletter.LastName'));
                            $count = 2;
                            foreach ($emails as $key=>$line){ 
                                $sheet->setCellValue('A' . $count, $line['email']);
                                $sheet->setCellValue('B' . $count, $line['name']);
                                $sheet->setCellValue('C' . $count, $line['surname']);
                                $count++;
                            }
                            $writer = new Xlsx($spreadsheet);
                            ob_start();
                            $writer->save('php://output');
                            $data = ob_get_clean();
                            break;
                        case 'txt': 
                            $name = 'newsletter_' . date('Ymd') . '.txt';
                            $separator = "\t";
                            $data = lang('Newsletter.Email') . $separator . lang('Newsletter.FirstName') . $separator . lang('Newsletter.LastName') . PHP_EOL;
                            foreach ($emails as $key=>$line){ 
                               $data .= implode($separator, array($line['email'], $line['name'], $line['surname'])) . PHP_EOL;
                            }
                            break;
                        case 'csv':
                        default:
                            $name = 'newsletter_' . date('Ymd') . '.csv';
                            $separator = ";";
                            $data = lang('Newsletter.Email') . $separator . lang('Newsletter.FirstName') . $separator . lang('Newsletter.LastName') . PHP_EOL;
                            foreach ($emails as $key=>$line){ 
                               $data .= implode($separator, array($line['email'], $line['name'], $line['surname'])) . PHP_EOL;
                            }
                            break;
                    }
                    return $this->response->download($name, $data);
                }
                $order_list = array(
                    array('field' => '', 'name' => lang('Newsletter.sort.Default')),
                    array('field' => 'email;asc', 'name' => lang('Newsletter.sort.EmailAsc')),
                    array('field' => 'email;desc', 'name' => lang('Newsletter.sort.EmailDesc')),
                    array('field' => 'name;asc', 'name' => lang('Newsletter.sort.FullNameAsc')),
                    array('field' => 'name;desc', 'name' => lang('Newsletter.sort.FullNameDesc')),
                );
                $groups = $query = $this->newsletterModel->db->table('newsletter_group ng')->join('newsletter_group_lang ngl', 'ng.id=ngl.id_group')->select('ng.id,ngl.name')->where('ngl.id_lang', $this->id_lang)->orderBy('ngl.name', 'ASC')->get()->getResultArray();
                echo view('Modules\Newsletter\Views\admin\email_list', array('emails' => $emails, 'breadcrumbs' => $breadcrumb, 'groups' => $groups, 'filters' => $get, 'order_list' => $order_list, 'action' => $action, 'pager' => $this->newsletterEmailModel->pager));
                break;
            case 'group-edit':
                $group = $this->newsletterModel->getGroupById($id);
            case 'group-add':
            case 'group-save':
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
                                'name' => [
                                    'rules' => 'required',
                                    'errors' => [
                                        'required' => $lang_name . lang('Newsletter.NameError')
                                    ],
                                ]
                            ]);
                            if (!$validation->run($lang)) {
                                $errors[] = array_merge($validation->getErrors());
                            }
                        }
                    }
                    if (empty($errors)) {
                        $result = $this->newsletterModel->saveGroup($id, $post);
                    }
                    if ($result) {
                        $this->session->setFlashdata('newsletter', array(
                            'status' => true,
                            'msg' => ($id ? lang('Newsletter.EditGroupSuccess') : lang('Newsletter.AddGroupSuccess')) . '!'
                        ));
                        HistoryStat($id,'','newsletter','Newsletter',$id ? lang('Newsletter.EditGroupSuccess') : lang('Newsletter.AddGroupSuccess'));
                        return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/newsletter/group-edit/' . $this->newsletterModel->id);
                    } else {
                        $flashdata = array(
                            'status' => false,
                            'msg' => ($id ? lang('Newsletter.EditGroupError') : lang('Newsletter.AddGroupError')) . '!',
                            'list' => $errors
                        );
                    }
                    $group = $post;
                    $group['id'] = $id;
                } else {
                    $flashdata = $this->session->getFlashdata('newsletter');
                }
                $this->breadcrumb->add(lang('Newsletter.GroupList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/newsletter/groups');
                if ($id) {
                    $this->breadcrumb->add(lang('Newsletter.GroupEdit'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/newsletter/group-edit/' . $id);
                } else {
                    $this->breadcrumb->add(lang('Newsletter.GroupAdd'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/newsletter/group-add');
                }
                $breadcrumb = $this->breadcrumb->render();
                echo view('Modules\Newsletter\Views\admin\group_add', array('action' => $action, 'group' => $group, 'flashdata' => $flashdata, 'breadcrumbs' => $breadcrumb));
                break;
            case 'group-export-csv':
                return $this->exportGroupEmails($id, 'csv');
                break;
            case 'group-export-xls':
            case 'group-export-xlsx':
                return $this->exportGroupEmails($id, 'xlsx');
                break;
            case 'group-export-txt':
                return $this->exportGroupEmails($id, 'txt');
                break;
            case 'groups':
                $this->breadcrumb->add(lang('Newsletter.GroupList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/newsletter/groups');
                $breadcrumb = $this->breadcrumb->render();
                $get = $this->request->getGet();
                $query = $this->newsletterModel->db->table('newsletter_group ng')->join('newsletter_group_lang ngl', 'ng.id=ngl.id_group')->select('ng.id,ng.publish,ngl.name')->where('ngl.id_lang', $this->id_lang);
                if(!empty($get)) {
                    foreach($get as $name=>$value) {
                        switch($name) {
                            case 'name': 
                                if(!empty($value)) {
                                    $query->like('ngl.name', $value);
                                }
                                break;
                            case 'publish':
                                if(in_array($value, array(0,1))) {
                                    $query->where('ng.publish', $value);
                                }
                                break;
                        }
                    }
                }
                if(empty($get['order'])) {
                    $get['order'] = 'id;asc';
                }
                switch($get['order']) {
                    case 'name;asc': $query->orderBy('ngl.name', 'ASC');
                        break;
                    case 'name;desc': $query->orderBy('ngl.name', 'DESC');
                        break;
                    default: $query->orderBy('ng.id', 'ASC');
                        break;
                }
                $groups = $query->get()->getResultArray();
                $order_list = array(
                    array('field' => '', 'name' => lang('Newsletter.sort.Default')),
                    array('field' => 'name;asc', 'name' => lang('Newsletter.sort.NameAsc')),
                    array('field' => 'name;desc', 'name' => lang('Newsletter.sort.NameDesc')),
                );
                echo view('Modules\Newsletter\Views\admin\group_list', array('groups' => $groups, 'breadcrumbs' => $breadcrumb, 'filters' => $get, 'order_list' => $order_list, 'action' => $action));
                break;
            default :
                return redirect()->to(($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/newsletter/groups');
                break;
        }
    }
    
    public function assets($action='') {
        $assets = array(
            'js' => array(),
            'css' => array(),
            'css_footer' => array()
        );
        switch ($action) {
            default :
                break;
        }
        return $assets;
    }

    public function ajax($action = '', $id = 0, $a='') {
        $post = $this->request->getPost();
        if (!empty($action)) {
            switch ($action) {
                case 'group-publish':
                    return $this->publishGroup($id);
                    break;
                case 'group-delete':
                    return $this->deleteGroup($id);
                    break;
                case 'mail-agreement':
                    return $this->agreementMail($id);
                    break;
                case 'mail-delete':
                    return $this->deleteMail($id);
                    break;
            }
        }
    }
    
    private function exportGroupEmails($id_group, $ext) {
        $group = $this->newsletterModel->db->table('newsletter_group ng')->join('newsletter_group_lang ngl', 'ng.id=ngl.id_group')->select('ngl.name')->where('ng.id', $id_group)->get()->getRowArray();
        $emails = $this->newsletterModel->db->table('newsletter_email')->select('email,name,surname')->where('id_group', $id_group)->get()->getResultArray();
        if(!empty($emails)) {
            switch($ext) {
                case 'xls': 
                case 'xlsx': 
                    $name = 'newsletter_' . (!empty($group) && !empty($group['name']) ? mb_url_title($group['name'], '_', true) . '_' : '') . date('Ymd') . '.xlsx';
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();
                    $sheet->setCellValue('A1', lang('Newsletter.Email'));
                    $sheet->setCellValue('B1', lang('Newsletter.FirstName'));
                    $sheet->setCellValue('C1', lang('Newsletter.LastName'));
                    $count = 2;
                    foreach ($emails as $key=>$line){ 
                        $sheet->setCellValue('A' . $count, $line['email']);
                        $sheet->setCellValue('B' . $count, $line['name']);
                        $sheet->setCellValue('C' . $count, $line['surname']);
                        $count++;
                    }
                    $writer = new Xlsx($spreadsheet);
                    ob_start();
                    $writer->save('php://output');
                    $data = ob_get_clean();
                    break;
                case 'txt': 
                    $name = 'newsletter_' . (!empty($group) && !empty($group['name']) ? mb_url_title($group['name'], '_', true) . '_' : '') . date('Ymd') . '.txt';
                    $separator = "\t";
                    $data = lang('Newsletter.Email') . $separator . lang('Newsletter.FirstName') . $separator . lang('Newsletter.LastName') . PHP_EOL;
                    foreach ($emails as $key=>$line){ 
                       $data .= implode($separator, array($line['email'], $line['name'], $line['surname'])) . PHP_EOL;
                    }
                    break;
                case 'csv':
                default:
                    $name = 'newsletter_' . (!empty($group) && !empty($group['name']) ? mb_url_title($group['name'], '_', true) . '_' : '') . date('Ymd') . '.csv';
                    $separator = ";";
                    $data = lang('Newsletter.Email') . $separator . lang('Newsletter.FirstName') . $separator . lang('Newsletter.LastName') . PHP_EOL;
                    foreach ($emails as $key=>$line){ 
                       $data .= implode($separator, array($line['email'], $line['name'], $line['surname'])) . PHP_EOL;
                    }
                    break;
            }
            return $this->response->download($name, $data);
        }
    }

    private function deleteGroup($id) {
        $result = $this->newsletterModel->deleteGroup($id);
        return $this->response->setJSON(array(
            'status' => true,
            'result' => $result,
            'id' => $id,
            'msg' => $result ? lang('Newsletter.Removed') : lang('Newsletter.Error')
        ));
        HistoryStat($id, '', 'newsletter', 'Newsletter', $result ? lang('Newsletter.Removed') : lang('Newsletter.Error'));
    }

    private function publishGroup($id) {
        $group = $this->newsletterModel->db->table('newsletter_group')->select('id,publish')->where('id', $id)->get()->getRowArray();
        if (!empty($group)) {
            $r = $this->newsletterModel->db->table('newsletter_group')->where('id', $id)->set('publish', $group['publish'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $group['publish'] ? 0 : 1,
                'msg' => $group['publish'] ? lang('Newsletter.Republished') : lang('Newsletter.Published')
            );
            HistoryStat($id, '', 'newsletter', 'Newsletter', $group['publish'] ? lang('Newsletter.Republished') : lang('Newsletter.Published'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $group['publish'],
                'msg' => lang('Newsletter.Error')
            );
        }
        return $this->response->setJSON($response);
    }

    private function deleteMail($id) {
        $result = $this->newsletterModel->deleteEmail($id);
        return $this->response->setJSON(array(
            'status' => true,
            'result' => $result,
            'id' => $id,
            'msg' => $result ? lang('Newsletter.Removed') : lang('Newsletter.Error')
        ));
        HistoryStat($id, '', 'newsletter', 'Newsletter', $result ? lang('Newsletter.Removed') : lang('Newsletter.Error'));
    }

    private function agreementMail($id) {
        $email = $this->newsletterModel->db->table('newsletter_email')->select('id,agreement')->where('id', $id)->get()->getRowArray();
        if (!empty($email)) {
            $r = $this->newsletterModel->db->table('newsletter_email')->where('id', $id)->set('agreement', $email['agreement'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $email['agreement'] ? 0 : 1,
                'msg' => $email['agreement'] ? lang('Newsletter.TurnedOff') : lang('Newsletter.TurnedOn')
            );
            HistoryStat($id, '', 'newsletter', 'Newsletter', $email['agreement'] ? lang('Newsletter.TurnedOff') : lang('Newsletter.TurnedOn'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $email['agreement'],
                'msg' => lang('Newsletter.Error')
            );
        }
        return $this->response->setJSON($response);
    }

    public function pageContent($id_content, $slug = '') {
        helper('filesystem');
        $groups = array();
        $list = $this->newsletterModel->db->table('newsletter_group ng')->join('newsletter_group_lang ngl', 'ng.id=ngl.id_group')->select('ng.id,ngl.name')->where('ngl.id_lang', $this->id_lang)->where('ng.publish', 1)->orderBy('ngl.name', 'ASC')->get()->getResultArray();
        if(!empty($list)) {
            foreach($list as $l) {
                $l['link'] = ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/newsletter/group-edit/' . $l['id'];
                $groups[$l['id']] = $l;
            }
        }
        $templates = get_templates_by_dir('modules/Newsletter/Views/user');
        return array(
            'elements' => $groups,
            'pc_templates' => $templates
        );
    }

}
