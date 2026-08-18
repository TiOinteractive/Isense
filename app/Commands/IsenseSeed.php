<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Modules\Isense\Libraries\Isense;
use Modules\Isense\Models\IsenseSectionModel;

/**
 * Seeduje dane sekcji strony głównej iSense z wartości domyślnych biblioteki,
 * aby każdy blok był widoczny i edytowalny w panelu (a nie tylko renderowany z kodu).
 * Pomija bloki, które już mają zapisane dane.
 *
 * Użycie: php spark isense:seed [id_page] [id_lang]
 */
class IsenseSeed extends BaseCommand
{
    protected $group       = 'Isense';
    protected $name        = 'isense:seed';
    protected $description = 'Seeduje brakujące dane sekcji strony iSense (domyślne) do CMS, aby były edytowalne.';

    public function run(array $params)
    {
        $idPage = (int) ($params[0] ?? 63);
        $idLang = (int) ($params[1] ?? 1);

        $db    = \Config\Database::connect();
        $lib   = new Isense();
        $model = new IsenseSectionModel();

        $blocks = $db->table('page_content pc')
            ->select('pc.id, pc.order, me.slug')
            ->join('module_element me', 'me.id = pc.id_module_element')
            ->join('module m', 'm.id = me.id_module')
            ->where('pc.id_page', $idPage)
            ->where('m.slug', 'Isense')
            ->orderBy('pc.order', 'ASC')
            ->get()->getResultArray();

        if (empty($blocks)) {
            CLI::write("Brak bloków iSense na stronie {$idPage}.", 'red');
            return;
        }

        $seeded = 0;
        $skipped = 0;
        foreach ($blocks as $b) {
            $exists = $model->where('id_page_cont', $b['id'])->first();
            if (! empty($exists)) {
                CLI::write("• blok {$b['id']} ({$b['slug']}) — już ma dane, pomijam", 'yellow');
                $skipped++;
                continue;
            }

            $fields = $lib->index(['id' => $b['id']], $idLang, $b['slug']);
            unset($fields['id'], $fields['section'], $fields['template']);

            if (empty($fields)) {
                CLI::write("• blok {$b['id']} ({$b['slug']}) — brak domyślnych pól, pomijam");
                $skipped++;
                continue;
            }

            $model->saveFields($b['id'], [$idLang => $fields]);
            CLI::write("✔ blok {$b['id']} ({$b['slug']}) — zaseedowano do CMS", 'green');
            $seeded++;
        }

        CLI::newLine();
        CLI::write("Gotowe. Zaseedowano: {$seeded}, pominięto: {$skipped}.", 'green');
    }
}
