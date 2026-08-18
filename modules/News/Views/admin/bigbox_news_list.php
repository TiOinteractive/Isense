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