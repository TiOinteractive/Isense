<div class="file-box">
    <div class="file">
        <?php if($file['type'] == 'image'): ?>
            <img src="/image/c/250/250/<?= $file['path']; ?>" alt="<?= $file['name']; ?>" />
        <?php else: ?>
            <span class="ext"><?=$file['ext']; ?></span>
        <?php endif; ?>
        <p class="name"><?= $file['name']; ?></p>
        <input type="checkbox" name="files[<?= $file['id']; ?>]" value="<?= $file['id']; ?>" id="file-<?= $file['id']; ?>" />
        <label for="file-<?= $file['id']; ?>"></label>
    </div>
</div>