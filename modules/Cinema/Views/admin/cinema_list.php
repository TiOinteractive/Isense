<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?php if(!empty($movie)): ?>
                <?=$movie['title']; ?>
                <span><?=lang('Cinema.CinemaCalendar');?></span>
            <?php else: ?>
                <?=lang('Cinema.CinemaCalendar');?>
            <?php endif; ?>
        </div>
        <p>
            <a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/cinema/add<?=!empty($movie) ? '/' . $movie['id'] : ''; ?>" title=""><?=lang('Cinema.AddCalendar');?></a>
            <a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/cinema/import<?=!empty($movie) ? '/' . $movie['id'] : ''; ?>" title=""><?=lang('Cinema.Import');?></a>
        </p>
        <?= view('Modules\Cinema\Views\admin\cinema_list_filters', array()); ?>
        <?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
        <div class="list">
            <div class="list-row list-head">
                <div class="list-col w50 no-padding"></div>
                <div class="list-col">
                    <?=lang('Cinema.Title');?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?=lang('Cinema.Type');?>
                </div>
                <div class="list-col center w200 hide-1200">
                    <?=lang('Cinema.CinemaPlace');?>
                </div>
                <div class="list-col center w200 hide-500">
                    <?=lang('Cinema.CinemaDate');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Cinema.Delete');?>
                </div>
            </div>
            <?php if(!empty($calendar)): ?>
                <?php foreach($calendar as $c): ?>
                    <div class="list-row list-row-<?=$c['id']; ?>">
                        <div class="list-col w50 no-padding">
                            <?php if(!empty($c['poster']) && !empty($c['poster']['path'])): ?>
                                <img src="/image/c/50/50/<?=$c['poster']['path']; ?>" alt="<?=esc($c['title']); ?>" />
                            <?php endif; ?>
                        </div>
                        <div class="list-col">
                            <?=$c['title']; ?>
                        </div>
                        <div class="list-col center w100 hide-1200">
                            <?php if(!empty($c['types'])): ?>
                                <?php foreach($c['types'] as $k=>$type): ?><?php if($k): ?>, <?php endif; ?><?=$type['name']; ?><?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="list-col center w200 hide-1200">
                            <?php if(!empty($c['place']) && !empty($c['place']['name'])): ?><?=$c['place']['name']; ?><?php endif; ?>
                        </div>
                        <div class="list-col center w200 hide-500">
                            <?=date('d.m.Y H:i', strtotime($c['date'])); ?>
                        </div>
                        <div class="list-col center w100">
                        <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))): ?><a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/cinema/delete/<?=$c['id']; ?>" data-title="<?=lang('Cinema.DeleteCinemaCalendar');?>" data-message="<?=lang('Cinema.CalendarDeleteConfirm') . ': <b>' . $c['title'] . '</b>'; ?>" data-btn-ok="<?=lang('Cinema.Remove');?>" data-btn-cancel="<?=lang('Cinema.Cancel');?>" title="<?=lang('Cinema.Delete');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
    </div>
</div>
