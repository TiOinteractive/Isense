<?php
helper(['url', 'isense']);
$d = $data ?? [];
$benefits = $d['benefits'] ?? [];
$devices = ['iphone' => 'iPhone', 'ipad' => 'iPad', 'macbook' => 'MacBook', 'imac' => 'iMac', 'watch' => 'Apple Watch', 'airpods' => 'AirPods / Beats'];
?>
<section id="naprawa-wysylkowa" class="bg-white py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="grid lg:grid-cols-2 gap-12 items-start">
            <!-- Lewa kolumna (edytowalna) -->
            <div>
                <?php if (!empty($d['badge'])): ?>
                    <div class="inline-flex items-center gap-2 bg-[#3b81f7]/10 text-[#3b81f7] px-4 py-2 rounded-full mb-6">
                        <?= isense_icon('truck', 'w-4 h-4') ?>
                        <span class="text-sm font-medium"><?= esc($d['badge']) ?></span>
                    </div>
                <?php endif; ?>
                <h2 class="text-3xl lg:text-5xl font-bold text-[#1D1D1F] mb-6"><?= esc($d['heading'] ?? '') ?></h2>
                <?php if (!empty($d['lead'])): ?><p class="text-lg text-[#6E6E73] mb-8"><?= esc($d['lead']) ?></p><?php endif; ?>
                <div class="space-y-6">
                    <?php foreach ($benefits as $b): ?>
                        <div class="flex gap-4">
                            <div class="w-12 h-12 bg-[#3b81f7]/10 rounded-xl flex items-center justify-center flex-shrink-0"><?= isense_icon($b['icon'] ?: 'check-circle', 'w-6 h-6 text-[#3b81f7]') ?></div>
                            <div>
                                <h3 class="font-semibold text-[#1D1D1F] mb-1"><?= esc($b['title'] ?? '') ?></h3>
                                <p class="text-sm text-[#6E6E73]"><?= esc($b['text'] ?? '') ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- Prawa kolumna: formularz (statyczny) -->
            <div class="bg-[#F5F5F7] rounded-3xl p-8 lg:p-10">
                <h3 class="text-2xl font-semibold text-[#1D1D1F] mb-6"><?= esc($d['form_heading'] ?? 'Formularz odbioru') ?></h3>
                <form class="space-y-4" data-ajax-form method="post" action="<?= site_url('isense/form-submit') ?>">
                    <input type="hidden" name="type" value="pickup">
                    <input type="hidden" name="block" value="<?= (int) ($id_cont ?? 0) ?>">
                    <div class="hidden" aria-hidden="true"><input type="text" name="company" tabindex="-1" autocomplete="off"></div>
                    <div data-form-msg class="hidden text-sm rounded-lg px-4 py-3"></div>
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
                        <label class="block text-sm font-medium text-[#1D1D1F] mb-2">Typ urządzenia</label>
                        <select name="device" class="w-full px-4 py-3 rounded-lg border border-[#D2D2D7] bg-white focus:border-[#3b81f7] focus:outline-none focus:ring-2 focus:ring-[#3b81f7]/20">
                            <option value="">Wybierz urządzenie</option>
                            <?php foreach ($devices as $v => $l): ?><option value="<?= $v ?>"><?= esc($l) ?></option><?php endforeach; ?>
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
