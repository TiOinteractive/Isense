<header class="main-header">
        <div class="top-header">
		      <div class="container">
			<?php if(!empty($settings['logo'])):?>
                <div class="logo">
                    <a href="/<?=$locale;?>" title="<?=$settings['company_name'];?>" class="logo_link"><?php if(!empty($settings['logo_resinet_svg'])):?><?=$settings['logo_resinet_svg'];?><?php else:?><img src="/image/r/300/100/<?=$settings['logo']['path'];?>" alt="<?=$settings['company_name'];?>" /><?php endif;?></a>
					      <div class="box users-box">
								<div class="select_lang">
								   <div class="inside trans400">
									   <ul>
										  <li class="active">PL <svg stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 9L12 15L18 9" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/></svg></li>
										  <li><a href="https://www-resinet-pl.translate.goog/?_x_tr_sl=auto&_x_tr_tl=en&_x_tr_hl=pl&_x_tr_pto=wapp" target="_blank">ENG</a></li>
										  <li><a href="https://www-resinet-pl.translate.goog/?_x_tr_sl=auto&_x_tr_tl=uk&_x_tr_hl=pl&_x_tr_pto=wapp" target="_blank">UA</a></li>
										  <li><a href="https://www-resinet-pl.translate.goog/?_x_tr_sl=auto&_x_tr_tl=de&_x_tr_hl=pl&_x_tr_pto=wapp" target="_blank">DE</a></li>
										  <li><a href="https://www-resinet-pl.translate.goog/?_x_tr_sl=auto&_x_tr_tl=cs&_x_tr_hl=pl&_x_tr_pto=wapp" target="_blank">CZ</a></li>
										  <li><a href="https://www-resinet-pl.translate.goog/?_x_tr_sl=auto&_x_tr_tl=sk&_x_tr_hl=pl&_x_tr_pto=wapp" target="_blank">SK</a></li>
										  <li><a href="https://www-resinet-pl.translate.goog/?_x_tr_sl=auto&_x_tr_tl=es&_x_tr_hl=pl&_x_tr_pto=wapp" target="_blank">ES</a></li>
									   </ul>	  
								   </div>
								</div>
							<div class="newsletter-box">
								<a class="newsletter-subscribe" href="<?=$locale ? '/' . $locale : ''; ?>/newsletter-action/form" title="<?=lang('Users.account.SubscribeToTheNewsletter'); ?>" data-close="<?=lang('Users.account.Close'); ?>" data-title="<?=lang('Users.account.SubscribeToTheNewsletter'); ?>">
																	<svg viewBox="0 0 512 512"><g data-name="1"><path d="M441.13,406h-372a15,15,0,0,1-15-15V139a15,15,0,0,1,15-15h372a15,15,0,0,1,15,15V391A15,15,0,0,1,441.13,406Zm-357-30h342V154h-342Z"/><path d="M255.13,336a15,15,0,0,1-10.49-4.28l-186-182a15,15,0,1,1,21-21.44L255.13,300,430.64,128.28a15,15,0,0,1,21,21.44l-186,182A15,15,0,0,1,255.13,336Z"/><path d="M69.13,406a15,15,0,0,1-10-26.13L193,258.91a15,15,0,1,1,20.1,22.26l-133.92,121A15,15,0,0,1,69.13,406Z"/><path d="M441.12,406a14.92,14.92,0,0,1-10-3.87l-133.92-121a15,15,0,0,1,20.1-22.26l133.92,121A15,15,0,0,1,441.12,406Z"/></g></svg>
								</a>
							</div>
							<div class="account">
								<?php if(empty($session_user)): ?>
									<a href="/<?=$global_links['login']; ?>" title="<?=lang('Users.account.LogIn'); ?>"><svg viewBox="0 0 32 32"><path d="M16,20a8,8,0,1,1,8-8A8,8,0,0,1,16,20ZM16,6a6,6,0,1,0,6,6A6,6,0,0,0,16,6Z"/><path d="M30,32H28A12,12,0,0,0,4,32H2a14,14,0,0,1,28,0Z"/></svg></a>
								<?php else: ?>
									<a href="/<?=$global_links['client_account']; ?>" title="<?=lang('Users.account.YourAccount'); ?>"><svg viewBox="0 0 32 32"><path d="M16,20a8,8,0,1,1,8-8A8,8,0,0,1,16,20ZM16,6a6,6,0,1,0,6,6A6,6,0,0,0,16,6Z"/><path d="M30,32H28A12,12,0,0,0,4,32H2a14,14,0,0,1,28,0Z"/></svg></a>
								<?php endif; ?>
							</div>
						</div>
                </div>
            <?php endif; ?>
            <div class="boxes">
                <div class="box search-box">
                   <form name="search" action="/szukaj" method="get" id="top-search">
								<div>
									<input type="hidden" name="cx" value="partner-pub-8190140067673768:8b5rdyntp83">
									<input type="hidden" name="cof" value="FORID:10">
									<input type="hidden" name="ie" value="utf-8">
									<input type="text" autocomplete="off" name="q" id="szuk_top" class="top-search" value="Szukaj w Rzeszowie..." onfocus="if($(this).val()=='Szukaj w Rzeszowie...') $(this).val('');" onblur="if($(this).val()=='') $(this).val('Szukaj w Rzeszowie...');">
								<svg viewBox="0 0 512 512" onclick="document.getElementById('top-search').submit()"><path d="M344.5,298c15-23.6,23.8-51.6,23.8-81.7c0-84.1-68.1-152.3-152.1-152.3C132.1,64,64,132.2,64,216.3  c0,84.1,68.1,152.3,152.1,152.3c30.5,0,58.9-9,82.7-24.4l6.9-4.8L414.3,448l33.7-34.3L339.5,305.1L344.5,298z M301.4,131.2  c22.7,22.7,35.2,52.9,35.2,85c0,32.1-12.5,62.3-35.2,85c-22.7,22.7-52.9,35.2-85,35.2c-32.1,0-62.3-12.5-85-35.2  c-22.7-22.7-35.2-52.9-35.2-85c0-32.1,12.5-62.3,35.2-85c22.7-22.7,52.9-35.2,85-35.2C248.5,96,278.7,108.5,301.4,131.2z"></path></svg>
								</div>
						</form>
                </div>
				<div class="big-menu">
								<button type="button" class="navbar-toggle-mobile"><svg viewBox="0 0 512 512" class="open"><path d="M64,384H448V341.33H64Zm0-106.67H448V234.67H64ZM64,128v42.67H448V128Z"/></svg> <svg viewBox="0 0 512 512" class="close"><path d="M437.5,386.6L306.9,256l130.6-130.6c14.1-14.1,14.1-36.8,0-50.9c-14.1-14.1-36.8-14.1-50.9,0L256,205.1L125.4,74.5  c-14.1-14.1-36.8-14.1-50.9,0c-14.1,14.1-14.1,36.8,0,50.9L205.1,256L74.5,386.6c-14.1,14.1-14.1,36.8,0,50.9  c14.1,14.1,36.8,14.1,50.9,0L256,306.9l130.6,130.6c14.1,14.1,36.8,14.1,50.9,0C451.5,423.4,451.5,400.6,437.5,386.6z"/></svg></button>
							</div>
            </div>
        </div>
    </div>
<?php if(!empty($languages) && count($languages) > 1): ?>
  <div class="lang"> 
   <?php foreach($languages as $lang): ?>
        <a href="<?=$lang['link']; ?>" title="<?=$lang['name']; ?>" <?php if($id_lang==$lang['id']) { echo ' class="active" '; } ?>><?=$lang['short_name']; ?></a>
    <?php endforeach; ?>
  </div>	
<?php endif; ?>
<?= view_cell('\App\Libraries\Page::showMenu', ['id_menu' => 2, 'id_lang' => $id_lang, 'locale' => $locale, 'template' => 'dropdown_mobile', 'submenu_levels' => 0, 'options' => ['mode' => 'external_active_submenu']]) ?>
</header>
<?php
    if(isset($breadcrumbs)) {
        echo view('user/breadcrumbs',array('bread'=>$breadcrumbs));
    }
?>