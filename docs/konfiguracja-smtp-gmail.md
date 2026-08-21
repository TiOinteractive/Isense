# Wysyłka maili przez Google Workspace (form@isense.pl)

> Dokument roboczy — plan wdrożenia. Nie zawiera haseł; hasło aplikacji trafia wyłącznie do `.env`, który nie jest w gicie.

## Kontekst

System wysyła maile przez CI4 `\Config\Services::email()` w trybie SMTP, ale `.env` wciąż wskazuje na skrzynkę odziedziczoną po innym projekcie: `poczta.tio.pl` / `powiadomienia@resinet.pl` / nazwa nadawcy „TiO CMS". Na isense.pl oznacza to albo brak wysyłki, albo maile z obcym brandingiem. Klient udostępnia skrzynkę `form@isense.pl` w Google Workspace (adres w domenie isense.pl ⇒ konto Workspace, nie zwykły Gmail).

Cel: przełączyć całą wysyłkę na `smtp.gmail.com` z autoryzacją hasłem aplikacji konta `form@isense.pl` i ujednolicić nadawcę na `form@isense.pl` / „iSense".

### Warunek wstępny — do zdobycia od klienta

Zwykłe hasło konta **nie zadziała**. Google od maja 2022 nie przyjmuje hasła konta po SMTP (`535-5.7.8 Username and Password not accepted`). Potrzebne jest **hasło aplikacji** (16 znaków):

1. Na koncie `form@isense.pl`: włączona **weryfikacja dwuetapowa** (myaccount.google.com → Bezpieczeństwo).
2. myaccount.google.com/apppasswords → utwórz hasło dla „iSense CMS" → skopiuj 16 znaków (wpisujemy **bez spacji**).
3. Jeśli strona haseł aplikacji jest niedostępna — administrator Workspace musi w Admin Console → Security → Access and data control → **Less secure apps** zezwolić na hasła aplikacji dla tej jednostki organizacyjnej (albo włączyć 2SV w politykach).

Alternatywa, jeśli klient nie chce hasła w `.env`: `smtp-relay.gmail.com:587` z autoryzacją po IP serwera (Admin Console → Apps → Google Workspace → Gmail → Routing → SMTP relay service). Wtedy `SMTPUser`/`SMTPPass` zostają puste, a w `.env` zmienia się tylko host. Do rozważenia dopiero jeśli hasło aplikacji odpadnie.

### Gotcha, które kształtuje ten plan

Gmail **przepisuje nagłówek `From`** na adres, którym się autoryzujemy, o ile podany nadawca nie jest zweryfikowanym aliasem („Wyślij jako"). Dlatego nie ma sensu zostawiać `no-reply@isense.pl` ani `powiadomienia@resinet.pl` — i tak wyjdzie `form@isense.pl`, a rozjedzie się tylko wyświetlana nazwa nadawcy. Stąd punkt 3 planu.

## Plan

### 1. `.env` — blok `# Email` (linie ~199–213)

```ini
 email.fromEmail = 'form@isense.pl'
 email.fromName = 'iSense'
 email.userAgent = 'CodeIgniter'
 email.protocol = 'smtp'
 email.protocolNewsletter = 'mail'
 email.mailPath = '/usr/sbin/sendmail'
 email.SMTPHost = 'smtp.gmail.com'
 email.SMTPUser = 'form@isense.pl'
 email.SMTPPass = 'xxxxxxxxxxxxxxxx'   # 16-znakowe haslo aplikacji, bez spacji
 email.SMTPPort = '587'
 email.SMTPCrypto = 'tls'
 email.SMTPTimeout = '30'
 email.charset = 'UTF-8'
```

Uwagi:
- `email.SMTPTimeout` to **nowy klucz** — właściwość `$SMTPTimeout` istnieje w `app/Config/Email.php:52` z domyślną wartością `5`, co przy handshake TLS do Google bywa za mało i objawia się losowymi timeoutami. CI4 mapuje `email.*` na config automatycznie, nic w kodzie nie trzeba dodawać.
- `protocolNewsletter` zostaje `mail` — newsletter nie jest na isense.pl używany, a Gmail ma twardy limit wysyłki (2000 odbiorców/dobę w Workspace), który przy masówce zablokowałby też maile transakcyjne.
- Alternatywa portu, jeśli hosting blokuje 587: `SMTPPort = '465'` + `SMTPCrypto = 'ssl'`.

### 2. `env.example` — ten sam blok, z placeholderami

Zsynchronizować strukturę (dodać `email.SMTPTimeout`, zmienić host na `smtp.gmail.com`, `SMTPPass = 'haslo_aplikacji_google'`), żeby kolejne wdrożenie nie odtworzyło konfiguracji `poczta.tio.pl`.

### 3. Ujednolicenie nadawcy

**a) `app/Helpers/error_notification_helper.php:166-167`** — jedyny hardcode brandingu resinet:

```php
$emailService->setFrom(env('email.fromEmail', 'form@isense.pl'), $domain . ' — Error — isense.pl');
```

**b) `app/Controllers/Front.php:206`** — zmienić fallback `'no-reply@isense.pl'` → `'form@isense.pl'`, żeby przy pustym ustawieniu w panelu nadawca zgadzał się z kontem SMTP (inaczej Gmail i tak przepisze `From`, a treść i nagłówek się rozjadą).

**c) Panel admina → Ustawienia → sekcja „Ustawienia formularzy"** (`app/Views/admin/settings/form.php:337-354`) — ustawić ręcznie w panelu:
- `form_sender` = `iSense`
- `form_sender_email` = `form@isense.pl`

**Ważne przy (c):** `app/Controllers/Front.php:158` używa `form_sender_email` **także jako fallbackowego odbiorcy**, gdy blok sekcji nie ma pola `recipient`. Po tej zmianie zgłoszenia z bloków bez `recipient` trafią na `form@isense.pl` (skrzynka wysyłkowa = skrzynka odbiorcza). Jeśli to niepożądane, w panelu należy uzupełnić ustawienie `email` (adres kontaktowy serwisu) i/lub pole `recipient` w każdym bloku formularza — kolejność fallbacku to `recipient` bloku → `form_sender_email` → `email`.

Moduł Form (`modules/Form/Libraries/Form.php:520`), reset hasła admina (`app/Controllers/Admin.php:193-208`) i moduł Users biorą nadawcę z `Config\Email::$fromEmail`, więc naprawiają się same przez punkt 1 — bez zmian w kodzie.

### 4. Higiena — `app/Controllers/Test.php`

Nieśledzony martwy plik z PHPMailerem: ma **hardcodowany `clientSecret` OAuth Microsoft** oraz błąd składni (typograficzny apostrof w `$refreshToken’,`), więc jest niewykonalny. Nie commitować; usunąć. Poza zakresem samej wysyłki, ale to wyciek sekretu leżący w drzewie roboczym.

## Weryfikacja

1. **Test połączenia SMTP z konsoli** — jednorazowy skrypt poza repo, który robi `setFrom` z configu, `setTo` na adres testowy i `send(false)`; przy porażce wypisuje `printDebugger(['headers'])`. Oczekiwane w logu SMTP: `250 2.0.0 OK` z `smtp.gmail.com`.
   - `535-5.7.8` ⇒ hasło aplikacji błędne / wciąż zwykłe hasło konta.
   - `Failed to connect` / timeout ⇒ port 587 zablokowany na hostingu → próba `465` + `ssl`.
   - Uwaga na dev (WAMP): wymagane rozszerzenie `openssl` w `php.ini`.
2. **Formularze iSense** — `/naprawa-z-odbiorem` i formularz kontaktowy: wysłać zgłoszenie, sprawdzić odpowiedź JSON `{"ok":true}` oraz wpływ maila na skrzynkę odbiorcy. Sprawdzić, że `Reply-To` wskazuje na adres zgłaszającego (`app/Controllers/Front.php:208`) — odpowiedź z Gmaila ma wracać do klienta, nie do `form@isense.pl`.
3. **Nagłówki maila** — w odebranej wiadomości: `From: iSense <form@isense.pl>`, `Return-Path` w domenie google, `dkim=pass` w `Authentication-Results`. Brak przepisania `From` na cudzy adres potwierdza spójność konfiguracji.
4. **Moduł Form** (jeśli jakiś formularz z tego modułu jest aktywny) — wysłać zgłoszenie z załącznikiem, sprawdzić że logo inline (CID) i pliki dochodzą; Gmail limituje wiadomość do 25 MB, budżet załączników w module to `form.maxAttachmentsKb` (domyślnie 8192 KB).
5. `writable/logs/` — brak wpisów `iSense form send failed` oraz `Email::send` po testach.
