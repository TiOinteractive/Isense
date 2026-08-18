<div class="aa-zone zone-0 type-0 source-adsense">
    <div class="aa-zone-container">
        <div class="aa-center">
            <span class="t"><?=lang('Advertisement.user.Advertisement'); ?></span>
            <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-9301198135861729" data-ad-slot="7878158194" data-ad-format="auto" data-full-width-responsive="true"></ins>
            <script>
                 (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>
    </div>
</div>
<?= view_cell('\Modules\Banner\Libraries\Banner::showBanner', ['id_zone' => 6, 'id_lang' => $id_lang, 'locale' => $locale, 'template' => 'partners']) ?>
<footer class="main-footer flavor-footer">
   <div class="container"> 
	<div class="flex">
	  		  <?php if(!empty($settings['logo_flavor'])):?>
                <div class="logo">
                    <a href="/rzeszowskie-smaki"><img src="/image/r/250/90/<?=$settings['logo_flavor']['path'];?>" alt="<?=$settings['company_name'];?>" /></a>
                </div>
            <?php endif; ?>
			<div class="right"><?= view_cell('\App\Libraries\Page::showMenu', ['id_menu' => 8, 'id_lang' => $id_lang, 'locale' => $locale, 'template' => 'menu6', 'submenu_levels' => 0, 'options' => ['mode' => 'external_active_submenu']]) ?>
			
			
			  <div class="contact flex">
			     <div>
				   <div class="box">
				   	<h4>KONTAKT</h4>
				     <?php if(!empty($settings['address'])): $addres=explode(',',$settings['address']); endif; ?>
					 <?php if(!empty($addres[0])):?><?=$addres[0];?><?php endif;?>
					 <?php if(!empty($addres[1])):?><br /><?=$addres[1];?><?php endif;?>
				   </div>
				   <div class="box">
				      <span>tel.</span> <a href="tel:<?=str_replace(' ','',$settings['advert_phone']);?>"><?=$settings['advert_phone'];?></a>
					  <br /><span>e-mail:</span> <a href="mailto:<?=$settings['flavor_email'];?>" target="_blank"><?=$settings['flavor_email'];?></a> 
				   </div>
				 </div>
			     <div>
				 <div class="box">
				 	<h4>REKLAMA, WPISY WYRÓŻNIONE</h4>
					Chcesz zareklamować się na portalu, 
					<br />lub wyróżnić swój lokal? Zadzwoń lub napisz:
				 </div>
				  <div class="box">
				      <span>tel.</span> <a href="tel:<?=str_replace(' ','',$settings['advert_phone']);?>"><?=$settings['advert_phone'];?></a>
					  <br /><span>e-mail:</span> <a href="mailto:<?=$settings['flavor_email2'];?>" target="_blank"><?=$settings['flavor_email2'];?></a> 
				   </div>
				 </div>
				 <div class="info">
				  Największa baza restauracjI i lokali gastronomicznych w Rzeszowie. Serwis jest częścią <a href="/">Rzeszowskiego Portalu Informacyjnego RESinet</a>.
				 </div>
			  </div>
			</div>
	</div>
	</div>
    <div class="bottom-footer">
        <div class="container">
            <div class="flex-footer">
                <div class="copy">2013 - <?=date('Y'); ?> &copy;   Wszelkie prawa zastrzeżone</div>
                <div class="realization"><?=lang('Users.Realization'); ?> <a href="https://tiointeractive.pl" title="TiO Interactive" target="_blank">TiO Interactive</a></div>
            </div>
        </div>
    </div>
</footer>
