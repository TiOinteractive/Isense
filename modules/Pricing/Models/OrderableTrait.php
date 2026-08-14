<?php

namespace Modules\Pricing\Models;

/**
 * Obsługa kolumny `order` (1..n) w obrębie rodzica — wspólna dla kategorii, usług i modeli.
 *
 * Pozycje numerowane są osobno w każdej gałęzi (usługi w obrębie kategorii, modele
 * w obrębie usługi), dlatego każda metoda przyjmuje warunek `$where` wskazujący rodzica.
 */
trait OrderableTrait
{
    /** Następna wolna pozycja w obrębie rodzica. */
    protected function nextOrder(array $where = []): int
    {
        $query = $this->db->table($this->table)->selectMax('order');
        foreach ($where as $k => $v) {
            $query->where($k, $v);
        }
        $row = $query->get()->getRowArray();

        return (int) ($row['order'] ?? 0) + 1;
    }

    /**
     * Przesunięcie elementu z pozycji $old_pos na $new_pos (drag&drop listy w panelu).
     * Element rozpoznajemy po pozycji w obrębie rodzica — dlatego $where jest istotne:
     * bez niego trafilibyśmy na element o tej samej pozycji z innej kategorii/usługi.
     */
    public function moveOrder(int $old_pos, int $new_pos, array $where = []): bool
    {
        if ($old_pos === $new_pos) {
            return false;
        }
        $this->db->transStart();

        $query = $this->db->table($this->table)->select('id')->where('order', $old_pos);
        foreach ($where as $k => $v) {
            $query->where($k, $v);
        }
        $item = $query->get()->getRowArray();

        if (! empty($item)) {
            $shift = $this->db->table($this->table);
            foreach ($where as $k => $v) {
                $shift->where($k, $v);
            }
            if ($new_pos > $old_pos) {
                $shift->set('order', '`order`-1', false)->where('order<=', $new_pos)->where('order>', $old_pos)->update();
            } else {
                $shift->set('order', '`order`+1', false)->where('order>=', $new_pos)->where('order<', $old_pos)->update();
            }
            $this->db->table($this->table)->where('id', $item['id'])->set('order', $new_pos)->update();
        }

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    /** Domknięcie luk po usunięciu elementu (pozycje zawsze 1..n). */
    protected function resetOrder(array $where = []): void
    {
        $query = $this->db->table($this->table)->select('id')->orderBy('order', 'ASC');
        foreach ($where as $k => $v) {
            $query->where($k, $v);
        }
        $no = 1;
        foreach ($query->get()->getResultArray() as $row) {
            $this->db->table($this->table)->where('id', $row['id'])->set('order', $no)->update();
            ++$no;
        }
    }
}
