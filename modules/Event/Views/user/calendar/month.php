<?php
/* 
Kalendarz - miesiąc
 */
?>
<?php if(!empty($data)): ?>
    <section class="section-<?=$id_cont; ?> event-calendar-section calendar-box sidebar">
        <input class="page-content" type="hidden" value="<?=$id_cont; ?>">
        <div class="container">
            <?php if(!empty($title)): ?> 
                <div class="title <?php if(!empty($mobile)):?>resinet-title<?php else :?>resinet-title-sidebar<?php endif;?>">
                   <h2 class="h-1"><?php if($url):?><a href="<?=$url; ?>" title="<?=esc($title); ?>"><?php endif; ?><?=$title; ?><?php if($url):?></a><?php endif; ?></h2>
                    <?php if(!empty($subtitle)): ?>
                        <h3 class="h-2"><?=$subtitle; ?></h3>
                    <?php endif; ?>
               </div>	
            <?php endif; ?>         
            
            <div class="event-calendar-box">
                <?=view('Modules\Event\Views\user\calendar\_month_inside.php'); ?>
            </div>
        </div> 	
    </section>
<?php endif; ?>