<?php

namespace Modules\Form\Libraries;

use Modules\Form\Models\FormModel;

class Form {

    public function __construct() {
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->formModel = new FormModel();
    }

    public function index($content, $id_lang, $slug = '') {
        $form = $this->formModel
                ->join('form_lang fl', 'form.id=fl.id_form')
                ->select('form.id,form.template,form.captcha,fl.name,fl.description')
                ->where('form.id_page_cont', $content['id'])
                ->where('fl.id_lang', $id_lang)
                ->first();
        if (!empty($form)) {
            $form['fields'] = $this->formModel->db
                    ->table('form_field ff')
                    ->join('form_field_lang ffl', 'ff.id=ffl.id_field')
                    ->select('ff.id,ff.type,ff.required,ffl.name,ffl.description')
                    ->where('ff.publish', 1)
                    ->where('ff.id_form', $form['id'])
                    ->where('ffl.id_lang', $id_lang)
                    ->orderBy('ff.order', 'ASC')
                    ->get()
                    ->getResultArray();
        }
        return $form;
    }

    public function assets($slug = '', $template = '', $id_form = 0, $data = array()) {
        $assets = array(
            'js' => array(),
            'js_code' => '',
            'css' => array()
        );
        switch ($slug) {
            default :
                $assets['js'][] = '/assets/js/page.js';
                $assets['js'][] = '/assets/js/form.js';
                if($data['captcha']) {
                    $assets['js'][] = 'https://www.google.com/recaptcha/api.js';
                    $assets['js_code'] .= 'function reCaptchaForm' . $data['id'] . 'Submit(token) {$("#form-' . $data['id'] . '").submit();}';
                }
                break;
        }
        return $assets;
    }

    public function ajax($post, $id_lang, $slug = '') {
        $result = false;
        $errors = array();
        $config = new \Config\Email();
        $form = $this->formModel
                ->join('form_lang fl', 'form.id=fl.id_form')
                ->select('form.id,form.addressee,form.addressee_cc,form.addressee_bcc,form.template,form.captcha,fl.name,fl.success_msg,fl.error_msg')
                ->where('form.id_page_cont', $post['content'])
                ->where('fl.id_lang', $id_lang)
                ->first();
        if (!empty($form)) {
            $form['fields'] = $this->formModel->db
                    ->table('form_field ff')
                    ->join('form_field_lang ffl', 'ff.id=ffl.id_field')
                    ->select('ff.id,ff.type,ff.required,ff.validation,ffl.name')
                    ->where('ff.publish', 1)
                    ->where('ff.id_form', $form['id'])
                    ->where('ffl.id_lang', $id_lang)
                    ->orderBy('ff.order', 'ASC')
                    ->get()
                    ->getResultArray();
        }
        if (!empty($form) && !empty($form['addressee']) && file_exists(ROOTPATH . 'modules/Form/Views/user/mails/' . $form['template'])) {
            if($form['captcha']){
                $secret = $this->formModel->db->table('settings')->select('value')->where('name', 'recaptchav3_secret_key')->get()->getRowArray();
                if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
                    $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
                }
                $post_data = http_build_query(
                    array(
                        'secret' => $secret['value'],
                        'response' => $post['g-recaptcha-response'],
                        'remoteip' => $_SERVER['REMOTE_ADDR']
                    )
                );
                $opts = array('http' =>
                    array(
                        'method'  => 'POST',
                        'header'  => 'Content-type: application/x-www-form-urlencoded',
                        'content' => $post_data
                    )
                );
                $context  = stream_context_create($opts);
                $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
                $captcha_result = json_decode($response);
                if (empty($captcha_result) || !$captcha_result->success) {
                    $errors['captcha'] = lang('Form.CaptchaError');
                }
            }
            $validation = \Config\Services::validation();
            $validation_rules = array(
                'field_h' => array(
                    'label' => '',
                    'rules' => 'empty'
                )
            );
            if (!empty($form['fields'])) {
                foreach ($form['fields'] as $field) {
                    $rules = array();
                    if ($field['required']) {
                        $rules[] = 'required';
                    }
                    if ($field['validation']) {
                        switch ($field['validation']) {
                            case 'phone': $rules[] = 'valid_phone';
                                break;
                            case 'email': $rules[] = 'valid_email';
                                break;
                            case 'zip_code': $rules[] = 'valid_zip_code';
                                break;
                            case 'nip': $rules[] = 'valid_nip';
                                break;
                            case 'regon': $rules[] = 'valid_regon';
                                break;
                            case 'pesel': $rules[] = 'valid_npesel';
                                break;
                        }
                    }
                    if (!empty($rules)) {
                        $validation_rules['field_' . $field['id']] = array(
                            'label' => $field['name'],
                            'rules' => implode('|', $rules)
                        );
                    }
                }
            }
            $validation->setRules($validation_rules);
            if (!$validation->run($post)) {
                $errors = array_merge($errors, $validation->getErrors());
            } else {
                $email = \Config\Services::email();
                $email->setFrom($config->fromEmail, $config->fromName);
                $email->setTo($form['addressee']);
                if (!empty($form['addressee_cc'])) {
                    $email->setCC($form['addressee_cc']);
                }
                if (!empty($form['addressee_bcc'])) {
                    $email->setBCC($form['addressee_bcc']);
                }
                $email->setSubject($form['name']);
                $message = view('Modules\Form\Views\user/mails/' . $form['template'], array('form' => $form, 'post' => $post));
                $email->setMessage($message);
                $result = $email->send();
            }
        }
        $response = array(
            'content' => $post['content'],
            'form' => !empty($form) ? $form['id'] : 0,
            'result' => $result,
            'errors' => $errors,
            'callback' => 'formCallback',
            'msg' => $result ? (!empty($form['success_msg']) ? $form['success_msg'] : lang('Form.SuccessMsg')) : (!empty($form['error_msg']) ? $form['error_msg'] : lang('Form.ErrorMsg'))
        );
        return $this->response->setJSON($response);
    }

}
