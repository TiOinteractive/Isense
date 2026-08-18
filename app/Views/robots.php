User-agent: *
<?php if($index): ?>
Allow: /
<?php else: ?>
Disallow: /
<?php endif; ?>
<?php if(!empty($disallow)) :?>
<?php foreach($disallow as $link): ?>
Disallow: <?=$link . PHP_EOL; ?>
<?php endforeach; ?>
<?php endif; ?>
Disallow: /reklama/openx/
Disallow: /*?page_news
Disallow: /*&page_news

User-agent: GPTBot
Allow: /

User-agent: ClaudeBot
Allow: /

User-agent: PerplexityBot
Allow: /

User-agent: GoogleExtendedBot
Allow: /

User-agent: anthropic-ai
Allow: /

User-agent: cohere-ai
Allow: /

Sitemap: <?= base_url(); ?>sitemap.xml