<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
                <?=$tag['tag']; ?>
                <span><?=lang('Tags.TagEdit'); ?></span>
        </div>
		<?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
		
		
	</div>
</div>	