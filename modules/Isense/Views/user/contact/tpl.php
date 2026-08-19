<?php
helper(['url', 'isense']);
$d = $data ?? [];
$subjects = $d['subjects'] ?? [];
?>
<section class="bg-white py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="grid lg:grid-cols-2 gap-12">
            <!-- Dane kontaktowe -->
            <div>
                <h2 class="text-3xl font-bold text-[#1D1D1F] mb-8"><?= esc($d['left_heading'] ?? 'Dane kontaktowe') ?></h2>
                <div class="space-y-6 mb-12">
                    <?php if (!empty($d['address'])): ?>
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-[#3b81f7]/10 rounded-xl flex items-center justify-center flex-shrink-0"><?= isense_icon('map-pin', 'w-6 h-6 text-[#3b81f7]') ?></div>
                        <div><h3 class="font-semibold text-[#1D1D1F] mb-1">Adres</h3><p class="text-[#6E6E73] whitespace-pre-line"><?= esc($d['address']) ?></p></div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($d['phone'])): ?>
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-[#3b81f7]/10 rounded-xl flex items-center justify-center flex-shrink-0"><?= isense_icon('phone', 'w-6 h-6 text-[#3b81f7]') ?></div>
                        <div><h3 class="font-semibold text-[#1D1D1F] mb-1">Telefon</h3><a href="tel:<?= esc(preg_replace('/\s+/', '', $d['phone']), 'attr') ?>" class="text-[#6E6E73] hover:text-[#3b81f7] transition-colors"><?= esc($d['phone']) ?></a></div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($d['email'])): ?>
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-[#3b81f7]/10 rounded-xl flex items-center justify-center flex-shrink-0"><?= isense_icon('mail', 'w-6 h-6 text-[#3b81f7]') ?></div>
                        <div><h3 class="font-semibold text-[#1D1D1F] mb-1">E-mail</h3><a href="mailto:<?= esc($d['email'], 'attr') ?>" class="text-[#6E6E73] hover:text-[#3b81f7] transition-colors"><?= esc($d['email']) ?></a></div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($d['hours'])): ?>
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-[#3b81f7]/10 rounded-xl flex items-center justify-center flex-shrink-0"><?= isense_icon('clock', 'w-6 h-6 text-[#3b81f7]') ?></div>
                        <div><h3 class="font-semibold text-[#1D1D1F] mb-1">Godziny otwarcia</h3><div class="text-[#6E6E73] whitespace-pre-line"><?= esc($d['hours']) ?></div></div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php if (!empty($d['map'])): ?>
                <div class="rounded-2xl overflow-hidden border border-[#D2D2D7] h-80">
                    <iframe title="Mapa — iSense" class="w-full h-full" style="border:0;" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="<?= esc($d['map'], 'attr') ?>"></iframe>
                </div>
                <?php endif; ?>
                <?php if (!empty($d['map_link'])): ?>
                <a href="<?= esc($d['map_link'], 'attr') ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 mt-3 text-sm text-[#3b81f7] hover:underline"><?= isense_icon('map-pin', 'w-4 h-4') ?>Otwórz w Google Maps</a>
                <?php endif; ?>
            </div>

            <!-- Formularz -->
            <div>
                <h2 class="text-3xl font-bold text-[#1D1D1F] mb-8"><?= esc($d['right_heading'] ?? 'Napisz do nas') ?></h2>
                <form class="space-y-6" data-ajax-form method="post" action="<?= site_url('isense/form-submit') ?>">
                    <input type="hidden" name="type" value="contact">
                    <input type="hidden" name="block" value="<?= (int) ($id_cont ?? 0) ?>">
                    <div class="hidden" aria-hidden="true"><input type="text" name="company" tabindex="-1" autocomplete="off"></div>
                    <div data-form-msg class="hidden text-sm rounded-lg px-4 py-3"></div>
                    <div>
                        <label class="block text-sm font-medium text-[#1D1D1F] mb-2">Imię i nazwisko *</label>
                        <input type="text" required name="name" placeholder="Jan Kowalski" class="w-full px-4 py-3 rounded-lg border border-[#D2D2D7] bg-white focus:border-[#3b81f7] focus:outline-none focus:ring-2 focus:ring-[#3b81f7]/20">
                    </div>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-[#1D1D1F] mb-2">E-mail *</label>
                            <input type="email" required name="email" placeholder="jan@example.com" class="w-full px-4 py-3 rounded-lg border border-[#D2D2D7] bg-white focus:border-[#3b81f7] focus:outline-none focus:ring-2 focus:ring-[#3b81f7]/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#1D1D1F] mb-2">Telefon</label>
                            <input type="tel" name="phone" placeholder="+48 123 456 789" class="w-full px-4 py-3 rounded-lg border border-[#D2D2D7] bg-white focus:border-[#3b81f7] focus:outline-none focus:ring-2 focus:ring-[#3b81f7]/20">
                        </div>
                    </div>
                    <div>
                        <label for="contact-subject" class="block text-sm font-medium text-[#1D1D1F] mb-2">Temat *</label>
                        <select required id="contact-subject" name="subject" class="w-full px-4 py-3 rounded-lg border border-[#D2D2D7] bg-white focus:border-[#3b81f7] focus:outline-none focus:ring-2 focus:ring-[#3b81f7]/20">
                            <option value="">Wybierz temat</option>
                            <?php foreach ($subjects as $s): if (empty($s['label'])) continue; ?><option value="<?= esc($s['label'], 'attr') ?>"><?= esc($s['label']) ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#1D1D1F] mb-2">Wiadomość *</label>
                        <textarea required name="message" rows="6" placeholder="Opisz swoje pytanie lub problem..." class="w-full px-4 py-3 rounded-lg border border-[#D2D2D7] bg-white focus:border-[#3b81f7] focus:outline-none focus:ring-2 focus:ring-[#3b81f7]/20 resize-none"></textarea>
                    </div>
                    <?php if (!empty($d['consent_text'])): ?>
                    <div class="bg-[#F5F5F7] rounded border border-[#E5E5EA] p-4">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" required name="consent" class="mt-0.5 w-4 h-4 flex-shrink-0 accent-[#3b81f7]">
                            <span class="text-xs text-[#6E6E73] leading-relaxed"><?= esc($d['consent_text']) ?> *</span>
                        </label>
                    </div>
                    <?php endif; ?>
                    <button type="submit" class="w-full bg-[#3b81f7] text-white px-8 py-4 rounded-lg hover:bg-[#2563eb] transition-colors font-medium flex items-center justify-center gap-2"><?= isense_icon('send', 'w-5 h-5') ?>Wyślij wiadomość</button>
                    <p class="text-xs text-[#6E6E73]">* Pola wymagane</p>
                </form>
            </div>
        </div>
    </div>
</section>
