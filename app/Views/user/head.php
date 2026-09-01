<!doctype html>
<html lang="<?=!empty($metatags['lang']) ? $metatags['lang'] : ''; ?>">
    <head>   
        <meta charset="UTF-8">
        <title><?=!empty($metatags['title']) ? $metatags['title'] : ''; ?></title>
        <meta name="robots" content="<?php if(!empty($metatags['index']) && empty($metatags['no_index'])): ?>index, follow<?php else: ?>noindex, nofollow<?php endif; ?>">
        <meta name="description" content="<?=!empty($metatags['description']) ? esc($metatags['description']) : ''; ?>">
        <meta name="author" content="TiO interactive :: e-business solutions - www.tiointeractive.pl">
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
        <?php if(!empty($metatags['canonical'])): ?><link rel="canonical" href="<?=$metatags['canonical']; ?>"><?php endif; ?>
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
        <link rel="shortcut icon" href="<?=!empty($metatags['favicon']) ? $metatags['favicon'] : ''; ?>">
        <link rel="apple-touch-icon" href="<?=!empty($metatags['apple_icon']) ? $metatags['apple_icon'] : ''; ?>">
        <?php if(!empty($settings['widget_gsv'])): ?><meta name="google-site-verification" content="<?=$settings['widget_gsv']; ?>" /><?php endif; ?>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <?php if($environment == 'production' || $environment == 'development2'): ?>
            <link rel="stylesheet" href="/tio.css<?=!empty($css_files) ? '?files=' . implode(',', $css_files) : ''; ?>&v=1.3">
        <?php elseif(!empty($css_files)): ?>
            <?php foreach($css_files as $css_file): ?>
                <link rel="stylesheet" href="<?=$css_file; ?>?v=1.3">
            <?php endforeach; ?>
        <?php endif; ?>
        <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <?php if(!empty($settings['widget_gtm'])): ?>
            <!-- Google Tag Manager -->
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','<?=$settings['widget_gtm']; ?>');</script>
            <!-- End Google Tag Manager -->
        <?php endif; ?>
        <?php if(!empty($settings['widget_ga'])): ?>    
            <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=<?=$settings['widget_ga']; ?>"></script>
            <script>
              window.dataLayer = window.dataLayer || [];
              function gtag(){dataLayer.push(arguments);}
              gtag('js', new Date());
              gtag('config', '<?=$settings['widget_ga']; ?>');
            </script>
            <!-- END Global site tag (gtag.js) - Google Analytics -->
        <?php endif; ?>
        <?php if(!empty($settings['widget_fp'])): ?>  
            <!-- Facebook Pixel -->
            <script>(function() {
            var _fbq = window._fbq || (window._fbq = []);
            if (!_fbq.loaded) {
            var fbds = document.createElement('script');
            fbds.async = true;
            fbds.src = '//connect.facebook.net/en_US/fbds.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(fbds, s);
            _fbq.loaded = true;
            }
            _fbq.push(['addPixelId', '<?=$settings['widget_ga']; ?>']);
            })();
            window._fbq = window._fbq || [];
            window._fbq.push(['track', 'PixelInitialized', {}]);
            </script>
            <!-- End Facebook Pixel -->
        <?php endif; ?>
        <?php if(!empty($metatags['microdata'])): ?>
            <?php /* Na produkcji JSON minifikowany, lokalnie czytelny do debugowania. */ ?>
            <?=ENVIRONMENT === 'production' ? generateJsonLdHtmlProd($metatags['microdata']) : generateJsonLdHtmlDev($metatags['microdata']); ?>
        <?php endif; ?>
        <?php if(!empty($breadcrumbs)): ?>
            <?=generateBreadcrumbListJsonLd($breadcrumbs, !empty($settings['company_name']) ? $settings['company_name'] : 'iSense', base_url()); ?>
        <?php endif; ?>
    </head>
<body class="<?=!empty($home) ? 'home' : 'page'?><?=!empty($mobile) ? ' mobile' : ''?>">
    <?php if(!empty($settings['widget_gtm'])): ?>
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?=$settings['widget_gtm']; ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
    <?php endif; ?>
