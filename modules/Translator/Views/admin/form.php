<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head"><?=lang('Translator.Edit') . (!empty($language['name']) ? ' - ' . $language['name'] : ''); ?></div>
        <?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
        <form class="form slider-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/translator/<?php echo $action; ?><?=!empty($language['id']) ? '/' . $language['id'] : '' ; ?>" method="post">
            <?php if(!empty($translation)): ?>
                <?php foreach($translation as $key=>$trans): ?>
                    <?php if(is_array($trans)): ?>
                        <?php if(!empty($trans)): ?>
                            <div class="form-row-space"></div>
                            <?php foreach($trans as $k=>$t): ?>
                                <?php if($k == ucfirst($key)): ?>
                                    <div class="form-row nag">
                                        <h3><?=$t ? $t : $k;?></h3>
                                        <input type="hidden" name="<?=$key . '[' . $k . ']'; ?>" value="<?=$t; ?>" />
                                    </div>
                                <?php else: ?>
                                   <?php if(!is_array($t)):?> 
									<div class="form-row">
                                        <div class="form-label">
                                            <label><?=$k; ?></label>
                                        </div>
                                        <div class="form-field">
                                            <textarea class="sm-h resizable" name="<?=$key . '[' . $k . ']'; ?>"><?=$t;?></textarea>
                                        </div>
                                    </div>
									<?php else:?>
									<div class="form-row">
                                        <div class="form-label">
                                            <label><?=$k; ?></label>
                                        </div>
									    <div class="form-field">
										   <?php foreach($t as $c=>$el):?>
										    <div style="display:flex;align-items:center;margin:5px 0px;"><div style="width:30px;"><?=$c;?></div> <textarea class="sm-h resizable" name="<?=$key . '[' . $k . ']['.$c.']'; ?>"><?=$el;?></textarea></div>
										   <?php endforeach;?>
										</div>
									</div>
									<?php endif;?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            <div class="form-row submit">
                <button type="submit" class=""><?=lang('Newsletter.Save');?></button>
            </div>     
        </form>
    </div>
</div>
