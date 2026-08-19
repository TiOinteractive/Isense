<?php

namespace Modules\Pricing\Libraries;

use Modules\Pricing\Models\PricingCategoryModel;

/**
 * Cennik jako blok treści strony.
 *
 * Zakładki = kategorie, lewa kolumna = usługi wybranej kategorii, prawa = modele z cenami.
 * Całość renderowana serwerowo; JavaScript tylko przełącza widoczność (patrz /assets/pricing/js/pricing.js).
 */
class Pricing
{
    public $locale;
    public $global_links;
    public $settings;
    public $is_mobile;

    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new PricingCategoryModel();
    }

    public function index($content, $id_lang, $slug = '', $link = null)
    {
        $ids = [];
        if (! empty($content['config']['categories'])) {
            $ids = array_values(array_filter(array_map('intval', (array) $content['config']['categories'])));
        }

        $categories = $this->categoryModel->getTreeForFront((int) $id_lang, $ids);
        // Pusty wynik = Home usuwa blok ze strony, zamiast renderować pustą sekcję.
        if (empty($categories)) {
            return [];
        }

        return [
            'id'         => $content['id'],
            'categories' => $categories,
            'show_tabs'  => count($categories) > 1,
            'currency'   => 'zł',
            'template'   => 'tpl.php',
        ];
    }

    public function assets($element_slug = '', $tpl = '', $id = 0, $data = [])
    {
        // style.css jest ładowany globalnie przez Home, więc dokładamy tylko skrypt przełączania.
        return ['js' => ['/assets/pricing/js/pricing.js']];
    }

    /**
     * „520 zł", „99,50 zł", „1 250 zł" — grosze tylko wtedy, gdy są niezerowe.
     * Brak ceny (NULL/0) zwraca pusty string; widok pokazuje wtedy „wycena indywidualna".
     */
    public static function formatPrice($price, string $currency = 'zł'): string
    {
        if ($price === null || $price === '' || ! is_numeric($price)) {
            return '';
        }
        $value = (float) $price;
        if ($value <= 0) {
            return '';
        }

        $formatted = number_format($value, 2, ',', "\xC2\xA0");
        $formatted = preg_replace('/,00$/', '', $formatted);

        return $formatted . "\xC2\xA0" . $currency;
    }
}
