<div class="list-row list-row-<?= $product['id']; ?>">
    <div class="list-col center w40">
        <input type="checkbox" name="related[]" value="<?= $product['id']; ?>" />
    </div>
    <div class="list-col w50">
        <?= $product['id']; ?>
    </div>
    <div class="list-col w100 no-padding">
        <?php if (!empty($product['path'])): ?>
                <img src="/image/c/90/90/<?= $product['path']; ?>" alt="<?= esc($product['name']); ?>" />
        <?php endif; ?>
    </div>
	<div class="list-col">
        <?= $product['name']; ?>
    </div>
	<div class="list-col w100">
        <?= $product['created_at']; ?>
    </div>
</div>