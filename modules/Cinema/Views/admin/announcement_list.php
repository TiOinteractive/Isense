<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?php if(!empty($movie)): ?>
                <?=$movie['title']; ?>
                <span><?=lang('Cinema.AnnouncementList');?></span>
            <?php else: ?>
                <?=lang('Cinema.AnnouncementList');?>
            <?php endif; ?>
        </div>
        <p><a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/cinema/add-announcement<?=!empty($movie) ? '/' . $movie['id'] : ''; ?>" title=""><?=lang('Cinema.AddAnnouncement');?></a></p>
        <?= view('Modules\Cinema\Views\admin\announcement_list_filters', array()); ?>
        <?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
        <div class="list">
            <div class="list-row list-head">
                <div class="list-col w50 no-padding"></div>
                <div class="list-col">
                    <?=lang('Cinema.Title');?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?=lang('Cinema.Genre');?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?=lang('Cinema.Type');?>
                </div>
                <div class="list-col center w200 hide-1200">
                    <?=lang('Cinema.CinemaPlace');?>
                </div>
                <div class="list-col center w100 hide-500">
                    <?=lang('Cinema.CinemaDate');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Cinema.Delete');?>
                </div>
            </div>
            <?php if(!empty($announcements)): ?>
                <?php foreach($announcements as $announcement): ?>
                    <div class="list-row list-row-<?=$announcement['id']; ?>">
                        <div class="list-col w50 no-padding">
                            <?php if(!empty($announcement['path'])): ?>
                                <img src="/image/c/50/50/<?=$announcement['path']; ?>" alt="<?=esc($announcement['title']); ?>" />
                            <?php endif; ?>
                        </div>
                        <div class="list-col">
                            <?=$announcement['title']; ?>
                        </div>
                        <div class="list-col center w100 hide-1200">
                            <?php if(!empty($announcement['genres'])): ?>
                                <?php foreach($announcement['genres'] as $k=>$genre): ?><?php if($k): ?>, <?php endif; ?><?=$genre['name']; ?><?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="list-col center w100 hide-1200">
                            <?php if(!empty($announcement['types'])): ?>
                                <?php foreach($announcement['types'] as $k=>$type): ?><?php if($k): ?>, <?php endif; ?><?=$type['name']; ?><?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="list-col center w200 hide-1200">
                            <?php if(!empty($announcement['place'])): ?><?=$announcement['place']; ?><?php endif; ?>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <?=date('d.m.Y', strtotime($announcement['date'])); ?>
                        </div>
                        <div class="list-col center w100">
                        <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))): ?><a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/cinema/delete-announcement/<?=$announcement['id']; ?>" data-title="<?=lang('Cinema.DeleteAnnouncement');?>" data-message="<?=lang('Cinema.AnnouncementDeleteConfirm') . ': <b>' . $announcement['title'] . '</b>'; ?>" data-btn-ok="<?=lang('Cinema.Remove');?>" data-btn-cancel="<?=lang('Cinema.Cancel');?>" title="<?=lang('Cinema.Delete');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
    </div>
</div>
