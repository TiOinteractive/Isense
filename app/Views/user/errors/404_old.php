<!doctype html>
<html lang="<?=!empty($metatags['lang']) ? $metatags['lang'] : ''; ?>">
    <head>   
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?=!empty($metatags['title']) ? $metatags['title'] : ''; ?></title>
        <meta name="robots" content="noindex, nofollow">
        <meta name="description" content="<?=!empty($metatags['description']) ? esc($metatags['description']) : ''; ?>" />
        <meta name="keywords" content="<?=!empty($metatags['keywords']) ? esc($metatags['keywords']) : ''; ?>" />
        <meta name="author" content="TiO interactive :: e-business solutions - www.tiointeractive.pl"/>
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta property="og:title" content="<?=!empty($metatags['title']) ? esc($metatags['title']) : ''; ?>">
        <meta property="og:type" content="website">
        <meta property="og:url" content="<?=!empty($metatags['url']) ? $metatags['url'] : ''; ?>">
        <meta property="og:image" content="<?=!empty($metatags['image']) ? $metatags['image'] : ''; ?>">
        <meta property="og:image:width" content="<?=!empty($metatags['image_width']) ? $metatags['image_width'] : ''; ?>">
        <meta property="og:image:height" content="<?=!empty($metatags['image_height']) ? $metatags['image_height'] : ''; ?>">
        <meta property="og:image:type" content="<?=!empty($metatags['image_type']) ? $metatags['image_type'] : ''; ?>">
        <meta property="og:image:alt" content="<?=!empty($metatags['image_alt']) ? $metatags['image_alt'] : ''; ?>">
        <meta property="og:description" content="<?=!empty($metatags['description']) ? esc($metatags['description']) : ''; ?>">
        <meta property="og:site_name" content="<?=!empty($metatags['site_name']) ? esc($metatags['site_name']) : ''; ?>">
        <meta name="twitter:card" content="summary">
        <meta name="twitter:title" content="<?=!empty($metatags['title']) ? esc($metatags['title']) : ''; ?>">
        <meta name="twitter:image:src" content="<?=!empty($metatags['image']) ? $metatags['image'] : ''; ?>">
        <meta name="twitter:description" content="<?=!empty($metatags['description']) ? esc($metatags['description']) : ''; ?>">
        <meta name="DC.Title" content="<?=!empty($metatags['title']) ? esc($metatags['title']) : ''; ?>">
        <meta name="DC.Type" content="website">
        <meta name="DC.Format" content="text/html">
        <meta name="DC.Description" content="<?=!empty($metatags['description']) ? esc($metatags['description']) : ''; ?>">
        <meta name="DC.Language" content="<?=!empty($metatags['lang']) ? $metatags['lang'] : ''; ?>">
        <meta http-equiv="content-language" content="<?=!empty($metatags['lang']) ? $metatags['lang'] : ''; ?>" />
        <link rel="canonical" href="<?=!empty($metatags['canonical']) ? $metatags['canonical'] : ''; ?>">
        <?php if(!empty($metatags['first'])): ?><link rel="first" href="<?=$metatags['first']; ?>"><?php endif; ?>
        <?php if(!empty($metatags['prev'])): ?><link rel="prev" href="<?=$metatags['prev']; ?>"><?php endif; ?>
        <?php if(!empty($metatags['next'])): ?><link rel="next" href="<?=$metatags['next']; ?>"><?php endif; ?>
        <?php if(!empty($metatags['last'])): ?><link rel="last" href="<?=$metatags['last']; ?>"><?php endif; ?>
        <?php if(!empty($metatags['alternative'])): ?>
            <?php foreach($metatags['alternative'] as $alt): ?>
                <?php /*if(!$alt.slug): ?><link rel="alternate" href="{$alt.link}" hreflang="x-default" /><?php endif;*/ ?>
                <link rel="alternate" href="<?=$alt['link']; ?>" hreflang="<?=$alt['lang_code']; ?>" />
            <?php endforeach; ?>
        <?php endif; ?>
        <link rel="shortcut icon" href="<?=!empty($metatags['favicon']) ? $metatags['favicon'] : ''; ?>" />
        <link rel="apple-touch-icon" href="<?=!empty($metatags['apple_icon']) ? $metatags['apple_icon'] : ''; ?>">
        <link rel="stylesheet" href="/assets/css/style.css">
<body>
    <div class="errors error-404">
        <div class="container">
            <h2><?=lang('Admin.errors.Error404'); ?></h2>
            <p><?=lang('Admin.errors.Error404Info'); ?></p>
        </div>
    </div>
</body>
</html>