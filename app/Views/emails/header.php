<?php
/**
 * Wspolna ramka maili CMS-a — czesc OTWIERAJACA. Domyka ja emails/footer.php,
 * a tresc wiadomosci wchodzi pomiedzy. Kontrakt musi zostac zachowany, bo
 * korzystaja z niego takze: modul Form, Newsletter, Newsletter2, Users
 * (aktywacja konta, przypomnienie hasla) i mail z importu Event.
 *
 * Wszystkie zmienne sa OPCJONALNE — nie kazdy nadawca przekazuje $settings
 * czy $cid_logo (import Event nie przekazuje zadnego), a na PHP 8 odwolanie
 * do brakujacego klucza to warning ladujacy w tresci maila.
 *
 * Style sa inline, nie w <style> — Gmail w widoku „obcietej wiadomosci"
 * i czesc klientow mobilnych gubi arkusz z <head>. W <style> zostaja tylko
 * media queries, ktorych inline zapisac sie nie da (i ktorych Outlook
 * desktop i tak nie czyta — dlatego uklad musi byc czytelny takze bez nich).
 *
 * Obejscia pod Outlooka (silnik Worda): valign jako atrybut zamiast wlasnosci
 * CSS, mso-table-lspace/rspace zerujace domyslny margines tabel, tekst
 * w <p style="margin:0"> zamiast golego tekstu i <div> (Word robi z nich
 * akapit z odstepem po ~13 px).
 */
$settings  = isset($settings) && is_array($settings) ? $settings : [];
$cid_logo  = isset($cid_logo) ? trim((string) $cid_logo) : '';
$preheader = isset($preheader) ? trim((string) $preheader) : '';

$company = trim((string) ($settings['company_name'] ?? ''));
$phone   = trim((string) ($settings['phone'] ?? ''));
$email   = trim((string) ($settings['email'] ?? ''));

// Logo z panelu → Ustawienia. Pasek naglowka jest bialy — jak gorna belka
// serwisu — wiec pasuje zwykle `logo`; `logo_dark` jest tylko zapasem dla
// instalacji, ktore maja wypelnione wylacznie jego. Preferowany nosnik to
// CID (obraz osadzony w wiadomosci — klienci pocztowi blokuja obrazy zdalne
// domyslnie), a URL jest awaryjny dla nadawcow, ktorzy logo nie zalaczaja.
$logo_url = '';
if ($cid_logo === '') {
    $logo_path = trim((string) ($settings['logo']['path'] ?? $settings['logo_dark']['path'] ?? ''));
    if ($logo_path !== '') {
        $logo_url = base_url('image/original/' . $logo_path);
    }
}

$table = 'border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt;';
?>
<!DOCTYPE html>
<html lang="<?= esc(lang('Emails.HtmlLang'), 'attr'); ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="x-apple-disable-message-reformatting">
<title><?= esc($company); ?></title>
<style>
    body { margin:0 !important; padding:0 !important; background:#F5F5F7; }
    table { border-collapse:collapse; }
    img { border:0; outline:none; text-decoration:none; -ms-interpolation-mode:bicubic; }
    @media only screen and (max-width:600px) {
        .tio-card { width:100% !important; border-radius:0 !important; }
        .tio-pad { padding-left:20px !important; padding-right:20px !important; }
        /* Logo i dane kontaktowe w naglowku zwijaja sie w jedna kolumne. */
        .tio-stack { display:block !important; width:100% !important; text-align:left !important; }
        .tio-stack-gap { padding-top:14px !important; }
    }
</style>
</head>
<body style="margin:0;padding:0;background:#F5F5F7;">
<?php if ($preheader !== ''): ?>
    <?php /* Podglad w skrzynce odbiorczej — ukryty w tresci wiadomosci. */ ?>
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;height:0;width:0;mso-hide:all;"><?= esc($preheader); ?></div>
<?php endif; ?>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="<?= $table; ?>background:#F5F5F7;">
    <tr>
        <td align="center" valign="top" style="padding:24px 12px;">
            <table role="presentation" class="tio-card" width="600" cellpadding="0" cellspacing="0" border="0" style="<?= $table; ?>width:600px;max-width:600px;background:#FFFFFF;border-radius:12px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,Helvetica,sans-serif;">
                <tr>
                    <td class="tio-pad" valign="top" style="background:#FFFFFF;border-bottom:1px solid #E5E5EA;padding:24px 32px;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="<?= $table; ?>">
                            <tr>
                                <td class="tio-stack" align="left" valign="middle" style="vertical-align:middle;">
                                    <?php /* Bez logo nie emitujemy <img src="cid:"> — klient pocztowy
                                             pokazalby ikone zepsutego obrazka. */ ?>
                                    <?php if ($cid_logo !== ''): ?>
                                        <img src="cid:<?= $cid_logo; ?>" alt="<?= esc($company, 'attr'); ?>" height="40" style="display:block;height:40px;width:auto;">
                                    <?php elseif ($logo_url !== ''): ?>
                                        <img src="<?= esc($logo_url); ?>" alt="<?= esc($company, 'attr'); ?>" height="40" style="display:block;height:40px;width:auto;">
                                    <?php elseif ($company !== ''): ?>
                                        <p style="margin:0;mso-line-height-rule:exactly;color:#1D1D1F;font-size:22px;line-height:30px;font-weight:700;letter-spacing:-0.02em;"><?= esc($company); ?></p>
                                    <?php endif; ?>
                                </td>
                                <td class="tio-stack tio-stack-gap" align="right" valign="middle" style="vertical-align:middle;color:#6E6E73;font-size:13px;line-height:19px;">
<?php if ($phone !== ''): ?>
                                    <p style="margin:0;mso-line-height-rule:exactly;font-size:13px;line-height:19px;"><?= esc(lang('Emails.Phone')); ?>: <a href="tel:<?= esc(preg_replace('/[^0-9+]/', '', $phone), 'attr'); ?>" style="color:#1D1D1F;text-decoration:none;font-weight:600;"><?= esc($phone); ?></a></p>
<?php endif; ?>
<?php if ($email !== ''): ?>
                                    <p style="margin:2px 0 0;mso-line-height-rule:exactly;font-size:13px;line-height:19px;"><?= esc(lang('Emails.Email')); ?>: <a href="mailto:<?= esc($email, 'attr'); ?>" style="color:#3b81f7;text-decoration:none;font-weight:600;"><?= esc($email); ?></a></p>
<?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="tio-pad" valign="top" style="mso-line-height-rule:exactly;padding:28px 32px;color:#1D1D1F;font-size:15px;line-height:22px;">
