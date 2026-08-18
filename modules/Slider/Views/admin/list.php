<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head"><?=lang('Slider.SliderList');?></div>
        <p><a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/slider/add" title=""><?=lang('Slider.AddSlider');?></a></p>
        <?= view('Modules\Slider\Views\admin\list_filters', array()); ?>
        <?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
        <div class="list">
            <div class="list-row list-head">
                <div class="list-col">
                    <?=lang('Slider.Name');?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?=lang('Slider.Archive');?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?=lang('Slider.Edit');?>
                </div>
                <div class="list-col center w100 hide-500">
                    <?=lang('Slider.Publish');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Slider.Delete');?>
                </div>
            </div>
            <?php if(!empty($sliders)): ?>
                <?php foreach($sliders as $slider): ?>
                    <div class="list-row list-row-<?=$slider['id']; ?>">
                        <div class="list-col">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/slider/edit/<?=$slider['id']; ?>" title="<?=$slider['name']; ?>"><?=$slider['name']; ?></a>
                        </div>
                        <div class="list-col center w100 hide-1200">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/slider/archive/<?=$slider['id']; ?>" title="<?=lang('Slider.Archive');?>"><i class="fa-solid fa-box-archive fa-xl"></i></i></a>
                        </div>
                        <div class="list-col center w100 hide-1200">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/slider/edit/<?=$slider['id']; ?>" title="<?=lang('Slider.Edit');?>"><i class="fa-solid fa-pencil fa-xl"></i></a>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <a class="list-publish-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/slider/publish/<?=$slider['id']; ?>" title="<?=lang('Slider.Publish');?>"><?php if(!empty($slider['publish']) && $slider['publish']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
                        </div>
                        <div class="list-col center w100">
                        <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))): ?><a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/slider/delete/<?=$slider['id']; ?>" data-title="<?=lang('Slider.DeleteSlider');?>" data-message="<?=lang('Slider.DeleteConfirm') . ': <b>' . $slider['name'] . '</b>'; ?>" data-btn-ok="<?=lang('Slider.Remove');?>" data-btn-cancel="<?=lang('Slider.Cancel');?>" title="<?=lang('Slider.Delete');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
    </div>
</div>
