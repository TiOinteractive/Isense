<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head"><?=lang('Newsletter.EmailList');?></div>
        <?=view('Modules\Newsletter\Views\admin\tabs'); ?>
        <p><a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/mail-add" title="<?=lang('Newsletter.AddEmail');?>"><i class="fa-solid fa-plus"></i> <?=lang('Newsletter.AddEmail');?></a></p>
        <?= view('Modules\Newsletter\Views\admin\email_list_filters', array()); ?>
        <?= view('admin/order_and_pagination', array('pager' => $pager,'on_page_list' => $on_page_list)); ?>
        <div class="list">
            <div class="list-row list-head">
                <div class="list-col <?php if(!empty($filters['order_array']) && !empty($filters['order_array']['email'])): ?> <?=$filters['order_array']['email']; ?><?php endif; ?>" data-order="email">
                    <?=lang('Newsletter.Email');?>
                </div>
                <div class="list-col <?php if(!empty($filters['order_array']) && !empty($filters['order_array']['name'])): ?> <?=$filters['order_array']['name']; ?><?php endif; ?>" data-order="name">
                    <?=lang('Newsletter.FullName');?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?=lang('Newsletter.Edit');?>
                </div>
                <div class="list-col center w100 hide-500 <?php if(!empty($filters['order_array']) && !empty($filters['order_array']['active'])): ?> <?=$filters['order_array']['active']; ?><?php endif; ?>" data-order="active">
                    <?=lang('Newsletter.Active');?>
                </div>
                <div class="list-col center w100 hide-500 <?php if(!empty($filters['order_array']) && !empty($filters['order_array']['agreement'])): ?> <?=$filters['order_array']['agreement']; ?><?php endif; ?>" data-order="agreement">
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
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/mail-edit/<?=$email['id']; ?>" title="<?=lang('Newsletter.Edit');?>"><i class="fa-solid fa-pencil fa-xl"></i></a>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <a class="list-publish-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/mail-active/<?=$email['id']; ?>" title="<?=lang('Newsletter.Active');?>"><?php if(!empty($email['active']) && $email['active']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <a class="list-publish-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/mail-agreement/<?=$email['id']; ?>" title="<?=lang('Newsletter.Agreement');?>"><?php if(!empty($email['agreement']) && $email['agreement']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
                        </div>
                        <div class="list-col center w100">
                        <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))): ?><a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/mail-delete/<?=$email['id']; ?>" data-title="<?=lang('Newsletter.DeleteEmail');?>" data-message="<?=lang('Newsletter.DeleteEmailConfirm') . ': <b>' . $email['email'] . '</b>'; ?>" data-btn-ok="<?=lang('Newsletter.Remove');?>" data-btn-cancel="<?=lang('Newsletter.Cancel');?>" title="<?=lang('Newsletter.Delete');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?= view('admin/order_and_pagination', array('pager' => $pager,'on_page_list' => $on_page_list)); ?>
    </div>
</div>
