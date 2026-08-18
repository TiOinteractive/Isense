<div class="main-cont ajax-cont">
<?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head"><?=lang('Admin.administrators.Addadmin');?></div>
		<form class="form slider-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/administrators/save" method="post" id="FormAdmin">
		    <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.administrators.Name');?></label>
                </div>
                <div class="form-field">
                    <input type="text" autocomplete="off" name="name" value="<?php if(isset($administrator['name'])) {echo $administrator['name'];} ;?>" />
					<?php if(isset($result['errors']['name'])):?>
						 <div class="alert alert-warning">
						   <?=$result['errors']['name']; ?>
						</div>
                   <?php endif;?>
                </div>
            </div>
		     <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.administrators.Login');?></label>
                </div>
                <div class="form-field">
                    <input type="text" autocomplete="off" name="login" value="<?php if(isset($administrator['login'])) {echo $administrator['login'];} ;?>" />
					<?php if(isset($result['errors']['login'])):?>
						 <div class="alert alert-warning">
						   <?=$result['errors']['login']; ?>
						</div>
                   <?php endif;?>
                </div>
            </div>
			<div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.administrators.Email');?></label>
                </div>
                <div class="form-field">
                    <input type="text" autocomplete="off" name="email" value="<?php if(isset($administrator['email'])) {echo $administrator['email'];} ;?>" />
					<?php if(isset($result['errors']['email'])):?>
						 <div class="alert alert-warning">
						   <?=$result['errors']['email']; ?>
						</div>
                   <?php endif;?>
					
                </div>
            </div>
			<div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.administrators.Password');?> (<?=lang('Admin.administrators.RequiredChar',[8]);?>)</label>
                </div>
                <div class="form-field">
                    <input type="password" autocomplete="off" name="password" value="">
					<?php if(isset($result['errors']['password'])):?>
						 <div class="alert alert-warning">
						   <?=$result['errors']['password']; ?>
						</div>
                   <?php endif;?>
                </div>
            </div>
			<div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.administrators.Role');?></label>
                </div>
                <div class="form-field">
				  <select name="role">
                    <?php
					$roles=lang('Admin.admin_role');
					if(isset($roles)) {
                     foreach($roles as $role_ind=>$role_val) {
						 
						echo '<option value="'.$role_ind.'">'.$role_val.'</option>'; 
					 }	 
                    }
					?>
				 </select>
                </div>
            </div>
			<div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.administrators.TechnicalAdmin');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="technical_admin" value="1" <?php if(isset($administrator['technical_admin']) and $administrator['technical_admin']==1) {echo ' checked="checked" ';} ?> />
                </div>
            </div>
			<div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.administrators.Active');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="active" value="1" <?php if(isset($administrator['active']) and $administrator['active']==1) {echo ' checked="checked" ';} ?> />
                </div>
            </div>
			<div class="form-row submit">
                <button type="button" onclick="AjaxSaveForm('#FormAdmin')"><?=lang('Admin.administrators.Addadmin');?></button>
            </div>
		</form>
    </div>
</div>
