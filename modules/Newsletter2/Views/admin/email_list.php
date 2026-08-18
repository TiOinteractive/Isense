<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head"><?=lang('Newsletter.EmailList');?></div>
        <?=view('Modules\Newsletter\Views\admin\tabs'); ?>
        <p><a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/mail-add" title=""><?=lang('Newsletter.AddEmail');?></a></p>
        <?= view('Modules\Newsletter\Views\admin\email_list_filters', array()); ?>
        <?= view('admin/order_and_pagination', array('pager' => $pager, 'order_list'=>$order_list)); ?>
        <div class="list">
            <div class="list-row list-head">
                <div class="list-col">
                    <?=lang('Newsletter.Email');?>
                </div>
                <div class="list-col">
                    <?=lang('Newsletter.FullName');?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?=lang('Newsletter.Edit');?>
                </div>
                <div class="list-col center w100 hide-500">
                    <?=lang('Newsletter.Agreement');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Newsletter.Delete');?>
                </div>
            </div>
            <?php if(!empty($emails)): ?>
                <?php foreach($emails as $email): ?>
                    <div class="list-row list-row-<?=$email['id']; ?>">
                        <div class="list-col">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/mail-edit/<?=$email['id']; ?>" title="<?=$email['email']; ?>"><?=$email['email']; ?></a>
                        </div>
                        <div class="list-col">
                            <?=$email['surname'] . ' ' . $email['name']; ?>
                        </div>
                        <div class="list-col center w100 hide-1200">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/mail-edit/<?=$email['id']; ?>" title="<?=lang('Newsletter.Edit');?>"><i class="fa-solid fa-pen-to-square fa-2x"></i></a>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <a class="list-publish-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/mail-agreement/<?=$email['id']; ?>" title="<?=lang('Newsletter.Agreement');?>"><?php if(!empty($email['agreement']) && $email['agreement']): ?><i class="fa-solid fa-square-check fa-2x"></i><?php else: ?><i class="fa-regular fa-square fa-2x"></i><?php endif; ?></a>
                        </div>
                        <div class="list-col center w100">
                        <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))): ?><a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/mail-delete/<?=$email['id']; ?>" data-title="<?=lang('Newsletter.DeleteEmail');?>" data-message="<?=lang('Newsletter.DeleteEmailConfirm') . ': <b>' . $email['email'] . '</b>'; ?>" data-btn-ok="<?=lang('Newsletter.Remove');?>" data-btn-cancel="<?=lang('Newsletter.Cancel');?>" title="<?=lang('Newsletter.Delete');?>"><i class="fa-solid fa-trash-can fa-2x"></i></a><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?= view('admin/order_and_pagination', array('pager' => $pager, 'order_list'=>$order_list)); ?>
    </div>
</div>
