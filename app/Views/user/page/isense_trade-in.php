<?php
/*
iSense — Trade-In
*/
$isAssets = rtrim(base_url('assets/isense'), '/'); ?>
<?= view('user/page/_isense_open') ?>

<!-- Hero -->
<section class="bg-gradient-to-b from-[#F5F5F7] to-white py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl lg:text-6xl font-bold text-[#1D1D1F] mb-6">Trade-In / Odkup sprzętu Apple</h1>
            <p class="text-lg lg:text-xl text-[#6E6E73]">Wycenimy Twoje urządzenie i zaproponujemy najlepszą ofertę odkupu lub rabatu na naprawę.</p>
        </div>
    </div>
</section>

<!-- Trade-In: opis + kreator wyceny -->
<section class="bg-[#F5F5F7] py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="grid lg:grid-cols-2 gap-12 items-start">
            <!-- Lewa kolumna -->
            <div>
                <div class="inline-flex items-center gap-2 bg-white text-[#3b81f7] px-4 py-2 rounded-full mb-6 border border-[#D2D2D7]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="m17 2 4 4-4 4"/><path d="M3 11v-1a4 4 0 0 1 4-4h14"/><path d="m7 22-4-4 4-4"/><path d="M21 13v1a4 4 0 0 1-4 4H3"/></svg>
                    <span class="text-sm font-medium">Trade-In</span>
                </div>

                <h2 class="text-3xl lg:text-5xl font-bold text-[#1D1D1F] mb-6">Oddaj stary sprzęt — odbierz rabat lub gotówkę</h2>
                <p class="text-lg text-[#6E6E73] mb-8">Wycenimy Twoje urządzenie i zaproponujemy najlepszą ofertę odkupu lub rabatu na naprawę.</p>

                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center flex-shrink-0 border border-[#D2D2D7]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-[#3b81f7]"><path d="M16 7h6v6"/><path d="m22 7-8.5 8.5-5-5L2 17"/></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-[#1D1D1F] mb-1">Najlepsza wycena na rynku</h3>
                            <p class="text-sm text-[#6E6E73]">Oferujemy konkurencyjne ceny za używany sprzęt Apple</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center flex-shrink-0 border border-[#D2D2D7]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-[#3b81f7]"><rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13"/><path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"/></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-[#1D1D1F] mb-1">Rabat przy naprawie</h3>
                            <p class="text-sm text-[#6E6E73]">Wykorzystaj wartość starego sprzętu jako rabat na naprawę</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center flex-shrink-0 border border-[#D2D2D7]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-[#3b81f7]"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-[#1D1D1F] mb-1">Gotówka lub przelew</h3>
                            <p class="text-sm text-[#6E6E73]">Wypłata od ręki lub szybki przelew bankowy</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prawa kolumna: kreator wyceny (stan domyślny, statyczny) -->
            <div class="bg-white rounded-3xl p-8 lg:p-10 shadow-xl border border-[#D2D2D7]">
                <h3 class="text-2xl font-semibold text-[#1D1D1F] mb-6">Kreator wyceny Trade-In</h3>

                <div class="space-y-6">
                    <!-- Krok 1: typ urządzenia -->
                    <div>
                        <label class="block text-sm font-medium text-[#1D1D1F] mb-3">1. Wybierz typ urządzenia</label>
                        <div class="grid grid-cols-2 gap-3">
                            <?php
                            $deviceTypes = [
                                ['id' => 'iphone', 'name' => 'iPhone'],
                                ['id' => 'ipad', 'name' => 'iPad'],
                                ['id' => 'macbook', 'name' => 'MacBook'],
                                ['id' => 'imac', 'name' => 'iMac'],
                                ['id' => 'watch', 'name' => 'Apple Watch'],
                            ];
                            foreach ($deviceTypes as $i => $device):
                                $active = ($i === 0)
                                    ? 'border-[#3b81f7] bg-[#3b81f7]/10'
                                    : 'border-[#D2D2D7] hover:border-[#6E6E73]';
                            ?>
                                <button type="button" class="p-4 rounded-xl border-2 transition-all <?= $active ?>">
                                    <span class="text-sm font-medium text-[#1D1D1F]"><?= esc($device['name']) ?></span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Krok 2: model -->
                    <div>
                        <label for="tradein-model" class="block text-sm font-medium text-[#1D1D1F] mb-3">2. Wybierz model</label>
                        <select id="tradein-model" class="w-full px-4 py-3 rounded-lg border border-[#D2D2D7] bg-white focus:border-[#3b81f7] focus:ring-2 focus:ring-[#3b81f7]/20">
                            <?php
                            $models = ['iPhone 15 Pro Max', 'iPhone 15 Pro', 'iPhone 15 Plus', 'iPhone 15'];
                            foreach ($models as $model): ?>
                                <option value="<?= esc($model, 'attr') ?>"><?= esc($model) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Krok 3: stan techniczny -->
                    <div>
                        <label class="block text-sm font-medium text-[#1D1D1F] mb-3">3. Określ stan techniczny</label>
                        <div class="space-y-2">
                            <?php
                            $conditions = [
                                ['name' => 'Idealny', 'description' => 'Jak nowy, bez śladów użytkowania'],
                                ['name' => 'Dobry', 'description' => 'Drobne ślady użytkowania'],
                                ['name' => 'Widoczne ślady', 'description' => 'Rysy, odpryski, ale w pełni sprawny'],
                                ['name' => 'Uszkodzony', 'description' => 'Pęknięty ekran lub inne uszkodzenia'],
                            ];
                            foreach ($conditions as $i => $condition):
                                $active = ($i === 0)
                                    ? 'border-[#3b81f7] bg-[#3b81f7]/10'
                                    : 'border-[#D2D2D7] hover:border-[#6E6E73]';
                            ?>
                                <button type="button" class="w-full text-left p-4 rounded-xl border-2 transition-all <?= $active ?>">
                                    <div class="font-medium text-[#1D1D1F] mb-1"><?= esc($condition['name']) ?></div>
                                    <div class="text-xs text-[#6E6E73]"><?= esc($condition['description']) ?></div>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Szacowana wartość (przykładowa, stan domyślny) -->
                    <div class="bg-gradient-to-br from-[#3b81f7] to-[#2563eb] rounded-2xl p-8 text-white">
                        <div class="text-sm font-medium mb-2 opacity-90">Szacowana wartość odkupu</div>
                        <div class="text-5xl font-bold mb-4">4 500 zł</div>
                        <p class="text-sm opacity-90">Ostateczna wycena może się różnić po weryfikacji urządzenia</p>
                    </div>

                    <!-- CTA -->
                    <div class="space-y-3">
                        <a href="<?= site_url('kontakt') ?>" class="block text-center w-full bg-[#3b81f7] text-white px-8 py-4 rounded-lg hover:bg-[#2563eb] transition-colors font-medium">Wymień na rabat przy naprawie</a>
                        <a href="<?= site_url('kontakt') ?>" class="block text-center w-full bg-white border-2 border-[#1D1D1F] text-[#1D1D1F] px-8 py-4 rounded-lg hover:bg-[#F5F5F7] transition-colors font-medium">Sprzedaj za gotówkę</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Jak działa Trade-In -->
<section class="bg-white py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-12 text-center">Jak działa Trade-In?</h2>
        <div class="grid md:grid-cols-4 gap-8 max-w-5xl mx-auto">
            <?php
            $steps = [
                ['1', 'Wycena online', 'Wypełnij formularz i otrzymaj szacunkową wycenę'],
                ['2', 'Weryfikacja', 'Przynieś sprzęt do serwisu lub wyślij kurierem'],
                ['3', 'Finalna wycena', 'Po sprawdzeniu potwierdzimy ostateczną cenę'],
                ['4', 'Wypłata', 'Otrzymasz gotówkę lub rabat na naprawę'],
            ];
            foreach ($steps as [$num, $title, $text]): ?>
                <div class="text-center">
                    <div class="w-16 h-16 bg-[#3b81f7] rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-white"><?= esc($num) ?></span>
                    </div>
                    <h3 class="text-lg font-semibold text-[#1D1D1F] mb-2"><?= esc($title) ?></h3>
                    <p class="text-sm text-[#6E6E73]"><?= esc($text) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Dlaczego warto -->
<section class="bg-[#F5F5F7] py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-12 text-center">Dlaczego warto?</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="bg-white rounded-2xl p-8">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-12 h-12 text-[#3b81f7] mb-4"><path d="M16 7h6v6"/><path d="m22 7-8.5 8.5-5-5L2 17"/></svg>
                <h3 class="text-xl font-semibold text-[#1D1D1F] mb-2">Najlepsza cena</h3>
                <p class="text-[#6E6E73]">Oferujemy konkurencyjne ceny na rynku</p>
            </div>
            <div class="bg-white rounded-2xl p-8">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-12 h-12 text-[#3b81f7] mb-4"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                <h3 class="text-xl font-semibold text-[#1D1D1F] mb-2">Natychmiastowa wypłata</h3>
                <p class="text-[#6E6E73]">Gotówka od ręki lub szybki przelew</p>
            </div>
            <div class="bg-white rounded-2xl p-8">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-12 h-12 text-[#3b81f7] mb-4"><rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13"/><path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"/></svg>
                <h3 class="text-xl font-semibold text-[#1D1D1F] mb-2">Rabat na naprawę</h3>
                <p class="text-[#6E6E73]">Wykorzystaj wartość jako zniżkę</p>
            </div>
            <div class="bg-white rounded-2xl p-8">
                <?= isense_icon('check-circle', 'w-12 h-12 text-[#3b81f7] mb-4') ?>
                <h3 class="text-xl font-semibold text-[#1D1D1F] mb-2">Prosty proces</h3>
                <p class="text-[#6E6E73]">Szybka i bezproblemowa transakcja</p>
            </div>
        </div>
    </div>
</section>

<!-- Jakie urządzenia przyjmujemy -->
<section class="bg-white py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-8 text-center">Jakie urządzenia przyjmujemy?</h2>
            <div class="bg-[#F5F5F7] rounded-2xl p-8 lg:p-12">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold text-[#1D1D1F] mb-4">Urządzenia mobilne</h3>
                        <ul class="space-y-2 text-[#6E6E73]">
                            <?php foreach (['iPhone (wszystkie modele)', 'iPad (wszystkie wersje)', 'Apple Watch', 'AirPods i Beats'] as $item): ?>
                                <li class="flex items-center gap-2">
                                    <?= isense_icon('check-circle', 'w-5 h-5 text-[#3b81f7]') ?><?= esc($item) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-[#1D1D1F] mb-4">Komputery</h3>
                        <ul class="space-y-2 text-[#6E6E73]">
                            <?php foreach (['MacBook (Air, Pro)', 'iMac (wszystkie rozmiary)', 'Mac mini', 'Mac Studio'] as $item): ?>
                                <li class="flex items-center gap-2">
                                    <?= isense_icon('check-circle', 'w-5 h-5 text-[#3b81f7]') ?><?= esc($item) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= view('user/page/_isense_close') ?>
