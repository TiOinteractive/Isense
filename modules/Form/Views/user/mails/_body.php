<?php
/**
 * Tresc maila ze zgloszeniem z formularza — wspolna dla wszystkich szablonow
 * (form1.php, form_isense.php, form_isense_2col.php sa tylko wrapperami).
 *
 * Zmienne z Form::ajax(): form, post, visible, labels, attachments, settings,
 * cid_logo, submitter, submitted_at. Starsze wywolania (bez trzech ostatnich)
 * nadal dzialaja — stad defaulty ponizej.
 *
 * UKLAD: jedno pole = jeden wiersz, etykieta nad wartoscia. Wariant
 * dwukolumnowy odpadl, bo Outlook (silnik Worda) ignoruje CSS-owy
 * vertical-align i procentowe szerokosci kolumn — wiersze rozjezdzaly sie
 * i robily o polowe wyzsze niz w przegladarce. Tutaj wysokosc wiersza wynika
 * wylacznie z tresci, wiec nie ma czego zepsuc.
 *
 * ZASADY POD OUTLOOKA:
 *  - tekst w komorce zawsze w <p style="margin:0"> — goly tekst i <div> staja
 *    sie w Wordzie akapitem z domyslnym odstepem po (~13 px);
 *  - valign="top" jako ATRYBUT, bo wlasnosc CSS jest ignorowana;
 *  - mso-table-lspace/rspace:0pt — Word dokleja tabelom wlasny margines;
 *  - mso-line-height-rule:exactly — inaczej Word zaokragla wysokosc linii;
 *  - zawartosc <td> w jednej linii, bez wciec: biale znaki miedzy tagiem
 *    a trescia potrafia zamienic sie w dodatkowy wiersz tekstu.
 */
$visible      = isset($visible) ? $visible : [];
$labels       = isset($labels) ? $labels : [];
$attachments  = isset($attachments) ? $attachments : [];
$submitter    = isset($submitter) ? trim((string) $submitter) : '';
$submitted_at = isset($submitted_at) ? trim((string) $submitted_at) : '';

$form_name = trim((string) ($form['name'] ?? ''));

// Podglad w skrzynce: nazwa formularza + kto wypelnil. Naglowek maila
// (emails/header.php) wstawia to w ukryty blok przed trescia.
$preheader = trim($form_name . ($submitter !== '' ? ' — ' . $submitter : ''));

$table = 'border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt;';
$label = 'margin:0;mso-line-height-rule:exactly;font-size:11px;line-height:16px;color:#8E8E93;';
$value = 'margin:3px 0 0;mso-line-height-rule:exactly;font-size:14px;line-height:20px;color:#1D1D1F;font-weight:600;';
?>
<?= view('emails/header', ['preheader' => $preheader]); ?>

<h1 style="margin:0;mso-line-height-rule:exactly;color:#1D1D1F;font-size:20px;line-height:28px;font-weight:700;letter-spacing:-0.02em;"><?= esc(lang('Form.mail.NewSubmission')); ?></h1>
<?php if ($form_name !== '' || $submitter !== ''): ?>
    <p style="margin:6px 0 0;mso-line-height-rule:exactly;color:#6E6E73;font-size:14px;line-height:20px;"><?php if ($form_name !== ''): ?><?= esc($form_name); ?><?php endif; ?><?php if ($form_name !== '' && $submitter !== ''): ?><span style="color:#C7C7CC;">&nbsp;·&nbsp;</span><?php endif; ?><?php if ($submitter !== ''): ?><strong style="color:#1D1D1F;font-weight:600;"><?= esc($submitter); ?></strong><?php endif; ?></p>
<?php endif; ?>
<?php if ($submitted_at !== ''): ?>
    <p style="margin:2px 0 0;mso-line-height-rule:exactly;color:#8E8E93;font-size:12px;line-height:18px;"><?= esc(lang('Form.mail.SubmittedAt')); ?>: <?= esc($submitted_at); ?></p>
<?php endif; ?>

<?php if (!empty($form['fields'])): ?>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;margin-top:20px;<?= $table; ?>border:1px solid #E5E5EA;">
        <?php $row = 0; ?>
        <?php foreach ($form['fields'] as $field): ?>
            <?php
                // Pola ukryte warunkiem sa pomijane — $visible wyliczylo Form::ajax().
                if (!empty($visible) && empty($visible[$field['id']])) { continue; }
                if ($field['type'] === 'file') { continue; } // zalaczniki maja wlasna sekcje

                $val = !empty($post['field_' . $field['id']]) ? $post['field_' . $field['id']] : '';
                if ($field['type'] === 'select') {
                    // Etykieta zamiast surowego ID opcji.
                    $val = !empty($labels[$field['id']]) ? $labels[$field['id']] : '';
                }
                if ($field['type'] === 'checkbox') {
                    $val = $val ? lang('Form.mail.Yes') : lang('Form.mail.No');
                }
                if (!is_string($val) || trim($val) === '') { continue; }

                $bg     = ++$row % 2 ? '#FFFFFF' : '#FAFAFC';
                $border = $row > 1 ? 'border-top:1px solid #E5E5EA;' : '';
            ?>
            <tr><td valign="top" style="background:<?= $bg; ?>;<?= $border; ?>padding:10px 16px;"><p style="<?= $label; ?>"><?= esc($field['name']); ?></p><p style="<?= $value; ?>"><?= nl2br(esc($val)); ?></p></td></tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php if (!empty($attachments)): ?>
    <h2 style="margin:24px 0 8px;mso-line-height-rule:exactly;color:#1D1D1F;font-size:15px;line-height:22px;font-weight:600;"><?= esc(lang('Form.mail.Attachments')); ?></h2>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;<?= $table; ?>border:1px solid #E5E5EA;">
        <?php $arow = 0; ?>
        <?php foreach ($form['fields'] as $field): ?>
            <?php if (empty($attachments[$field['id']])) { continue; } ?>
            <?php foreach ($attachments[$field['id']] as $attachment): ?>
                <?php $aborder = ++$arow > 1 ? 'border-top:1px solid #E5E5EA;' : ''; ?>
<?php /* kontekst 'html', nie 'attr' — 'attr' koduje ':' i '/' jako encje, co czesc
         klientow pocztowych pokazuje jako polamany adres. */ ?>
                <tr><td valign="top" style="<?= $aborder; ?>padding:10px 16px;"><p style="<?= $label; ?>"><?= esc($field['name']); ?></p><p style="<?= $value; ?>"><a href="<?= esc($attachment['url']); ?>" style="color:#3b81f7;text-decoration:none;"><?= esc($attachment['name']); ?></a><span style="color:#8E8E93;font-weight:400;">&nbsp;·&nbsp;<?= esc(lang('Form.mail.FileSize', [(int) round($attachment['size'] / 1024)])); ?></span></p></td></tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?= view('emails/footer'); ?>
