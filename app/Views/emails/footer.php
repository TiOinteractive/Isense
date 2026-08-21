<?php
/**
 * Wspolna ramka maili CMS-a — czesc ZAMYKAJACA (otwiera ja emails/header.php).
 * Kazda pozycja jest warunkowa: instalacja, ktora nie ma uzupelnionego NIP-u
 * czy godzin otwarcia, nie dostanie pustego wiersza ze sama etykieta.
 *
 * $settings dociera tu, bo CI4 domyslnie zachowuje dane miedzy wywolaniami
 * view() w jednym requescie — ale zabezpieczamy sie tak samo jak w naglowku.
 *
 * Kazda linia to <p style="margin:0"> zamiast <div> — Outlook (silnik Worda)
 * robi z <div> akapit z domyslnym odstepem po, przez co stopka sie rozjezdza.
 */
$settings = isset($settings) && is_array($settings) ? $settings : [];

$company = trim((string) ($settings['company_name'] ?? ''));
$address = trim((string) ($settings['address'] ?? ''));
$phone   = trim((string) ($settings['phone'] ?? ''));
$email   = trim((string) ($settings['email'] ?? ''));
$nip     = trim((string) ($settings['nip'] ?? ''));
$hours   = trim((string) ($settings['opening_hours'] ?? ''));

$socials = [];
foreach (['facebook' => 'Facebook', 'instagram' => 'Instagram', 'youtube' => 'YouTube', 'tiktok' => 'TikTok', 'twitter' => 'X'] as $key => $label) {
    $url = trim((string) ($settings[$key] ?? ''));
    if ($url !== '') {
        $socials[$label] = $url;
    }
}

$line = 'margin:3px 0 0;mso-line-height-rule:exactly;font-size:12px;line-height:18px;';
$sep  = '<span style="color:#C7C7CC;">&nbsp;·&nbsp;</span>';
?>
                    </td>
                </tr>
                <tr>
                    <td class="tio-pad" align="center" valign="top" style="mso-line-height-rule:exactly;background:#F5F5F7;border-top:1px solid #E5E5EA;padding:24px 32px;color:#6E6E73;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,Helvetica,sans-serif;font-size:12px;line-height:18px;">
<?php if ($company !== ''): ?>
                        <p style="margin:0;mso-line-height-rule:exactly;color:#1D1D1F;font-size:14px;line-height:20px;font-weight:600;"><?= esc($company); ?></p>
<?php endif; ?>
<?php if ($address !== ''): ?>
                        <p style="<?= $line; ?>"><?= nl2br(esc($address)); ?></p>
<?php endif; ?>
<?php if ($phone !== '' || $email !== ''): ?>
                        <p style="<?= $line; ?>"><?php if ($phone !== ''): ?><a href="tel:<?= esc(preg_replace('/[^0-9+]/', '', $phone), 'attr'); ?>" style="color:#6E6E73;text-decoration:none;"><?= esc($phone); ?></a><?php endif; ?><?php if ($phone !== '' && $email !== ''): ?><?= $sep; ?><?php endif; ?><?php if ($email !== ''): ?><a href="mailto:<?= esc($email, 'attr'); ?>" style="color:#3b81f7;text-decoration:none;"><?= esc($email); ?></a><?php endif; ?></p>
<?php endif; ?>
<?php if ($hours !== ''): ?>
                        <p style="<?= $line; ?>"><?= esc(lang('Emails.OpeningHours')); ?>: <?= nl2br(esc($hours)); ?></p>
<?php endif; ?>
<?php if ($nip !== ''): ?>
                        <p style="<?= $line; ?>"><?= esc(lang('Emails.Nip')); ?>: <?= esc($nip); ?></p>
<?php endif; ?>
<?php if (!empty($socials)): ?>
                        <p style="margin:10px 0 0;mso-line-height-rule:exactly;font-size:12px;line-height:18px;"><?= esc(lang('Emails.FollowUs')); ?>: <?php $first = true; ?><?php foreach ($socials as $label => $url): ?><?php if (!$first): ?><?= $sep; ?><?php endif; ?><a href="<?= esc($url, 'attr'); ?>" style="color:#3b81f7;text-decoration:none;"><?= esc($label); ?></a><?php $first = false; ?><?php endforeach; ?></p>
<?php endif; ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
