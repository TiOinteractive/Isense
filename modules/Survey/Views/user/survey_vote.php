<?php if(!empty($results)):?>
    <?php if(empty($results['status'])):?>
	   <p class="error_info" style="font-size:13px;margin-bottom:10px;">Już oddałeś głos w ankiecie !</p>
	<?php else:?>
	   <p class="ok_info">Dziękujemy za oddanie głosu !</p>
	<?php endif;?>
   <?=view('Modules\Survey\Views\user\survey_results', array('data' => $results['results']));?>   				  
<?php else:?>
<?php if(empty($votes)):?>
 <p class="error_info">Musisz zaznaczyć conajmniej jedną odpowiedź !</p>
<?php endif;?>
 <?php if(!empty($survey['options'])):?>
	  <?php foreach($survey['options'] as $option):?>
	    <div class="option <?php if(!empty($survey['single'])):?>single<?php endif;?><?php if(empty($votes)):?> error<?php endif;?>" id="option_<?=$option['id'];?>">
		   <input id="opt<?=$option['id'];?>" name="option[]" type="<?php if(!empty($survey['single'])):?>radio<?php else:?>checkbox<?php endif;?>" value="<?=$option['id'];?>">
		   <label for="opt<?=$option['id'];?>"><?=$option['option'];?></label>
		</div>
	   <?php endforeach;?>
 <?php endif;?>
 <?php endif;?>