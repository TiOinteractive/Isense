<?=view('emails/header'); ?>
<?php if(!empty($form['fields'])): ?>
    <table>
    <?php foreach($form['fields'] as $field): ?>
        <tr>
            <td><?=$field['name']; ?></td>
            <td><?=!empty($post['field_' . $field['id']]) ? $post['field_' . $field['id']] : ''; ?></td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>
<?=view('emails/footer'); ?>
