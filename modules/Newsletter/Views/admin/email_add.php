<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?php if(!empty($email) && !empty($email['id'])): ?>
                <?=$email['email']; ?>
                <span><?=lang('Newsletter.EmailEdit'); ?></span>
            <?php else: ?>
                <?=lang('Newsletter.NewEmailAdd'); ?>
            <?php endif; ?>
        </div>
        <?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
        <form class="form email-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/<?php echo $action; ?><?=!empty($email['id']) ? '/' . $email['id'] : '' ; ?>" method="post">
            <div class="form-row nag">
                <h3><?=lang('Newsletter.EmailSettings'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Newsletter.Email');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="email" value="<?= !empty($email['email']) ? esc($email['email']) : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Newsletter.FirstName');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="name" value="<?= !empty($email['name']) ? esc($email['name']) : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Newsletter.LastName');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="surname" value="<?= !empty($email['surname']) ? esc($email['surname']) : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Newsletter.Group');?></label>
                </div>
                <div class="form-field">
                    <select name="id_group">
                        <option value="0"></option>
                        <?php if(!empty($groups)): ?>
                            <?php foreach($groups as $group): ?>
                                <option value="<?=$group['id']; ?>"<?php if(!empty($email['id_group']) && $group['id'] == $email['id_group']): ?> selected="selected"<?php endif; ?>><?=$group['name']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Newsletter.Agreement');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="agreement" <?php if(!empty($email['agreement']) && $email['agreement']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>   
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Newsletter.Active');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="active" <?php if(!empty($email['active']) && $email['active']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>         
            <div class="form-row submit">
                <button type="submit" class=""><?=lang('Newsletter.Save');?></button>
            </div>     
        </form>
    </div>
</div>
