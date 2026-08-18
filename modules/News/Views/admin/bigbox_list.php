<?php if(!empty($box_list)):?>
					  <?php foreach($box_list as $item):?>
					    <div class="list-row order-item">
						  <div class="list-col center w50 order order-no"><?=$item['id'];?></div>
						  <div class="list-col w50 no-padding">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/news/edit/<?=$item['id_page_cont'];?>/<?=$item['id'];?>" target="_blank"><img src="/image/c/50/50/<?=$item['path'];?>" alt="<?=esc($item['title']);?>"></a>
                          </div>
						  <div class="list-col"> <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/news/edit/<?=$item['id_page_cont'];?>/<?=$item['id'];?>" target="_blank"><?=$item['title'];?></a></div>
						  <div class="list-col center w100 hide-1200"><?php if($item['date']!='0000-00-00 00:00:00'):?><?=date("d.m.Y",strtotime($item['date']));?><?php else:?><?=date("d.m.Y",strtotime($item['created_at']));?><?php endif;?></div>
						  <div class="list-col w50 center"><?php if (!empty($item['publish']) && $item['publish']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></div>
						  <div class="list-col w50 center">
						  
						   <?php if (isset($_SESSION['role']) and!in_array($_SESSION['role'], array('editor', 'contributor'))) { ?>  <a class="list-remove-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/news/removeBigBox/<?= $item['id']; ?>" data-title="<?= lang('News.DeleteNewsBigBox'); ?>" data-message="<?= lang('News.DeleteConfirmBigBox') . ': <b>' . esc($item['title']) . '</b>'; ?>" data-btn-ok="<?= lang('News.Remove'); ?>" data-btn-cancel="<?= lang('News.Cancel'); ?>" title="<?= lang('News.DeleteNewsBigBox'); ?>"><i class="fa-regular fa-trash-can fa-xl"></i></a> <?php } ?>
						  
						  
						  </div>
						</div>
<?php endforeach;?>
<?php else:?>
	Brak przypisanych newsów
<?php endif;?>