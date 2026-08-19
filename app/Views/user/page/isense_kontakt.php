<?php
/*
iSense — Kontakt
*/
?>
<?= view('user/page/_isense_open') ?>

<!-- Hero -->
<section class="bg-gradient-to-b from-[#F5F5F7] to-white py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl lg:text-6xl font-bold text-[#1D1D1F] mb-6">Skontaktuj się z nami</h1>
            <p class="text-lg lg:text-xl text-[#6E6E73]">Jesteśmy tu, aby pomóc. Skontaktuj się z nami w dowolny sposób.</p>
        </div>
    </div>
</section>

<!-- Dane kontaktowe + formularz -->
<section class="bg-white py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="grid lg:grid-cols-2 gap-12">
            <div>
                <h2 class="text-3xl font-bold text-[#1D1D1F] mb-8">Dane kontaktowe</h2>
                <div class="space-y-6 mb-12">
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-[#3b81f7]/10 rounded-xl flex items-center justify-center flex-shrink-0"><?= isense_icon('map-pin', 'w-6 h-6 text-[#3b81f7]') ?></div>
                        <div>
                            <h3 class="font-semibold text-[#1D1D1F] mb-1">Adres</h3>
                            <p class="text-[#6E6E73]">ul. Dobra 56/66, Budynek Biblioteki UW<br>(minus 1, lok. nr A32), 00-312 Warszawa</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-[#3b81f7]/10 rounded-xl flex items-center justify-center flex-shrink-0"><?= isense_icon('phone', 'w-6 h-6 text-[#3b81f7]') ?></div>
                        <div>
                            <h3 class="font-semibold text-[#1D1D1F] mb-1">Telefon</h3>
                            <a href="tel:+48504806905" class="text-[#6E6E73] hover:text-[#3b81f7] transition-colors">+48 504 806 905</a>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-[#3b81f7]/10 rounded-xl flex items-center justify-center flex-shrink-0"><?= isense_icon('mail', 'w-6 h-6 text-[#3b81f7]') ?></div>
                        <div>
                            <h3 class="font-semibold text-[#1D1D1F] mb-1">E-mail</h3>
                            <a href="mailto:dobra@isense.pl" class="text-[#6E6E73] hover:text-[#3b81f7] transition-colors">dobra@isense.pl</a>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-[#3b81f7]/10 rounded-xl flex items-center justify-center flex-shrink-0"><?= isense_icon('clock', 'w-6 h-6 text-[#3b81f7]') ?></div>
                        <div>
                            <h3 class="font-semibold text-[#1D1D1F] mb-1">Godziny otwarcia</h3>
                            <div class="text-[#6E6E73]">Poniedziałek – Piątek: 9:00 – 19:00<br>Sobota – Niedziela: Nieczynne</div>
                        </div>
                    </div>
                </div>
                <div class="rounded-2xl overflow-hidden border border-[#D2D2D7] h-80">
                    <iframe title="Mapa — iSense" class="w-full h-full" style="border:0;" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                        src="https://www.google.com/maps?q=Biblioteka+Uniwersytecka+w+Warszawie,+Dobra+56/66,+Warszawa&output=embed"></iframe>
                </div>
            </div>

            <div>
                <h2 class="text-3xl font-bold text-[#1D1D1F] mb-8">Napisz do nas</h2>
                <form class="space-y-6" onsubmit="event.preventDefault(); this.reset(); alert('Dziękujemy! Wiadomość została wysłana — skontaktujemy się z Tobą wkrótce.');">
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
                            <option value="quote">Wycena naprawy</option>
                            <option value="status">Status zlecenia</option>
                            <option value="trade-in">Trade-In / Odkup</option>
                            <option value="warranty">Gwarancja</option>
                            <option value="other">Inne pytanie</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#1D1D1F] mb-2">Wiadomość *</label>
                        <textarea required name="message" rows="6" placeholder="Opisz swoje pytanie lub problem..." class="w-full px-4 py-3 rounded-lg border border-[#D2D2D7] bg-white focus:border-[#3b81f7] focus:outline-none focus:ring-2 focus:ring-[#3b81f7]/20 resize-none"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-[#3b81f7] text-white px-8 py-4 rounded-lg hover:bg-[#2563eb] transition-colors font-medium flex items-center justify-center gap-2">
                        <?= isense_icon('send', 'w-5 h-5') ?>Wyślij wiadomość
                    </button>
                    <p class="text-sm text-[#6E6E73] text-center">* Pola wymagane</p>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Kontakt telefoniczny -->
<section class="bg-[#F5F5F7] py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-[#1D1D1F] mb-8">Preferujesz kontakt telefoniczny?</h2>
            <p class="text-lg text-[#6E6E73] mb-8">Zadzwoń do nas, a nasi serwisanci odpowiedzą na wszystkie pytania.</p>
            <a href="tel:+48504806905" class="inline-flex items-center gap-3 bg-[#3b81f7] text-white px-8 py-4 rounded-lg hover:bg-[#2563eb] transition-colors font-medium text-lg">
                <?= isense_icon('phone', 'w-6 h-6') ?>+48 504 806 905
            </a>
            <p class="mt-6 text-sm text-[#6E6E73]">Pon–Pt: 9:00–19:00</p>
        </div>
    </div>
</section>

<?= view('user/page/_isense_close') ?>
