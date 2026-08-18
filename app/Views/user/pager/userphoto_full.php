<?php

use CodeIgniter\Pager\PagerRenderer;

if ($pager->getPageCount() > 1):
    /**
     * @var PagerRenderer $pager
     */
    $pager->setSurroundCount(2);
    ?>
    <nav aria-label="<?= lang('Pager.pageNavigation') ?>">
        <ul class="pagination">
            <?php if ($pager->hasPrevious()) : ?>
                <li class="li-txt first">
                    <a href="<?= $pager->getFirst() ?><?php if(empty($_GET['show'])):?>&show=photo<?php endif;?>" aria-label="<?= lang('Admin.pager.First') ?>">
                        <span aria-hidden="true"><i class="fa-solid fa-angles-left"></i></span>
                    </a>
                </li>
                <li class="li-txt previous">
                    <a href="<?= $pager->getPrevious() ?><?php if(empty($_GET['show'])):?>&show=photo<?php endif;?>" aria-label="<?= lang('Admin.pager.Previous') ?>">
                        <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                    </a>
                </li>
            <?php endif ?>
			<?php if(!empty($_GET['page'])):?>
			<li class="back"><a href="/foto/user/4194326291" title="Galeria użytkownika ViC">Powrót</a></li>
			<?php endif;?>
            <?php foreach ($pager->links() as $link) : ?>
                <li <?= $link['active'] ? 'class="active"' : '' ?>>
                    <a href="<?= $link['uri'] ?><?php if(empty($_GET['show'])):?>&show=photo<?php endif;?>">
                        <?= $link['title'] ?>
                    </a>
                </li>
            <?php endforeach ?>
            <?php if ($pager->hasNext()) : ?>
                <li class="li-txt next">
                    <a href="<?= $pager->getNext() ?><?php if(empty($_GET['show'])):?>&show=photo<?php endif;?>" aria-label="<?= lang('Admin.pager.Next') ?>">
                        <span aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>
                    </a>
                </li>
                <li class="li-txt last">
                    <a href="<?= $pager->getLast() ?><?php if(empty($_GET['show'])):?>&show=photo<?php endif;?>" aria-label="<?= lang('Admin.pager.Last') ?>">
                        <span aria-hidden="true"><i class="fa-solid fa-angles-right"></i></span>
                    </a>
                </li>
            <?php endif ?>
        </ul>
    </nav>
<?php endif; ?>