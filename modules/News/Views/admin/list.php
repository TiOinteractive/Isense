<div class="form-row-space"></div>
<div class="form-row nag">
    <h3><?= lang('News.NewsList'); ?></h3>
    <a class="configuration" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/news/configuration/<?= $id_content; ?>" title="<?=lang('News.Configuration'); ?>"><i class="fa-solid fa-gear"></i></a>
</div>
<p><a class="btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/news/add/<?= $id_content; ?>" title="<?= lang('News.AddNews'); ?>"><i class="fa-solid fa-plus"></i> <?= lang('News.AddNews'); ?></a></p>
<?= view('Modules\News\Views\admin\list_filters', array()); ?>
<?= view('admin/order_and_pagination', array('pager' => $pager, 'on_page_list' => $on_page_list)); ?> 
<div class="newsletter-options">
    <div class="newsletter-count"><?=lang('News.NewsletterCountInfo'); ?>: <strong><?=$newsletter_count; ?></strong></div>
    <a class="btn small clear" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/news/newsletter-clear/<?= $id_page; ?>"><?=lang('News.NewsletterClear'); ?></a>
</div>
<?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
<div class="list">
    <div class="list-row list-head">
        <?php /*if (empty($filters['order_array']) or!empty($filters['order_array']['order'])): ?>     
            <div class="list-col center w50<?php if (!empty($filters['order_array']) && !empty($filters['order_array']['order'])): ?> <?= $filters['order_array']['order']; ?><?php endif; ?>" data-order="order">
                <?= lang('News.Lp'); ?>
            </div>
        <?php endif; */?>	
        <div class="list-col w50 no-padding"></div>	
        <div class="list-col<?php if (!empty($filters['order_array']) && !empty($filters['order_array']['name'])): ?> <?= $filters['order_array']['name']; ?><?php endif; ?>" data-order="name">
            <?= lang('News.Title'); ?>
        </div>
        <div class="list-col center w100 hide-1200<?php if (!empty($filters['order_array']) && !empty($filters['order_array']['date'])): ?> <?= $filters['order_array']['date']; ?><?php endif; ?>" data-order="date">
            <?= lang('News.Date'); ?>
        </div>
        <div class="list-col center w100 hide-1200">
            <?= lang('News.Views'); ?>
        </div>
        <div class="list-col center w100 hide-1200<?php if (!empty($filters['order_array']) && !empty($filters['order_array']['newsletter'])): ?> <?= $filters['order_array']['newsletter']; ?><?php endif; ?>" data-order="newsletter">
            <?=lang('News.Newsletter');?>
        </div>
        <div class="list-col center w100 hide-1200<?php if (!empty($filters['order_array']) && !empty($filters['order_array']['investments'])): ?> <?= $filters['order_array']['investments']; ?><?php endif; ?>" data-order="investments">
            <?=lang('News.Investments');?>
        </div>
		<div class="list-col center w90 hide-1200<?php if (!empty($filters['order_array']) && !empty($filters['order_array']['show_in_box'])): ?> <?= $filters['order_array']['show_in_box']; ?><?php endif; ?>" data-order="show_in_box">
            <?=lang('News.Box');?>
        </div>
        <div class="list-col center w90 hide-1200<?php if (!empty($filters['order_array']) && !empty($filters['order_array']['slider'])): ?> <?= $filters['order_array']['slider']; ?><?php endif; ?>" data-order="slider">
            <?=lang('News.Slider');?>
        </div>
        <div class="list-col center w90 hide-1200<?php if (!empty($filters['order_array']) && !empty($filters['order_array']['home'])): ?> <?= $filters['order_array']['home']; ?><?php endif; ?>" data-order="home">
            <?= lang('News.Home'); ?>
        </div>
        <div class="list-col center w100 hide-1200">
            <?= lang('News.Edit'); ?>
        </div>
        <div class="list-col center w100 hide-500<?php if (!empty($filters['order_array']) && !empty($filters['order_array']['publish'])): ?> <?= $filters['order_array']['publish']; ?><?php endif; ?>" data-order="publish">
            <?= lang('News.Publish'); ?>
        </div>
        <div class="list-col center w100">
            <?= lang('News.Delete'); ?>
        </div>
    </div>
    <?php if (!empty($news_list)): ?>
        <div<?php if (!empty($filters['order']) && in_array($filters['order'], array('order,asc', 'order,desc'))): ?> class="list-order-box"<?php endif; ?> data-url="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/news/order/<?= $id_content; ?>">
            <?php foreach ($news_list as $k => $news): ?>
                <div class="list-row list-row-<?= $news['id']; ?>">
                    <?php /*if (empty($filters['order_array']) or!empty($filters['order_array']['order'])): ?>  
                        <div class="list-col center w50 order">
                            <?= $news['order']; ?>
                        </div>
                    <?php endif; */ ?>
                    <div class="list-col w50 no-padding">
                        <?php if(!empty($news['path'])): ?>
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/news/edit/<?= $id_content; ?>/<?=$news['id']; ?>" title="<?=esc($news['title']); ?>">
                                <img src="/image/c/50/50/<?=$news['path']; ?>" alt="<?=esc($news['title']); ?>" />
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="list-col">
                        <a href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/news/edit/<?= $id_content; ?>/<?= $news['id']; ?>" title="<?= $news['title']; ?>"><strong><?= $news['title']; ?></strong></a>
                    </div>
                    <div class="list-col center w100 hide-1200">
                        <?php if ($news['date'] != '0000-00-00 00:00:00') {
                            echo date("d.m.Y", strtotime($news['date']));
                        } ?>
                    </div>
                    <div class="list-col center w100 hide-1200">
                        <?=$news['views']; ?>
                    </div>
                    <div class="list-col center w100 hide-1200">
                        <a class="list-newsletter-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/news/newsletter/<?= $news['id']; ?>" title="<?= lang('News.Newsletter'); ?>"><?php if (!empty($news['newsletter']) && $news['newsletter']): ?><i class="fa-solid fa-toggle-on fa-xl"></i><?php else: ?><i class="fa-solid fa-toggle-off fa-xl"></i><?php endif; ?></a>
                    </div>
                    <div class="list-col center w100 hide-1200">
                        <a class="list-home-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/news/investments/<?= $news['id']; ?>" title="<?= lang('News.Investments'); ?>"><?php if (!empty($news['investments']) && $news['investments']): ?><i class="fa-solid fa-toggle-on fa-xl"></i><?php else: ?><i class="fa-solid fa-toggle-off fa-xl"></i><?php endif; ?></a>
                    </div>
					<div class="list-col center w90 hide-1200">
					   <select class="ajax_select" name="show_in_box" data-url="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/news/select_box/<?= $news['id']; ?>">
                        <option value="">-</option>
                        <option value="1" <?php if(!empty($news['show_in_box']) and $news['show_in_box']==1):?> selected="selected"<?php endif;?>>Big Box 1</option>
                        <option value="2" <?php if(!empty($news['show_in_box']) and $news['show_in_box']==2):?> selected="selected"<?php endif;?>>Box 2</option>
						<option value="3" <?php if(!empty($news['show_in_box']) and $news['show_in_box']==3):?> selected="selected"<?php endif;?>>Box 3</option>
						<option value="4" <?php if(!empty($news['show_in_box']) and $news['show_in_box']==4):?> selected="selected"<?php endif;?>>Box 4</option>
						<option value="5" <?php if(!empty($news['show_in_box']) and $news['show_in_box']==5):?> selected="selected"<?php endif;?>>Box 5</option>
						<option value="6" <?php if(!empty($news['show_in_box']) and $news['show_in_box']==6):?> selected="selected"<?php endif;?>>Box 6</option>
                     </select>
					</div>
                    <div class="list-col center w90 hide-1200">
                        <a class="list-home-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/news/slider/<?= $news['id']; ?>" title="<?= lang('News.Slider'); ?>"><?php if (!empty($news['slider']) && $news['slider']): ?><i class="fa-solid fa-toggle-on fa-xl"></i><?php else: ?><i class="fa-solid fa-toggle-off fa-xl"></i><?php endif; ?></a>
                    </div>
                    <div class="list-col center w90 hide-1200">
                        <a class="list-home-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/news/home/<?= $news['id']; ?>" title="<?= lang('News.Home'); ?>"><?php if (!empty($news['home']) && $news['home']): ?><i class="fa-solid fa-toggle-on fa-xl"></i><?php else: ?><i class="fa-solid fa-toggle-off fa-xl"></i><?php endif; ?></a>
                    </div>
                    <div class="list-col center w100 hide-1200">
                        <a href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/news/edit/<?= $id_content; ?>/<?= $news['id']; ?>" title="<?= lang('News.Edit'); ?>"><i class="fa-solid fa-pencil fa-xl"></i></a>
                    </div>
                    <div class="list-col center w100 hide-500">
                        <a class="list-publish-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/news/publish/<?= $news['id']; ?>" title="<?= lang('News.Publish'); ?>"><?php if (!empty($news['publish']) && $news['publish']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>

                        <?php if(!empty($news['publish_date'])):?>
                        <span style="font-size:11px;"><?= date("d.m.Y H:i", strtotime($news['publish_date'])); ?></span>
                        <?php endif;?>
                    </div>
                    <div class="list-col center w100">
                <?php if (isset($_SESSION['role']) and!in_array($_SESSION['role'], array('editor', 'contributor'))) { ?>  <a class="list-remove-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/news/delete/<?= $news['id']; ?>" data-title="<?= lang('News.DeleteNews'); ?>" data-message="<?= lang('News.DeleteConfirm') . ': <b>' . esc($news['title']) . '</b>'; ?>" data-btn-ok="<?= lang('News.Remove'); ?>" data-btn-cancel="<?= lang('News.Cancel'); ?>" title="<?= lang('News.Delete'); ?>"><i class="fa-regular fa-trash-can fa-xl"></i></a> <?php } ?>
                    </div>
                </div>
        <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="list-row no-list-result"><?= lang('News.NoListResult'); ?></div>
<?php endif; ?> 
<?= view('admin/order_and_pagination', array('pager' => $pager, 'order_list' => $order_list)); ?>
</div>