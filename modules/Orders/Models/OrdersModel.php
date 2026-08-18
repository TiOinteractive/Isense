<?php

namespace Modules\Orders\Models;

use CodeIgniter\Model;

/**
 * Zlecenia serwisowe iSense.
 * Nagłówek zlecenia: `isense_order`, etapy osi czasu: `isense_order_step`.
 */
class OrdersModel extends Model
{
    protected $table         = 'isense_order';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['number', 'customer', 'email', 'phone', 'address', 'device', 'service', 'estimated', 'status', 'note', 'publish', 'edited_at', 'created_at'];

    /** Dostępne statusy zlecenia (klucz => klucz tłumaczenia). */
    public static function statuses(): array
    {
        return [
            'new'       => 'Orders.StatusNew',
            'diagnosis' => 'Orders.StatusDiagnosis',
            'repair'    => 'Orders.StatusRepair',
            'ready'     => 'Orders.StatusReady',
            'done'      => 'Orders.StatusDone',
            'cancelled' => 'Orders.StatusCancelled',
        ];
    }

    /** Ikony dostępne dla etapów osi czasu. */
    public static function icons(): array
    {
        return ['package', 'search', 'wrench', 'check-circle', 'truck', 'clock', 'shield', 'credit-card', 'battery', 'zap', 'smartphone', 'laptop'];
    }

    public function getOrderById($id)
    {
        return $this->where('id', $id)->first();
    }

    /** Zlecenie po numerze (kolacja *_ci → nieczułe na wielkość liter). */
    public function getOrderByNumber(string $number, bool $onlyPublished = true)
    {
        $q = $this->where('number', trim($number));
        if ($onlyPublished) {
            $q->where('publish', 1);
        }
        return $q->first();
    }

    /** Etapy osi czasu zlecenia, posortowane. */
    public function getSteps($idOrder): array
    {
        if (empty($idOrder)) {
            return [];
        }
        return $this->db->table('isense_order_step')
            ->where('id_order', $idOrder)
            ->orderBy('order', 'ASC')->orderBy('id', 'ASC')
            ->get()->getResultArray();
    }

    /** Czy numer zlecenia jest już zajęty przez inne zlecenie. */
    public function numberTaken($id, string $number): bool
    {
        $q = $this->where('number', trim($number));
        if (! empty($id)) {
            $q->where('id !=', $id);
        }
        return ! empty($q->first());
    }

    /** Zapis zlecenia wraz z etapami (steps z POST). Zwraca bool; ID w $this->id. */
    public function saveOrder($id, array $post): bool
    {
        $data = [
            'number'    => trim($post['number'] ?? ''),
            'customer'  => trim($post['customer'] ?? ''),
            'email'     => trim($post['email'] ?? ''),
            'phone'     => trim($post['phone'] ?? ''),
            'address'   => trim($post['address'] ?? ''),
            'device'    => trim($post['device'] ?? ''),
            'service'   => trim($post['service'] ?? ''),
            'estimated' => trim($post['estimated'] ?? ''),
            'status'    => array_key_exists($post['status'] ?? '', self::statuses()) ? $post['status'] : 'new',
            'note'      => trim($post['note'] ?? ''),
            'publish'   => ! empty($post['publish']) ? 1 : 0,
            'edited_at' => date('Y-m-d H:i:s'),
        ];

        $this->db->transStart();
        if (! empty($id)) {
            $this->set($data)->where('id', $id)->update();
            $this->id = $id;
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->insert($data);
            $this->id = $this->getInsertID();
        }

        // Etapy: nadpisujemy komplet (prosto i przewidywalnie).
        $this->db->table('isense_order_step')->where('id_order', $this->id)->delete();
        $steps = $post['steps'] ?? [];
        $order = 0;
        foreach ($steps as $s) {
            $name = trim($s['name'] ?? '');
            if ($name === '') {
                continue; // pusty wiersz pomijamy
            }
            $this->db->table('isense_order_step')->insert([
                'id_order' => $this->id,
                'order'    => $order++,
                'icon'     => trim($s['icon'] ?? 'package'),
                'name'     => $name,
                'date'     => trim($s['date'] ?? ''),
                'state'    => in_array($s['state'] ?? '', ['done', 'current', 'todo'], true) ? $s['state'] : 'todo',
            ]);
        }
        $this->db->transComplete();

        return $this->db->transStatus();
    }

    public function deleteOrder($id): bool
    {
        if (empty($id)) {
            return false;
        }
        $this->db->transStart();
        $this->db->table('isense_order_step')->where('id_order', $id)->delete();
        $this->where('id', $id)->delete();
        $this->db->transComplete();

        return $this->db->transStatus();
    }
}
