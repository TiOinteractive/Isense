<?php

namespace Modules\Form\Libraries;

use Modules\Form\Models\FormModel;

/**
 * Front-end modulu formularzy: render, walidacja, upload i wysylka maila.
 *
 * Pola moga byc warunkowe — widoczne tylko gdy wskazany select ma wybrana jedna
 * z zadeklarowanych opcji. Widocznosc liczymy NIEZALEZNIE po stronie serwera
 * (isFieldVisible()), bo JS mozna w przegladarce wylaczyc albo obejsc. Pole
 * ukryte nie dostaje ZADNYCH regul walidacji (w tym `required`), jego wartosc
 * jest usuwana z POST-a i nie trafia do maila.
 */
class Form {

    /** Rozszerzenia dopuszczone dla pol typu `file`. */
    const ALLOWED_EXT = array('jpg', 'jpeg', 'png', 'gif', 'webp');

    /** Maksymalna glebokosc lancucha warunkow — zabezpieczenie przed cyklem. */
    const MAX_CONDITION_DEPTH = 10;

    protected $request;
    protected $response;
    protected $session;
    protected $formModel;

    /* Ustawiane z zewnatrz przez app/Controllers/Home.php przy tworzeniu modulu. */
    public $locale;
    public $global_links;
    public $settings;
    public $is_mobile;

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
            $form['fields'] = $this->loadFields($form['id'], $id_lang);
            if ($form['template'] === 'form_isense.php') {
                $form['contact'] = $this->loadIsenseContact($content['id'], $id_lang);
            }
        }
        return $form;
    }

    /**
     * Dane lewej kolumny szablonu form_isense.php — adres, telefon, mail,
     * godziny i mapa.
     *
     * Kolejnosc zrodel: sekcja `contact` modulu Isense stojaca na tej samej
     * stronie (nadpisanie per-strona) -> ustawienia globalne panelu.
     *
     * Sekcja pozwala pokazac inne dane na konkretnej podstronie. Gdy jej nie ma
     * — a tak jest dzis w calym serwisie — kolumna leci z ustawien globalnych,
     * dzieki czemu adres, telefon, mail, godziny i mapa sa edytowalne z panelu.
     * Wartosci lustrzane wobec Modules\Isense\Libraries\Isense::defaults('contact').
     */
    private function loadIsenseContact($id_page_cont, $id_lang) {
        $fallback = $this->contactFromSettings();

        $db = $this->formModel->db;

        // Blok `contact` modulu Isense z tej samej strony co formularz.
        $row = $db->table('page_content pc')
                ->join('page_content pc_self', 'pc_self.id_page = pc.id_page')
                ->join('module_element me', 'me.id = pc.id_module_element')
                ->join('module m', 'm.id = me.id_module')
                ->select('pc.id')
                ->where('pc_self.id', $id_page_cont)
                ->where('m.slug', 'Isense')
                ->where('me.slug', 'contact')
                ->where('pc.publish', 1)
                ->orderBy('pc.order', 'ASC')
                ->limit(1)
                ->get()->getRowArray();

        if (empty($row)) {
            return $fallback;
        }

        $data = $db->table('isense_section s')
                ->join('isense_section_lang sl', 's.id = sl.id_isense_section')
                ->select('sl.data')
                ->where('s.id_page_cont', $row['id'])
                ->where('sl.id_lang', $id_lang)
                ->get()->getRowArray();

        if (empty($data['data'])) {
            return $fallback;
        }
        $fields = json_decode($data['data'], true);
        if (!is_array($fields)) {
            return $fallback;
        }
        // Puste pola sekcji uzupelniamy kopia zapasowa, zeby nie gubic kolumny.
        // Bierzemy tylko klucze lewej kolumny i tylko wartosci tekstowe — sekcja
        // trzyma w tym samym JSON-ie takze tablice (np. `subjects`).
        $picked = array();
        foreach (array_keys($fallback) as $key) {
            if (!empty($fields[$key]) && is_string($fields[$key])) {
                $picked[$key] = $fields[$key];
            }
        }
        return array_merge($fallback, $picked);
    }

    /**
     * Lewa kolumna zlozona z ustawien globalnych panelu (tabela `settings`).
     *
     * `address`, `phone` i `email` istnialy tam od dawna — wczesniej te same
     * wartosci byly zaszyte w kodzie, wiec zmiana w panelu nie dawala efektu.
     * `opening_hours` i `map_location` to pola dodane razem z ta zmiana
     * (app/Views/admin/settings/form.php).
     *
     * `left_heading` celowo puste — form_isense.php ma wlasny domyslny naglowek.
     */
    private function contactFromSettings() {
        $settings = !empty($this->settings) ? $this->settings : array();

        $get = function ($key) use ($settings) {
            return !empty($settings[$key]) && is_string($settings[$key]) ? trim($settings[$key]) : '';
        };

        $contact = array(
            'left_heading' => '',
            'address' => $get('address'),
            'phone' => $get('phone'),
            'email' => $get('email'),
            'hours' => $get('opening_hours'),
            'nip' => $get('nip'),
            'regon' => $get('regon'),
        );

        return array_merge($contact, $this->buildMapUrls($get('map_location')));
    }

    /**
     * Adres wpisany w panelu -> URL osadzenia mapy i link do Google Maps.
     *
     * Format `?q=...&output=embed` nie wymaga klucza API — to ten sam wariant,
     * ktory byl tu wczesniej zapisany na sztywno.
     *
     * Pusty adres zwraca puste ciagi: form_isense.php oslania oba miejsca przez
     * !empty(), wiec mapa i link po prostu sie nie renderuja.
     */
    private function buildMapUrls($location) {
        if ($location === '') {
            return array('map' => '', 'map_link' => '');
        }
        $q = rawurlencode($location);
        return array(
            'map' => 'https://www.google.com/maps?q=' . $q . '&output=embed',
            'map_link' => 'https://maps.google.com/?q=' . $q,
        );
    }

    /**
     * Opublikowane pola formularza wraz z opcjami selectow.
     *
     * Wspolne dla index() i ajax() — wczesniej byly to dwa niemal identyczne
     * zapytania roznce sie lista kolumn, co latwo sie rozjezdzalo.
     */
    private function loadFields($id_form, $id_lang) {
        $fields = $this->formModel->db
                ->table('form_field ff')
                ->join('form_field_lang ffl', 'ff.id=ffl.id_field')
                ->select('ff.id,ff.type,ff.required,ff.validation,ff.parent_field,ff.parent_values,'
                       . 'ff.max_files,ff.max_file_size,ffl.name,ffl.description')
                ->where('ff.publish', 1)
                ->where('ff.id_form', $id_form)
                ->where('ffl.id_lang', $id_lang)
                ->orderBy('ff.order', 'ASC')
                ->get()
                ->getResultArray();

        if (empty($fields)) {
            return array();
        }

        $ids = array_column($fields, 'id');
        $options = $this->formModel->db
                ->table('form_field_option ffo')
                ->join('form_field_option_lang ffol', 'ffo.id=ffol.id_option')
                ->select('ffo.id,ffo.id_field,ffo.slug,ffol.name')
                ->whereIn('ffo.id_field', $ids)
                ->where('ffo.publish', 1)
                ->where('ffol.id_lang', $id_lang)
                ->orderBy('ffo.order', 'ASC')
                ->get()
                ->getResultArray();

        $by_field = array();
        foreach ($options as $option) {
            $by_field[$option['id_field']][] = $option;
        }

        $present = array_flip($ids);
        foreach ($fields as $k => $field) {
            $fields[$k]['options'] = !empty($by_field[$field['id']]) ? $by_field[$field['id']] : array();
            // Warunek osierocony (rodzic usuniety albo niepublikowany) — fail-open:
            // pokazujemy pole zawsze, zamiast ukrywac je na wieki. Zerujemy tutaj,
            // zeby PHP (walidacja, mail) i JS widzialy dokladnie to samo.
            if (!empty($field['parent_field']) && !isset($present[$field['parent_field']])) {
                $fields[$k]['parent_field'] = 0;
                $fields[$k]['parent_values'] = '';
            }
        }
        return $fields;
    }

    /**
     * Czy pole jest widoczne przy takim POST-cie?
     * Rekurencja idzie w gore lancucha rodzicow; memoizacja i limit glebokosci
     * chronia przed cyklem w danych.
     */
    private function isFieldVisible($field, $by_id, $post, &$memo, $depth = 0) {
        if ($depth > self::MAX_CONDITION_DEPTH) {
            return false;
        }
        if (array_key_exists($field['id'], $memo)) {
            return $memo[$field['id']];
        }
        $memo[$field['id']] = false; // wstepnie — przerywa ewentualny cykl

        $visible = true;
        if (!empty($field['parent_field']) && isset($by_id[$field['parent_field']])) {
            $parent = $by_id[$field['parent_field']];
            $allowed = array_filter(array_map('trim', explode(',', (string) $field['parent_values'])), 'strlen');
            $value = isset($post['field_' . $parent['id']]) ? trim((string) $post['field_' . $parent['id']]) : '';
            $visible = $this->isFieldVisible($parent, $by_id, $post, $memo, $depth + 1)
                    && $value !== ''
                    && in_array($value, $allowed, true);
        }

        $memo[$field['id']] = $visible;
        return $visible;
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
                // Prefiks, nie porownanie — obejmuje wszystkie warianty szablonu
                // (form_isense, form_isense_2col, ...). Bez tego nowy wariant
                // renderowal sie bez form.css, wiec podswietlanie bledu na zgodzie
                // RODO nie dzialalo.
                if (strpos($template, 'form_isense') === 0) {
                    // Assets dedupuje, wiec nadmiarowy wpis jest nieszkodliwy —
                    // a formularz musi sie wyswietlic poprawnie takze wtedy, gdy
                    // jest jedynym blokiem iSense na stronie.
                    $assets['css'][] = '/assets/isense/css/isense.css';
                    $assets['css'][] = '/assets/isense/css/form.css';
                }
                // Skrypt Google tylko gdy captcha jest wlaczona I klucz witryny
                // uzupelniony — przy pustym kluczu widok renderuje zwykly submit,
                // wiec api.js byloby zbednym zapytaniem do zewnetrznego serwisu.
                $sitekey = !empty($this->settings['recaptchav3_site_key']) ? $this->settings['recaptchav3_site_key'] : '';
                if(!empty($data['captcha']) && $sitekey !== '') {
                    $assets['js'][] = 'https://www.google.com/recaptcha/api.js';
                    $assets['js_code'] .= 'function reCaptchaForm' . $data['id'] . 'Submit(token) {$("#form-' . $data['id'] . '").submit();}';
                }
                break;
        }
        return $assets;
    }

    public function ajax($post, $id_lang, $slug = '') {
        helper(array('url', 'form_limits'));

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
            $form['fields'] = $this->loadFields($form['id'], $id_lang);
        }
        if (!empty($form) && !empty($form['addressee']) && file_exists(ROOTPATH . 'modules/Form/Views/user/mails/' . $form['template'])) {
            $secret = array();
            if($form['captcha']){
                $secret = $this->formModel->db->table('settings')->select('value')->where('name', 'recaptchav3_secret_key')->get()->getRowArray();
            }
            // Captcha wlaczona, ale klucze nieuzupelnione w Ustawieniach -> pomijamy
            // weryfikacje zamiast odrzucac kazde zgloszenie. Widok w tej samej
            // sytuacji renderuje zwykly submit (brak tokenu do sprawdzenia), wiec
            // bez tego warunku formularza nie dalo sie wyslac w ogole.
            if($form['captcha'] && empty($secret['value'])){
                log_message('warning', 'Form ' . $form['id'] . ': captcha wlaczona, ale settings.recaptchav3_secret_key jest puste — weryfikacja pominieta.');
            }
            if($form['captcha'] && !empty($secret['value'])){
                if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
                    $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
                }
                $post_data = http_build_query(
                    array(
                        'secret' => $secret['value'],
                        // Brak tokenu (np. zablokowany skrypt Google) to nie blad PHP,
                        // tylko nieudana weryfikacja — Google odrzuci puste `response`.
                        'response' => !empty($post['g-recaptcha-response']) ? $post['g-recaptcha-response'] : '',
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

            // --- Widocznosc pol wyliczona z POST-a -------------------------------
            $by_id = array();
            foreach ($form['fields'] as $field) {
                $by_id[$field['id']] = $field;
            }
            $memo = array();
            $visible = array();
            foreach ($form['fields'] as $field) {
                $visible[$field['id']] = $this->isFieldVisible($field, $by_id, $post, $memo);
                if (!$visible[$field['id']]) {
                    // Wartosc pola ukrytego ignorujemy — bot albo nieaktualny DOM.
                    unset($post['field_' . $field['id']]);
                }
            }

            // --- Reguly walidacji -----------------------------------------------
            $images = new \Config\Images();
            $validation = \Config\Services::validation();
            $validation_rules = array(
                'field_h' => array(
                    'label' => '',
                    'rules' => 'empty'
                )
            );
            foreach ($form['fields'] as $field) {
                if (empty($visible[$field['id']])) {
                    continue; // pole ukryte — zadnych regul, w tym `required`
                }
                $key = 'field_' . $field['id'];
                $rules = array();
                $messages = array();

                switch ($field['type']) {

                    case 'select':
                        $rules[] = $field['required'] ? 'required' : 'permit_empty';
                        $option_ids = array_map('intval', array_column($field['options'], 'id'));
                        if (!empty($option_ids)) {
                            // Bez tego warunku select bez opcji odrzucalby kazda wartosc.
                            $rules[] = 'in_list[' . implode(',', $option_ids) . ']';
                            $messages['in_list'] = lang('Form.SelectOptionInvalid');
                        }
                        break;

                    case 'file':
                        // Pole opcjonalne, w ktorym nic nie wybrano, NIE dostaje zadnych
                        // regul. Reguly plikowe CI4 przy calkowicie nieobecnym polu
                        // dostaja `[null]` z getFile() i zwracaja false — czyli
                        // „plik za duzy" mimo braku pliku (StrictRules\FileRules).
                        if (!$field['required'] && !$this->uploadedFiles($key)) {
                            break;
                        }
                        $max_kb = form_effective_max_kb($field['max_file_size']);
                        if ($field['required']) {
                            $rules[] = 'uploaded[' . $key . ']';
                            $messages['uploaded'] = lang('Form.file.NotUploaded');
                        }
                        $rules[] = 'max_size[' . $key . ',' . $max_kb . ']';
                        $rules[] = 'mime_in[' . $key . ',' . $images->imageMimeIn . ']';
                        $rules[] = 'is_image[' . $key . ']';
                        // Celowo NIE images.maxImageDims ('3000,3000') — telefon robi
                        // 4032x3024 i globalny limit odrzucalby typowe zdjecie.
                        $rules[] = 'max_dims[' . $key . ',' . (env('form.maxImageDims') ?: '8000,8000') . ']';
                        $messages['max_size'] = lang('Form.file.TooBig', array($max_kb));
                        $messages['mime_in'] = lang('Form.file.WrongType');
                        $messages['is_image'] = lang('Form.file.NotImage');
                        $messages['max_dims'] = lang('Form.file.DimsTooBig');
                        break;

                    default: // text / textarea / number / checkbox
                        if ($field['required']) {
                            $rules[] = 'required';
                            if ($field['type'] === 'checkbox') {
                                // Nazwa checkboxa to cala jego etykieta (np. klauzula RODO
                                // ma kilkaset znakow), wiec domyslne „Pole {nazwa} jest
                                // wymagane" byloby nie do przeczytania.
                                $messages['required'] = lang('Form.field.CheckboxRequired');
                            }
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
                                case 'pesel': $rules[] = 'valid_pesel';
                                    break;
                            }
                        }
                        break;
                }

                if (!empty($rules)) {
                    $validation_rules[$key] = array(
                        // Etykieta trafia do komunikatow bledow — dluga nazwa pola
                        // (klauzula zgody) rozsadzilaby je, wiec przycinamy.
                        'label' => mb_strimwidth($field['name'], 0, 80, '…'),
                        'rules' => implode('|', $rules),
                        'errors' => $messages
                    );
                }
            }
            $validation->setRules($validation_rules);
            if (!$validation->run($post)) {
                $errors = array_merge($errors, $validation->getErrors());
            }

            // --- Kontrole spoza regul CI: liczba plikow i budzet zalacznikow -----
            if (empty($errors)) {
                $total_kb = 0;
                $max_total_kb = (int) (env('form.maxAttachmentsKb') ?: 8192);
                foreach ($form['fields'] as $field) {
                    if ($field['type'] !== 'file' || empty($visible[$field['id']])) {
                        continue;
                    }
                    $key = 'field_' . $field['id'];
                    foreach ($this->uploadedFiles($key) as $file) {
                        $total_kb += (int) ceil($file->getSize() / 1024);
                    }
                    if (count($this->uploadedFiles($key)) > (int) $field['max_files']) {
                        $errors[$key] = lang('Form.file.TooMany', array((int) $field['max_files']));
                    }
                }
                // Base64 zwieksza rozmiar wiadomosci ok. 1,37x — budzet chroni
                // przed odbiciem od limitu SMTP odbiorcy.
                if ($total_kb > $max_total_kb) {
                    $errors['attachments'] = lang('Form.file.TotalTooBig', array(round($max_total_kb / 1024, 1)));
                }
            }

            if (empty($errors)) {
                // --- Zapis plikow ------------------------------------------------
                $dir = 'form/' . date('Ym');
                $attachments = array();
                foreach ($form['fields'] as $field) {
                    if ($field['type'] !== 'file' || empty($visible[$field['id']])) {
                        continue;
                    }
                    foreach ($this->uploadedFiles('field_' . $field['id']) as $file) {
                        $ext = strtolower($file->getClientExtension());
                        if (!in_array($ext, self::ALLOWED_EXT, true)) {
                            $ext = $file->guessExtension() ?: 'jpg'; // rozszerzenie z realnego MIME
                        }
                        $base = mb_url_title(pathinfo($file->getClientName(), PATHINFO_FILENAME), '-', true);
                        $base = $base !== '' ? mb_substr($base, 0, 60) : 'zdjecie';
                        // Losowy sufiks: brak kolizji w obrebie miesiaca oraz
                        // nieodgadywalny URL (trasa /file/... nie ma autoryzacji).
                        $name = $base . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
                        $path = $file->store($dir, $name);
                        $attachments[$field['id']][] = array(
                            'path' => $path,
                            'name' => $file->getClientName(),
                            'size' => @filesize(WRITEPATH . 'uploads/' . $path),
                            // /file/... serwuje oryginal; /image/... konwertowaloby do webp.
                            'url' => base_url('file/' . $path),
                        );
                    }
                }

                // --- Etykiety wybranych opcji ------------------------------------
                $labels = array();
                foreach ($form['fields'] as $field) {
                    if ($field['type'] !== 'select' || empty($visible[$field['id']])) {
                        continue;
                    }
                    $value = isset($post['field_' . $field['id']]) ? $post['field_' . $field['id']] : '';
                    foreach ($field['options'] as $option) {
                        if ((string) $option['id'] === (string) $value) {
                            $labels[$field['id']] = $option['name'];
                            break;
                        }
                    }
                }

                // --- Imie i nazwisko zglaszajacego --------------------------------
                // Znacznikiem jest walidacja 'name' ustawiana przy polu w panelu
                // (nie dodaje zadnej reguly — patrz switch w bloku walidacji).
                // Trafia na koniec tematu maila i do naglowka tresci.
                $submitter = '';
                foreach ($form['fields'] as $field) {
                    if ($field['validation'] !== 'name' || empty($visible[$field['id']])) {
                        continue;
                    }
                    $value = isset($post['field_' . $field['id']]) ? trim((string) $post['field_' . $field['id']]) : '';
                    if ($value !== '') {
                        $submitter = mb_strimwidth($value, 0, 60, '…');
                        break;
                    }
                }

                // --- Mail ---------------------------------------------------------
                $email = \Config\Services::email();
                $email->setFrom($config->fromEmail, $config->fromName);
                $email->setTo($form['addressee']);
                if (!empty($form['addressee_cc'])) {
                    $email->setCC($form['addressee_cc']);
                }
                if (!empty($form['addressee_bcc'])) {
                    $email->setBCC($form['addressee_bcc']);
                }
                $subject = $form['name'];
                if ($submitter !== '') {
                    $subject .= lang('Form.mail.SubjectSuffix', array($submitter));
                }
                $email->setSubject($subject);

                // Logo w naglowku maila (emails/header.php) osadzamy inline przez CID.
                // Bez tego szablon wypuszcza <img src="cid:"> — w kliencie pocztowym
                // to ikona zepsutego obrazka.
                $cid_logo = '';
                $settings = !empty($this->settings) ? $this->settings : array();
                // Pasek naglowka maila jest bialy, wiec bierzemy zwykle logo;
                // wersja na ciemne tlo jest zapasem dla instalacji, ktore maja
                // wypelnione tylko ja.
                foreach (array('logo', 'logo_dark') as $logo_key) {
                    if (empty($settings[$logo_key]['path'])) {
                        continue;
                    }
                    $logo_path = WRITEPATH . 'uploads/' . $settings[$logo_key]['path'];
                    if (!is_file($logo_path)) {
                        continue;
                    }
                    $email->attach($logo_path);
                    $cid_logo = $email->setAttachmentCID($logo_path);
                    break;
                }

                $message = view('Modules\Form\Views\user/mails/' . $form['template'], array(
                    'form' => $form,
                    'post' => $post,
                    'visible' => $visible,
                    'labels' => $labels,
                    'attachments' => $attachments,
                    // emails/header.php uzywa obu — bez nich PHP 8 sypie warningami.
                    'settings' => $settings,
                    'cid_logo' => $cid_logo,
                    // Wyliczone wyzej — szablon nie musi drugi raz przeszukiwac pol.
                    'submitter' => $submitter,
                    'submitted_at' => date('d.m.Y H:i'),
                ));
                $email->setMessage($message);
                foreach ($attachments as $list) {
                    foreach ($list as $attachment) {
                        $email->attach(WRITEPATH . 'uploads/' . $attachment['path']);
                    }
                }
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

    /**
     * Realnie przeslane pliki z pola `field_X[]` — bez pustych slotow
     * (UPLOAD_ERR_NO_FILE) i bez plikow z bledem uploadu.
     */
    private function uploadedFiles($key) {
        $files = $this->request->getFileMultiple($key);
        if (empty($files)) {
            return array();
        }
        $valid = array();
        foreach ($files as $file) {
            if (!empty($file) && $file->getError() !== UPLOAD_ERR_NO_FILE && $file->isValid()) {
                $valid[] = $file;
            }
        }
        return $valid;
    }

}
