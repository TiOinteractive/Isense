<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head"><?=lang('Admin.page.PagesList');?></div>
        <p><a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/page/add" title=""><i class="fa-solid fa-plus"></i> &nbsp;<?=lang('Admin.page.AddPage'); ?></a></p>
        <div class="list">
            <div class="list-row list-head">
                <div class="list-col">
                    <?=lang('Admin.page.Name');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Admin.page.Edit');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Admin.page.Configuration');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Admin.page.Publish');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Admin.page.Delete');?>
                </div>
            </div>
            <?php if(!empty($pages)): ?>
                <?php foreach($pages as $k=>$page): ?>
                    <?= view('admin/page/list_item', array('page'=>$page, 'count'=>count($pages), 'item_no'=>$k+1)); ?>
                <?php endforeach;  ?>
            <?php endif; ?>
        </div>
    </div>
</div>
