<div class="list-row list-row-<?=$left_page['id']; ?><?php if(!empty($url_array[1]) and !empty($url_array[3]) and $url_array[1]=='page' and $url_array[3]==$left_page['id']):?> active <?php endif;?> level-<?=$left_page['level']; ?><?= $item_no<$count ? ' full' : ''; ?> re-<?=$left_page['re_id']; ?> <?php if(($left_page['level']>0) and ((!empty($_COOKIE['LeftMenu_'.$left_page['re_id']]) and $_COOKIE['LeftMenu_'.$left_page['re_id']]!='show') or empty($_COOKIE['LeftMenu_'.$left_page['re_id']]))):?> hide<?php endif;?>">
    <div class="list-col">
        <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/page/content/<?=$left_page['id']; ?>" title="<?=$left_page['name']; ?>"><?=$left_page['name']; ?></a>
    </div>
   <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))) {?> <a class="link-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/page/configuration/<?=$left_page['id']; ?>" title="<?=lang('Admin.page.Edit');?>"><i class="fa-solid fa-gear"></i></a> <?php } ?>
   <?php if(!empty($left_page['list'])): ?><a href="javascript:ShowHideMenu(<?=$left_page['id']; ?>,<?=$left_page['level'];?>);" title="<?=lang('Admin.ShowHide');?>" class="hide-btn"><i class="fa-solid <?php if(!empty($_COOKIE['LeftMenu_'.$left_page['id']]) && $_COOKIE['LeftMenu_'.$left_page['id']]=='show') { ?>fa-chevron-up<?php } else { ?>fa-chevron-down<?php }?>"></i></a><?php endif; ?>
</div>
<?php if(!empty($left_page['list'])): ?>
    <?php foreach($left_page['list'] as $j=>$p): ?>
        <?= view('admin/page/menu_left_list_item', array('left_page' => $p, 'count'=>count($left_page['list']), 'item_no'=>$j+1)); ?>
    <?php endforeach; ?>
<?php endif; ?>