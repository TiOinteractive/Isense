<?php
/*
  Smaki - Wyswig tpl
 */
 

 if(!empty($id_sidebar)): ?>
	<div class="sidebar-column">
<?php endif; ?> 

<section class="section section-<?= $id_cont; ?> section-wyswig">

        <?php if (!empty($title)): ?>
            <h2 class="head"><span><?= $title; ?></span></h2>
        <?php endif; ?>
        <?php if (!empty($subtitle)): ?>
            <h3><?= $subtitle; ?></h3>
        <?php endif; ?>
        <?php if (!empty($data) && !empty($data['content'])): ?>
            <div class="wyswig wyswig-<?= $data['id']; ?>">
                <?= $data['content']; ?>
            </div>
        <?php endif; ?>

</section>


<?php if(!empty($id_sidebar)): ?>
         <?= view_cell('\App\Libraries\Sidebar::showSidebar', ['id' => $id_sidebar, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
	</div>
<?php endif; ?>		