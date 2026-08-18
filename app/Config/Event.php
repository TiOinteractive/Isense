<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Event extends BaseConfig
{
    /**
     * Feedy importu kupbilecik: klucz = id_page_cont bloku docelowego, wartosc = URL.
     * Adresy ustawiane z .env kluczami event.importUrls.<id_page_cont> - pusty URL oznacza feed pominiety.
     * BaseConfig zasila tylko istniejace klucze tablicy, wiec kazdy obslugiwany blok musi byc tu zadeklarowany.
     *
     * @var array<int, string>
     */
    public $importUrls = [
        36 => '',
        125 => '',
    ];

    /**
     * Odbiorcy maila z podsumowaniem importu, rozdzieleni przecinkiem.
     * Nadpisywalne z .env kluczem event.importMailTo - np. zeby na dev przekierowac maile na skrzynke testowa.
     * Wartosc ponizej jest fallbackiem: brak klucza w .env nie wycisza powiadomien.
     *
     * @var string
     */
    public $importMailTo = '';
}
