<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head"><?=lang('Newsletter.NewsletterList');?></div>
        <?=view('Modules\Newsletter\Views\admin\tabs'); ?>
        <p><a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/add" title=""><?=lang('Newsletter.AddNewsletter');?></a></p>
        <?= view('Modules\Newsletter\Views\admin\list_filters', array()); ?>
        <?= view('admin/order_and_pagination', array('pager'=>$pager)); ?>
        <div class="list newsletters-list">
            <div class="list-row list-head">
                <div class="list-col">
                    <?=lang('Newsletter.Subject');?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?=lang('Newsletter.History');?>
                </div>
                <div class="list-col center w100 hide-500">
                    <?=lang('Newsletter.Sending');?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?=lang('Newsletter.Edit');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Newsletter.Delete');?>
                </div>
                <div class="list-col center w100 hide-500">
                    <?=lang('Newsletter.Preview');?>
                </div>
            </div>
            <?php if(!empty($newsletters)): ?>
                <?php foreach($newsletters as $newsletter): ?>
                    <div class="list-row list-row-<?=$newsletter['id']; ?>">
                        <div class="list-col">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/edit/<?=$newsletter['id']; ?>" title="<?=$newsletter['subject']; ?>"><?=$newsletter['subject']; ?></a>
                        </div>
                        <?php /* ?>
                        <div class="list-col center w100 hide-500">
                            <?php if(!empty($newsletter['history'])): ?>
                                <?php foreach($newsletter['history'] as $history): ?>
                                        <div class="newsletter-progress-box history-<?=$history['id']; ?>" data-status-url="<?=$locale ? '/' . $locale : ''; ?>/nosession/newsletter/update-sending<?=!empty($history['hash']) ? '/' . $history['hash'] : '' ; ?>">
                                            <div class="progress-bar">
                                                <div class="bar" style="width:<?=round($history['emails_sent'] * 100 / $history['emails']); ?>%"></div>
                                                <span class="percentage"><?=round($history['emails_sent'] * 100 / $history['emails']); ?>%</span>
                                            </div>
                                        </div>
                                    <?php if(in_array($history['status'], array('set', 'sending'))): ?>
                                        <a class="list-remove-btn cancel-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/cancel/<?=$history['id']; ?>" data-title="<?=lang('Newsletter.CancelNewsletter');?>" data-message="<?=lang('Newsletter.CancelNewsletterConfirm') . ': <b>' . $newsletter['subject'] . '</b>'; ?>" data-btn-ok="<?=lang('Newsletter.Cancel');?>" data-btn-cancel="<?=lang('Newsletter.Close');?>" title="<?=lang('Newsletter.CancelSending');?>"><i class="fa-solid fa-rectangle-xmark"></i> <?=lang('Newsletter.CancelSending');?></a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <?php */ ?>
                        <div class="list-col center w100 hide-500">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/history/<?=$newsletter['id']; ?>" title="<?=lang('Newsletter.Edit');?>"><i class="fa-solid fa-box-archive fa-xl"></i></a>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/send/<?=$newsletter['id']; ?>" title="<?=lang('Newsletter.Edit');?>"><i class="fa-solid fa-paper-plane fa-xl"></i></a>
                        </div>
                        <div class="list-col center w100 hide-1200">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/edit/<?=$newsletter['id']; ?>" title="<?=lang('Newsletter.Edit');?>"><i class="fa-solid fa-pencil fa-xl"></i></a>
                        </div>
                        <div class="list-col center w100">
                            <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))): ?><a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/delete/<?=$newsletter['id']; ?>" data-title="<?=lang('Newsletter.DeleteNewsletter');?>" data-message="<?=lang('Newsletter.DeleteNewsletterConfirm') . ': <b>' . $newsletter['subject'] . '</b>'; ?>" data-btn-ok="<?=lang('Newsletter.Remove');?>" data-btn-cancel="<?=lang('Newsletter.Cancel');?>" title="<?=lang('Newsletter.Delete');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a><?php endif; ?>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <a class="list-preview-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/preview/<?=$newsletter['id']; ?>" title="<?=lang('Newsletter.Preview');?>" data-title="<?=$newsletter['subject']; ?>" data-btn-close="<?=lang('Newsletter.Close'); ?>"><i class="fa-solid fa-magnifying-glass fa-xl"></i></a>
                        </div>
                    </div>
                    <?php if(!empty($newsletter['history_list'])): ?>
                        <?php foreach($newsletter['history_list'] as $h): ?>
                            <div class="list-sub-row list-row list-sub-row-<?=$h['id']; ?> list-parent-row-<?=$newsletter['id']; ?><?php if(!empty($h['status']) && in_array($h['status'], array('set', 'sending'))): ?> in-progress<?php endif; ?>">
                                <div class="list-col">
                                    <span class="caption"><?=lang('Newsletter.Date'); ?>:</span> <?=date('d.m.Y H:i', strtotime($h['send_time'] ? $h['send_time'] : $h['created_at'])); ?>
                                </div>
                                <div class="list-col">
                                    <span class="caption"><?=lang('Newsletter.Subject');?>:</span> <?=$h['subject']; ?>
                                </div>
                                <div class="list-col">
                                    <?php if(!empty($h['status'])): ?>
                                        <?php if(in_array($h['status'], array('set', 'sending')) && strtotime($h['send_time']) >= strtotime('-5 minute')): ?>
                                            <div class="newsletter-progress-box history-<?=$h['id']; ?>" data-status-url="<?=$locale ? '/' . $locale : ''; ?>/nosession/newsletter/update-sending<?=!empty($h['hash']) ? '/' . $h['hash'] : '' ; ?>">
                                                <div class="progress-bar">
                                                    <div class="bar" style="width:<?=round($h['emails_all'] * 100 / $h['emails']); ?>%"></div>
                                                    <span class="percentage"><?=round($h['emails_all'] * 100 / $h['emails']); ?>%</span>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <span class="caption"><?=lang('Newsletter.Status');?>:</span> <?=lang('Newsletter.status.' . $h['status']); ?>
                                        <?php endif; ?>
                                        <?php if(in_array($h['status'], array('set', 'sending'))): ?>
                                            <a class="list-remove-btn cancel-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/cancel/<?=$h['id']; ?>" data-title="<?=lang('Newsletter.CancelNewsletter');?>" data-message="<?=lang('Newsletter.CancelNewsletterConfirm') . ': <b>' . $h['subject'] . '</b>'; ?>" data-btn-ok="<?=lang('Newsletter.Cancel');?>" data-btn-cancel="<?=lang('Newsletter.Close');?>" title="<?=lang('Newsletter.CancelSending');?>"><i class="fa-solid fa-rectangle-xmark"></i> <?=lang('Newsletter.CancelSending');?></a>
                                         <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                <div class="list-col center">
                                    <span class="caption"><?=lang('Newsletter.Addresses');?> / <?=lang('Newsletter.Sent');?> / <?=lang('Newsletter.Readed');?> / <?=lang('Newsletter.Error');?></span>
                                    <?=$h['emails']; ?>/<?=$h['emails_sent'] + $h['emails_readed']; ?>/<?=$h['emails_readed']; ?>/<?=$h['emails_error']; ?>
                                </div>
                                <div class="list-col center w100 hide-500">
                                    <a class="list-preview-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/history-preview/<?=$h['id']; ?>" title="<?=lang('Newsletter.Preview');?>" data-title="<?=$h['subject']; ?>" data-btn-close="<?=lang('Newsletter.Close'); ?>"><i class="fa-solid fa-magnifying-glass fa-xl"></i></a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?= view('admin/order_and_pagination', array('pager'=>$pager)); ?>
    </div>
</div>
