<div class="modal-restaurant-rate">
<?php if(empty($session_user)):?>
<p class="kom error">Aby dodać opinię lub komentarz musisz być zalogowany/-a.<br />Kliknij w poniższy przycisk aby zalogować się do serwisu.</p>
<?php else:?>
<?php if(!empty($data['avg']['rating'])):?>
	<h2>Ogólna ocena lokalu: <span><?=number_format($data['avg']['rating'], 1, ',', '');?></span></h2>
<?php endif;?>	
<p class="info">(zaznacz gwiazdki przy parametrach poniżej)</p>
<?php if(!empty($data_form) and empty($data_form['rate_1']) and empty($data_form['rate_2']) and empty($data_form['rate_3']) and empty($data_form['rate_4']) and empty($data_form['comment'])):?>
   <p class="kom error">Zaznacz przynajmniej jedną ocenę</p>
<?php elseif(!empty($response)):?>
<p class="kom">Dziękujemy, Twoja opinia została zapisana. <?php if(!empty($data_form['comment'])):?><br />Twój komentarz zostanie opublikowany po akceptacji przez moderatora serwisu.<?php endif;?></p>
<?php elseif(empty($response)):?>
<?php if(!empty($user_rates)):?>
  <p class="kom">Już oceniłeś/-aś ten lokal na: <b><?=$user_rates['main'];?></b>. Poniżej Twoje oceny szczegółowe.<br /> Jeżeli chcesz, w każdym momencie możesz zmienić swoją ocenę.</p>
<?php endif;?>
<?php endif;?>
<?php if(empty($response)):?>
  <form id="formRateModal">	
	<div class="row">
	   <label>Jedzenie:</label>
	   <div class="rate" data-type="rate_1" data-score=<?php if(!empty($user_rates['list'][1])):?>"<?=$user_rates['list'][1];?>"<?php else:?>0<?php endif;?>></div>
	   <span id="rate_1"><?php if(!empty($user_rates['list'][1])):?><?=$user_rates['list'][1];?>/5<?php else:?>Nie oceniono<?php endif;?></span>
	</div>
	<div class="row">
	   <label>Wystrój:</label>
	   <div class="rate" data-type="rate_2" data-score=<?php if(!empty($user_rates['list'][2])):?>"<?=$user_rates['list'][2];?>"<?php else:?>0<?php endif;?>></div>
	   <span id="rate_2"><?php if(!empty($user_rates['list'][2])):?><?=$user_rates['list'][2];?>/5<?php else:?>Nie oceniono<?php endif;?></span>
	</div>
	<div class="row">
	   <label>Obsługa:</label>
	   <div class="rate" data-type="rate_3" data-score=<?php if(!empty($user_rates['list'][3])):?>"<?=$user_rates['list'][3];?>"<?php else:?>0<?php endif;?>></div>
	   <span id="rate_3"><?php if(!empty($user_rates['list'][3])):?><?=$user_rates['list'][3];?>/5<?php else:?>Nie oceniono<?php endif;?></span>
	</div>
	<div class="row">
	   <label>Ceny:</label>
	   <div class="rate" data-type="rate_4" data-score=<?php if(!empty($user_rates['list'][4])):?>"<?=$user_rates['list'][4];?>"<?php else:?>0<?php endif;?>></div>
	   <span id="rate_4"><?php if(!empty($user_rates['list'][4])):?><?=$user_rates['list'][4];?>/5<?php else:?>Nie oceniono<?php endif;?></span>
	</div>
	<div class="rate_all row">
		<label>Twoja ogólna ocena:</label> 
	    <span class="rate_all"><?php if(!empty($user_rates['main'])):?><?=$user_rates['main'];?>/5<?php else:?>Nie oceniono<?php endif;?></span>
	</div>
    <h3>Dodaj opinię:</h3>
    <textarea name="comment"></textarea>
  </form>
</div>
<script>
    /* jQuery jest ladowane z `defer` (app/Views/user/foot.php), wiec `$` nie
       istnieje jeszcze w trakcie parsowania tego bloku — stad DOMContentLoaded. */
    document.addEventListener('DOMContentLoaded', function() {
		  $('.modal-restaurant-rate .rate').each(function(){
              var type=$(this).attr('data-type');
              var score=$(this).attr('data-score');
              var readonly=false;
			  $(this).raty({
				starHalf:'/assets/gfx/star-half-big.png',starOff:'/assets/gfx/star-off-big.png',
				starOn:'/assets/gfx/star-on-big.png'
				,score:score,
				scoreName: type,
                readOnly:readonly,
				half: false,
                mouseover: function(score, evt) {
                    mouseoverRateModal(type,score);
                },
                mouseout: function() {
                    mouseoutRateModal(type);
                }
			  });	  
		  });
	});	
</script>		
<?php endif;?>
<?php endif;?>