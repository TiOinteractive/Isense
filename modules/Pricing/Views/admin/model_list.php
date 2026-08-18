<?php $admin = ($locale ? '/' . $locale : '') . '/' . env('ADMIN_PANEL_SLUG'); ?>
<div class="main-cont">
    <?php if (isset($breadcrumbs)) { echo $breadcrumbs; } ?>
    <div class="c">
        <div class="head">
            <?= esc($service['name']); ?>
            <span><?= lang('Pricing.ModelList'); ?></span>
        </div>
        <?= view('admin/alert_box', ['flashdata' => ! empty($flashdata) ? $flashdata : []]); ?>
        <div class="form-row-space"></div>
        <div class="form-row nag">
            <h3><?= lang('Pricing.ModelList'); ?></h3>
        </div>
        <p>
            <a class="btn" href="<?= $admin; ?>/pricing/model-add/<?= $service['id']; ?>" title="<?= lang('Pricing.ModelAdd'); ?>"><i class="fa-solid fa-plus"></i> <?= lang('Pricing.ModelAdd'); ?></a>
            <a class="btn" href="<?= $admin; ?>/pricing/service-edit/<?= $service['id_category']; ?>/<?= $service['id']; ?>" title="<?= lang('Pricing.ServiceEdit'); ?>"><i class="fa-solid fa-pencil"></i> <?= lang('Pricing.ServiceEdit'); ?></a>
        </p>

        <?= view('Modules\Pricing\Views\admin\_filters', ['filters' => $filters, 'filters_action' => $admin . '/pricing/service/' . $service['id']]); ?>
        <?= view('admin/order_and_pagination', ['pager' => $pager, 'on_page_list' => $on_page_list, 'order_list' => $order_list, 'filters' => $filters]); ?>

        <div class="list">
            <div class="list-row list-head">
                <div class="list-col center w50<?= ! empty($filters['order_array']['order']) ? ' ' . $filters['order_array']['order'] : ''; ?>" data-order="order">
                    <?= lang('Pricing.Lp'); ?>
                </div>
                <div class="list-col<?= ! empty($filters['order_array']['name']) ? ' ' . $filters['order_array']['name'] : ''; ?>" data-order="name">
                    <?= lang('Pricing.ModelName'); ?>
                </div>
                <div class="list-col center w120"><?= lang('Pricing.Price'); ?></div>
                <div class="list-col center w120 hide-1200"><?= lang('Pricing.Time'); ?></div>
                <div class="list-col center w120 hide-1200"><?= lang('Pricing.Warranty'); ?></div>
                <div class="list-col center w100 hide-1200"><?= lang('Pricing.Edit'); ?></div>
                <div class="list-col center w100 hide-500<?= ! empty($filters['order_array']['publish']) ? ' ' . $filters['order_array']['publish'] : ''; ?>" data-order="publish">
                    <?= lang('Pricing.Publish'); ?>
                </div>
                <div class="list-col center w100"><?= lang('Pricing.Delete'); ?></div>
            </div>
            <?php if (! empty($models)): ?>
                <div<?= ($filters['order'] ?? '') === 'order,asc' ? ' class="list-order-box"' : ''; ?> data-url="<?= $admin; ?>/pricing/model-order/<?= $service['id']; ?>">
                    <?php foreach ($models as $model): ?>
                        <div class="list-row list-row-<?= $model['id']; ?>">
                            <div class="list-col center w50 order"><?= $model['order']; ?></div>
                            <div class="list-col">
                                <a href="<?= $admin; ?>/pricing/model-edit/<?= $service['id']; ?>/<?= $model['id']; ?>" title="<?= esc($model['name']); ?>"><strong><?= esc($model['name']); ?></strong></a>
                            </div>
                            <div class="list-col center w120"><?= esc($model['price']); ?></div>
                            <div class="list-col center w120 hide-1200"><?= esc($model['time']); ?></div>
                            <div class="list-col center w120 hide-1200"><?= esc($model['warranty']); ?></div>
                            <div class="list-col center w100 hide-1200">
                                <a href="<?= $admin; ?>/pricing/model-edit/<?= $service['id']; ?>/<?= $model['id']; ?>" title="<?= lang('Pricing.Edit'); ?>"><i class="fa-solid fa-pencil fa-xl"></i></a>
                            </div>
                            <div class="list-col center w100 hide-500">
                                <a class="list-publish-btn" href="<?= $admin; ?>/pricing/model-publish/<?= $model['id']; ?>" title="<?= lang('Pricing.Publish'); ?>"><i class="<?= ! empty($model['publish']) ? 'fa-solid fa-square-check' : 'fa-regular fa-square'; ?> fa-xl"></i></a>
                            </div>
                            <div class="list-col center w100">
                                <?php if (isset($_SESSION['role']) && ! in_array($_SESSION['role'], ['editor', 'contributor'], true)): ?>
                                    <a class="list-remove-btn" href="<?= $admin; ?>/pricing/model-delete/<?= $model['id']; ?>" data-title="<?= lang('Pricing.ModelDelete'); ?>" data-message="<?= lang('Pricing.ModelDeleteConfirm') . ': <b>' . esc($model['name']) . '</b>'; ?>" data-btn-ok="<?= lang('Pricing.Remove'); ?>" data-btn-cancel="<?= lang('Pricing.Cancel'); ?>" title="<?= lang('Pricing.Delete'); ?>"><i class="fa-regular fa-trash-can fa-xl"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="list-row no-list-result"><?= lang('Pricing.NoModelResult'); ?></div>
            <?php endif; ?>
            <?= view('admin/order_and_pagination', ['pager' => $pager, 'order_list' => $order_list, 'filters' => $filters]); ?>
        </div>

        <div class="form-row-space"></div>
        <div class="form-row nag">
            <h3><?= lang('Pricing.ImportHeader'); ?></h3>
        </div>
        <form class="form" action="<?= $admin; ?>/pricing/model-import/<?= $service['id']; ?>" method="post">
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Pricing.ImportLabel'); ?></label>
                    <div class="desc"><?= lang('Pricing.ImportHint'); ?></div>
                </div>
                <div class="form-field">
                    <textarea name="models_text" rows="6" placeholder="iPhone 15 Pro Max | od 1200 zł | 24 h | 12 miesięcy"></textarea>
                </div>
            </div>
            <div class="form-row submit">
                <button type="submit"><?= lang('Pricing.ImportSubmit'); ?></button>
            </div>
        </form>
    </div>
</div>
