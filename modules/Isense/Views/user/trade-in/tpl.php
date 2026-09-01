<?php
helper(['url', 'isense']);
$d = $data ?? [];
$benefits = $d['benefits'] ?? [];
$currency = trim($d['currency'] ?? 'zł');
$round = max(1, (int) ($d['round'] ?? 10));

// Parsowanie modeli "Nazwa | cena" z pola tekstowego urządzenia.
$parseModels = static function ($text) {
    $out = [];
    foreach (preg_split('/\r\n|\r|\n/', (string) $text) as $line) {
        if (trim($line) === '') {
            continue;
        }
        $parts = array_map('trim', explode('|', $line));
        $name  = $parts[0] ?? '';
        $value = isset($parts[1]) ? (float) preg_replace('/[^0-9.]/', '', str_replace(',', '.', $parts[1])) : 0;
        if ($name !== '') {
            $out[] = ['name' => $name, 'value' => $value];
        }
    }
    return $out;
};

$devices = [];
foreach ($d['devices'] ?? [] as $dev) {
    $devices[] = ['label' => $dev['type_label'] ?? '', 'models' => $parseModels($dev['models_text'] ?? '')];
}
$conditions = [];
foreach ($d['conditions'] ?? [] as $c) {
    $conditions[] = ['label' => $c['label'] ?? '', 'description' => $c['description'] ?? '', 'factor' => (float) ($c['factor'] ?? 100)];
}

// Wycena początkowa (pierwszy model pierwszego urządzenia × pierwszy stan) — renderowana serwerowo (działa też bez JS).
$money = static function ($v) use ($currency, $round) {
    $v = round($v / $round) * $round;
    return number_format($v, 0, ',', ' ') . ' ' . $currency;
};
$firstVal = $devices[0]['models'][0]['value'] ?? 0;
$firstFactor = $conditions[0]['factor'] ?? 100;
$initEstimate = $money($firstVal * $firstFactor / 100);

$wizardData = json_encode([
    'currency'   => $currency,
    'round'      => $round,
    'devices'    => $devices,
    'conditions' => array_map(fn($c) => ['label' => $c['label'], 'factor' => $c['factor']], $conditions),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

$devBtn  = 'p-4 rounded-xl border-2 transition-all text-center';
$condBtn = 'w-full text-left p-4 rounded-xl border-2 transition-all';
$actCls  = 'border-[#3b81f7] bg-[#3b81f7]/10';
$inactCls = 'border-[#D2D2D7] hover:border-[#6E6E73]';
?>
<section class="bg-[#F5F5F7] py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="grid lg:grid-cols-2 gap-12 items-start">
            <!-- Lewa kolumna (edytowalna) -->
            <div>
                <?php if (!empty($d['badge'])): ?>
                    <div class="inline-flex items-center gap-2 bg-white text-[#3b81f7] px-4 py-2 rounded-full mb-6 border border-[#D2D2D7]">
                        <?= isense_icon('refresh-cw', 'w-4 h-4') ?>
                        <span class="text-sm font-medium"><?= esc($d['badge']) ?></span>
                    </div>
                <?php endif; ?>
                <h2 class="text-3xl lg:text-5xl font-bold text-[#1D1D1F] mb-6"><?= esc($d['heading'] ?? '') ?></h2>
                <?php if (!empty($d['lead'])): ?><p class="text-lg text-[#6E6E73] mb-8"><?= esc($d['lead']) ?></p><?php endif; ?>
                <div class="space-y-6">
                    <?php foreach ($benefits as $b): ?>
                        <div class="flex gap-4">
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center flex-shrink-0 border border-[#D2D2D7]"><?= isense_icon($b['icon'] ?: 'check-circle', 'w-6 h-6 text-[#3b81f7]') ?></div>
                            <div>
                                <h3 class="font-semibold text-[#1D1D1F] mb-1"><?= esc($b['title'] ?? '') ?></h3>
                                <p class="text-sm text-[#6E6E73]"><?= esc($b['text'] ?? '') ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- Prawa kolumna: kreator wyceny (interaktywny) -->
            <div class="bg-white rounded-3xl p-8 lg:p-10 shadow-xl border border-[#D2D2D7]" data-tradein>
                <script type="application/json" data-tradein-data><?= $wizardData ?></script>
                <h3 class="text-2xl font-semibold text-[#1D1D1F] mb-6"><?= esc($d['wizard_title'] ?? 'Kreator wyceny Trade-In') ?></h3>
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-[#1D1D1F] mb-3">1. Wybierz typ urządzenia</label>
                        <div class="grid grid-cols-2 gap-3">
                            <?php foreach ($devices as $i => $dev): ?>
                                <button type="button" data-tradein-device="<?= $i ?>" class="<?= $devBtn ?> <?= $i === 0 ? $actCls : $inactCls ?>"><span class="text-sm font-medium text-[#1D1D1F]"><?= esc($dev['label']) ?></span></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div>
                        <label for="tradein-model" class="block text-sm font-medium text-[#1D1D1F] mb-3">2. Wybierz model</label>
                        <select data-tradein-model id="tradein-model" class="w-full px-4 py-3 rounded-lg border border-[#D2D2D7] bg-white focus:border-[#3b81f7] focus:ring-2 focus:ring-[#3b81f7]/20">
                            <?php foreach (($devices[0]['models'] ?? []) as $mi => $mod): ?><option value="<?= $mi ?>"><?= esc($mod['name']) ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#1D1D1F] mb-3">3. Określ stan techniczny</label>
                        <div class="space-y-2">
                            <?php foreach ($conditions as $i => $c): ?>
                                <button type="button" data-tradein-cond="<?= $i ?>" data-factor="<?= esc($c['factor'], 'attr') ?>" class="<?= $condBtn ?> <?= $i === 0 ? $actCls : $inactCls ?>">
                                    <div class="font-medium text-[#1D1D1F] mb-1"><?= esc($c['label']) ?></div>
                                    <?php if (!empty($c['description'])): ?><div class="text-xs text-[#6E6E73]"><?= esc($c['description']) ?></div><?php endif; ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-[#3b81f7] to-[#2563eb] rounded-2xl p-8 text-white">
                        <div class="text-sm font-medium mb-2 opacity-90">Szacowana wartość odkupu</div>
                        <div class="text-5xl font-bold mb-4" data-tradein-estimate><?= esc($initEstimate) ?></div>
                        <p class="text-sm opacity-90">Ostateczna wycena może się różnić po weryfikacji urządzenia</p>
                    </div>
                    <div class="space-y-3">
                        <a href="<?= site_url($d['cta1_url'] ?? 'kontakt') ?>" class="block text-center w-full bg-[#3b81f7] text-white px-8 py-4 rounded-lg hover:bg-[#2563eb] transition-colors font-medium"><?= esc($d['cta1_label'] ?? '') ?></a>
                        <a href="<?= site_url($d['cta2_url'] ?? 'kontakt') ?>" class="block text-center w-full bg-white border-2 border-[#1D1D1F] text-[#1D1D1F] px-8 py-4 rounded-lg hover:bg-[#F5F5F7] transition-colors font-medium"><?= esc($d['cta2_label'] ?? '') ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
