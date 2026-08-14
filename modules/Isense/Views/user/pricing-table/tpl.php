<?php
helper(['url', 'isense']);
$d = $data ?? [];
$groups = $d['groups'] ?? [];
/* "by_service" = jedna karta na usługę, z ceną per model. "by_device" = poprzedni układ (karta na grupę modeli, tabela z kolumną na model),
   nadal używany np. na stronie iPad. Oba układy czytają te same pola (models_csv / rows_text). */
$byService = ($d['layout'] ?? 'by_device') === 'by_service';
$splitCsv = static fn($s) => array_values(array_filter(array_map('trim', explode(',', (string) $s)), fn($v) => $v !== ''));
?>
<section id="cennik" class="bg-white py-10 lg:py-14">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <?php if (!empty($d['heading'])): ?><h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-3"><?= esc($d['heading']) ?></h2><?php endif; ?>
        <?php if (!empty($d['lead'])): ?><p class="text-[#6E6E73] mb-10"><?= esc($d['lead']) ?></p><?php endif; ?>

        <?php if ($byService): ?>
            <div class="space-y-6">
                <?php foreach ($groups as $group):
                    $models = $splitCsv($group['models_csv'] ?? '');
                    $lines = preg_split('/\r\n|\r|\n/', trim((string) ($group['rows_text'] ?? '')));
                    foreach ($lines as $line):
                        if (trim($line) === '') continue;
                        $cells = array_map('trim', explode('|', $line));
                        $service = array_shift($cells);
                ?>
                    <div class="border border-[#E5E5EA] rounded overflow-hidden">
                        <div class="bg-[#F5F5F7] px-6 py-4 border-b border-[#E5E5EA]">
                            <h3 class="font-bold text-[#1D1D1F] text-lg"><?= esc($service) ?></h3>
                        </div>
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-[#E5E5EA]">
                                    <th class="text-left px-6 py-3 text-[#6E6E73] font-normal">Model</th>
                                    <th class="text-right px-6 py-3 text-[#6E6E73] font-normal">Cena</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $ri = 0; foreach ($models as $mi => $m): ?>
                                    <tr class="border-b border-[#F0F0F0] <?= ($ri % 2 === 0) ? 'bg-white' : 'bg-[#FAFAFA]' ?>">
                                        <td class="px-6 py-3 text-[#1D1D1F] font-medium"><?= esc($m) ?></td>
                                        <td class="px-6 py-3 text-right font-semibold text-[#3b81f7]"><?= esc($cells[$mi] ?? '') ?></td>
                                    </tr>
                                <?php $ri++; endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; endforeach; ?>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($groups as $group):
                    $models = $splitCsv($group['models_csv'] ?? '');
                    $lines = preg_split('/\r\n|\r|\n/', trim((string) ($group['rows_text'] ?? '')));
                ?>
                    <div class="border border-[#E5E5EA] rounded overflow-hidden">
                        <div class="w-full flex items-center justify-between px-6 py-4 bg-[#F5F5F7] text-left">
                            <span class="font-bold text-[#1D1D1F]"><?= esc($group['group_title'] ?? '') ?></span>
                            <?= isense_icon('chevron-down', 'w-5 h-5 text-[#6E6E73]') ?>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-[#E5E5EA] bg-white">
                                        <th class="text-left px-6 py-3 text-[#6E6E73] font-medium w-1/3">Usługa</th>
                                        <?php foreach ($models as $m): ?><th class="text-center px-4 py-3 text-[#1D1D1F] font-semibold"><?= esc($m) ?></th><?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $ri = 0; foreach ($lines as $line):
                                        if (trim($line) === '') continue;
                                        $cells = array_map('trim', explode('|', $line));
                                        $service = array_shift($cells);
                                    ?>
                                        <tr class="border-b border-[#F0F0F0] <?= ($ri % 2 === 0) ? 'bg-white' : 'bg-[#FAFAFA]' ?>">
                                            <td class="px-6 py-3 text-[#1D1D1F] font-medium"><?= esc($service) ?></td>
                                            <?php foreach ($cells as $p): ?><td class="px-4 py-3 text-center font-semibold text-[#3b81f7]"><?= esc($p) ?></td><?php endforeach; ?>
                                        </tr>
                                    <?php $ri++; endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($d['note'])): ?><p class="text-xs text-[#6E6E73] mt-4"><?= esc($d['note']) ?></p><?php endif; ?>
    </div>
</section>
