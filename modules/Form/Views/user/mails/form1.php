<?=view('emails/header'); ?>
<?php
    // Pola ukryte warunkiem sa pomijane — $visible wyliczylo Form::ajax().
    // Stare wywolania widoku (bez $visible/$labels/$attachments) nadal dzialaja.
    $visible = isset($visible) ? $visible : array();
    $labels = isset($labels) ? $labels : array();
    $attachments = isset($attachments) ? $attachments : array();
?>
<?php if(!empty($form['fields'])): ?>
    <table cellpadding="6" cellspacing="0" border="0" style="width:100%;border-collapse:collapse">
    <?php foreach($form['fields'] as $field): ?>
        <?php
            if(!empty($visible) && empty($visible[$field['id']])) { continue; }
            if($field['type'] === 'file') { continue; } // zalaczniki maja wlasna sekcje

            $value = !empty($post['field_' . $field['id']]) ? $post['field_' . $field['id']] : '';
            if($field['type'] === 'select') {
                // Etykieta zamiast surowego ID opcji.
                $value = !empty($labels[$field['id']]) ? $labels[$field['id']] : '';
            }
            if($field['type'] === 'checkbox') {
                $value = $value ? lang('Form.mail.Yes') : lang('Form.mail.No');
            }
            if(!is_string($value) || trim($value) === '') { continue; }
        ?>
        <tr style="border-bottom:1px solid #eee">
            <td style="vertical-align:top;width:40%"><strong><?=esc($field['name']); ?></strong></td>
            <td style="vertical-align:top"><?=nl2br(esc($value)); ?></td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>
<?php if(!empty($attachments)): ?>
    <h3><?=lang('Form.mail.Attachments'); ?></h3>
    <ul>
    <?php foreach($form['fields'] as $field): ?>
        <?php if(empty($attachments[$field['id']])) { continue; } ?>
        <?php foreach($attachments[$field['id']] as $attachment): ?>
            <li>
                <?=esc($field['name']); ?>:
<?php /* kontekst 'html', nie 'attr' — 'attr' koduje ':' i '/' jako encje, co czesc
         klientow pocztowych pokazuje jako polamany adres. */ ?>
                <a href="<?=esc($attachment['url']); ?>"><?=esc($attachment['name']); ?></a>
                (<?=(int) round($attachment['size'] / 1024); ?> KB)
            </li>
        <?php endforeach; ?>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>
<?=view('emails/footer'); ?>
