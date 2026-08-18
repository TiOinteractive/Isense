<div class="list-row list-row-<?= $product['id']; ?>">
    <div class="list-col w100 no-padding">
	<input type="hidden" name="related[]" value="<?= $product['id']; ?>" />
        <?php if (!empty($product['path'])): ?>
                <img src="/image/c/90/90/<?= $product['path']; ?>" alt="<?= esc($product['name']); ?>" />
        <?php endif; ?>
    </div>
	<div class="list-col">
        <?= $product['name']; ?>
    </div>
	<div class="list-col w200">
        <?= $product['created_at']; ?>
    </div>
	<div class="list-col w200 center">
      <a href="/<?=env('ADMIN_PANEL_SLUG'); ?>/foto/gallery-edit/<?=$product['id_page_cont']; ?>/<?=$product['id']; ?>" title="<?=lang('Foto.GalleryEdit');?>" target="_blank"><i class="fa-solid fa-pencil fa-xl"></i></a>
    </div>
	<div class="list-col w100 center">
     <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))) {?>  <a class="list-remove-related" href="/<?=env('ADMIN_PANEL_SLUG'); ?>/foto/related-remove/<?=$product['id']; ?>"><i class="fa-regular fa-trash-can fa-xl"></i></a> <?php }?>
    </div>
</div>