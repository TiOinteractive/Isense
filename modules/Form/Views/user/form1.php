<?php
/* 
Formularz 1
*/
?>
<section class="section section-<?=$id_cont; ?>">
    <div class="container">
        <?php if(!empty($title)): ?>
            <h2><?=$title; ?></h2>
        <?php endif; ?>
        <?php if(!empty($subtitle)): ?>
            <h3><?=$subtitle; ?></h3>
        <?php endif; ?>
        <?php if(!empty($data)): ?>
            <div class="form form-<?=$data['id']; ?>">
                <?php if(!empty($data['description'])): ?>
                    <div class="description"><?=$data['description']; ?></div>
                <?php endif; ?>
				<?php if(!empty($data['name'])): ?>
                    <h2 class="form-name"><?=$data['name']; ?></h2>
                <?php endif; ?>
                <?php if(!empty($data['fields'])): ?>
                    <form id="form-<?=$data['id']; ?>" class="form form-<?=$data['id']; ?> ajax" method="post" action="<?=uri_string(); ?>">
                        <input type="hidden" name="content" value="<?=$id_cont; ?>" />
                        <div class="field-box h">
                            <input type="text" name="field_h" value="" />
                        </div>
                        <?php foreach($data['fields'] as $field): ?>
                            <div class="field-box<?=$field['required'] ? ' required' : ''; ?>">
                                <?php
								if(!empty($field['description'])) {$placeholder=' placeholder="'.$field['description'].'" ';} else {$placeholder='';} 	
                                    switch($field['type']) {
                                        case 'textarea':
                                            echo '<div class="label"><label for="field-' . $field['id'] . '">' . $field['name'] . ($field['required'] ? '<span class="req">*</span>' : '') . '</label></div>';
                                            echo '<div><textarea '.$placeholder.' name="field_' . $field['id'] . '" id="field-' . $field['id'] . '"></textarea></div>';
                                            break;
                                        case 'number':
                                            echo '<div class="label"><label for="field-' . $field['id'] . '">' . $field['name'] . ($field['required'] ? '<span class="req">*</span>' : '') . '</label></div>';
                                            echo '<div><input '.$placeholder.' type="number" name="field_' . $field['id'] . '" value="" id="field-' . $field['id'] . '" /></div>';
                                            break;
                                        case 'checkbox':
                                            echo '<div class="label"><input type="checkbox" name="field_' . $field['id'] . '" value="1" id="field-' . $field['id'] . '" /></div>';
                                            echo '<div><label for="field-' . $field['id'] . '">' . ($field['required'] ? '<span class="req">*</span> ' : '') . $field['name'] . '</label></div>';
                                            break;
                                        default:
                                            echo '<div class="label"><label for="field-' . $field['id'] . '">' . $field['name'] . ($field['required'] ? '<span class="req">*</span>' : '') . '</label></div>';
                                            echo '<div><input type="text" '.$placeholder.' name="field_' . $field['id'] . '" value="" id="field-' . $field['id'] . '" /></div>';
                                            break;
                                    }
                                ?>
                            </div>
                        <?php endforeach; ?>
                        <div class="field-box submit">
                           <div class="label">&nbsp;</div><div>
                                <?php if($data['captcha']): ?>
                                    <button class="g-recaptcha trans400" data-sitekey="<?=$settings['recaptchav3_site_key']; ?>" data-callback='reCaptchaForm<?=$data['id']; ?>Submit' data-action='submit'><?=lang('Form.field.Send'); ?></button>
                                <?php else: ?>
                                    <input type="submit" name="submit" value="<?=lang('Form.field.Send'); ?>" class="trans400">
                                <?php endif; ?>
                           </div>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        <?php endif; ?>
</section>