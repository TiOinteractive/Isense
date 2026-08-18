<?php

namespace Modules\Orders\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Breadcrumb;
use Modules\Orders\Models\OrdersModel;

/**
 * Panel: zlecenia serwisowe iSense (lista, dodawanie, edycja, usuwanie, zmiana statusu).
 * URL: /{ADMIN_PANEL_SLUG}/orders
 */
class OrdersAdmin extends BaseController
{
    protected $ordersModel;
    protected $breadcrumb;

    public function __construct()
    {
        $this->request  = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session  = \Config\Services::session();
        $this->ordersModel = new OrdersModel();
    }

    public function index($action = '', $id = 0, $id2 = 0)
    {
        $base = ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG');
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', $base);
        $this->breadcrumb->add(lang('Orders.OrdersList'), $base . '/orders');

        switch ($action) {
            case 'edit':
                $order = $this->ordersModel->getOrderById($id);
                if (empty($order)) {
                    return redirect()->to($base . '/orders');
                }
                $steps = $this->ordersModel->getSteps($id);
                // brak break — wspólna obsługa z add/save
            case 'add':
            case 'save':
                $order = $order ?? [];
                $steps = $steps ?? [];
                $flashdata = [];
                $post = $this->request->getPost();

                if (! empty($post)) {
                    $errors = [];
                    $validation = \Config\Services::validation();
                    $validation->reset();
                    $validation->setRules([
                        'number' => ['rules' => 'required', 'errors' => ['required' => lang('Orders.NumberError')]],
                    ]);
                    if (! $validation->run($post)) {
                        $errors = $validation->getErrors();
                    }
                    if (empty($errors) && $this->ordersModel->numberTaken($id, $post['number'] ?? '')) {
                        $errors['number'] = lang('Orders.NumberTaken');
                    }
                    $mail = trim($post['email'] ?? '');
                    if (empty($errors) && $mail !== '' && ! filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                        $errors['email'] = lang('Orders.EmailError');
                    }

                    $result = false;
                    if (empty($errors)) {
                        $result = $this->ordersModel->saveOrder($id, $post);
                    }

                    if ($result) {
                        $msg = $id ? lang('Orders.EditSuccess') : lang('Orders.AddSuccess');
                        $this->session->setFlashdata('orders', ['status' => true, 'msg' => $msg . '!']);
                        if (function_exists('HistoryStat')) {
                            HistoryStat($id, '', 'orders', 'Orders', $msg);
                        }
                        return redirect()->to($base . '/orders/edit/' . $this->ordersModel->id);
                    }

                    $flashdata = ['status' => false, 'msg' => lang('Orders.SaveError'), 'list' => $errors];
                    // odtwórz wprowadzone dane
                    $order = $post;
                    $order['id'] = $id;
                    $steps = [];
                    foreach ($post['steps'] ?? [] as $s) {
                        $steps[] = ['icon' => $s['icon'] ?? '', 'name' => $s['name'] ?? '', 'date' => $s['date'] ?? '', 'state' => $s['state'] ?? 'todo'];
                    }
                } else {
                    $flashdata = $this->session->getFlashdata('orders');
                    // Nowe zlecenie: podpowiedz standardowy szkielet osi czasu.
                    if ($action !== 'edit' && empty($steps)) {
                        $steps = [
                            ['icon' => 'package', 'name' => lang('Orders.DefaultStep1'), 'date' => '', 'state' => 'done'],
                            ['icon' => 'search', 'name' => lang('Orders.DefaultStep2'), 'date' => '', 'state' => 'current'],
                            ['icon' => 'wrench', 'name' => lang('Orders.DefaultStep3'), 'date' => '', 'state' => 'todo'],
                            ['icon' => 'check-circle', 'name' => lang('Orders.DefaultStep4'), 'date' => '', 'state' => 'todo'],
                        ];
                    }
                }

                if ($action === 'edit') {
                    $this->breadcrumb->add(lang('Orders.OrderEdit') . (! empty($order['number']) ? ': ' . $order['number'] : ''), $base . '/orders/edit/' . $id);
                } else {
                    $this->breadcrumb->add(lang('Orders.OrderAdd'), $base . '/orders/add');
                }

                echo view('Modules\Orders\Views\admin\add', [
                    'action'      => $action === 'edit' ? 'edit' : 'add',
                    'order'       => $order,
                    'steps'       => $steps,
                    'statuses'    => OrdersModel::statuses(),
                    'icons'       => OrdersModel::icons(),
                    'flashdata'   => $flashdata,
                    'breadcrumbs' => $this->breadcrumb->render(),
                ]);
                break;

            default:
                $get = $this->request->getGet();
                $query = $this->ordersModel->select('id,number,customer,email,phone,device,service,status,estimated,publish,created_at');
                if (! empty($get['q'])) {
                    $q = trim($get['q']);
                    // numer telefonu wyszukujemy też bez spacji/myślników (np. "+48 504 806 905" vs "504806905")
                    $qDigits = preg_replace('/[^0-9]/', '', $q);
                    $query->groupStart()
                        ->like('number', $q)
                        ->orLike('customer', $q)
                        ->orLike('email', $q)
                        ->orLike('phone', $q)
                        ->orLike('address', $q)
                        ->orLike('device', $q)
                        ->orLike('service', $q)
                        ->orLike('note', $q);
                    if (strlen($qDigits) >= 3) {
                        $query->orLike("REPLACE(REPLACE(REPLACE(phone,' ',''),'-',''),'+','')", $qDigits, 'both', null, true);
                    }
                    $query->groupEnd();
                }
                if (! empty($get['status'])) {
                    $query->where('status', $get['status']);
                }

                if (empty($get['order'])) {
                    $get['order'] = 'created_at;desc';
                }
                switch ($get['order']) {
                    case 'created_at;asc':
                        $query->orderBy('created_at', 'ASC')->orderBy('id', 'ASC');
                        break;
                    case 'number;asc':
                        $query->orderBy('number', 'ASC');
                        break;
                    case 'number;desc':
                        $query->orderBy('number', 'DESC');
                        break;
                    case 'status;asc':
                        $query->orderBy('status', 'ASC')->orderBy('created_at', 'DESC');
                        break;
                    default:
                        $query->orderBy('created_at', 'DESC')->orderBy('id', 'DESC');
                        break;
                }

                $perPage = ! empty($get['on_page']) ? (int) $get['on_page'] : 20;
                $orders = $query->paginate($perPage);

                echo view('Modules\Orders\Views\admin\list', [
                    'orders'      => $orders,
                    'total'       => $this->ordersModel->pager ? $this->ordersModel->pager->getTotal() : count($orders),
                    'statuses'    => OrdersModel::statuses(),
                    'filters'     => $get,
                    'pager'       => $this->ordersModel->pager,
                    'order_list'  => [
                        ['field' => 'created_at;desc', 'name' => lang('Orders.sort.DateDesc')],
                        ['field' => 'created_at;asc', 'name' => lang('Orders.sort.DateAsc')],
                        ['field' => 'number;asc', 'name' => lang('Orders.sort.NumberAsc')],
                        ['field' => 'number;desc', 'name' => lang('Orders.sort.NumberDesc')],
                        ['field' => 'status;asc', 'name' => lang('Orders.sort.Status')],
                    ],
                    'on_page_list' => [10 => '10', 20 => '20', 50 => '50', 100 => '100'],
                    'breadcrumbs' => $this->breadcrumb->render(),
                ]);
                break;
        }
    }

    /** Zasoby panelu: repeater etapów osi czasu. */
    public function assets($action = '')
    {
        return ['js' => ['/adm/js/isense-admin.js']];
    }

    public function ajax($action = '', $id = 0)
    {
        switch ($action) {
            case 'delete':
                return $this->deleteOrder($id);
            case 'publish':
                return $this->publishOrder($id);
        }
        return $this->response->setJSON(['status' => false]);
    }

    private function publishOrder($id)
    {
        $order = $this->ordersModel->select('id,publish')->where('id', $id)->first();
        if (empty($order)) {
            return $this->response->setJSON(['status' => false, 'msg' => lang('Orders.Error')]);
        }
        $new = $order['publish'] ? 0 : 1;
        $this->ordersModel->set('publish', $new)->where('id', $id)->update();

        return $this->response->setJSON(['status' => true, 'publish' => $new, 'msg' => $new ? lang('Orders.Published') : lang('Orders.Unpublished')]);
    }

    private function deleteOrder($id)
    {
        $result = $this->ordersModel->deleteOrder($id);
        if (function_exists('HistoryStat')) {
            HistoryStat($id, '', 'orders', 'Orders', $result ? lang('Orders.Removed') : lang('Orders.Error'));
        }

        return $this->response->setJSON(['status' => true, 'result' => $result, 'id' => $id, 'msg' => $result ? lang('Orders.Removed') : lang('Orders.Error')]);
    }
}
