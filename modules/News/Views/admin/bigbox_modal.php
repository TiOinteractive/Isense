<form class="filters" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/news/add_bigbox/<?=$id_box;?>" method="post" style="margin:0px;">
    <div class="filter">
        <label><?=lang('News.Title'); ?></label>
        <input type="text" name="title" value="<?=!empty($filters['title']) ? $filters['title'] : ''; ?>" />
    </div>
	<div class="filter">
        <label><?=lang('News.Date'); ?></label>
        <input type="text" class="datepicker-range" name="date" value="<?=!empty($filters['date']) ? $filters['date'] : ''; ?>" />
    </div>
	<div class="filter">
        <button type="submit"><?=lang('News.Search'); ?></button>
    </div>
</form>
<form class="filters-results" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/news/bigbox-save/<?=$id_box;?>" method="post">
    <div class="list">
    <div class="list-row list-head">
        <div class="list-col center w40"></div>
		<div class="list-col w50">ID</div>
        <div class="list-col w50"></div>
        <div class="list-col">
            <?=lang('News.Title');?>
        </div> 
		<div class="list-col w200">
            <?=lang('News.Page');?>
        </div> 
		<div class="list-col w100">
            <?=lang('News.Date');?>
        </div>
    </div>	
	 <?php if(!empty($newslist)): ?>
			<?php foreach($newslist as $k=>$news): ?>
				
				
			<div class="list-row list-row-<?= $news['id']; ?>">
					<div class="list-col center w40">
						<input type="checkbox" name="news[]" value="<?= $news['id']; ?>" />
					</div>
			<div class="list-col w50">
				<?= $news['id']; ?>
			</div>
			<div class="list-col w50 no-padding">
				<?php if (!empty($news['path'])): ?>
						<img src="/image/c/50/50/<?= $news['path']; ?>" alt="<?= esc($news['title']); ?>" />
				<?php endif; ?>
			</div>	
			<div class="list-col">
				<?= $news['title']; ?>
			</div>	
			<div class="list-col w200">
				<?php if(!empty( $news['page_name'])):?><?= $news['page_name']; ?><?php else:?><?= $news['page_title']; ?><?php endif;?>
			</div>
			<div class="list-col w100">
				<?= date("d.m.Y",strtotime($news['date'])); ?>
			</div>
			</div>
			<?php endforeach; ?>
		<?php else: ?>
			<div class="list-row no-list-result"><?=lang('News.NoListResult'); ?></div>
		<?php endif; ?>	
</div>
</form>