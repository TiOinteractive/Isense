<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head"><?=lang('Admin.administration.Sidebars');?></div>
        <p><a href="/<?= env('ADMIN_PANEL_SLUG'); ?>/sidebar/add" title="<?=lang('Admin.sidebar.AddSidebar');?>" class="btn"><?=lang('Admin.sidebar.AddSidebar');?></a></p>
        <div class="list">
            <div class="list-row list-head">
                <div class="list-col">
                    <?=lang('Admin.sidebar.Name');?>
                </div>
                <div class="list-col w100 center hide-1200">
                    <?=lang('Admin.sidebar.Content');?>
                </div>
                <div class="list-col w100 center hide-1200">
                    <?=lang('Admin.sidebar.Configuration');?>
                </div>
                <div class="list-col w100 center">
                    <?=lang('Admin.sidebar.Delete');?>
                </div>
            </div>
            <?php if(!empty($sidebars)): ?>
                <?php foreach($sidebars as $k=>$sidebar): ?>
                    <div class="list-row list-row-<?=$sidebar['id']; ?>">
                        <div class="list-col">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/sidebar/content/<?=$sidebar['id']; ?>" title="<?=esc($sidebar['name']);?>"><?=$sidebar['name']; ?></a>
                        </div>
                        <div class="list-col w100 center">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/sidebar/content/<?=$sidebar['id']; ?>" title="<?=lang('Admin.sidebar.Configuration');?>"><i class="fas fa-edit fa-2x"></i></a>
                        </div>
                        <div class="list-col w100 center hide-1200">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/sidebar/edit/<?=$sidebar['id']; ?>" title="<?=lang('Admin.sidebar.Edit');?>"><i class="fa-solid fa-gear fa-2x"></i></a>
                        </div>
                        <div class="list-col w100 center">
                            <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))): ?><a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/sidebar/delete/<?=$sidebar['id']; ?>" data-title="<?=lang('Admin.sidebar.DeleteSidebar');?>" data-message="<?=lang('Admin.sidebar.DeleteConfirm') . ': <b>' . $sidebar['name'] . '</b>'; ?>" data-btn-ok="<?=lang('Admin.sidebar.Remove');?>" data-btn-cancel="<?=lang('Admin.sidebar.Cancel');?>" title="<?=lang('Admin.sidebar.Delete');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach;  ?>
            <?php endif; ?>
        </div>
    </div>
</div>
