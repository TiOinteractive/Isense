<?php
/*
llms.txt — mapa serwisu dla modeli językowych (App\Controllers\Llms::index).
Format wg llmstxt.org: H1 z nazwą, cytat z opisem, sekcje H2 z listami linków.
Widok tylko składa tekst; skąd biorą się dane, opisuje kontroler.
*/
echo '# ' . $name . "\n";
if ($intro !== '') {
    echo "\n> " . $intro . "\n";
}

foreach ($sections as $section) {
    echo "\n## " . $section['title'] . "\n\n";
    foreach ($section['items'] as $item) {
        echo '- [' . $item['name'] . '](' . $item['url'] . ')';
        echo $item['description'] !== '' ? ': ' . $item['description'] : '';
        echo "\n";
    }
}

echo "\n## Informacje o firmie\n\n";
foreach ($company as $label => $value) {
    echo '- ' . $label . ': ' . $value . "\n";
}
echo '- Mapa strony: ' . $sitemap . "\n";
