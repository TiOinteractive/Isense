<header class="main-header-flavor">
		<div class="black-header">
		<div class="container">
		 <div class="inside">
		  <?php if(!empty($settings['logo_flavor'])):?>
                <div class="logo">
                    <a href="/rzeszowskie-smaki"><img src="/image/r/300/100/<?=$settings['logo_flavor']['path'];?>" alt="<?=$settings['company_name'];?>" /></a>
                </div>
            <?php endif; ?>
			    <div class="box">
				  <div class="top_box">
				    <?= view_cell('\App\Libraries\Page::showMenu', ['id_menu' => 3, 'id_lang' => $id_lang, 'locale' => $locale, 'template' => 'flavor', 'submenu_levels' => 0, 'options' => ['mode' => 'external_active_submenu']]) ?>
					<div class="login">
					
					

					  <?php if(empty($session_user)): ?>
                            <a href="/<?=$global_links['login']; ?>" title="<?=lang('Users.account.LogIn'); ?>" class="log-in-ajax"><svg viewBox="0 0 32 32"><path d="M16,20a8,8,0,1,1,8-8A8,8,0,0,1,16,20ZM16,6a6,6,0,1,0,6,6A6,6,0,0,0,16,6Z"/><path d="M30,32H28A12,12,0,0,0,4,32H2a14,14,0,0,1,28,0Z"/></svg> Zaloguj się</a>
                        <?php else: ?>
                            <a href="/<?=$global_links['client_account']; ?>" title="<?=lang('Users.account.YourAccount'); ?>"><svg viewBox="0 0 32 32"><path d="M16,20a8,8,0,1,1,8-8A8,8,0,0,1,16,20ZM16,6a6,6,0,1,0,6,6A6,6,0,0,0,16,6Z"/><path d="M30,32H28A12,12,0,0,0,4,32H2a14,14,0,0,1,28,0Z"/></svg> <?=!empty($session_user['name']) ? $session_user['name'] : lang('Users.account.YourAccount'); ?> </a>
                        <?php endif; ?>
					
					
					
					</div>
				  </div>
				  <div class="search-box">
				  		<form class="wyszukiwarka_top" name="szukaj_top" action="/rzeszowskie-smaki/szukaj" method="get">
								<div>
								<svg viewBox="0 0 512 512"><path d="M344.5,298c15-23.6,23.8-51.6,23.8-81.7c0-84.1-68.1-152.3-152.1-152.3C132.1,64,64,132.2,64,216.3  c0,84.1,68.1,152.3,152.1,152.3c30.5,0,58.9-9,82.7-24.4l6.9-4.8L414.3,448l33.7-34.3L339.5,305.1L344.5,298z M301.4,131.2  c22.7,22.7,35.2,52.9,35.2,85c0,32.1-12.5,62.3-35.2,85c-22.7,22.7-52.9,35.2-85,35.2c-32.1,0-62.3-12.5-85-35.2  c-22.7-22.7-35.2-52.9-35.2-85c0-32.1,12.5-62.3,35.2-85c22.7-22.7,52.9-35.2,85-35.2C248.5,96,278.7,108.5,301.4,131.2z"/></svg>
								<input type="text" autocomplete="off" name="szukane" id="szuk_top" class="top-search" value="<?php if(!empty($_GET['szukane'])):?><?=$_GET['szukane'];?><?php else:?>Znajdź coś dobrego, lokal, kuchnię...<?php endif;?>" onfocus="if($(this).val()=='Znajdź coś dobrego, lokal, kuchnię...') $(this).val('');" onblur="if($(this).val()=='') $(this).val('Znajdź coś dobrego, lokal, kuchnię...');"></div>
								<a class="send_btn" href="javascript:$('form[name=szukaj_top]').submit();">Szukaj</a>
						</form>
					<div class="social">	
					<?php if(!empty($settings['facebook_flavor'])): ?><a class="icon facebook" href="<?=$settings['facebook_flavor']; ?>" title="" target="_blank" rel="nofollow"><svg viewBox="0 0 56.693 56.693"><path d="M40.43,21.739h-7.645v-5.014c0-1.883,1.248-2.322,2.127-2.322c0.877,0,5.395,0,5.395,0V6.125l-7.43-0.029  c-8.248,0-10.125,6.174-10.125,10.125v5.518h-4.77v8.53h4.77c0,10.947,0,24.137,0,24.137h10.033c0,0,0-13.32,0-24.137h6.77  L40.43,21.739z"/></svg></a><?php endif; ?>
					</div>
					<div class="social">
					<?php if(!empty($settings['instagram_flavor'])): ?><a class="icon instagram" href="<?=$settings['instagram_flavor']; ?>" title="" target="_blank" rel="nofollow"><svg data-name="Layer 1" id="Layer_1" viewBox="0 0 128 128"><title/><path d="M83,23a22,22,0,0,1,22,22V83a22,22,0,0,1-22,22H45A22,22,0,0,1,23,83V45A22,22,0,0,1,45,23H83m0-8H45A30.09,30.09,0,0,0,15,45V83a30.09,30.09,0,0,0,30,30H83a30.09,30.09,0,0,0,30-30V45A30.09,30.09,0,0,0,83,15Z"/><path  d="M90.14,32a5.73,5.73,0,1,0,5.73,5.73A5.73,5.73,0,0,0,90.14,32Z"/><path  d="M64.27,46.47A17.68,17.68,0,1,1,46.6,64.14,17.7,17.7,0,0,1,64.27,46.47m0-8A25.68,25.68,0,1,0,90,64.14,25.68,25.68,0,0,0,64.27,38.47Z"/></svg></a><?php endif; ?>	
					</div>	
				     <div class="map"><a href="/rzeszowskie-smaki/mapa-lokali"><svg viewBox="0 0 16 16"><path d="M8,16c0,0,6-5.582,6-10s-2.686-6-6-6S2,1.582,2,6S8,16,8,16z M5,5c0-1.657,1.343-3,3-3s3,1.343,3,3S9.657,8,8,8S5,6.657,5,5  z"/></svg> Mapa lokali</a></div>
				  </div>
				</div>
		 </div>
		 </div>
		</div>
</header>