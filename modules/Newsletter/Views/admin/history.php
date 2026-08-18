<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?=$newsletter['name']; ?>
            <span><?=lang('Newsletter.NewsletterHistory');?></span>
        </div>
        <?=view('Modules\Newsletter\Views\admin\tabs'); ?>
        <div class="list newsletters-list">
            <div class="list-row list-head">
                <div class="list-col">
                    <?=lang('Newsletter.Subject');?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?=lang('Newsletter.Status');?>
                </div>
                <div class="list-col center w100 hide-500">
                    <?=lang('Newsletter.Date');?>
                </div>
                <div class="list-col center w200 hide-1200">
                    <?=lang('Newsletter.Addresses');?> / <?=lang('Newsletter.Sent');?> / <?=lang('Newsletter.Readed');?> / <?=lang('Newsletter.Error');?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?=lang('Newsletter.Groups');?>
                </div>
                <div class="list-col center w100 hide-500">
                    <?=lang('Newsletter.Statistics');?>
                </div>
                <div class="list-col center w100 hide-500">
                    <?=lang('Newsletter.Preview');?>
                </div>
            </div>
            <?php if(!empty($history)): ?>
                <?php foreach($history as $newsletter): ?>
                    <div class="list-row list-row-<?=$newsletter['id']; ?><?php if(!empty($newsletter['status']) && in_array($newsletter['status'], array('set', 'sending'))): ?> in-progress<?php endif; ?>">
                        <div class="list-col">
                            <?=$newsletter['subject']; ?>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <?php if(!empty($newsletter['status'])): ?>
                                <?php if(in_array($newsletter['status'], array('set', 'sending')) && strtotime($newsletter['send_time']) >= strtotime('-5 minute')): ?>
                                    <div class="newsletter-progress-box history-<?=$newsletter['id']; ?>" data-status-url="<?=$locale ? '/' . $locale : ''; ?>/nosession/newsletter/update-sending<?=!empty($newsletter['hash']) ? '/' . $newsletter['hash'] : '' ; ?>">
                                        <div class="progress-bar">
                                            <div class="bar" style="width:<?=round($newsletter['emails_all'] * 100 / $newsletter['emails']); ?>%"></div>
                                            <span class="percentage"><?=round($newsletter['emails_all'] * 100 / $newsletter['emails']); ?>%</span>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <?=lang('Newsletter.status.' . $newsletter['status']); ?>
                                <?php endif; ?>
                                <?php if(in_array($newsletter['status'], array('set', 'sending'))): ?>
                                    <a class="list-remove-btn cancel-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/cancel/<?=$newsletter['id']; ?>" data-title="<?=lang('Newsletter.CancelNewsletter');?>" data-message="<?=lang('Newsletter.CancelNewsletterConfirm') . ': <b>' . $newsletter['subject'] . '</b>'; ?>" data-btn-ok="<?=lang('Newsletter.Cancel');?>" data-btn-cancel="<?=lang('Newsletter.Close');?>" title="<?=lang('Newsletter.CancelSending');?>"><i class="fa-solid fa-rectangle-xmark"></i> <?=lang('Newsletter.CancelSending');?></a>
                                 <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <?=date('d.m.Y H:i', strtotime($newsletter['send_time'] ? $newsletter['send_time'] : $newsletter['created_at'])); ?>
                        </div>
                        <div class="list-col center w200 hide-1200">
                            <?=$newsletter['emails']; ?>/<?=$newsletter['emails_sent'] + $newsletter['emails_readed']; ?>/<?=$newsletter['emails_readed']; ?>/<?=$newsletter['emails_error']; ?>
                        </div>
                        <div class="list-col center w100 hide-1200">
                            <?php if(!empty($newsletter['groups'])): ?>
                                <?php foreach($newsletter['groups'] as $g=>$group): ?>
                                    <?=($g ? '<br />' : '') . $group['name']; ?>
                                <?php endforeach; ?>
                            <?php endif;?>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/statistics/<?=$newsletter['id']; ?>" title="<?=lang('Newsletter.Statistics');?>"><i class="fa-solid fa-area-chart fa-xl"></i></a>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <a class="list-preview-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/history-preview/<?=$newsletter['id']; ?>" title="<?=lang('Newsletter.Preview');?>" data-title="<?=$newsletter['subject']; ?>" data-btn-close="<?=lang('Newsletter.Close'); ?>"><i class="fa-solid fa-magnifying-glass fa-xl"></i></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>