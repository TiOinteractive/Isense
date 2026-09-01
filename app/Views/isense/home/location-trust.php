<?php
$assets = $assets ?? rtrim(base_url('assets/isense'), '/');
$visitOptions = [
    ['image' => 'loc-serwis.png',  'title' => 'Serwis Warszawa Śródmieście', 'description' => "ul. Dobra 56/66, Budynek Biblioteki UW\n(minus 1, lok. nr A32), 00-312 Warszawa\nPon–Pt 9:00–19:00"],
    ['image' => 'loc-sklep.png',   'title' => 'Sklep stacjonarny Warszawa Śródmieście', 'description' => 'Odwiedź nas osobiście. Bezpłatna diagnoza na miejscu, wycena od ręki. Przyjmujemy sprzęt bez wcześniejszego umawiania się.'],
    ['image' => 'loc-wysylka.png', 'title' => 'Naprawa wysyłkowa', 'description' => 'Wyślij sprzęt kurierem lub zamów bezpłatny odbiór. Naprawiamy i odsyłamy z powrotem na terenie całej Polski.'],
];
?>
<section class="bg-white py-8 lg:py-12">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="text-center mb-12 max-w-3xl mx-auto">
            <h2 class="text-[22.5px] lg:text-[36px] font-bold text-[#1D1D1F] mb-4 leading-tight">Odwiedź osobiście nasz serwis<br>lub skorzystaj z opcji door-to-door</h2>
        </div>
        <div class="grid lg:grid-cols-2 gap-12 items-end">
            <div>
                <p class="text-[#1D1D1F] font-bold text-xl leading-relaxed mb-6">Czekamy na Ciebie w naszym punkcie serwisowym na warszawskim Powiślu w podziemiach biblioteki Uniwersytetu Warszawskiego.</p>
                <p class="text-[#6E6E73] leading-relaxed mb-4">Obsługujemy klientów indywidualnych jak i korporacyjnych z całej Polski.</p>
                <p class="text-[#6E6E73] leading-relaxed mb-4">Zdając się na nasz serwis door-to-door masz gwarancję, że Twój sprzęt zostanie zdiagnozowany i naprawiony przez serwisanta wyspecjalizowanego w danej gamie produktowej.</p>
                <p class="text-[#6E6E73] leading-relaxed mb-4">Szczególnie skutecznie udaje nam się naprawiać telefony iPhone, które inne, lokalne serwisy nie są w stanie trafnie zdiagnozować. Sprzęt Apple wymaga od serwisanta aktualnej wiedzy i dostępu do części najwyższej jakości — czego często lokalnie brakuje. Pamiętaj, że powierzając sprzęt amatorom ryzykujesz jego wtórne uszkodzenie i możesz zmniejszyć szanse na skuteczną naprawę.</p>
                <p class="text-[#1D1D1F] font-semibold leading-relaxed mb-8">W iSense zawsze otrzymasz gwarancję na wykonaną naprawę i użyte części.</p>
                <a href="<?= site_url('kontakt') ?>" class="inline-flex items-center gap-2 bg-[#3b81f7] text-white px-6 py-3 rounded-sm font-semibold hover:bg-[#2563eb] transition-colors">
                    Dowiedz się więcej
                    <?= isense_icon('arrow-right', 'w-5 h-5') ?>
                </a>
            </div>
            <div class="flex flex-col gap-4">
                <?php foreach ($visitOptions as $option): ?>
                    <div class="bg-[#F5F5F7] rounded overflow-hidden border border-[#E5E5EA] flex">
                        <div class="w-[120px] flex-shrink-0 overflow-hidden self-stretch">
                            <?= isense_img($option['image'], $option['title'], 'w-full h-full object-cover', ['sizes' => '120px']) ?>
                        </div>
                        <div class="p-4 flex-1 flex flex-col">
                            <h3 class="text-base font-semibold text-[#1D1D1F] mb-2"><?= esc($option['title']) ?></h3>
                            <p class="text-xs text-[#6E6E73] leading-relaxed whitespace-pre-line flex-1"><?= esc($option['description']) ?></p>
                            <div class="flex items-center gap-2 text-[#3b81f7] font-medium text-sm mt-3">
                                Zobacz
                                <?= isense_icon('arrow-right', 'w-4 h-4') ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
