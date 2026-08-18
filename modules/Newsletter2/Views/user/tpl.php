<?php
/* 
Newsletter - formularz zapisu 1
*/
?>
<section class="section-<?=$id_cont; ?> newsletter-section newsletter-<?=$data['id'];?>">
    <div class="container">
        <?php if(!empty($title)): ?>
            <h2><?=$title; ?></h2>
        <?php endif; ?>
        <?php if(!empty($subtitle)): ?>
            <h3><?=$subtitle; ?></h3>
        <?php endif; ?>
        <?php if(!empty($data)): ?>
            <form class="newsletter-form newsletter-<?=$data['id']; ?> ajax" method="post" action="<?=uri_string(); ?>">
                <input type="hidden" name="content" value="<?=$id_cont; ?>" />
                <div class="field-box h">
                    <input type="text" name="field_h" value="" />
                </div>
                <div class="field-box">
                    <input type="text" name="email" value="" />
                </div>
                <div class="field-box submit">
                   <div><input type="submit" name="submit" value="<?=lang('Form.field.Send'); ?>" class="trans400"></div>
                </div>
            </form>
        <?php endif; ?>
    </div>
    <?= view_cell('\Modules\Newsletter\Libraries\Newsletter::checkGet') ?>
</section>
