<div class="container">
			<div id="foto_search">
            <form method="get" action="/foto">
				<div class="szukaj_bg"><div>
				<svg viewBox="0 0 512 512" width="512px"><path d="M448.3,424.7L335,311.3c20.8-26,33.3-59.1,33.3-95.1c0-84.1-68.1-152.2-152-152.2c-84,0-152,68.2-152,152.2  s68.1,152.2,152,152.2c36.2,0,69.4-12.7,95.5-33.8L425,448L448.3,424.7z M120.1,312.6c-25.7-25.7-39.8-59.9-39.8-96.3  s14.2-70.6,39.8-96.3S180,80,216.3,80c36.3,0,70.5,14.2,96.2,39.9s39.8,59.9,39.8,96.3s-14.2,70.6-39.8,96.3  c-25.7,25.7-59.9,39.9-96.2,39.9C180,352.5,145.8,338.3,120.1,312.6z"></path></svg>
				<input type="text" name="search" class="form_text" placeholder="<?=lang('Foto.SearchPlaceholder');?>"><input type="submit" value="<?=lang('Foto.Search');?>" class="szukaj trans400"></div></div>
				<div><?=lang('Foto.NumberOfWorks');?>: <b><?=$stats['photos']+$stats['gallery_files'];?></b></div>
				<?php /*
				<div><?=lang('Foto.NumberOfUsers');?>: <b><?=$stats['photos_user']+$stats['gallery_user'];?></b>  &nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;</div>
				<div><?=lang('Foto.WaitingForAutorization');?>: <b><?=$stats['photos_unpublish']+$stats['gallery_unpublish'];?></b></div>
				*/ ?>
            </form>
         </div>
</div>