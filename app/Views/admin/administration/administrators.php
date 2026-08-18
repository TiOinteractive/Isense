<div class="main-cont ajax-cont">
<?php
if(session()->getFlashdata('response')) {
?>
<script>
 $( document ).ready(function() {
    $.alert({
		type:'green',
		boxWidth: '300px',
        title: '<?=lang('Admin.administrators.Addadmin');?>',
        content: '<?=session()->getFlashdata('response');?>',
		useBootstrap: false
    });
 });	
</script>
<?php
}
?>
<?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
	<div class="head"><?=lang('Admin.administration.Administrators');?></div>
        <p><a href="/<?= env('ADMIN_PANEL_SLUG'); ?>/administrators/add" title="<?=lang('Admin.administrators.Addadmin');?>" class="btn"><?=lang('Admin.administrators.Addadmin');?></a></p>
        <div class="list">
            <div class="list-row list-head">
                <div class="list-col">
                    <?=lang('Admin.administrators.Name');?>
                </div>
				<div class="list-col">
                    <?=lang('Admin.administrators.Login');?>
                </div>
				<div class="list-col hide-992">
                    <?=lang('Admin.administrators.Role');?>
                </div>
				<div class="list-col hide-500">
                    <?=lang('Admin.administrators.Email');?>
                </div>
				<div class="list-col hide-1200">
                    <?=lang('Admin.administrators.LastActivity');?>
                </div>
				<div class="list-col center hide-1200">
                    <?=lang('Admin.administrators.Active');?>
                </div>
				<div class="list-col center hide-1200">
                    <?=lang('Admin.administrators.Edit');?>
                </div>
				<div class="list-col center">
                    <?=lang('Admin.administrators.Delete');?>
                </div>
            </div>
            <?php 
			foreach($administrators as $admin): ?>
            <div class="list-row">
                <div class="list-col">
                    <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/administrators/edit/<?=$admin['id']; ?>" title="<?=lang('Admin.administrators.Edit');?>"><?=$admin['name']; ?></a>
                </div>
				<div class="list-col">
                    <?=$admin['login']; ?>
                </div>
				<div class="list-col hide-992">
                    <?=lang('Admin.admin_role.'.$admin['role'].''); ?>
                </div>
				<div class="list-col hide-500">
                    <?=$admin['email']; ?>
                </div>
				<div class="list-col hide-1200">
                    <?php if($admin['last_active']!='0000-00-00 00:00:00') { echo date("Y-m-d H:i",strtotime($admin['last_active'])); } ?>
                </div>
				<div class="list-col center hide-1200">
                    <a href="javascript:AjaxCheckbox('<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/administrators/active/<?=$admin['id']; ?>');" title="<?=lang('Admin.administrators.Active');?>"><?php if ($admin['active']==1) { echo '<i class="fas fa-check-square fa-2x"></i>';} else {echo '<i class="far fa-square fa-2x"></i>';} ?></a>
                </div>
                <div class="list-col center hide-1200">
                    <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/administrators/edit/<?=$admin['id']; ?>" title="<?=lang('Admin.administrators.Edit');?>"><i class="fas fa-edit fa-2x"></i></a>
                </div>
				<div class="list-col center">
                    <a href="javascript:confirm_delete(<?=$admin['id']; ?>,'<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/administrators/delete/','<?=lang('Admin.administrators.DeleteUser');?>','<?=lang('Admin.administrators.DeleteConfirm');?>','<?=lang('Admin.administrators.Cancel');?>');" title="<?=lang('Admin.administrators.Delete');?>"><i class="fas fa-trash-alt fa-2x"></i></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
