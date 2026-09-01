<?php
/*
llms-full.txt — pełna treść serwisu w jednym pliku (App\Controllers\Llms::full).
Każda strona to blok: nagłówek z nazwą, adres, opis SEO i tekst z bloków.
Strony rozdziela `---`, bo modele tną długie dokumenty po takich granicach.
*/
echo '# ' . $name . "\n";
if ($intro !== '') {
    echo "\n> " . $intro . "\n";
}
echo "\nSkrócony spis stron: " . $index_url . "\n";
echo 'Mapa strony: ' . $sitemap . "\n";

foreach ($documents as $doc) {
    echo "\n---\n\n";
    echo '## ' . $doc['name'] . "\n\n";
    echo $doc['url'] . "\n";
    if ($doc['description'] !== '') {
        echo "\n" . $doc['description'] . "\n";
    }
    if (! empty($doc['body'])) {
        echo "\n" . implode("\n", $doc['body']) . "\n";
    }
}

if (! empty($company)) {
    echo "\n---\n\n## Informacje o firmie\n\n";
    foreach ($company as $label => $value) {
        echo '- ' . $label . ': ' . $value . "\n";
    }
}
