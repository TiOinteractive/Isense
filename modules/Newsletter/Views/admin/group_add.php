<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?php if(!empty($group) && !empty($group['id'])): ?>
                <?=$group['name']; ?>
                <span><?=lang('Newsletter.GroupEdit'); ?></span>
            <?php else: ?>
                <?=lang('Newsletter.NewGroupAdd'); ?>
            <?php endif; ?>
        </div>
        <?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
        <form class="form newsletter-group-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/<?php echo $action; ?><?=!empty($group['id']) ? '/' . $group['id'] : '' ; ?>" method="post">
            <div class="form-row nag">
                <h3><?=lang('Newsletter.GroupSettings'); ?></h3>
            </div>
            
            <div class="tabs">
                <?php if(!empty($languages) && count($languages) > 1): ?>
                    <div class="tabs-head">
                        <?php $l=0; foreach($languages as $lang): ?>
                        <div class="tab<?=$l==0 ? ' active' : ''; ?>"><span class="name"><?=$lang['name']; ?></span><span class="short-name"><?=$lang['short_name']; ?></span></div>
                        <?php ++$l; endforeach; ?>
                    </div>
                    <div class="tabs-content">
                <?php endif; ?>
                    <?php $l=0; foreach($languages as $lang): ?>
                        <div class="tab-item<?=$l==0 ? ' active' : ''; ?>">
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Newsletter.Name');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="lang[<?=$lang['id']; ?>][name]" value="<?= !empty($group['lang']) ? esc($group['lang'][$lang['id']]['name']) : ''; ?>" >
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Newsletter.Content');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea class="wyswig-textarea" name=lang[<?=$lang['id']; ?>][description]"><?=!empty($group['lang']) ? $group['lang'][$lang['id']]['description'] : ''; ?></textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Newsletter.SuccessMessage');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea name="lang[<?=$lang['id']; ?>][success_msg]"><?=!empty($group['lang']) ? $group['lang'][$lang['id']]['success_msg'] : ''; ?></textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Newsletter.ErrorMessage');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea name="lang[<?=$lang['id']; ?>][error_msg]"><?=!empty($group['lang']) ? $group['lang'][$lang['id']]['error_msg'] : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                    <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Newsletter.Type');?></label>
                </div>
                <div class="form-field">
                    <select name="type">
                        <option value=""></option>
                        <option value="marketing"<?php if(!empty($group['type']) && $group['type'] == 'marketing'): ?> selected="selected"<?php endif; ?>><?=lang('Newsletter.types.Marketing'); ?></option>
                        <option value="newsletter"<?php if(!empty($group['type']) && $group['type'] == 'newsletter'): ?> selected="selected"<?php endif; ?>><?=lang('Newsletter.types.Newsletter'); ?></option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Newsletter.Publish');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="publish" <?php if(!empty($group['publish']) && $group['publish']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>           
            <div class="form-row submit">
                <button type="submit" class=""><?=lang('Newsletter.Save');?></button>
            </div>     
        </form>
    </div>
</div>
