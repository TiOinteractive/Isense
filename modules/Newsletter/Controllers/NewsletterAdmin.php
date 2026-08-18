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
        $newsletter = array();
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
        $this->breadcrumb->add(lang('Newsletter.Newsletter'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/newsletter');
        switch ($action) {
            case 'statistics':
                $get = $this->request->getGet();
                $newsletter = $this->newsletterModel->getHistroyById($id);
                $statistics = $this->newsletterModel->getHistroyStatisticsById($id, $get);
                $this->breadcrumb->add(lang('Newsletter.NewsletterHistory'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/newsletter/history/' . $newsletter['id_newsletter']);
                $this->breadcrumb->add(lang('Newsletter.Statistics') . ': ' . $newsletter['subject'], ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/newsletter/statistics/' . $id);
                $breadcrumb = $this->breadcrumb->render();
                echo view('Modules\Newsletter\Views\admin\statistics', array('action' => $action, 'newsletter' => $newsletter, 'statistics' => $statistics, 'breadcrumbs' => $breadcrumb, 'filters' => $get));
                break;
            case 'history-preview':
                $newsletter = $this->newsletterModel->getHistroyById($id);
                $this->breadcrumb->add(lang('Newsletter.NewsletterHistory'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/newsletter/history/' . $newsletter['id_newsletter']);
                $this->breadcrumb->add(lang('Newsletter.NewsletterPreview') . ': ' . $newsletter['subject'], ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/newsletter/preview/' . $id);
                $breadcrumb = $this->breadcrumb->render();
                echo view('Modules\Newsletter\Views\admin\preview', array('action' => $action, 'newsletter' => $newsletter, 'breadcrumbs' => $breadcrumb));
                break;
            case 'preview':
                $newsletter = $this->newsletterModel->getNewsletterById($id);
                $this->breadcrumb->add(lang('Newsletter.NewsletterPreview') . ': ' . $newsletter['subject'], ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/newsletter/preview/' . $id);
                $breadcrumb = $this->breadcrumb->render();
                echo view('Modules\Newsletter\Views\admin\preview', array('action' => $action, 'newsletter' => $newsletter, 'breadcrumbs' => $breadcrumb));
                break;
            case 'history':
                $history = $this->newsletterModel->getNewsletterHistroyById($id);
                $newsletter = $this->newsletterModel->getNewsletterById($id);
                $this->breadcrumb->add(lang('Newsletter.NewsletterHistory') . ': ' . $newsletter['name'], ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/newsletter/history/' . $id);
                $breadcrumb = $this->breadcrumb->render();
                echo view('Modules\Newsletter\Views\admin\history', array('action' => $action, 'newsletter' => $newsletter, 'history' => $history, 'breadcrumbs' => $breadcrumb));
                break;
            case 'send':
                $settings = array();
                $list = $this->newsletterModel->db->table('settings s')->join('settings_lang sl', 's.id=sl.id_settings', 'left')->select('s.name,CASE WHEN s.value IS NULL THEN sl.value ELSE s.value END as value', false)->groupStart()->where('sl.id_lang', $this->id_lang)->orWhere('sl.id', null)->groupEnd()->whereIn('s.name', array('company_name', 'company_short_name', 'neewsletter_title_default', 'form_sender_email', 'form_sender'))->get()->getResultArray();
                if (!empty($list)) {
                    foreach ($list as $l) {
                        $settings[$l['name']] = $l['value'];
                    }
                }
                $config = new \Config\Email();
                $newsletter = $this->newsletterModel->getNewsletterById($id);
                $subquery = $this->newsletterModel->db->table('newsletter_email ne')->where('ne.id_group', 'ng.id', false)->where('ne.active', 1)->selectCount('ne.id');
                $groups = $query = $this->newsletterModel->db->table('newsletter_group ng')->join('newsletter_group_lang ngl', 'ng.id=ngl.id_group')->select('ng.id,ngl.name')->selectSubquery($subquery, 'count')->where('ngl.id_lang', $this->id_lang)->orderBy('ngl.name', 'ASC')->get()->getResultArray();
                $this->breadcrumb->add(lang('Newsletter.NewsletterSend') . ': ' . $newsletter['name'], ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/newsletter/send/' . $id);
                $breadcrumb = $this->breadcrumb->render();
                echo view('Modules\Newsletter\Views\admin\send', array('action' => $action, 'newsletter' => $newsletter, 'groups' => $groups, 'email_config' => $config, 'settings' => $settings, 'breadcrumbs' => $breadcrumb));
                break;
            case 'export':
                return $this->exportEmailBuilder();
                break;
            case 'edit':
                $newsletter = $this->newsletterModel->getNewsletterById($id);
            case 'add':
            case 'save':
                $templates = $this->newsletterModel->getNewsletterTemplates();
                $flashdata = $this->session->getFlashdata('newsletter');
                $this->breadcrumb->add(lang('Newsletter.EmailBuilder') . (!empty($newsletter['name']) ? ': ' . $newsletter['name'] : ''), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/newsletter/email-builder');
                $breadcrumb = $this->breadcrumb->render();
                echo view('Modules\Newsletter\Views\admin\email_builder', array('action' => $action, 'breadcrumbs' => $breadcrumb, 'newsletter' => $newsletter, 'templates' => $templates, 'flashdata' => $flashdata));
                break;
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
                        HistoryStat($id, '', 'newsletter', 'Newsletter', $id ? lang('Newsletter.EditEmailSuccess') : lang('Newsletter.AddEmailSuccess'));
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
                    $this->breadcrumb->add(lang('Newsletter.EmailEdit') . (!empty($email['email']) ? ': ' . $email['email'] : ''), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/newsletter/mail-edit/' . $id);
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
                $query = $this->newsletterEmailModel->select('id,agreement,active,email,name,surname');
                if (!empty($get)) {
                    foreach ($get as $name => $value) {
                        switch ($name) {
                            case 'name':
                                if (!empty($value)) {
                                    $query->groupStart();
                                    $query->like('name', $value);
                                    $query->orLike('surname', $value);
                                    $query->orLike('email', $value);
                                    $query->groupEnd();
                                }
                                break;
                            case 'group':
                                if (!empty($value)) {
                                    $query->where('id_group', $value);
                                }
                                break;
                            case 'agreement':
                                if (in_array($value, array(0, 1))) {
                                    $query->where('agreement', $value);
                                }
                                break;
                        }
                    }
                }


                if (empty($get['order'])) {
                    $get['order_array'] = array();
                    $query->orderBy('id', 'ASC');
                }
                if (!empty($get['order'])) {
                    $tmp = explode(',', $get['order']);
                    $get['order_array'][$tmp[0]] = $tmp[1] == 'asc' ? 'asc' : 'desc';
                    switch ($tmp[0]) {
                        case 'email': $query->orderBy('email', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
                        case 'name': $query->orderBy('surname', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            $query->orderBy('name', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
                        case 'active': $query->orderBy('active', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
                    }
                }


                $emails = $query->paginate(!empty($get['on_page']) ? $get['on_page'] : 20);
                if (!empty($get) && !empty($get['export'])) {
                    switch ($get['export']) {
                        case 'xls':
                        case 'xlsx':
                            $name = 'newsletter_' . date('Ymd') . '.xlsx';
                            $spreadsheet = new Spreadsheet();
                            $sheet = $spreadsheet->getActiveSheet();
                            $sheet->setCellValue('A1', lang('Newsletter.Email'));
                            $sheet->setCellValue('B1', lang('Newsletter.FirstName'));
                            $sheet->setCellValue('C1', lang('Newsletter.LastName'));
                            $count = 2;
                            foreach ($emails as $key => $line) {
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
                            foreach ($emails as $key => $line) {
                                $data .= implode($separator, array($line['email'], $line['name'], $line['surname'])) . PHP_EOL;
                            }
                            break;
                        case 'csv':
                        default:
                            $name = 'newsletter_' . date('Ymd') . '.csv';
                            $separator = ";";
                            $data = lang('Newsletter.Email') . $separator . lang('Newsletter.FirstName') . $separator . lang('Newsletter.LastName') . PHP_EOL;
                            foreach ($emails as $key => $line) {
                                $data .= implode($separator, array($line['email'], $line['name'], $line['surname'])) . PHP_EOL;
                            }
                            break;
                    }
                    return $this->response->download($name, $data);
                }
                $on_page_list = array(
                    20 => 20,
                    40 => 40,
                    80 => 80,
                );
                $groups = $query = $this->newsletterModel->db->table('newsletter_group ng')->join('newsletter_group_lang ngl', 'ng.id=ngl.id_group')->select('ng.id,ngl.name')->where('ngl.id_lang', $this->id_lang)->orderBy('ngl.name', 'ASC')->get()->getResultArray();
                echo view('Modules\Newsletter\Views\admin\email_list', array('emails' => $emails, 'breadcrumbs' => $breadcrumb, 'groups' => $groups, 'filters' => $get, 'on_page_list' => $on_page_list, 'action' => $action, 'pager' => $this->newsletterEmailModel->pager));
                break;
            case 'group-edit':
                $group = $this->newsletterModel->getGroupById($id, $this->id_lang);
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
                        HistoryStat($id, '', 'newsletter', 'Newsletter', $id ? lang('Newsletter.EditGroupSuccess') : lang('Newsletter.AddGroupSuccess'));
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
                    $this->breadcrumb->add(lang('Newsletter.GroupEdit') . (!empty($group['name']) ? ': ' . $group['name'] : ''), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/newsletter/group-edit/' . $id);
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
                if (!empty($get)) {
                    foreach ($get as $name => $value) {
                        switch ($name) {
                            case 'name':
                                if (!empty($value)) {
                                    $query->like('ngl.name', $value);
                                }
                                break;
                            case 'publish':
                                if (in_array($value, array(0, 1))) {
                                    $query->where('ng.publish', $value);
                                }
                                break;
                        }
                    }
                }



                if (empty($get['order'])) {
                    $get['order_array'] = array();
                    $query->orderBy('id', 'ASC');
                }
                if (!empty($get['order'])) {
                    $tmp = explode(',', $get['order']);
                    $get['order_array'][$tmp[0]] = $tmp[1] == 'asc' ? 'asc' : 'desc';
                    switch ($tmp[0]) {
                        case 'name': $query->orderBy('name', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
                        case 'publish': $query->orderBy('publish', $tmp[1] == 'asc' ? 'ASC' : 'DESC');
                            break;
                    }
                }
                $groups = $query->get()->getResultArray();
                if (!empty($groups)) {
                    foreach ($groups as $k => $group) {
                        //$groups[$k]['emails_active'] = $this->newsletterModel->db->table('newsletter_email')->where('id_group', $group['id'])->where('active', 1)->countAllResults();
                        //$groups[$k]['emails_agreement'] = $this->newsletterModel->db->table('newsletter_email')->where('id_group', $group['id'])->where('agreement', 1)->countAllResults();
                        $groups[$k]['emails_tosend'] = $this->newsletterModel->db->table('newsletter_email')->where('id_group', $group['id'])->where('active', 1)->where('agreement', 1)->countAllResults();
                        $groups[$k]['emails_all'] = $this->newsletterModel->db->table('newsletter_email')->where('id_group', $group['id'])->countAllResults();
                    }
                }

                echo view('Modules\Newsletter\Views\admin\group_list', array('groups' => $groups, 'breadcrumbs' => $breadcrumb, 'filters' => $get, 'action' => $action));
                break;
            default :
                $breadcrumb = $this->breadcrumb->render();
                $get = $this->request->getGet();
                $query = $this->newsletterModel->select('id,subject');
                if (!empty($get)) {
                    foreach ($get as $name => $value) {
                        switch ($name) {
                            case 'subject':
                                if (!empty($value)) {
                                    $query->like('subject', $value);
                                }
                                break;
                        }
                    }
                }
                $query->orderBy('created_at', 'DESC');
                $newsletters = $query->paginate(20);
                if (!empty($newsletters)) {
                    foreach ($newsletters as $n => $newsletter) {
                        $subquery = $this->newsletterModel->db->table('newsletter_stats ns')->where('ns.id_history', 'nh.id', false)->selectCount('ns.id');
                        $newsletters[$n]['history'] = $this->newsletterModel->db->table('newsletter_history nh')->select('nh.id,nh.hash,nh.emails,nh.status,nh.send_time,nh.edited_at,nh.created_at')->selectSubquery($subquery, 'emails_sent')->whereIn('nh.status', array('set', 'sending'))->where('nh.send_time>=', date('Y-m-d H:i:s', strtotime('-5 minute')))->get()->getResultArray();
                        $newsletters[$n]['history_list'] = $this->newsletterModel->getNewsletterHistroyById($newsletter['id']);
                    }
                }
                echo view('Modules\Newsletter\Views\admin\list', array('newsletters' => $newsletters, 'breadcrumbs' => $breadcrumb, 'filters' => $get, 'action' => $action, 'pager' => $this->newsletterModel->pager));
                break;
        }
    }

    public function assets($action = '') {
        $assets = array(
            'js' => array(),
            'js_ready' => array(),
            'css' => array(),
            'css_footer' => array(),
        );
        switch ($action) {
            case 'history':
            case 'send':
                $assets['js'][] = '/adm/js/newsletter.js';
                break;
            case 'edit':
            case 'add':
            case 'save':
                $assets['css_footer'][] = '/adm/third-party/jquery-file-upload-master/css/jquery.fileupload.css';
                $assets['css_footer'][] = '/adm/third-party/jquery-file-upload-master/css/jquery.fileupload-ui.css';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/tmpl.min.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/load-image.all.min.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/canvas-to-blob.min.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.blueimp-gallery.min.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.iframe-transport.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-process.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-image.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-audio.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-video.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-validate.js';
                $assets['js'][] = '/adm/third-party/jquery-file-upload-master/js/jquery.fileupload-ui.js';
                $assets['js'][] = '/adm/js/file-uploader.js';
                $assets['js'][] = '/adm/js/emailbuilder.js';
                /* $assets['css_footer'][] = '/adm/third-party/email-builder/css/editor.css';
                  $assets['js'][] = '/adm/third-party/email-builder/js/editor2.js';
                  $assets['js'][] = '/adm/third-party/email-builder/js/custom2.js'; */
                break;
            default:
                $assets['js'][] = '/adm/js/newsletter.js';
                break;
        }
        return $assets;
    }

    public function ajax($action = '', $id = 0) {
        $post = $this->request->getPost();
        if (!empty($action)) {
            switch ($action) {
                case 'edit':
                case 'add':
                case 'save':
                    if (!empty($post) && (!empty($post['module']) || (!empty($post['action']) && in_array($post['action'], array('template-save', 'template-load', 'template-delete'))))) {
                        if (empty($post['action'])) {
                            $post['action'] = '';
                        }
                        switch ($post['action']) {
                            case 'template-delete':
                                return $this->deleteEmailBuilderTemplate(!empty($post['id']) ? $post['id'] : 0);
                                break;
                            case 'template-load':
                                return $this->loadEmailBuilderTemplate(!empty($post['id']) ? $post['id'] : 0);
                                break;
                            case 'template-save':
                                return $this->saveEmailBuilderTemplate($post);
                                break;
                            case 'config':
                                return $this->emailBuilderModuleConfig($post);
                                break;
                            case 'builder':
                            default:
                                return $this->emailBuilderModule($post);
                                break;
                        }
                    } else {
                        return $this->saveEmailBuilder($id, $post);
                    }
                    break;
                case 'delete':
                    return $this->deleteNewsletter($id);
                    break;
                case 'group-publish':
                    return $this->publishGroup($id);
                    break;
                case 'group-delete':
                    return $this->deleteGroup($id);
                    break;
                case 'mail-agreement':
                    return $this->agreementMail($id);
                    break;
                case 'mail-active':
                    return $this->activeMail($id);
                    break;
                case 'mail-delete':
                    return $this->deleteMail($id);
                    break;
                case 'check-sending':
                    return $this->checkNewsletterSending($id);
                    break;
                case 'send':
                    return $this->sendNewsletter($id);
                    break;
                case 'send-test':
                    return $this->sendTestNewsletter($id);
                    break;
                case 'set-sending':
                    return $this->setSendingNewsletter($id);
                    break;
                case 'preview':
                    return $this->preview($id);
                    break;
                case 'history-preview':
                    return $this->historyPreview($id);
                    break;
                case 'cancel':
                    return $this->cancelNewsletter($id);
                    break;
            }
        }
    }

    private function exportGroupEmails($id_group, $ext) {
        $group = $this->newsletterModel->db->table('newsletter_group ng')->join('newsletter_group_lang ngl', 'ng.id=ngl.id_group')->select('ngl.name')->where('ng.id', $id_group)->get()->getRowArray();
        $emails = $this->newsletterModel->db->table('newsletter_email')->select('email,name,surname')->where('id_group', $id_group)->get()->getResultArray();
        if (!empty($emails)) {
            switch ($ext) {
                case 'xls':
                case 'xlsx':
                    $name = 'newsletter_' . (!empty($group) && !empty($group['name']) ? mb_url_title($group['name'], '_', true) . '_' : '') . date('Ymd') . '.xlsx';
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();
                    $sheet->setCellValue('A1', lang('Newsletter.Email'));
                    $sheet->setCellValue('B1', lang('Newsletter.FirstName'));
                    $sheet->setCellValue('C1', lang('Newsletter.LastName'));
                    $count = 2;
                    foreach ($emails as $key => $line) {
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
                    foreach ($emails as $key => $line) {
                        $data .= implode($separator, array($line['email'], $line['name'], $line['surname'])) . PHP_EOL;
                    }
                    break;
                case 'csv':
                default:
                    $name = 'newsletter_' . (!empty($group) && !empty($group['name']) ? mb_url_title($group['name'], '_', true) . '_' : '') . date('Ymd') . '.csv';
                    $separator = ";";
                    $data = lang('Newsletter.Email') . $separator . lang('Newsletter.FirstName') . $separator . lang('Newsletter.LastName') . PHP_EOL;
                    foreach ($emails as $key => $line) {
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

    private function activeMail($id) {
        $email = $this->newsletterModel->db->table('newsletter_email')->select('id,active')->where('id', $id)->get()->getRowArray();
        if (!empty($email)) {
            $r = $this->newsletterModel->db->table('newsletter_email')->where('id', $id)->set('active', $email['active'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $email['active'] ? 0 : 1,
                'msg' => $email['active'] ? lang('Newsletter.TurnedOff') : lang('Newsletter.TurnedOn')
            );
            HistoryStat($id, '', 'newsletter', 'Newsletter', $email['active'] ? lang('Newsletter.TurnedOff') : lang('Newsletter.TurnedOn'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $email['active'],
                'msg' => lang('Newsletter.Error')
            );
        }
        return $this->response->setJSON($response);
    }

    public function pageContent($id_content, $slug = '') {
        helper('filesystem');
        $groups = array();
        $list = $this->newsletterModel->db->table('newsletter_group ng')->join('newsletter_group_lang ngl', 'ng.id=ngl.id_group')->select('ng.id,ngl.name')->where('ngl.id_lang', $this->id_lang)->where('ng.publish', 1)->orderBy('ngl.name', 'ASC')->get()->getResultArray();
        if (!empty($list)) {
            foreach ($list as $l) {
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

    private function prepareEmailBuilderModule($data, $add_id = false) {
        $html = '';
        if (file_exists(ROOTPATH . 'modules/Newsletter/Views/admin/emailbuilder_modules/templates/' . $data['module'] . '.php')) {
            $view_data = array();
            if (!empty($data['data'])) {
                $view_data['data'] = $data['data'];
            }
            switch ($data['module']) {
                case 'news':
                    $news = $this->newsletterModel->db->table('news n')->join('news_lang nl', 'n.id=nl.id_news')->join('links l', 'l.id=nl.id_link')->select('n.id,nl.title,l.link')->where('nl.id_lang', $this->id_lang)->where('l.id_lang', $this->id_lang)->where('n.publish', 1)->where('n.newsletter', 1)->whereIn('n.id_page_cont', array(10, 11, 12, 13, 38))->orderBy('n.date', 'DESC')->limit(!empty($data) && !empty($data['data']) && !empty($data['data']['count']) ? $data['data']['count'] : 10)->get()->getResultArray();
                    if (!empty($news)) {
                        $view_data['news'] = array();
                        foreach ($news as $n) {
                            $n['photo'] = $this->newsletterModel->db->table('news_files nf')->join('news_files_lang nfl', 'nf.id=nfl.id_file')->select('nf.path,nf.crop_dimension,nfl.caption,nfl.author')->where('nf.id_news', $n['id'])->where('nf.publish', 1)->where('nf.field', 'photo')->where('nfl.id_lang', $this->id_lang)->get()->getRowArray();
                            $view_data['news'][] = array(
                                'name' => $n['title'],
                                'link' => base_url() . $n['link'],
                                'img' => base_url() . 'image/ext/c/460/300/' . (!empty($n['photo']) ? $n['photo']['path'] : ''),
                                'original_img' => base_url() . 'image/ext/' . (!empty($n['photo']) ? $n['photo']['path'] : ''),
                            );
                        }
                    }
                    break;
                case 'entertainment':
                    $entertainment_news = $this->newsletterModel->db->table('news n')->join('news_lang nl', 'n.id=nl.id_news')->join('links l', 'l.id=nl.id_link')->select('n.id,nl.title,l.link')->where('nl.id_lang', $this->id_lang)->where('l.id_lang', $this->id_lang)->where('n.publish', 1)->where('n.newsletter', 1)->whereIn('n.id_page_cont', array(3))->orderBy('n.date', 'DESC')->limit(!empty($data) && !empty($data['data']) && !empty($data['data']['count']) ? $data['data']['count'] : 6)->get()->getResultArray();
                    if (!empty($entertainment_news)) {
                        $view_data['entertainment'] = array();
                        foreach ($entertainment_news as $n) {
                            $n['photo'] = $this->newsletterModel->db->table('news_files nf')->join('news_files_lang nfl', 'nf.id=nfl.id_file')->select('nf.path,nf.crop_dimension,nfl.caption,nfl.author')->where('nf.id_news', $n['id'])->where('nf.publish', 1)->where('nf.field', 'photo')->where('nfl.id_lang', $this->id_lang)->get()->getRowArray();
                            $view_data['entertainment'][] = array(
                                'name' => $n['title'],
                                'link' => base_url() . $n['link'],
                                'img' => base_url() . 'image/ext/c/460/300/' . (!empty($n['photo']) ? $n['photo']['path'] : ''),
                                'original_img' => base_url() . 'image/ext/' . (!empty($n['photo']) ? $n['photo']['path'] : ''),
                            );
                        }
                    }
                    break;
                case 'cinema':
                    $cinema = $this->newsletterModel->db->table('cinema_movie cm')->join('cinema_movie_lang cml', 'cm.id=cml.id_movie')->join('links l', 'l.id=cml.id_link')->join('cinema_calendar cc', 'cm.id=cc.id_movie')->select('cm.id,cml.title,l.link')->where('cml.id_lang', $this->id_lang)->where('l.id_lang', $this->id_lang)->where('cm.publish', 1)->where('cc.date >=', date('Y-m-d'))->orderBy('cc.date', 'ASC')->groupBy('cm.id')->limit(!empty($data) && !empty($data['data']) && !empty($data['data']['count']) ? $data['data']['count'] : 8)->get()->getResultArray();
                    if (!empty($cinema)) {
                        $view_data['cinema'] = array();
                        foreach ($cinema as $c) {
                            $c['photo'] = $this->newsletterModel->db->table('cinema_files cf')->join('cinema_files_lang cfl', 'cf.id=cfl.id_file')->select('cf.path,cfl.caption,cfl.author')->where('cf.id_cinema', $c['id'])->where('cf.publish', 1)->where('cf.field', 'movie_poster')->where('cfl.id_lang', $this->id_lang)->get()->getRowArray();
                            $view_data['cinema'][] = array(
                                'name' => $c['title'],
                                'link' => base_url() . $c['link'],
                                'img' => base_url() . 'image/ext/c/400/578/' . (!empty($c['photo']) ? $c['photo']['path'] : ''),
                                'original_img' => base_url() . 'image/ext/' . (!empty($c['photo']) ? $c['photo']['path'] : ''),
                            );
                        }
                    }
                    break;
                case 'calendar':
                    $date_start = date('Y-m-d');
                    $events = $this->newsletterModel->db->table('event_calendar')->join('event e', 'e.id=event_calendar.id_event')
                                    ->join('event_lang el', 'e.id=el.id_event')
                                    ->join('event_place ep', 'ep.id=event_calendar.id_place', 'left')
                                    ->join('event_place_lang epl', 'ep.id=epl.id_place', 'left')
                                    ->join('links l', 'l.id=el.id_link')
                                    ->join('links l2', 'l2.id=epl.id_link', 'left')
                                    ->join('event_type_lang etl', 'etl.id_type=e.id_type', 'left')
                                    ->select('IF(date_end IS NULL, 1, 0) as period,event_calendar.id,event_calendar.id_event,event_calendar.id_place,e.for_kids,e.patronage,el.price,el.name,l.link,event_calendar.custom_place,event_calendar.date_start,event_calendar.date_end,event_calendar.hours,epl.name as place,l2.link as place_link,etl.name as type_name,etl.slug as type_slug')
                                    ->where('el.id_lang', $this->id_lang)
                                    ->where('e.publish', 1)
                                    ->groupStart()
                                    ->where('epl.id_lang', $this->id_lang)
                                    ->orWhere('ep.id', NULL)
                                    ->groupEnd()
                                    ->groupStart()
                                    ->where('ep.publish', 1)
                                    ->orWhere('event_calendar.custom_place !=', '')
                                    ->groupEnd()
                                    ->groupStart()
                                    ->where('etl.id_lang', $this->id_lang)
                                    ->orWhere('etl.id', null)
                                    ->groupEnd()
                                    ->groupStart()
                                    ->groupStart()
                                    ->where('event_calendar.date_start >=', $date_start)
                                    ->where('event_calendar.date_end', null)
                                    ->groupEnd()
                                    ->orWhere('event_calendar.date_end >=', $date_start)
                                    ->groupEnd()
                                    ->orderBy('period', 'DESC')
                                    ->orderBy('event_calendar.date_start', 'ASC')
                                    ->orderBy('event_calendar.hours', 'ASC')
                                    ->groupBy('e.id')
                                    ->limit(!empty($data) && !empty($data['data']) && !empty($data['data']['count']) ? $data['data']['count'] : 16)
                                    ->get()->getResultArray();
                    if (!empty($events)) {
                        $view_data['calendar'] = array();
                        foreach ($events as $e) {
                            $e['photo'] = $this->newsletterModel->db->table('event_files ef')->join('event_files_lang efl', 'ef.id=efl.id_file')->select('ef.path,efl.caption,efl.author')->where('ef.id_event', $e['id_event'])->where('ef.publish', 1)->where('ef.field', 'photo')->where('efl.id_lang', $this->id_lang)->get()->getRowArray();
                            $view_data['calendar'][] = array(
                                'name' => $e['name'],
                                'link' => base_url() . $e['link'],
                                'img' => base_url() . 'image/ext/c/600/400/' . (!empty($e['photo']) ? $e['photo']['path'] : ''),
                                'original_img' => base_url() . 'image/ext/' . (!empty($e['photo']) ? $e['photo']['path'] : ''),
                                "club" => !empty($e['place']) ? $e['place'] : $e['custom_place'],
                                "club_link" => !empty($e['place_link']) ? base_url() . $e['place_link'] : '',
                                "date_from" => $e['date_start'],
                                "date_to" => $e['date_end'],
                                "type" => $e['type_name'],
                            );
                        }
                    }
                    break;
            }
            $view_data['width'] = 800;
            $html = view('Modules\Newsletter\Views/admin/emailbuilder_modules/templates/' . $data['module'], $view_data);
            if (empty($data['id'])) {
                $html = '<div class="module" data-module="' . $data['module'] . '" data-id="' . uniqid() . '">' . $html . '</div>';
            } elseif ($add_id && !empty($data['id'])) {
                $html = '<div class="module" data-module="' . $data['module'] . '" data-id="' . $data['id'] . '">' . $html . '</div>';
            }
        }
        return $html;
    }

    private function emailBuilderModule($data) {
        $html = $this->prepareEmailBuilderModule($data);
        $response = array(
            'html' => base64_encode(urlencode($html)),
            'id' => !empty($data['id']) ? $data['id'] : '',
            'module' => !empty($data['module']) ? $data['module'] : '',
        );
        return $this->response->setJSON($response);
    }

    private function deleteEmailBuilderTemplate($id) {
        $result = false;
        if (!empty($id)) {
            $result = $this->newsletterModel->db->table('newsletter_tpls')->where('id', $id)->delete();
        }
        $templates = $this->newsletterModel->getNewsletterTemplates();
        $html = view('Modules\Newsletter\Views\admin\email_builder_tpls', array('templates' => $templates, 'locale' => $this->locale, 'action' => 'edit'));
        $response = array(
            'html' => base64_encode(urlencode($html)),
            'message' => $result ? lang('Newsletter.emailbuilder.NewsletterTemplateDeleted') : lang('Newsletter.emailbuilder.NewsletterTemplateDeleteError'),
            'status' => true,
        );
        return $this->response->setJSON($response);
    }

    private function loadEmailBuilderTemplate($id) {
        $result = false;
        $html = '';
        if (!empty($id)) {
            $result = true;
            $template = $this->newsletterModel->db->table('newsletter_tpls')->select('id,name,modules')->where('id', $id)->get()->getRowArray();
            if (!empty($template)) {
                $template['modules'] = json_decode($template['modules'], true);
                if (!empty($template['modules'])) {
                    foreach ($template['modules'] as $module_conf) {
                        $html .= $this->prepareEmailBuilderModule($module_conf, true);
                    }
                }
            }
        }
        $response = array(
            'html' => base64_encode(urlencode($html)),
            'message' => $result ? lang('Newsletter.emailbuilder.NewsletterTemplateLoaded') : lang('Newsletter.emailbuilder.NewsletterTemplateLoadError'),
            'status' => true,
        );
        return $this->response->setJSON($response);
    }

    private function saveEmailBuilderTemplate($post) {
        helper('text');
        $errors = array();
        $result = false;
        if (!empty($post['id'])) {
            $is_template = $this->newsletterModel->db->table('newsletter_tpls')->select('id,name')->where('id', $post['id'])->get()->getRowArray();
            if (!empty($is_template) && empty($post['name'])) {
                $post['name'] = $is_template['name'];
            }
        }
        if (empty($post['name'])) {
            $errors['name'] = lang('Newsletter.emailbuilder.NewsletterTemplateNameError');
        }
        $data = array(
            'name' => !empty($post['name']) ? $post['name'] : date('d.m.Y H:i'),
            'modules' => !empty($post['modules']) ? json_encode($post['modules']) : ''
        );

        if (empty($errors)) {
            if (!empty($is_template)) {
                $result = $this->newsletterModel->db->table('newsletter_tpls')->where('id', $post['id'])->update($data);
            } else {
                $result = $this->newsletterModel->db->table('newsletter_tpls')->insert($data);
            }
        }
        $templates = $this->newsletterModel->getNewsletterTemplates();
        $html = view('Modules\Newsletter\Views\admin\email_builder_tpls', array('templates' => $templates, 'locale' => $this->locale, 'action' => 'edit'));
        $response = array(
            'html' => base64_encode(urlencode($html)),
            'message' => $result ? lang('Newsletter.emailbuilder.NewsletterTemplateSaved') : lang('Newsletter.emailbuilder.NewsletterTemplateSaveError'),
            'status' => $result,
            'errors' => $errors,
        );
        return $this->response->setJSON($response);
    }

    private function emailBuilderModuleConfig($data) {
        $html = '';
        if (file_exists(ROOTPATH . 'modules/Newsletter/Views/admin/emailbuilder_modules/configs/' . $data['module'] . '.php')) {
            $view_data = $data;
            $view_data['locale'] = $this->locale;
            $html = view('Modules\Newsletter\Views/admin/emailbuilder_modules/configs/' . $data['module'], $view_data);
        }
        $response = array(
            'html' => base64_encode(urlencode($html)),
            'id' => !empty($data['id']) ? $data['id'] : '',
            'module' => !empty($data['module']) ? $data['module'] : '',
        );
        return $this->response->setJSON($response);
    }

    private function saveEmailBuilder($id, $post) {
        helper('text');
        $errors = array();
        $result = false;
        if (empty($post['name'])) {
            $errors['name'] = lang('Newsletter.emailbuilder.NewsletterNameError');
        }
        if (!empty($post['html'])) {
            $post['html'] = urldecode(base64_decode($post['html']));
        }
        if (empty($post['html'])) {
            $errors['html'] = lang('Newsletter.emailbuilder.NewsletterHtmlError');
        }
        if (empty($errors)) {
            if (!is_dir(WRITEPATH . 'newsletter')) {
                $r = @mkdir(WRITEPATH . 'newsletter', 0755, true);
            }
            if (!is_dir(WRITEPATH . 'newsletter/' . date('Y'))) {
                @mkdir(WRITEPATH . 'newsletter/' . date('Y'), 0755, true);
            }
            $data = array(
                'subject' => !empty($post['name']) ? $post['name'] : date('Y-m-d H:i:s'),
                'plain_text' => !empty($post['plain_text']) ? $post['plain_text'] : '',
                'html_text' => !empty($html) ? $html : '',
            );
            $this->newsletterModel->db->transStart();
            $newsletter = $this->newsletterModel->db->table('newsletter')->select('id,hash,file')->where('id', $id)->get()->getRowArray();
            if (!empty($newsletter)) {
                $result = $this->newsletterModel->db->table('newsletter')->set($data)->where('id', $id)->update();
                $hash = $newsletter;
                $data['file'] = !empty($newsletter['file']) ? $newsletter['file'] : date('Y') . '/' . $hash;
            } else {
                $hash = random_string('sha1');
                $count = 1;
                do {
                    $is = $this->newsletterModel->db->table('newsletter')->select('id')->where('hash', $hash)->get()->getRowArray();
                    if (!empty($is)) {
                        $hash = random_string('sha1');
                    }
                    ++$count;
                } while (!empty($is) && $count <= 1000);
                $data['hash'] = $hash;
                $data['file'] = date('Y') . '/' . $hash . '.html';
                $result = $this->newsletterModel->db->table('newsletter')->insert($data);
                $id = $this->newsletterModel->db->insertID();
            }
            $this->newsletterModel->db->transComplete();
            $result = $this->newsletterModel->db->transStatus();
            if ($result) {
                $r = write_file(WRITEPATH . 'newsletter/' . $data['file'], $post['html']);
                $this->session->setFlashdata('newsletter', array(
                    'status' => true,
                    'msg' => lang('Newsletter.emailbuilder.NewsletterSaved') . '!'
                ));
            }
        }
        $response = array(
            'message' => $result ? lang('Newsletter.emailbuilder.NewsletterSaved') : lang('Newsletter.emailbuilder.NewsletterSaveError'),
            'redirect' => ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/newsletter/' . ($id ? 'edit/' . $id : 'add'),
            'status' => $result,
            'errors' => $errors,
        );
        return $this->response->setJSON($response);
    }

    private function emailBuilder($id) {
        helper('text');
        $post = $this->request->getPost();
        if (isset($post['process_campaign'])) {
            $html = "";
            if (!empty($post['html_text'])) {
                $html = base64_decode($post['html_text']);
                $html = preg_replace('/data-medium-editor-editor-index[^>]*/', '', $html);
                $html = preg_replace('/data-medium-editor-element[^>]*/', '', $html);
                $html = preg_replace('/contenteditable[^>]*/', '', $html);
                $html = preg_replace('/spellcheck[^>]*/', '', $html);
                $html = preg_replace('/aria-multiline[^>]*/', '', $html);
                $html = base64_encode($html);
            }

            $data = array(
                'subject' => !empty($post['subject']) ? $post['subject'] : date('Y-m-d H:i:s'),
                'plain_text' => !empty($post['plain_text']) ? $post['plain_text'] : '',
                'html_text' => !empty($html) ? $html : '',
            );
            $hash = random_string('sha1');
            $count = 1;
            do {
                $is = $this->newsletterModel->db->table('newsletter_history')->select('id')->where('hash', $hash)->get()->getRowArray();

                if (!empty($is)) {
                    $hash = random_string('sha1');
                }
                ++$count;
            } while (!empty($is) && $count <= 1000);
            $data['hash'] = $hash;
            if ($id) {
                $result = $this->newsletterModel->db->table('newsletter')->set($data)->where('id', $id)->update();
                $this->id = $id;
            } else {
                $result = $this->newsletterModel->db->table('newsletter')->insert($data);
                $this->id = $this->newsletterModel->db->insertID();
            }

            $response = array(
                'message' => array(
                    'status' => $result,
                    'message' => $result ? lang('Newsletter.emailbuilder.CampaignSaved') : lang('Newsletter.emailbuilder.CampaignSaveError'),
                ),
                'type' => 'success'
            );
            return $this->response->setJSON($response);
        }
    }

    private function exportEmailBuilder() {
        $post = $this->request->getPost();
        if (!empty($post['type'])) {
            $type = $post['type'];
            $template = $post['theme'];
            switch ($post['type']) {
                case 'html':
                    $template_html = base64_decode($post['templateHTML']);
                    if (empty($template_html)) {
                        $template_html = ' ';
                    }
                    return $this->response->download('aaaaa.html', $template_html);
                    break;
                case 'zip':
                    $file = @tempnam("/tmp", "zip");
                    $zip = new \ZipArchive();
                    $zip->open($file, \ZipArchive::OVERWRITE);
                    $template_html = base64_decode($post['templateHTML']);
                    $template_html = 'aaaaa';
                    /* $usedImages = array();
                      // Get used images
                      preg_match_all('#([^/]+)\.jpg|jpeg|png|gif#i', $template_html, $usedImages );
                      // Remove repeated images
                      $usedImages = array_unique($usedImages[0]);
                      // Transform list of used images into string
                      $usedImages = implode(',', $usedImages);
                      $newImgBase = 'img';
                      // Change absolute image paths to relative across all img tags
                      $template_html = preg_replace('#(<img.*src=")[^"]+(\/(.*)">)#i', "$1{$newImgBase}$2", $template_html);
                      // Change absolute image paths to relative across all background images
                      $template_html = preg_replace('#(<.*background=")[^"]+(\/(.*)">)#i', "$1{$newImgBase}$2", $template_html);
                      $template_html = preg_replace('#(<.*style=".*url\("|\'|\&quot;)[^"]+(\/(.*)\).*"|\'|\&quot;>)#i', "$1{$newImgBase}$2", $template_html);
                     * 
                     */
                    //  echo $templateHTML;die();
                    $zip->addFromString('index.html', $template_html);
                    //   $zip->addFile('file_on_server.ext', 'second_file_name_within_archive.ext');
                    $options = array('add_path' => 'img/', 'remove_all_path' => TRUE);
                    // $zip->addGlob('uploads/*.{jpg,jpeg,gif,png}', GLOB_BRACE, $options);
                    // Add used images only
                    //$zip->addGlob( '../uploads/{' . $usedImages . '}', GLOB_BRACE, $options);

                    $options = array('add_path' => 'img/', 'remove_all_path' => TRUE);
                    //$zip->addGlob('../templates/' . $template . '/img/{'. $usedImages .'}', GLOB_BRACE, $options);
                    // Close and send to users
                    $zip->close();
                    return $this->response->download('aaaaa.zip', $file);
                    exit();
                    break;
            }
        }
    }

    private function checkNewsletterSending($id) {
        $post = $this->request->getPost();
        if (!empty($post['groups'])) {
            $emails = $this->newsletterModel->db->table('newsletter_email')->whereIn('id_group', $post['groups'])->where('active', 1)->groupBy('email')->countAllResults();
            $response = array(
                'emails' => $emails,
                'msg' => lang('Newsletter.ConfirmNewsletterSending'),
                'msg2' => lang('Newsletter.ConfirmNewsletterSummary', [$emails]),
                'status' => true,
            );
        } else {
            $response = array(
                'emails' => 0,
                'msg' => lang('Newsletter.NoGroupsChecked'),
                'status' => false,
            );
        }
        return $this->response->setJSON($response);
    }

    private function setSendingNewsletter($id) {
        helper('text');
        $post = $this->request->getPost();
        $newsletter = $this->newsletterModel->db->table('newsletter')->select('subject,plain_text,html_text,status,file')->where('id', $id)->get()->getRowArray();
        $is_history = $this->newsletterModel->db->table('newsletter_history')->select('subject,plain_text,html_text,status,file')->where('id_newsletter', $id)->whereIn('status', array('sending', 'set'))->get()->getRowArray();
        $html_text = '';
        if (!empty($newsletter['file']) && file_exists(WRITEPATH . 'newsletter/' . $newsletter['file'])) {
            $html_text = file_get_contents(WRITEPATH . 'newsletter/' . $newsletter['file']);
        }
        if (empty($html_text)) {
            $response = array(
                'status' => false,
                'msg' => lang('Newsletter.EmptyMessage'),
            );
        } elseif (empty($post['groups'])) {
            $response = array(
                'status' => false,
                'msg' => lang('Newsletter.NoGroupsChecked'),
            );
        } elseif (empty($post['sending_date'])) {
            $response = array(
                'status' => false,
                'msg' => lang('Newsletter.EmptySendingDate'),
            );
        } elseif (empty($is_history['status'])) {
            $hash = random_string('sha1');
            $count = 1;
            do {
                $is = $this->newsletterModel->db->table('newsletter_history')->select('id')->where('hash', $hash)->get()->getRowArray();
                if (!empty($is)) {
                    $hash = random_string('sha1');
                }
                ++$count;
            } while (!empty($is) && $count <= 1000);
            $history = array(
                'id_newsletter' => $id,
                'hash' => $hash,
                'groups' => implode(',', $post['groups']),
                'subject' => !empty($post['subject']) ? $post['subject'] : '',
                'from_name' => !empty($post['from_name']) ? $post['from_name'] : '',
                'from_email' => !empty($post['from_email']) ? $post['from_email'] : '',
                'reply_to' => !empty($post['reply_to']) ? $post['reply_to'] : '',
                'plain_text' => $newsletter['plain_text'],
                'html_text' => $newsletter['html_text'],
                'file' => $newsletter['file'],
                'status' => 'set',
                'send_time' => date('Y-m-d H:i:s', strtotime($post['sending_date'])),
                'utm' => json_encode(array(
                    'source' => !empty($post['utm_source']) ? $post['utm_source'] : '',
                    'medium' => !empty($post['utm_medium']) ? $post['utm_medium'] : '',
                    'campaign' => !empty($post['utm_campaign']) ? $post['utm_campaign'] : '',
                )),
                'emails' => $this->newsletterModel->db->table('newsletter_email')->whereIn('id_group', $post['groups'])->where('active', 1)->groupBy('email')->countAllResults(),
            );
            $this->newsletterModel->db->transStart();
            $this->newsletterModel->db->table('newsletter_history')->insert($history);
            $history['id'] = $this->newsletterModel->db->insertID();
            $history['file'] = date('Y') . '/history' . $history['id'] . '_' . $hash . '.html';
            $this->newsletterModel->db->table('newsletter_history')->set('file', $history['file'])->where('id', $history['id'])->update();
            $this->newsletterModel->db->transComplete();
            $result = $this->newsletterModel->db->transStatus();
            if ($result) {
                $newsletter_head_and_foot = $this->getNewsletterHeadAndFoot(!empty($history['from_name']) ? $history['from_name'] : '', !empty($history['subject']) ? $history['subject'] : '');
                $html_text = $newsletter_head_and_foot['head'] . $html_text . $newsletter_head_and_foot['foot'];
                if (!is_dir(WRITEPATH . 'newsletter')) {
                    $r = @mkdir(WRITEPATH . 'newsletter', 0755, true);
                }
                if (!is_dir(WRITEPATH . 'newsletter/' . date('Y'))) {
                    @mkdir(WRITEPATH . 'newsletter/' . date('Y'), 0755, true);
                }
                write_file(WRITEPATH . 'newsletter/' . $history['file'], $html_text);
                //copy(WRITEPATH . 'newsletter/' . $newsletter['file'], WRITEPATH . 'newsletter/' . $history['file']);
                $command = "at -f /home/www/resinet/html/modules/Newsletter/send_at_newsletter " . date('H:i m/d/Y ', strtotime($post['sending_date']));
                exec($command);
                $response = array(
                    'status' => true,
                    'msg' => lang('Newsletter.SetSendingSuccess'),
                );
            } else {
                $response = array(
                    'status' => false,
                    'msg' => lang('Newsletter.SetSendingError'),
                );
            }
        } else {
            $response = array(
                'status' => false,
                'msg' => lang('Newsletter.SetSendingExist'),
            );
        }
        return $this->response->setJSON($response);
    }

    private function sendNewsletter($id) {
        helper('text');
        $post = $this->request->getPost();
        $newsletter = $this->newsletterModel->db->table('newsletter')->select('subject,plain_text,html_text,status,file')->where('id', $id)->get()->getRowArray();
        $html_text = '';
        if (!empty($newsletter['file']) && file_exists(WRITEPATH . 'newsletter/' . $newsletter['file'])) {
            $html_text = file_get_contents(WRITEPATH . 'newsletter/' . $newsletter['file']);
        }
        //if(empty(base64_decode($newsletter['html_text']))) {
        if (empty($html_text)) {
            $response = array(
                'status' => false,
                'msg' => lang('Newsletter.EmptyMessage'),
            );
        } elseif ($newsletter['status'] != 'sending') {
            $hash = random_string('sha1');
            $count = 1;
            do {
                $is = $this->newsletterModel->db->table('newsletter_history')->select('id')->where('hash', $hash)->get()->getRowArray();
                if (!empty($is)) {
                    $hash = random_string('sha1');
                }
                ++$count;
            } while (!empty($is) && $count <= 1000);
            $history = array(
                'id_newsletter' => $id,
                'hash' => $hash,
                'groups' => implode(',', $post['groups']),
                'subject' => !empty($post['subject']) ? $post['subject'] : '',
                'from_name' => !empty($post['from_name']) ? $post['from_name'] : '',
                'from_email' => !empty($post['from_email']) ? $post['from_email'] : '',
                'reply_to' => !empty($post['reply_to']) ? $post['reply_to'] : '',
                'plain_text' => $newsletter['plain_text'],
                'html_text' => $newsletter['html_text'],
                'file' => $newsletter['file'],
                'status' => 'sending',
                'emails' => $this->newsletterModel->db->table('newsletter_email')->whereIn('id_group', $post['groups'])->where('active', 1)->groupBy('email')->countAllResults(),
            );
            $this->newsletterModel->db->transStart();
            $this->newsletterModel->db->table('newsletter_history')->insert($history);
            $history['id'] = $this->newsletterModel->db->insertID();
            $history['file'] = date('Y') . '/history' . $history['id'] . '_' . $hash . '.html';
            $this->newsletterModel->db->table('newsletter_history')->set('file', $history['file'])->where('id', $history['id'])->update();
            $this->newsletterModel->db->table('newsletter')->set('status', 'sending')->where('id', $id)->update();
            $this->newsletterModel->db->transComplete();
            $result = $this->newsletterModel->db->transStatus();
            if ($result) {
                $newsletter_head_and_foot = $this->getNewsletterHeadAndFoot(!empty($history['from_name']) ? $history['from_name'] : '', !empty($history['subject']) ? $history['subject'] : '');
                $html_text = $newsletter_head_and_foot['head'] . $html_text . $newsletter_head_and_foot['foot'];
                if (!is_dir(WRITEPATH . 'newsletter')) {
                    $r = @mkdir(WRITEPATH . 'newsletter', 0755, true);
                }
                if (!is_dir(WRITEPATH . 'newsletter/' . date('Y'))) {
                    @mkdir(WRITEPATH . 'newsletter/' . date('Y'), 0755, true);
                }
                write_file(WRITEPATH . 'newsletter/' . $history['file'], $html_text);
                //copy(WRITEPATH . 'newsletter/' . $newsletter['file'], WRITEPATH . 'newsletter/' . $history['file']);
                $this->sendNewsletterToGroups($id, $history, $post['groups']);
                $count = $this->newsletterModel->db->table('newsletter_stats')->where('id_newsletter', $id)->where('id_history', $history['id'])->countAllResults();
                $response = array(
                    'emails' => $history['emails'],
                    'count' => $count,
                    'status' => true,
                    'msg' => lang('Newsletter.NewsletterWasSent'),
                );
            } else {
                $response = array(
                    'status' => false,
                    'msg' => lang('Newsletter.NewsletterSendError'),
                );
            }
            $this->newsletterModel->db->table('newsletter')->set('status', '')->where('id', $id)->update();
            $this->newsletterModel->db->table('newsletter_history')->set('status', $result ? 'sent' : 'error')->where('id', $history['id'])->update();
        } else {
            $response = array(
                'status' => false,
                'msg' => lang('Newsletter.NewsletterIsBeingSent'),
            );
        }
        return $this->response->setJSON($response);
    }

    private function sendNewsletterToGroups($id_newsletter, $history, $groups) {
        $emails = $this->newsletterModel->db->table('newsletter_email ne')->select('ne.id,ne.email,ne.name,ne.surname,ne.hash')->whereIn('ne.id_group', $groups)->where('ne.active', 1)->groupBy('ne.email')->get()->getResultArray();
        if (!empty($emails)) {
            $config = new \Config\Email();
            $email_service = \Config\Services::email();
            $html = '';
            //$html = base64_decode($history['html_text']);
            if (!empty($history['file']) && file_exists(WRITEPATH . 'newsletter/' . $history['file'])) {
                $html = file_get_contents(WRITEPATH . 'newsletter/' . $history['file']);
            }
            foreach ($emails as $email) {
                $unsubscribe_link = base_url() . 'newsletter-action/unsubscribe/' . $email['hash'];
                $readed = '<img src="' . base_url() . 'newsletter-action/readed/' . $history['hash'] . '/' . $email['hash'] . '" alt="" />';
                $tmp_html = $readed . str_replace('{UNSUBSCRIBE_URL}', $unsubscribe_link, $html);
                $email_service->clear();
                $email_service->protocol = !empty($config->protocolNewsletter) ? $config->protocolNewsletter : 'mail';
                $email_service->setFrom($history['from_email'], $history['from_name']);
                $email_service->setReplyTo($history['reply_to'], $history['from_name']);
                $email_service->setSubject($history['subject']);
                $email_service->setMessage($tmp_html);
                $email_service->setTo($email['email']);
                $result = $email_service->send();
                $data = array(
                    'id_newsletter' => $id_newsletter,
                    'id_history' => $history['id'],
                    'id_email' => $email['id'],
                    'status' => $result ? 'sent' : 'error',
                );
                $this->newsletterModel->db->table('newsletter_stats')->insert($data);
            }
        }
    }

    private function sendTestNewsletter($id) {
        helper('text');
        $post = $this->request->getPost();
        $newsletter = $this->newsletterModel->db->table('newsletter')->select('subject,plain_text,html_text,status,file')->where('id', $id)->get()->getRowArray();
        $html_text = '';
        if (!empty($newsletter['file']) && file_exists(WRITEPATH . 'newsletter/' . $newsletter['file'])) {
            $html_text = file_get_contents(WRITEPATH . 'newsletter/' . $newsletter['file']);
        }
        if (empty($post['test'])) {
            $response = array(
                'status' => false,
                'msg' => lang('Newsletter.PutTestEmailAddress'),
            );
        } elseif (empty($html_text)) {
            $response = array(
                'status' => false,
                'msg' => lang('Newsletter.EmptyMessage'),
            );
        } elseif ($newsletter['status'] != 'sending') {
            $unsubscribe_link = base_url() . 'newsletter-action/unsubscribe/test';
            $html_text = str_replace('{UNSUBSCRIBE_URL}', $unsubscribe_link, $html_text);
            $newsletter_head_and_foot = $this->getNewsletterHeadAndFoot(!empty($post['from_name']) ? $post['from_name'] : '', !empty($post['subject']) ? $post['subject'] : '');
            $html_text = $newsletter_head_and_foot['head'] . $html_text . $newsletter_head_and_foot['foot'];
            $config = new \Config\Email();
            $email_service = \Config\Services::email();
            $email_service->clear();
            $email_service->protocol = !empty($config->protocolNewsletter) ? $config->protocolNewsletter : 'mail';
            $email_service->setFrom(!empty($post['from_email']) ? $post['from_email'] : '', !empty($post['from_name']) ? $post['from_name'] : '');
            $email_service->setReplyTo(!empty($post['reply_to']) ? $post['reply_to'] : '', !empty($post['from_name']) ? $post['from_name'] : '');
            $email_service->setSubject('TEST: ' . (!empty($post['subject']) ? $post['subject'] : ''));
            $email_service->setMessage($html_text);
            $email_service->setTo($post['test']);
            $result = $email_service->send();

            $response = array(
                'debug' => !$result ? $email_service->printDebugger(['headers']) : '',
                'msg' => $result ? lang('Newsletter.MessageWasSent') : lang('Newsletter.MessageSendError'),
                'status' => $result,
            );
        } else {
            $response = array(
                'status' => false,
                'msg' => lang('Newsletter.NewsletterIsBeingSent'),
            );
        }
        return $this->response->setJSON($response);
    }

    private function deleteNewsletter($id) {
        $newsletter = $this->newsletterModel->db->table('newsletter')->select('id,hash,file')->where('id', $id)->get()->getRowArray();
        $result = $this->newsletterModel->deleteNewsletter($id);
        if ($result && file_exists(WRITEPATH . 'newsletter/' . $newsletter['file'])) {
            @unlink(WRITEPATH . 'newsletter/' . $newsletter['file']);
        }
        HistoryStat($id, '', 'newsletter', 'Newsletter', $result ? lang('Newsletter.Removed') : lang('Newsletter.Error'));
        return $this->response->setJSON(array(
                    'status' => true,
                    'result' => $result,
                    'id' => $id,
                    'msg' => $result ? lang('Newsletter.Removed') : lang('Newsletter.Error')
        ));
    }

    private function cancelNewsletter($id) {
        $this->newsletterModel->db->transStart();
        $this->newsletterModel->db->table('newsletter_history')->set('status', 'cancelled')->where('id', $id)->update();
        $this->newsletterModel->db->transComplete();
        $result = $this->newsletterModel->db->transStatus();
        return $this->response->setJSON(array(
                    'status' => true,
                    'result' => $result,
                    'id' => $id,
                    'msg' => $result ? lang('Newsletter.Cancelled') : lang('Newsletter.Error'),
                    'msg2' => lang('Newsletter.status.cancelled'),
                    'cancel' => true
        ));
    }

    public function historyPreview($id) {
        $history = $this->newsletterModel->db->table('newsletter_history')->select('subject,plain_text,html_text,from_name,from_email,reply_to,file')->where('id', $id)->get()->getRowArray();
        $html = '<p>';
        $html .= lang('Newsletter.FromName') . ': ' . $history['from_name'] . '<br />';
        $html .= lang('Newsletter.FromEmail') . ': ' . $history['from_email'] . '<br />';
        $html .= lang('Newsletter.ReplyTo') . ': ' . $history['reply_to'];
        $html .= '</p>';
        if (!empty($history['file']) && file_exists(WRITEPATH . 'newsletter/' . $history['file'])) {
            $html .= file_get_contents(WRITEPATH . 'newsletter/' . $history['file']);
        } else {
            $html .= base64_decode($history['html_text']);
        }
        $response = array(
            'status' => true,
            'html' => base64_encode(urlencode($html)),
        );
        return $this->response->setJSON($response);
    }

    public function preview($id) {
        $newsletter = $this->newsletterModel->db->table('newsletter')->select('subject,plain_text,html_text,file')->where('id', $id)->get()->getRowArray();
        if (!empty($newsletter['file']) && file_exists(WRITEPATH . 'newsletter/' . $newsletter['file'])) {
            $html = file_get_contents(WRITEPATH . 'newsletter/' . $newsletter['file']);
        } else {
            $html = base64_decode($newsletter['html_text']);
        }
        $response = array(
            'status' => true,
            'html' => base64_encode(urlencode($html)),
        );
        return $this->response->setJSON($response);
    }

    public function getNewsletterHeadAndFoot($title = '', $slogan = '', $styles = '') {
        $settings = array();
        $list = $this->newsletterModel->db->table('settings s')->join('settings_lang sl', 's.id=sl.id_settings')->select('s.name,CASE WHEN s.value IS NULL THEN sl.value ELSE s.value END as value', false)->where('sl.id_lang', $this->id_lang)->whereIn('s.name', array('company_name', 'company_short_name', 'neewsletter_title_default'))->get()->getResultArray();
        if (!empty($list)) {
            foreach ($list as $l) {
                $settings[$l['name']] = $l['value'];
            }
        }
        if (empty($title) && (!empty($settings['company_name']) || !empty($settings['company_short_name']))) {
            $title = !empty($settings['company_short_name']) ? $settings['company_short_name'] : $settings['company_name'];
        }
        if (empty($slogan) && !empty($settings['neewsletter_title_default'])) {
            $slogan = $settings['neewsletter_title_default'];
        }
        $default_styles = '@import url(https://fonts.googleapis.com/css?family=Lato:400,700,900); 
            html{width: 100%;}
            body{-webkit-text-size-adjust: none; -ms-text-size-adjust: none; margin: 0; padding: 0;}table{border-spacing: 0; border-collapse: collapse;}
            table td{border-collapse: collapse;}
            .yshortcuts a{border-bottom: none !important;}
            img{display: block !important;}
            a{text-decoration: none; color: #a0cf50;}
            /* Media Queries */
            @media only screen and (max-width: 800px){
                body{width: auto !important;}
                table[class="table800"]{width: 750px !important;}
                table[class="full-width"]{width: 371px !important; text-align: center !important;}
                table[class="half-width"]{width: 179px !important; text-align: center !important;}
                table[class="space-full-width"]{width: 8px !important;}
                table[class="half-width-space"]{width: 8px !important;}
                table[class="space-full-width-1"]{width: 1px !important;}
                table[class="news-nag-line-full-width"]{width: 251px !important;}
                table[class="entertainment-nag-line-full-width"]{width: 190px !important;}
                table[class="calendar-nag-line-full-width"]{width: 188px !important;}
                table[class="partners-nag-line-full-width"]{width: 198px !important;}
                table[class="partners-full-width"]{width: 246px !important;}
                table[class="footer-full-width"]{width: 172px !important;}
                table[class="footer-big-full-width"]{width: 339px !important;}
                table[class="fspace1-full-width"]{height: 66px !important;}
                table[class="social-space-full-width"]{width: 100px !important;}
                table[class="date-full-width"]{width: 170px !important;}
                table[class="hspace-full-width"]{width: 0px !important;}
                img[class="img-full"], img[class="img-full conf-photo"]{max-width: 100% !important; height: auto !important;}
            }
            @media only screen and (max-width: 749px){
                table[class="table800"]{width: 700px !important;}
                table[class="full-width"]{width: 346px !important; text-align: center !important;}
                table[class="half-width"]{width: 167px !important; text-align: center !important;}
                table[class="news-nag-line-full-width"]{width: 226px !important;}
                table[class="entertainment-nag-line-full-width"]{width: 165px !important;}
                table[class="calendar-nag-line-full-width"]{width: 163px !important;}
                table[class="partners-nag-line-full-width"]{width: 173px !important;}
                table[class="partners-full-width"]{width: 188px !important;}
                table[class="footer-full-width"]{width: 160px !important;}
                table[class="footer-big-full-width"]{width: 315px !important;}
                table[class="social-space-full-width"]{width: 100px !important;}
                table[class="fspace1-full-width"]{height: 96px !important;}
            }
            @media only screen and (max-width: 699px){
                table[class="table800"]{width: 650px !important;}
                table[class="full-width"]{width: 321px !important; text-align: center !important;}
                table[class="half-width"]{width: 154px !important; text-align: center !important;}
                table[class="news-nag-line-full-width"]{width: 201px !important;}
                table[class="entertainment-nag-line-full-width"]{width: 140px !important;}
                table[class="calendar-nag-line-full-width"]{width: 138px !important;}
                table[class="partners-nag-line-full-width"]{width: 148px !important;}
                table[class="partners-full-width"]{width: 213px !important;}
                table[class="footer-full-width"]{width: 147px !important;}
                table[class="footer-big-full-width"]{width: 300px !important;}
                table[class="social-space-full-width"]{width: 50px !important;}
            }
            @media only screen and (max-width: 649px){
                table[class="table800"]{width: 600px !important;}
                table[class="full-width"]{width: 296px !important; text-align: center !important;}
                table[class="half-width"]{width: 142px !important; text-align: center !important;}
                table[class="news-nag-line-full-width"]{width: 176px !important;}
                table[class="entertainment-nag-line-full-width"]{width: 115px !important;}
                table[class="calendar-nag-line-full-width"]{width: 113px !important;}
                table[class="partners-nag-line-full-width"]{width: 123px !important;}
                table[class="partners-full-width"]{width: 188px !important;}
                table[class="footer-full-width"]{width: 135px !important;}
                table[class="footer-big-full-width"]{width: 285px !important;}
                table[class="social-space-full-width"]{width: 10px !important;}
            }
            @media only screen and (max-width: 599px){
                table[class="table800"]{width: 550px !important;}
                table[class="full-width"]{width: 271px !important; text-align: center !important;}
                table[class="half-width"]{width: 130px !important; text-align: center !important;}
                table[class="news-nag-line-full-width"]{width: 151px !important;}
                table[class="entertainment-nag-line-full-width"]{width: 90px !important;}
                table[class="calendar-nag-line-full-width"]{width: 88px !important;}
                table[class="partners-nag-line-full-width"]{width: 98px !important;}
                table[class="partners-full-width"]{width: 163px !important;}
                table[class="footer-full-width"]{width: 120px !important;}
                table[class="footer-big-full-width"]{width: 260px !important;}
                table[class="social-space-full-width"]{width: 130px !important;}
                table[class="date-full-width"]{width: 290px !important;}
                table[class="fspace1-full-width"]{height: 106px !important;}
            }
            @media only screen and (max-width: 549px){
                table[class="table800"]{width: 500px !important;}
                table[class="news-nag-line-full-width"],table[class="entertainment-nag-line-full-width"],table[class="calendar-nag-line-full-width"],table[class="partners-nag-line-full-width"]{width: 0px !important; display: none;}
                table[class="news-nag-line-full-width"] td,table[class="entertainment-nag-line-full-width"] td,table[class="calendar-nag-line-full-width"] td,table[class="partners-nag-line-full-width"] td{background-color: #fff !important;}
                table[class="news-nag-full-width"],table[class="entertainment-nag-full-width"],table[class="calendar-nag-full-width"],table[class="partners-nag-full-width"]{width: 100% !important; text-align: center;}
                table[class="news-nag-full-width"] td,table[class="entertainment-nag-full-width"] td,table[class="calendar-nag-full-width"] td,table[class="partners-nag-full-width"] td,tr[class="cinema-nag"] td{font-size: 26px !important;}
                table[class="full-width"]{width: 100% !important; text-align: center !important;}
                table[class="half-width"]{width: 243px !important; text-align: center !important;}
                table[class="full-width"] td[class="h"]{height: 1px;}
                table[class="full-width"] td[class="g"]{height: 10px;}
                table[class="space-full-width"]{width: 1px !important;}
                table[class="partners-full-width"],table[class="footer-full-width"],table[class="footer-big-full-width"]{width: 100% !important;}
                table[class="footer-full-width"] td,table[class="footer-big-full-width"] td{text-align: center !important;}
                table[class="fspace1-full-width"]{width: 100% !important; height: 1px !important;}
                table[class="fspace1-full-width"] td{height: 1px !important;}
                table[class="fspace8-full-width"] td{width: 100% !important; height: 6px !important;}
                table[class="social-space-full-width"]{width: 80px !important;}
                table[class="date-full-width"]{width: 240px !important;}
            }
            @media only screen and (max-width: 499px){
                table[class="table800"]{width: 450px !important;}
                table[class="half-width"]{width: 218px !important; text-align: center !important;}
                table[class="partners-full-width"] img[class="img-full"], table[class="partners-full-width"] img[class="img-full conf-photo"]{max-width: 140px !important;}
                table[class="news-nag-full-width"] td, table[class="entertainment-nag-full-width"] td, table[class="calendar-nag-full-width"] td, table[class="partners-nag-full-width"] td, tr[class="cinema-nag"] td{font-size: 22px !important;}
                table[class="social-space-full-width"]{width: 0px !important;}
                table[class="social-space-full-width"],table[class="hspace-full-width"],table[class="date-full-width"],table[class="logo-full-width"]{width: 100% !important;}
                table[class="social-full-width"]{margin: 0 auto; float: none;}
                table[class="date-full-width"] td{text-align: center !important;}
                table[class="button-full"] td {font-size: 12px !important;}
            }
            @media only screen and (max-width: 449px){
                table[class="table800"]{width: 400px !important;}
                table[class="half-width"]{width: 193px !important; text-align: center !important;}
            }
            @media only screen and (max-width: 399px){
                table[class="table800"]{width: 350px !important;}
                table[class="half-width"]{width: 168px !important; text-align: center !important;}
            }
            @media only screen and (max-width: 349px){
                table[class="table800"]{width: 300px !important;}
                table[class="half-width"]{width: 143px !important; text-align: center !important;}
            }
            @media only screen and (max-width: 299px){
                table[class="table800"]{width: 250px !important;}
                table[class="half-width"]{width: 118px !important; text-align: center !important;}
            }';
        $styles = $default_styles . $styles;

        $head = '<!DOCTYPE html PUBLIC "-/W3C/DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="X-UA-Compatible" content="IE=edge"/><meta name="x-apple-disable-message-reformatting" /><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1"/>';
        if (!empty($title)) {
            $head .= '<title>' . $title . '</title>';
        }
        if (!empty($styles)) {
            $styles = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $styles);
            $styles = str_replace(': ', ':', $styles);
            $styles = str_replace(array("\r\n", "\r", "\n", "\t"), '', $styles);
            $styles = preg_replace('/\s+/', ' ', $styles);
            $head .= '<style type="text/css">' . $styles . '</style>';
        }
        $head .= '</head><body marginwidth="0" marginheight="0" style="margin-top: 0; margin-bottom: 0; padding-top: 0; padding-bottom: 0; width: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;" offset="0" topmargin="0" leftmargin="0"><div class="module-container" data-module="header">';
        if (!empty($slogan)) {
            $head .= '<p style="margin:0;"><span class="preheader" style="display: none !important; visibility: hidden; opacity: 0; color: transparent; height: 0; width: 0;">' . $slogan . '</span></p>';
        }
        $foot = '</div></body></html>';
        return array(
            'head' => $head,
            'foot' => $foot,
        );
    }

}
