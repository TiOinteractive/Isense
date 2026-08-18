<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?php if(!empty($redirect) && !empty($redirect['id'])): ?>
                <?=$redirect['from']; ?>
                <span><?=lang('Redirects.RedirectEdit'); ?></span>
            <?php else: ?>
                <?=lang('Redirects.NewRedirectAdd'); ?>
            <?php endif; ?>
        </div>
        <?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
        <form class="form slider-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/redirects/<?php echo $action; ?><?=!empty($redirect['id']) ? '/' . $redirect['id'] : '' ; ?>" method="post">
            <div class="form-row nag">
                <h3><?=lang('Redirects.RedirectSettings'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Redirects.RedirectFrom');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="from" value="<?= !empty($redirect['from']) ? $redirect['from'] : ''; ?>" >
                    <span class="s">(<?=lang('Redirects.FromInfo'); ?>)</span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Redirects.RedirectTo');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="to" value="<?= !empty($redirect['to']) ? $redirect['to'] : ''; ?>" >
                    <span class="s">(<?=lang('Redirects.ToInfo'); ?>)</span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Redirects.Type');?></label>
                </div>
                <div class="form-field">
                    <select name="type">
                        <option value="301"<?php if(!empty($redirect['type']) && $redirect['type'] == '301'): ?> selected="selected"<?php endif; ?>><?=lang('Redirects.redirect.301'); ?></option>
                        <option value="302"<?php if(!empty($redirect['type']) && $redirect['type'] == '302'): ?> selected="selected"<?php endif; ?>><?=lang('Redirects.redirect.302'); ?></option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Redirects.Group');?></label>
                </div>
                <div class="form-field">
                    <select name="group">
                        <option value=""></option>
                        <option value="resinet"<?php if(!empty($redirect['type']) && $redirect['type'] == 'resinet'): ?> selected="selected"<?php endif; ?>><?=lang('Redirects.group.resinet'); ?></option>
                        <option value="entertainment"<?php if(!empty($redirect['type']) && $redirect['type'] == 'entertainment'): ?> selected="selected"<?php endif; ?>><?=lang('Redirects.group.entertainment'); ?></option>
                        <option value="flavor"<?php if(!empty($redirect['type']) && $redirect['type'] == 'flavor'): ?> selected="selected"<?php endif; ?>><?=lang('Redirects.group.flavor'); ?></option>
                        <option value="foto"<?php if(!empty($redirect['type']) && $redirect['type'] == 'foto'): ?> selected="selected"<?php endif; ?>><?=lang('Redirects.group.foto'); ?></option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Redirects.ShortLink');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="short" <?php if(!empty($redirect['short']) && $redirect['short']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>  
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Redirects.Publish');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="publish" <?php if(!empty($redirect['publish']) && $redirect['publish']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>           
            <div class="form-row submit">
                <button type="submit" class=""><?=lang('Redirects.Save');?></button>
            </div>     
        </form>
    </div>
</div>
