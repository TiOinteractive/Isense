<?php
/* 
Co robimy tpl
*/
?>
<section class="<?php if(!empty($id_cont)): ?>section-<?=$id_cont; ?><?php endif; ?>">
    <div id="co_robimy_home">
	  <div class="boxy">
       <?php if(!empty($data['banners'])) { 
	      foreach($data['banners'] as $baner) {
			  
			  if($baner['id']==8) {
				  $svg=' <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><title></title><g data-name="1" id="_1"><path d="M397.78,316H192.65A15,15,0,0,1,178,304.33L143.46,153.85a15,15,0,0,1,14.62-18.36H432.35A15,15,0,0,1,447,153.85L412.4,304.33A15,15,0,0,1,397.78,316ZM204.59,286H385.84l27.67-120.48H176.91Z"></path><path d="M222,450a57.48,57.48,0,1,1,57.48-57.48A57.54,57.54,0,0,1,222,450Zm0-84.95a27.48,27.48,0,1,0,27.48,27.47A27.5,27.5,0,0,0,222,365.05Z"></path><path d="M368.42,450a57.48,57.48,0,1,1,57.48-57.48A57.54,57.54,0,0,1,368.42,450Zm0-84.95a27.48,27.48,0,1,0,27.48,27.47A27.5,27.5,0,0,0,368.42,365.05Z"></path><path d="M158.08,165.49a15,15,0,0,1-14.23-10.26L118.14,78H70.7a15,15,0,1,1,0-30H129a15,15,0,0,1,14.23,10.26l29.13,87.49a15,15,0,0,1-14.23,19.74Z"></path></g></svg>';
			  }	  
			  elseif($baner['id']==9) {
				  $svg=' <svg viewBox="0 0 50 50"><rect fill="none" height="50" width="50"></rect><circle cx="25" cy="25" fill="none" r="24" stroke="#ffffff" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"></circle><ellipse cx="25" cy="25" fill="none" rx="12" ry="24" stroke="#ffffff" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"></ellipse><path d="M6.365,40.438C10.766,37.729,17.479,36,25,36  c7.418,0,14.049,1.682,18.451,4.325" fill="none" stroke="#ffffff" stroke-miterlimit="10" stroke-width="2"></path><path d="M43.635,9.563C39.234,12.271,32.521,14,25,14  c-7.417,0-14.049-1.682-18.451-4.325" fill="none" stroke="#ffffff" stroke-miterlimit="10" stroke-width="2"></path><line fill="none" stroke="#ffffff" stroke-miterlimit="10" stroke-width="2" x1="1" x2="49" y1="25" y2="25"></line><line fill="none" stroke="#ffffff" stroke-miterlimit="10" stroke-width="2" x1="25" x2="25" y1="1" y2="49"></line></svg>'; 
			  }	  
			  elseif($baner['id']==10) {
				$svg=' <svg viewBox="0 0 32 32"><defs><style>.cls-1{fill:none;stroke:#fff;stroke-linejoin:round;stroke-width:2px;}</style></defs><title></title><g data-name="381-Browser" id="_381-Browser"><rect class="cls-1" height="26" width="30" x="1" y="3"></rect><line class="cls-1" x1="1" x2="31" y1="9" y2="9"></line><line class="cls-1" x1="4" x2="6" y1="6" y2="6"></line><line class="cls-1" x1="8" x2="10" y1="6" y2="6"></line></g></svg>';  
			  }	  
			  elseif($baner['id']==11) {
				$svg=' <svg viewBox="0 0 512 512"><g id="XMLID_174_"><path class="st0" d="M41,315.8c-5.8,0-11.4-1.6-16.4-4.6L6,300v172.5h67.5V297.1l-11,10.2   C56.7,312.8,49,315.8,41,315.8z" id="XMLID_3390_"></path><polygon class="st0" id="XMLID_183_" points="143.2,232.4 143.2,472.6 210.8,472.6 210.8,225.5 176.1,201.8  "></polygon><path class="st0" d="M290.2,273.1c-6.4,0-12.6-1.9-17.9-5.5l-13.3-9.1v214h67.5V251.8L311,265.4   C305.2,270.4,297.8,273.1,290.2,273.1z" id="XMLID_182_"></path><polygon class="st0" id="XMLID_175_" points="378.5,472.6 446,472.6 446,148 378.5,206.7  "></polygon><path class="st1" d="M401.1,69.3L429,96.4L288.8,217.5l-105.1-71.8c-7.3-5-17.2-4.3-23.7,1.8l-121.6,113L6.1,240.9   v44.4l25.1,15.1c7.3,4.4,16.5,3.4,22.8-2.4l120.9-112.4l104.6,71.5c7.1,4.9,16.7,4.3,23.2-1.3l153.7-132.9l23,22.3L506,39.4   L401.1,69.3z" id="XMLID_498_"></path></g></svg>';  
			  }	  
			  
			  if(isset($baner['name'])) {
				$word_tab=explode(' ',$baner['name']);
                if($word_tab) {
					$baner['name']='<span class="trans400">'.str_replace($word_tab[0],$word_tab[0].'</span>',$baner['name']);				
				}  
			  }	  
			 ?> 
			 <div class="box box_<?=$baner['id'];?>" style="background-image:url(/image/<?=$baner['path'] ? $baner['path']: $baner['m_path'];?>);">
			  <figure>
			  
			  <?php if($baner['url']) { ?><a href="<?=$baner['url'];?>"><?php } ?>  
				 <svg class="hexagon_big hex" viewBox="0 0 16 16">
					<path fill-rule="evenodd" d="M8.5.134a1 1 0 0 0-1 0l-6 3.577a1 1 0 0 0-.5.866v6.846a1 1 0 0 0 .5.866l6 3.577a1 1 0 0 0 1 0l6-3.577a1 1 0 0 0 .5-.866V4.577a1 1 0 0 0-.5-.866L8.5.134z"></path>
				</svg>	
			    <div class="hexagon trans400">
                      <?=$svg;?> <svg class="hex" viewBox="0 0 16 16">
						<path fill-rule="evenodd" d="M8.5.134a1 1 0 0 0-1 0l-6 3.577a1 1 0 0 0-.5.866v6.846a1 1 0 0 0 .5.866l6 3.577a1 1 0 0 0 1 0l6-3.577a1 1 0 0 0 .5-.866V4.577a1 1 0 0 0-.5-.866L8.5.134z"></path>
					</svg>	                
				</div>
		  <?php if($baner['url']) { ?></a><?php }?>
			  </figure>
			  <h2><?php if($baner['url']) { ?><a href="<?=$baner['url'];?>"><?php } ?><?=$baner['name'];?><?php if($baner['url']) { ?></a><?php } ?></h2>
			  <div class="txt">
                <?=$baner['caption'];?>
              </div>
				<?php if($baner['url']) { ?>
				<div class="hexagon_arrow">
				  <a href="<?=$baner['url'];?>"><svg viewBox="0 0 512 512"><polygon points="352,128.4 319.7,96 160,256 160,256 160,256 319.7,416 352,383.6 224.7,256 "></polygon></svg></a>
					  <svg class="tloh" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
						<path fill-rule="evenodd" d="M8.5.134a1 1 0 0 0-1 0l-6 3.577a1 1 0 0 0-.5.866v6.846a1 1 0 0 0 .5.866l6 3.577a1 1 0 0 0 1 0l6-3.577a1 1 0 0 0 .5-.866V4.577a1 1 0 0 0-.5-.866L8.5.134z"></path>
					  </svg>
				</div>
				<?php } ?>
	       </div>
           <?php	   
	      }
	   }
	   ?>
	   </div>
		<?php if(!empty($title)): ?>
		<div class="wielokat">
		   <a href="<?= view_cell('\App\Libraries\Link::getLinkByPageId', 'id_page=26, id_lang='.$id_lang) ?>"> 
			  <h2><?=$title; ?></h2>
			<p class="trans400"><?= lang('User.other.more'); ?></p>
			<svg class="hex" viewBox="0 0 16 16">
				<path fill-rule="evenodd" d="M8.5.134a1 1 0 0 0-1 0l-6 3.577a1 1 0 0 0-.5.866v6.846a1 1 0 0 0 .5.866l6 3.577a1 1 0 0 0 1 0l6-3.577a1 1 0 0 0 .5-.866V4.577a1 1 0 0 0-.5-.866L8.5.134z"></path>
			</svg>
			</a>
		   </div>
		 <?php endif; ?>
    </div>
</section>