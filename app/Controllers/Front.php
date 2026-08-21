<?php

namespace App\Controllers;

/**
 * Isense — front-end serwisu iSense.
 *
 * Na tym etapie renderuje statyczny theme (bez zależności od bazy CMS).
 * Docelowo poszczególne strony staną się stronami CMS zarządzanymi przez panel.
 */
class Front extends BaseController
{
    public function index()
    {
        helper(['url', 'isense']);

        return view('isense/home', [
            'title'       => 'iSense — Serwis i naprawa sprzętu Apple',
            'description' => 'Niezależny serwis Apple w Warszawie. Naprawa iPhone, iPad, iMac i MacBook. '
                . 'Bezpłatna diagnoza, naprawa wysyłkowa door-to-door z całej Polski.',
            'assets'      => rtrim(base_url('assets/isense'), '/'),
        ]);
    }

    /**
     * Dynamiczna strona modelu: naprawy/<kategoria>/<model>.
     * Cennik i lista modeli pochodzą z bloku `models-grid` strony kategorii (edytowalne w panelu).
     */
    public function modelPricing($category = '', $model = '')
    {
        helper(['url', 'isense']);
        $category = trim((string) $category);
        $model    = trim((string) $model);

        $db = \Config\Database::connect();
        // Strona kategorii po linku naprawy/<kategoria>.
        $link = $db->table('links')->where('link', 'naprawy/' . $category)->where('id_lang', 1)->get()->getRowArray();
        if (empty($link)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $pl = $db->table('page_lang')->where('id_link', $link['id'])->where('id_lang', 1)->get()->getRowArray();
        if (empty($pl)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $idPage = (int) $pl['id_page'];

        // Blok models-grid tej kategorii.
        $block = $db->table('page_content pc')
            ->join('module_element me', 'me.id = pc.id_module_element')
            ->select('pc.id')->where('pc.id_page', $idPage)->where('me.slug', 'models-grid')
            ->get()->getRowArray();
        $mg = ! empty($block) ? (new \Modules\Isense\Models\IsenseSectionModel())->getFields((int) $block['id'], 1) : [];

        // Nazwa modelu — musi być na liście modeli kategorii (inaczej 404). Cennik: własny modelu → fallback kategorii.
        $name = '';
        $modelServices = '';
        foreach ($mg['items'] ?? [] as $it) {
            if (trim($it['slug'] ?? '') === $model && $model !== '') {
                $name = $it['name'] ?? '';
                $modelServices = trim($it['services_text'] ?? '');
                break;
            }
        }
        if ($name === '') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $servicesText = $modelServices !== '' ? $modelServices : (string) ($mg['services_text'] ?? '');

        // Cennik usług (Usługa | Cena | Czas | Gwarancja).
        $services = [];
        foreach (preg_split('/\r\n|\r|\n/', $servicesText) as $line) {
            if (trim($line) === '') {
                continue;
            }
            $c = array_map('trim', explode('|', $line));
            $services[] = ['name' => $c[0] ?? '', 'price' => $c[1] ?? '', 'time' => $c[2] ?? '', 'warranty' => $c[3] ?? ''];
        }

        return view('isense/model-pricing', [
            'title'          => 'Serwis ' . $name . ' — cennik napraw | iSense',
            'description'    => 'Cennik napraw ' . $name . ' w serwisie iSense. Oryginalne części Apple, gwarancja, szybka realizacja.',
            'assets'         => rtrim(base_url('assets/isense'), '/'),
            'category'       => $category,
            'model_name'     => $name,
            'services_intro' => $mg['services_intro'] ?? '',
            'services'       => $services,
        ]);
    }

    /**
     * Wyszukiwanie statusu zlecenia (AJAX). Zlecenia z bloku `status-tracker` (edytowalne w panelu).
     * GET ?order=ORD-12345 → JSON {ok, html} lub {ok:false, message}.
     */
    public function orderStatus()
    {
        helper(['url', 'isense']);
        $query = trim((string) $this->request->getGet('order'));
        if ($query === '') {
            return $this->response->setStatusCode(422)->setJSON(['ok' => false, 'message' => 'Podaj numer zlecenia.']);
        }

        $model = new \Modules\Orders\Models\OrdersModel();
        $found = $model->getOrderByNumber($query);
        if (empty($found)) {
            return $this->response->setJSON([
                'ok'      => false,
                'message' => 'Nie znaleziono zlecenia o numerze „' . esc($query) . '". Sprawdź numer i spróbuj ponownie lub skontaktuj się z nami.',
            ]);
        }

        $timeline = [];
        foreach ($model->getSteps($found['id']) as $s) {
            $timeline[] = ['icon' => $s['icon'], 'name' => $s['name'], 'date' => $s['date'], 'state' => $s['state']];
        }

        $statuses = \Modules\Orders\Models\OrdersModel::statuses();
        $html = view('isense/order-status', ['order' => [
            'number'    => $found['number'],
            'device'    => $found['device'],
            'service'   => $found['service'],
            'estimated' => $found['estimated'],
            'status'    => isset($statuses[$found['status']]) ? lang($statuses[$found['status']]) : '',
            'timeline'  => $timeline,
        ]]);

        return $this->response->setJSON(['ok' => true, 'html' => $html]);
    }

    /**
     * Obsługa formularzy iSense (naprawa z odbiorem / kontakt).
     * Adres odbiorcy: pole `recipient` bloku (id_page_cont) → fallback ustawienia globalne.
     * Zwraca JSON (formularze wysyłane AJAX-em).
     */
    public function formSubmit()
    {
        helper(['url', 'isense']);
        $req = $this->request;

        if (strtolower($req->getMethod()) !== 'post') {
            return $this->response->setStatusCode(405)->setJSON(['ok' => false, 'error' => 'Metoda niedozwolona.']);
        }

        // Honeypot — pole ukryte, boty je wypełniają. Udajemy sukces, nic nie wysyłamy.
        if (trim((string) $req->getPost('company')) !== '') {
            return $this->response->setJSON(['ok' => true, 'message' => 'Dziękujemy!']);
        }

        $type  = $req->getPost('type') === 'contact' ? 'contact' : 'pickup';
        $block = (int) $req->getPost('block');

        // Adres odbiorcy: z danych bloku, inaczej z ustawień globalnych.
        $recipient = '';
        if ($block > 0) {
            $fields = (new \Modules\Isense\Models\IsenseSectionModel())->getFields($block, 1);
            $recipient = trim($fields['recipient'] ?? '');
        }
        if (! filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            $recipient = isense_setting('form_sender_email', isense_setting('email', ''));
        }
        if (! filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setStatusCode(500)->setJSON(['ok' => false, 'error' => 'Brak skonfigurowanego adresu odbiorcy.']);
        }

        // Walidacja pól wymaganych.
        $name  = trim((string) $req->getPost('name'));
        $email = trim((string) $req->getPost('email'));
        if ($name === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setStatusCode(422)->setJSON(['ok' => false, 'error' => 'Uzupełnij poprawnie imię i adres e-mail.']);
        }
        if ($type === 'pickup' && trim((string) $req->getPost('phone')) === '') {
            return $this->response->setStatusCode(422)->setJSON(['ok' => false, 'error' => 'Podaj numer telefonu.']);
        }
        if ($type === 'contact' && trim((string) $req->getPost('message')) === '') {
            return $this->response->setStatusCode(422)->setJSON(['ok' => false, 'error' => 'Wpisz treść wiadomości.']);
        }

        // Zbuduj treść (allowlista znanych pól).
        $labels = [
            'name'        => 'Imię i nazwisko',
            'email'       => 'E-mail',
            'phone'       => 'Telefon',
            'address'     => 'Adres odbioru',
            'device'      => 'Typ urządzenia',
            'subject'     => 'Temat',
            'description' => 'Opis usterki',
            'message'     => 'Wiadomość',
        ];
        $rows = '';
        foreach ($labels as $k => $label) {
            $v = trim((string) $req->getPost($k));
            if ($v === '') {
                continue;
            }
            $rows .= '<tr><td style="padding:6px 12px;font-weight:bold;color:#1D1D1F;vertical-align:top">' . esc($label)
                . '</td><td style="padding:6px 12px;color:#333">' . nl2br(esc($v)) . '</td></tr>';
        }

        $title = $type === 'pickup'
            ? 'Nowe zgłoszenie: naprawa z odbiorem'
            : 'Nowa wiadomość z formularza kontaktowego';
        $body = '<div style="font-family:Arial,sans-serif"><h2 style="color:#1D1D1F">' . esc($title) . '</h2>'
            . '<table style="border-collapse:collapse;font-size:14px">' . $rows . '</table>'
            . '<p style="color:#888;font-size:12px;margin-top:16px">Wysłano ze strony iSense (' . esc(current_url()) . ').</p></div>';

        $mail = \Config\Services::email();
        $mail->setFrom(isense_setting('form_sender_email', 'no-reply@isense.pl'), isense_setting('form_sender', 'iSense'));
        $mail->setTo($recipient);
        $mail->setReplyTo($email, $name);
        $mail->setSubject($title);
        $mail->setMessage($body);
        $mail->setMailType('html');

        if ($mail->send(false)) {
            return $this->response->setJSON(['ok' => true, 'message' => 'Dziękujemy! Zgłoszenie zostało wysłane — skontaktujemy się wkrótce.']);
        }

        log_message('error', 'iSense form send failed: ' . $mail->printDebugger(['headers', 'subject']));
        return $this->response->setStatusCode(500)->setJSON(['ok' => false, 'error' => 'Nie udało się wysłać wiadomości. Spróbuj ponownie lub zadzwoń: ' . isense_phone() . '.']);
    }
}
