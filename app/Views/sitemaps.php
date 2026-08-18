<?php echo '<?xml version="1.0" encoding="UTF-8"?>';  
echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

if(!empty($list)) {
    foreach($list as $l) {
        echo '<sitemap>
                <loc>' . base_url() . $l['link'] . '</loc>
            </sitemap>';
    }
}

echo '</sitemapindex>';