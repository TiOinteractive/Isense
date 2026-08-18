<?php echo '<?xml version="1.0" encoding="UTF-8"?>';  
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">';

if(!empty($list)) {
    foreach($list as $l) {
        if(!empty($l)) {
            foreach($l as $url) {
                echo '<url>
                    <loc>' . base_url() . (!empty($languages) && !empty($languages[$url['id_lang']]) && $languages[$url['id_lang']]['slug'] ? $languages[$url['id_lang']]['slug'] . '/' : '') . str_replace('&','&amp;', $url['link']).'</loc>';
                if(!empty($url['langs']) && !empty($languages) && count($url['langs']) > 1) {
                    foreach($url['langs'] as $lang) {
                        echo '<xhtml:link rel="alternate" hreflang="' . (!empty($languages) && !empty($languages[$lang['id_lang']]) && $languages[$lang['id_lang']]['lang_code'] ? $languages[$lang['id_lang']]['lang_code'] : '') . '" href="' . base_url() . (!empty($languages) && !empty($languages[$lang['id_lang']]) && $languages[$lang['id_lang']]['slug'] ? $languages[$lang['id_lang']]['slug'] . '/' : '') . str_replace('&','&amp;', $lang['link']).'" />';
                    }
                }
                if(!empty($url['images'])) {
                    foreach($url['images'] as $image) {
                        echo '<image:image>
                            <image:loc>' . base_url() . 'image/' . $image['path'] . '</image:loc>
                            <image:caption>' . str_replace('&','&amp;', !empty($image['caption']) ? $image['caption'] : $image['name']) . '</image:caption>
                        </image:image>';
                    }
                }
                if(!empty($url['videos'])) {
                    foreach($url['videos'] as $video) {
                        echo '<video:video>
                            <video:content_loc>' . base_url() . 'video/' . $video['path'] . '</video:content_loc>
                            <video:description>' . str_replace('&','&amp;', !empty($video['caption']) ? $video['caption'] : $video['name']) . '</video:description>
                        </video:video>';
                    }
                }
                echo '<lastmod>'. date("Y-m-d", strtotime($url['edited_at'] && $url['edited_at'] != '0000-00-00 00:00:00' ? $url['edited_at'] : $url['created_at'])) . '</lastmod>
                    <changefreq>' . (!empty($url['changefreq']) ? $url['changefreq'] : 'weekly') . '</changefreq>
                    <priority>' . (!empty($url['priority']) ? $url['priority'] : '0.5') . '</priority>
                </url>';
            }
        }
    }
}

echo '</urlset>';