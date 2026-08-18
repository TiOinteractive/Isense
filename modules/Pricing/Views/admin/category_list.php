<?php $admin = ($locale ? '/' . $locale : '') . '/' . env('ADMIN_PANEL_SLUG'); ?>
<div class="main-cont">
    <?php if (isset($breadcrumbs)) { echo $breadcrumbs; } ?>
    <div class="c">
        <div class="head"><?= lang('Pricing.Pricing'); ?></div>
        <?= view('admin/alert_box', ['flashdata' => ! empty($flashdata) ? $flashdata : []]); ?>
        <div class="form-row-space"></div>
        <div class="form-row nag">
            <h3><?= lang('Pricing.CategoryList'); ?></h3>
        </div>
        <p><a class="btn" href="<?= $admin; ?>/pricing/category-add" title="<?= lang('Pricing.CategoryAdd'); ?>"><i class="fa-solid fa-plus"></i> <?= lang('Pricing.CategoryAdd'); ?></a></p>

        <?= view('Modules\Pricing\Views\admin\_filters', ['filters' => $filters, 'filters_action' => $admin . '/pricing']); ?>
        <?= view('admin/order_and_pagination', ['pager' => $pager, 'on_page_list' => $on_page_list, 'order_list' => $order_list, 'filters' => $filters]); ?>

        <div class="list">
            <div class="list-row list-head">
                <div class="list-col center w50<?= ! empty($filters['order_array']['order']) ? ' ' . $filters['order_array']['order'] : ''; ?>" data-order="order">
                    <?= lang('Pricing.Lp'); ?>
                </div>
                <div class="list-col<?= ! empty($filters['order_array']['name']) ? ' ' . $filters['order_array']['name'] : ''; ?>" data-order="name">
                    <?= lang('Pricing.CategoryName'); ?>
                </div>
                <div class="list-col center w100 hide-1200"><?= lang('Pricing.ServicesCount'); ?></div>
                <div class="list-col center w100 hide-1200"><?= lang('Pricing.Edit'); ?></div>
                <div class="list-col center w100 hide-500<?= ! empty($filters['order_array']['publish']) ? ' ' . $filters['order_array']['publish'] : ''; ?>" data-order="publish">
                    <?= lang('Pricing.Publish'); ?>
                </div>
                <div class="list-col center w100"><?= lang('Pricing.Delete'); ?></div>
            </div>
            <?php if (! empty($categories)): ?>
                <div<?= ($filters['order'] ?? '') === 'order,asc' ? ' class="list-order-box"' : ''; ?> data-url="<?= $admin; ?>/pricing/category-order">
                    <?php foreach ($categories as $category): ?>
                        <div class="list-row list-row-<?= $category['id']; ?>">
                            <div class="list-col center w50 order"><?= $category['order']; ?></div>
                            <div class="list-col">
                                <a href="<?= $admin; ?>/pricing/category/<?= $category['id']; ?>" title="<?= esc($category['name']); ?>"><strong><?= esc($category['name']); ?></strong></a>
                            </div>
                            <div class="list-col center w100 hide-1200"><?= (int) $category['services']; ?></div>
                            <div class="list-col center w100 hide-1200">
                                <a href="<?= $admin; ?>/pricing/category-edit/<?= $category['id']; ?>" title="<?= lang('Pricing.Edit'); ?>"><i class="fa-solid fa-pencil fa-xl"></i></a>
                            </div>
                            <div class="list-col center w100 hide-500">
                                <a class="list-publish-btn" href="<?= $admin; ?>/pricing/category-publish/<?= $category['id']; ?>" title="<?= lang('Pricing.Publish'); ?>"><i class="<?= ! empty($category['publish']) ? 'fa-solid fa-square-check' : 'fa-regular fa-square'; ?> fa-xl"></i></a>
                            </div>
                            <div class="list-col center w100">
                                <?php if (isset($_SESSION['role']) && ! in_array($_SESSION['role'], ['editor', 'contributor'], true)): ?>
                                    <a class="list-remove-btn" href="<?= $admin; ?>/pricing/category-delete/<?= $category['id']; ?>" data-title="<?= lang('Pricing.CategoryDelete'); ?>" data-message="<?= lang('Pricing.CategoryDeleteConfirm') . ': <b>' . esc($category['name']) . '</b>'; ?>" data-btn-ok="<?= lang('Pricing.Remove'); ?>" data-btn-cancel="<?= lang('Pricing.Cancel'); ?>" title="<?= lang('Pricing.Delete'); ?>"><i class="fa-regular fa-trash-can fa-xl"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="list-row no-list-result"><?= lang('Pricing.NoCategoryResult'); ?></div>
            <?php endif; ?>
            <?= view('admin/order_and_pagination', ['pager' => $pager, 'order_list' => $order_list, 'filters' => $filters]); ?>
        </div>
    </div>
</div>
