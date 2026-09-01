<?php
/*
iSense — O serwisie
*/
$isAssets = rtrim(base_url('assets/isense'), '/'); ?>
<?= view('user/page/_isense_open') ?>

<!-- Hero -->
<section class="bg-gradient-to-b from-[#F5F5F7] to-white py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl lg:text-6xl font-bold text-[#1D1D1F] mb-6">O serwisie iSense</h1>
            <p class="text-lg lg:text-xl text-[#6E6E73]">Od 2008 roku naprawiamy sprzęt Apple z pasją i profesjonalizmem.</p>
        </div>
    </div>
</section>

<!-- Historia -->
<section class="bg-white py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-6">Nasza historia</h2>
                <div class="space-y-4 text-lg text-[#6E6E73]">
                    <p>iSense powstało z pasji do technologii Apple i chęci zapewnienia profesjonalnych usług naprawczych na najwyższym poziomie.</p>
                    <p>Nieprzerwanie od 2008 roku specjalizujemy się wyłącznie w serwisie produktów Apple. Ta specjalizacja pozwala nam doskonale znać każdy model i zapewnić najwyższą jakość napraw.</p>
                    <p>Zaufały nam jednostki administracji publicznej, ministerstwa, znane międzynarodowe koncerny i agencje reklamowe — a także klienci indywidualni z całej Polski.</p>
                </div>
            </div>
            <div class="rounded-3xl overflow-hidden">
                <?= isense_img('loc-serwis.png', 'Serwis iSense', 'w-full h-full object-cover', ['sizes' => '(min-width: 1024px) 50vw, 100vw']) ?>
            </div>
        </div>
    </div>
</section>

<!-- Wartości -->
<section class="bg-[#F5F5F7] py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-12 text-center">Nasze wartości</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php
            $values = [
                ['icon' => 'award', 'title' => 'Jakość', 'text' => 'Używamy tylko dedykowanych części i stosujemy najwyższe standardy napraw.'],
                ['icon' => 'users', 'title' => 'Profesjonalizm', 'text' => 'Nasz zespół to doświadczeni serwisanci z pasją do sprzętu Apple.'],
                ['icon' => 'target', 'title' => 'Precyzja', 'text' => 'Każda naprawa wykonywana z dbałością o najmniejsze detale.'],
                ['icon' => 'heart', 'title' => 'Pasja', 'text' => 'Kochamy to, co robimy — i widać to w efektach naszej pracy.'],
            ];
            foreach ($values as $v): ?>
                <div class="bg-white rounded-2xl p-8 text-center">
                    <div class="w-16 h-16 bg-[#3b81f7]/10 rounded-2xl flex items-center justify-center mx-auto mb-6"><?= isense_icon($v['icon'], 'w-8 h-8 text-[#3b81f7]') ?></div>
                    <h3 class="text-xl font-semibold text-[#1D1D1F] mb-3"><?= esc($v['title']) ?></h3>
                    <p class="text-[#6E6E73]"><?= esc($v['text']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Statystyki -->
<section class="bg-gradient-to-br from-[#1D1D1F] to-[#2C2C2E] py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <h2 class="text-3xl lg:text-4xl font-bold text-white mb-12 text-center">iSense w liczbach</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <?php
            $stats = [['2008', 'rok założenia'], ['80%', 'napraw w 48h roboczych'], ['100%', 'sprzęt Apple'], ['1h', 'wymiana szybki iPhone']];
            foreach ($stats as [$n, $l]): ?>
                <div class="text-center">
                    <div class="text-4xl lg:text-6xl font-bold text-[#3b81f7] mb-2"><?= esc($n) ?></div>
                    <div class="text-[#86868B]"><?= esc($l) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Dlaczego tylko Apple -->
<section class="bg-white py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-6">Dlaczego tylko Apple?</h2>
            <p class="text-lg text-[#6E6E73] mb-8">Specjalizacja w jednej marce pozwala nam osiągnąć najwyższy poziom wiedzy i doświadczenia. Znamy każdy model, każdą generację, każdy typowy problem. To przekłada się na szybsze diagnozy, skuteczniejsze naprawy i większą satysfakcję naszych klientów.</p>
            <p class="text-lg text-[#6E6E73]">Jesteśmy pasjonatami produktów Apple i traktujemy każde urządzenie z należytą starannością — tak, jakby było naszym własnym.</p>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="bg-[#F5F5F7] py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-6">Dołącz do naszych zadowolonych klientów</h2>
            <p class="text-lg text-[#6E6E73] mb-8">Skontaktuj się z nami już dziś i doświadcz profesjonalnego serwisu Apple.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?= site_url('kontakt') ?>" class="inline-block bg-[#3b81f7] text-white px-8 py-4 rounded-lg hover:bg-[#2563eb] transition-colors font-medium">Wyceń naprawę</a>
                <a href="<?= site_url('kontakt') ?>" class="inline-block bg-white border-2 border-[#1D1D1F] text-[#1D1D1F] px-8 py-4 rounded-lg hover:bg-[#F5F5F7] transition-colors font-medium">Skontaktuj się</a>
            </div>
        </div>
    </div>
</section>

<?= view('user/page/_isense_close') ?>
