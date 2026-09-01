<?php
/*
iSense — Status naprawy
*/
$isAssets = rtrim(base_url('assets/isense'), '/'); ?>
<?= view('user/page/_isense_open') ?>

<!-- Hero -->
<section class="relative py-16 lg:py-24 bg-gradient-to-b from-[#F5F5F7] to-white">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl lg:text-6xl font-bold text-[#1D1D1F] mb-6">Sprawdź status naprawy</h1>
            <p class="text-lg lg:text-xl text-[#6E6E73] mb-8">Wpisz numer zlecenia, aby zobaczyć aktualny status naprawy</p>

            <!-- Formularz wyszukiwania -->
            <form onsubmit="event.preventDefault();" class="max-w-xl mx-auto">
                <div class="flex gap-2">
                    <input type="text" name="order" placeholder="np. ORD-12345" class="flex-1 px-6 py-4 rounded-lg border border-[#D2D2D7] bg-white focus:border-[#3b81f7] focus:ring-2 focus:ring-[#3b81f7]/20 text-lg">
                    <button type="submit" class="bg-[#3b81f7] text-white px-8 py-4 rounded-lg hover:bg-[#2563eb] transition-colors font-medium flex items-center gap-2">
                        <?= isense_icon('search', 'w-5 h-5') ?>Sprawdź
                    </button>
                </div>
            </form>

            <p class="mt-6 text-sm text-[#6E6E73]">Numer zlecenia znajdziesz w potwierdzeniu przyjęcia sprzętu (e-mail lub SMS)</p>
            <p class="mt-2 text-sm text-[#6E6E73]">Przykładowy numer testowy: <span class="font-mono font-medium text-[#3b81f7]">ORD-12345</span></p>
        </div>
    </div>
</section>

<!-- Podgląd statusu (statyczny przykład) -->
<section class="bg-white py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="max-w-4xl mx-auto">
            <?php
            $statusData = [
                'orderNumber' => 'ORD-12345',
                'device'      => 'iPhone 15 Pro',
                'service'     => 'Wymiana wyświetlacza',
                'estimatedCompletion' => '26.05.2026',
                'steps' => [
                    ['icon' => 'package',      'name' => 'Przyjęcie zlecenia', 'date' => '24.05.2026, 10:30', 'completed' => true,  'current' => false],
                    ['icon' => 'search',       'name' => 'Diagnoza',           'date' => '24.05.2026, 14:15', 'completed' => true,  'current' => false],
                    ['icon' => 'wrench',       'name' => 'Naprawa w toku',     'date' => '25.05.2026',        'completed' => false, 'current' => true],
                    ['icon' => 'check-circle', 'name' => 'Gotowe do odbioru',  'date' => '-',                 'completed' => false, 'current' => false],
                ],
            ];
            $stepsCount = count($statusData['steps']);
            ?>

            <!-- Informacje o zleceniu -->
            <div class="bg-[#F5F5F7] rounded-2xl p-8 mb-12">
                <div class="grid md:grid-cols-3 gap-6">
                    <div>
                        <div class="text-sm text-[#6E6E73] mb-1">Numer zlecenia</div>
                        <div class="font-mono font-semibold text-[#1D1D1F] text-lg"><?= esc($statusData['orderNumber']) ?></div>
                    </div>
                    <div>
                        <div class="text-sm text-[#6E6E73] mb-1">Urządzenie</div>
                        <div class="font-semibold text-[#1D1D1F] text-lg"><?= esc($statusData['device']) ?></div>
                    </div>
                    <div>
                        <div class="text-sm text-[#6E6E73] mb-1">Usługa</div>
                        <div class="font-semibold text-[#1D1D1F] text-lg"><?= esc($statusData['service']) ?></div>
                    </div>
                </div>
            </div>

            <!-- Oś czasu -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-[#1D1D1F] mb-8">Status naprawy</h2>
                <div class="space-y-6">
                    <?php foreach ($statusData['steps'] as $index => $step): ?>
                        <div class="flex gap-6">
                            <!-- Linia osi czasu -->
                            <div class="flex flex-col items-center">
                                <div class="w-14 h-14 rounded-full flex items-center justify-center <?= $step['completed'] ? 'bg-green-100 border-2 border-green-500' : ($step['current'] ? 'bg-[#3b81f7]/10 border-2 border-[#3b81f7]' : 'bg-[#F5F5F7] border-2 border-[#D2D2D7]') ?>">
                                    <?= isense_icon($step['icon'], 'w-7 h-7 ' . ($step['completed'] ? 'text-green-600' : ($step['current'] ? 'text-[#3b81f7]' : 'text-[#6E6E73]'))) ?>
                                </div>
                                <?php if ($index < $stepsCount - 1): ?>
                                    <div class="w-0.5 flex-1 min-h-12 <?= $step['completed'] ? 'bg-green-500' : 'bg-[#D2D2D7]' ?>"></div>
                                <?php endif; ?>
                            </div>

                            <!-- Treść -->
                            <div class="flex-1 pb-8">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold mb-1 <?= $step['current'] ? 'text-[#3b81f7]' : 'text-[#1D1D1F]' ?>"><?= esc($step['name']) ?></h3>
                                        <div class="text-sm text-[#6E6E73]"><?= esc($step['date']) ?></div>
                                    </div>
                                    <?php if ($step['completed']): ?>
                                        <div class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Wykonano</div>
                                    <?php elseif ($step['current']): ?>
                                        <div class="px-3 py-1 bg-[#3b81f7]/10 text-[#3b81f7] rounded-full text-xs font-medium">W trakcie</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Szacowana data zakończenia -->
            <div class="bg-gradient-to-br from-[#3b81f7] to-[#2563eb] rounded-2xl p-8 text-white">
                <div class="flex items-center gap-4">
                    <?= isense_icon('truck', 'w-12 h-12') ?>
                    <div>
                        <div class="text-sm opacity-90 mb-1">Szacowana data zakończenia</div>
                        <div class="text-2xl font-bold"><?= esc($statusData['estimatedCompletion']) ?></div>
                    </div>
                </div>
            </div>

            <!-- Kontakt -->
            <div class="mt-8 text-center">
                <p class="text-[#6E6E73] mb-4">Masz pytania dotyczące naprawy?</p>
                <a href="tel:+48504806905" class="inline-block text-[#3b81f7] font-medium hover:underline">Zadzwoń: +48 504 806 905</a>
            </div>
        </div>
    </div>
</section>

<!-- Jak to działa -->
<section class="bg-[#F5F5F7] py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-2xl font-bold text-[#1D1D1F] mb-8 text-center">Jak to działa?</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <?php
                $howto = [
                    ['icon' => 'package',      'title' => 'Otrzymaj numer',  'text' => 'Po przyjęciu sprzętu otrzymasz numer zlecenia SMS-em i e-mailem'],
                    ['icon' => 'search',       'title' => 'Sprawdź status',  'text' => 'Wpisz numer zlecenia w pole powyżej'],
                    ['icon' => 'check-circle', 'title' => 'Odbierz sprzęt',  'text' => 'Powiadomimy Cię, gdy naprawa będzie gotowa'],
                ];
                foreach ($howto as $h): ?>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-[#3b81f7]/10 rounded-2xl flex items-center justify-center mx-auto mb-4"><?= isense_icon($h['icon'], 'w-8 h-8 text-[#3b81f7]') ?></div>
                        <h3 class="font-semibold text-[#1D1D1F] mb-2"><?= esc($h['title']) ?></h3>
                        <p class="text-sm text-[#6E6E73]"><?= esc($h['text']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<?= view('user/page/_isense_close') ?>
