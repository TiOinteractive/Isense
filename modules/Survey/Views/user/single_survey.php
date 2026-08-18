<?php
/* 
Ankieta
*/
if(!empty($data['survey'])) {
    $survey = $data['survey'];
}
?>
<?php
if(!empty($survey)):
?>
<section id="home-survey">
  <h3 class="title"><a href="/ankiety">Ankieta</a></h3>
  <div class="inside">
    <h4><?=$survey['question'];?></h4> 
    <?php if(!empty($survey['options'])):?>
	 <form action="<?=$locale;?>/survey" id="SurveyForm_<?=$survey['id'];?>">
	   <div class="options">
	   <?php foreach($survey['options'] as $option):?>
	    <div class="option <?php if(!empty($survey['single'])):?>single<?php endif;?>" id="option_<?=$option['id'];?>">
		   <input id="opt<?=$option['id'];?>" name="option<?php if(!empty($survey['single'])):?>[]<?php endif;?>" type="<?php if(!empty($survey['single'])):?>radio<?php else:?>checkbox<?php endif;?>" value="<?=$option['id'];?>">
		   <label for="opt<?=$option['id'];?>"><?=$option['option'];?></label>
		</div>
	   <?php endforeach;?>
	   </div>
	   <div class="btns">
	     <div class="results"><button type="button" name="results" class="trans400" onclick="showSurveyResults(<?=$survey['id'];?>);">Wyniki</button></div>
		 <div class="vote"><button type="button" name="vote" class="trans400" onclick="voteSurvey(<?=$survey['id'];?>);">Głosuj</button></div>
	   </div>
     </form>
   <?php endif;?>
  </div>  
</section>
<?php endif;?>