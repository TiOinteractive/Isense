<?php
/* 
Lista ankiet z wynikami
*/
?>
<script src="/adm/third-party/apexcharts/dist/apexcharts.min.js"></script>
<section class="section-<?= $id_cont; ?> survey-list">
    <div class="container">
        <?php if(!empty($title)): ?> 
        <div class="title resinet-title">
            <h2><?php if(!empty($url)): ?><a href="<?= $url; ?>"><?php endif; ?><?= $title; ?><?php if(!empty($url)): ?></a><?php endif; ?></h2>
            <?php if(!empty($subtitle)): ?>
            <h3><?= $subtitle; ?></h3>
            <?php endif; ?>
        </div>	
        <?php endif; ?> 
		
	<?php if(!empty($data['list']['active'])):?>
	  <div class="list listactive">
	    <h4>Ankiety aktualne</h4>	
		
		
	<?php foreach($data['list']['active'] as $survey):?>
		  <div class="survey-box">
		     <h3><?=$survey['question'];?></h3>
			 <h4><?=date("d.m.Y",strtotime($survey['date_start']));?> - <?=date("d.m.Y",strtotime($survey['date_end']));?></h4>
		
		
			 <form action="<?=$locale;?>/survey" id="SurveyFormList_<?=$survey['id'];?>">
			   <div class="options">
			   <?php foreach($survey['options'] as $option):?>
				<div class="option <?php if(!empty($survey['single'])):?>single<?php endif;?>" id="option_<?=$option['id'];?>">
				   <input id="lopt<?=$option['id'];?>" name="option<?php if(!empty($survey['single'])):?>[]<?php endif;?>" type="<?php if(!empty($survey['single'])):?>radio<?php else:?>checkbox<?php endif;?>" value="<?=$option['id'];?>">
				   <label for="lopt<?=$option['id'];?>"><?=$option['option'];?></label>
				</div>
			   <?php endforeach;?>
			   </div>
			   <div class="btns">
				 <div class="results"><button type="button" name="results" class="trans400" onclick="showSurveyResultsList(<?=$survey['id'];?>);">Wyniki</button></div>
				 <div class="vote"><button type="button" name="vote" class="trans400" onclick="voteSurveyList(<?=$survey['id'];?>);">Głosuj</button></div>
			   </div>
			 </form>
		
		
		
		  </div>
	<?php endforeach;?>	  
	  </div>
    <?php endif;?>	   	
	<?php if(!empty($data['list']['archive'])):?>
	  <div class="list listarchive">
	    <h4>Ankiety zakończone</h4>
	<?php foreach($data['list']['archive'] as $survey):?>
		  <div class="survey-box">
		     <h3><?=$survey['question'];?></h3>
			 <h4><?=date("d.m.Y",strtotime($survey['date_start']));?> - <?=date("d.m.Y",strtotime($survey['date_end']));?></h4>
		  <div id="survey-chart-list<?=$survey['id'];?>"></div>
			<?php
			$rdata = array();
			 if(!empty($survey['result'])) {
					foreach($survey['result'] as $o) {
						$rdata[] = array(
							'x' => $o['option'],
							'y' => !empty($o['count']) ? intval($o['count']) : 0
						);
					}
				}
			?>				
			<script>
			setTimeout(() => {
			new ApexCharts(document.querySelector('#survey-chart-list<?=$survey['id'];?>'), {
								chart: {
								  type: 'bar'
								},
								legend: {
								  position: 'bottom',
								},	
								series: [{
									name: "<?=lang('Survey.Votes'); ?>",
									data: <?=json_encode($rdata);?>,
									color: "#ffc800",
								}],
								xaxis: {
									labels: {
										rotate: 0,
										trim: true,
									}
								}
							  }).render();
							  
			}, "1000");				  
			</script>
		  </div>
		  <?php endforeach;?>
	  </div>
	<?php endif;?>	
	</div>
</section>	