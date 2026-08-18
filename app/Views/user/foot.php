<?php if(!empty($footer_flashdata)): ?>
    <?php foreach($footer_flashdata as $flashdata): ?>
        <?php if(!empty($flashdata)): ?>
            <div class="footer-flashdata <?=$flashdata['status'] ? 'success' : 'error'; ?>" data-title="<?=$flashdata['title']; ?>" data-close="<?=$flashdata['close']; ?>">
                <p><?=$flashdata['msg']; ?></p>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>
<?php if (!empty($css_footer)): ?>
    <?php if ($environment == 'production' || $environment == 'development2'): ?>
        <link rel="stylesheet" href="/tio.css?files=<?= implode(',', $css_footer); ?>">
    <?php else: ?>
        <?php foreach ($css_footer as $css_file): ?>
            <link rel="stylesheet" href="<?= $css_file; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
<?php endif; ?>
<?php if (!empty($css_code)): ?>
    <style>
        <?php echo $css_code;?>
    </style>
<?php endif; ?>
<?php if (0 && $environment == 'production' || $environment == 'development2'): ?>
    <script src="/tio.js<?= !empty($js_files) ? '?files=' . implode(',', $js_files) : ''; ?>"></script>
<?php elseif (!empty($js_files)): ?>
    <?php foreach ($js_files as $js_file): ?>
        <script src="<?= $js_file; ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>
<?php if (!empty($js_ready)): ?>
    <script>
        $(function () {
            <?php foreach ($js_ready as $js) {echo $js . PHP_EOL;} ?>
        });
    </script>
<?php endif; ?>
<?php if (!empty($js_load)): ?>
    <script>
        $(window).on('load', function () {
            <?php foreach ($js_load as $js) {echo $js . PHP_EOL;}?>
        });
    </script>
<?php endif; ?>
<?php if (!empty($js_code)): ?>
    <script>
        <?php echo $js_code;?>
    </script>
<?php endif; ?>
<script async src="//www.reklamy.org.pl/www/delivery/asyncjs.php"></script>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9301198135861729" crossorigin="anonymous"></script>
</body>
</html>