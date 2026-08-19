<?php
/*
iSense — Naprawa z odbiorem
*/
$isAssets = rtrim(base_url('assets/isense'), '/'); ?>
<?= view('user/page/_isense_open') ?>

<!-- Hero -->
<section class="bg-[#F5F5F7] py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl lg:text-6xl font-bold text-[#1D1D1F] mb-6">Naprawa z odbiorem kuriera</h1>
            <p class="text-lg lg:text-xl text-[#6E6E73]">Bezpłatny odbiór i dostawa sprzętu. Nie musisz wychodzić z domu!</p>
        </div>
    </div>
</section>

<!-- Jak to działa -->
<section class="bg-white py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-12 text-center">Jak to działa?</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php
            $steps = [
                ['icon' => 'package', 'title' => 'Złóż zamówienie', 'description' => 'Wypełnij formularz odbioru z weryfikacją SMS'],
                ['icon' => 'map-pin', 'title' => 'Kurier odbiera sprzęt', 'description' => 'W ustalonym terminie kurier odbierze urządzenie'],
                ['icon' => 'clock', 'title' => 'Naprawiamy', 'description' => 'Wykonujemy naprawę z najwyższą starannością'],
                ['icon' => 'check-circle', 'title' => 'Dostawa do Ciebie', 'description' => 'Kurier dostarczy naprawiony sprzęt pod wskazany adres'],
            ];
            foreach ($steps as $index => $step): ?>
                <div class="text-center">
                    <div class="w-16 h-16 bg-[#3b81f7] rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-white"><?= $index + 1 ?></span>
                    </div>
                    <div class="w-12 h-12 bg-[#3b81f7]/10 rounded-xl flex items-center justify-center mx-auto mb-4"><?= isense_icon($step['icon'], 'w-6 h-6 text-[#3b81f7]') ?></div>
                    <h3 class="text-lg font-semibold text-[#1D1D1F] mb-2"><?= esc($step['title']) ?></h3>
                    <p class="text-sm text-[#6E6E73]"><?= esc($step['description']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Naprawa wysyłkowa + formularz -->
<section id="naprawa-wysylkowa" class="bg-white py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="grid lg:grid-cols-2 gap-12 items-start">
            <!-- Lewa kolumna -->
            <div>
                <div class="inline-flex items-center gap-2 bg-[#3b81f7]/10 text-[#3b81f7] px-4 py-2 rounded-full mb-6">
                    <?= isense_icon('truck', 'w-4 h-4') ?>
                    <span class="text-sm font-medium">Door-to-Door</span>
                </div>

                <h2 class="text-3xl lg:text-5xl font-bold text-[#1D1D1F] mb-6">Naprawa wysyłkowa</h2>
                <p class="text-lg text-[#6E6E73] mb-8">Nie masz czasu przyjechać do serwisu? Skorzystaj z naprawy wysyłkowej — odbieramy kurierem z całej Polski, naprawiamy i odsyłamy z powrotem.</p>

                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-[#3b81f7]/10 rounded-xl flex items-center justify-center flex-shrink-0"><?= isense_icon('package', 'w-6 h-6 text-[#3b81f7]') ?></div>
                        <div>
                            <h3 class="font-semibold text-[#1D1D1F] mb-1">Bezpłatny odbiór i dostawa</h3>
                            <p class="text-sm text-[#6E6E73]">Kurier odbierze sprzęt i dostarczy po naprawie bez żadnych opłat</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-[#3b81f7]/10 rounded-xl flex items-center justify-center flex-shrink-0"><?= isense_icon('shield', 'w-6 h-6 text-[#3b81f7]') ?></div>
                        <div>
                            <h3 class="font-semibold text-[#1D1D1F] mb-1">Bezpieczne opakowanie</h3>
                            <p class="text-sm text-[#6E6E73]">Zapewniamy profesjonalne zabezpieczenie sprzętu podczas transportu</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-[#3b81f7]/10 rounded-xl flex items-center justify-center flex-shrink-0"><?= isense_icon('check-circle', 'w-6 h-6 text-[#3b81f7]') ?></div>
                        <div>
                            <h3 class="font-semibold text-[#1D1D1F] mb-1">Weryfikacja SMS</h3>
                            <p class="text-sm text-[#6E6E73]">Bezpieczny proces zamówienia z weryfikacją numeru telefonu</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prawa kolumna: formularz -->
            <div class="bg-[#F5F5F7] rounded-3xl p-8 lg:p-10">
                <h3 class="text-2xl font-semibold text-[#1D1D1F] mb-6">Formularz odbioru</h3>

                <form class="space-y-4" onsubmit="event.preventDefault(); this.reset(); alert('Dziękujemy! Skontaktujemy się wkrótce.');">
                    <div>
                        <label class="block text-sm font-medium text-[#1D1D1F] mb-2">Imię i nazwisko</label>
                        <input type="text" name="name" placeholder="Jan Kowalski" class="w-full px-4 py-3 rounded-lg border border-[#D2D2D7] bg-white focus:border-[#3b81f7] focus:outline-none focus:ring-2 focus:ring-[#3b81f7]/20">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#1D1D1F] mb-2">Adres odbioru</label>
                        <input type="text" name="address" placeholder="ul. Przykładowa 123, 00-001 Warszawa" class="w-full px-4 py-3 rounded-lg border border-[#D2D2D7] bg-white focus:border-[#3b81f7] focus:outline-none focus:ring-2 focus:ring-[#3b81f7]/20">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#1D1D1F] mb-2">Numer telefonu</label>
                        <div class="flex gap-2">
                            <input type="tel" name="phone" placeholder="+48 123 456 789" class="flex-1 px-4 py-3 rounded-lg border border-[#D2D2D7] bg-white focus:border-[#3b81f7] focus:outline-none focus:ring-2 focus:ring-[#3b81f7]/20">
                            <button type="button" class="px-4 py-3 bg-[#3b81f7] text-white rounded-lg hover:bg-[#2563eb] transition-colors whitespace-nowrap">Wyślij kod</button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#1D1D1F] mb-2">Adres e-mail</label>
                        <input type="email" name="email" placeholder="jan@example.com" class="w-full px-4 py-3 rounded-lg border border-[#D2D2D7] bg-white focus:border-[#3b81f7] focus:outline-none focus:ring-2 focus:ring-[#3b81f7]/20">
                    </div>

                    <div>
                        <label for="pickup-device" class="block text-sm font-medium text-[#1D1D1F] mb-2">Typ urządzenia</label>
                        <select id="pickup-device" name="device" class="w-full px-4 py-3 rounded-lg border border-[#D2D2D7] bg-white focus:border-[#3b81f7] focus:outline-none focus:ring-2 focus:ring-[#3b81f7]/20">
                            <option value="">Wybierz urządzenie</option>
                            <option value="iphone">iPhone</option>
                            <option value="ipad">iPad</option>
                            <option value="macbook">MacBook</option>
                            <option value="imac">iMac</option>
                            <option value="watch">Apple Watch</option>
                            <option value="airpods">AirPods / Beats</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#1D1D1F] mb-2">Opis usterki</label>
                        <textarea name="description" placeholder="Opisz problem z urządzeniem..." class="w-full px-4 py-3 rounded-lg border border-[#D2D2D7] bg-white focus:border-[#3b81f7] focus:outline-none focus:ring-2 focus:ring-[#3b81f7]/20 min-h-24"></textarea>
                    </div>

                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="terms" class="mt-1 w-5 h-5 text-[#3b81f7] border-[#D2D2D7] rounded focus:ring-[#3b81f7]">
                        <span class="text-sm text-[#6E6E73]">Potwierdzam poprawność danych i akceptuję <a href="#" class="text-[#3b81f7] hover:underline">regulamin usługi</a></span>
                    </label>

                    <button type="submit" class="w-full bg-[#3b81f7] text-white px-8 py-4 rounded-lg hover:bg-[#2563eb] transition-colors font-medium">Zamów kuriera</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Zalety Door-to-Door -->
<section class="bg-[#F5F5F7] py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-12 text-center">Zalety usługi Door-to-Door</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <div class="bg-white rounded-2xl p-8">
                <div class="text-4xl mb-4">🚚</div>
                <h3 class="text-xl font-semibold text-[#1D1D1F] mb-2">Bezpłatna przesyłka</h3>
                <p class="text-[#6E6E73]">Nie płacisz nic za odbiór i dostawę sprzętu</p>
            </div>
            <div class="bg-white rounded-2xl p-8">
                <div class="text-4xl mb-4">🏠</div>
                <h3 class="text-xl font-semibold text-[#1D1D1F] mb-2">Wygoda</h3>
                <p class="text-[#6E6E73]">Wszystko załatwiasz nie wychodząc z domu</p>
            </div>
            <div class="bg-white rounded-2xl p-8">
                <div class="text-4xl mb-4">📦</div>
                <h3 class="text-xl font-semibold text-[#1D1D1F] mb-2">Bezpieczne opakowanie</h3>
                <p class="text-[#6E6E73]">Profesjonalne zabezpieczenie podczas transportu</p>
            </div>
        </div>
    </div>
</section>

<?= view('user/page/_isense_close') ?>
