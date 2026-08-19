<?php

namespace Modules\Pricing\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Breadcrumb;
use Modules\Pricing\Models\PricingCategoryModel;
use Modules\Pricing\Models\PricingModelModel;
use Modules\Pricing\Models\PricingServiceModel;

/**
 * Panel: moduł Cennik.
 *
 * Trzy poziomy: kategoria (iPhone, iPad, ...) → usługa (wymiana wyświetlacza, ...) → model
 * (iPhone 15 Pro + cena, czas realizacji, gwarancja). Na każdym poziomie kolejność ustawia się
 * przeciąganiem wiersza listy.
 *
 * Adresy (dispatch przez App\Controllers\Admin::index → index($action, $id, $id2)):
 *   /pricing                              lista kategorii
 *   /pricing/category-add                 nowa kategoria
 *   /pricing/category-edit/{id}           edycja kategorii
 *   /pricing/category/{id}                usługi w kategorii
 *   /pricing/service-add/{id_cat}         nowa usługa
 *   /pricing/service-edit/{id_cat}/{id}   edycja usługi
 *   /pricing/service/{id}                 modele w usłudze
 *   /pricing/model-add/{id_service}       nowy model
 *   /pricing/model-edit/{id_service}/{id} edycja modelu
 */
class PricingAdmin extends BaseController
{
    // Ustawiane z zewnątrz przez App\Controllers\Admin po utworzeniu instancji.
    public $id_lang   = 1;
    public $locale    = '';
    public $languages = [];

    protected $session;
    protected $breadcrumb;
    protected PricingCategoryModel $categoryModel;
    protected PricingServiceModel $serviceModel;
    protected PricingModelModel $modelModel;

    public function __construct()
    {
        $this->request  = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session  = \Config\Services::session();

        $this->categoryModel = new PricingCategoryModel();
        $this->serviceModel  = new PricingServiceModel();
        $this->modelModel    = new PricingModelModel();
    }

    /** Adres w panelu z uwzględnieniem locale i slugu panelu. */
    private function url(string $path = ''): string
    {
        return ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . ($path !== '' ? '/' . $path : '');
    }

    public function index($action = '', $id = 0, $id2 = 0)
    {
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->add('Home', $this->url());
        $this->breadcrumb->add(lang('Pricing.Pricing'), $this->url('pricing'));

        switch ($action) {
            case 'category-add':
            case 'category-edit':
            case 'category-save':
                return $this->categoryForm($action, (int) $id);

            case 'category':
                return $this->serviceList((int) $id);

            case 'service-add':
            case 'service-edit':
            case 'service-save':
                return $this->serviceForm($action, (int) $id, (int) $id2);

            case 'service':
                return $this->modelList((int) $id);

            case 'model-add':
            case 'model-edit':
            case 'model-save':
                return $this->modelForm($action, (int) $id, (int) $id2);

            case 'model-import':
                return $this->modelImport((int) $id);

            default:
                return $this->categoryList();
        }
    }

    public function assets($action = '')
    {
        return ['js' => [], 'css' => [], 'css_footer' => []];
    }

    /**
     * Konfiguracja bloku cennika przypiętego do strony (Strony → Treść).
     * Wywoływane przez App\Controllers\Admin; $slug to slug elementu modułu.
     */
    public function pageContent($id_content, $slug = '')
    {
        helper('filesystem');

        return [
            'categories'   => $this->categoryModel->getForSelect($this->id_lang, true),
            'pc_templates' => get_templates_by_dir('modules/Pricing/Views/user/pricing'),
            'form_view'    => 'Modules\Pricing\Views\admin\pricing_config',
        ];
    }

    // ─────────────────────────────── Kategorie ───────────────────────────────

    private function categoryList()
    {
        $get = $this->request->getGet();
        if (empty($get['order'])) {
            $get['order'] = 'order,asc';
        }
        $get['order_array'] = $this->orderArray($get['order']);

        echo view('Modules\Pricing\Views\admin\category_list', [
            'categories'   => $this->categoryModel->getListForAdmin($get, $this->id_lang),
            'filters'      => $get,
            'order_list'   => $this->orderList(),
            'on_page_list' => $this->onPageList(),
            'pager'        => $this->categoryModel->pager,
            'flashdata'    => $this->session->getFlashdata('pricing'),
            'locale'       => $this->locale,
            'breadcrumbs'  => $this->breadcrumb->render(),
        ]);

        return null;
    }

    private function categoryForm(string $action, int $id)
    {
        $category = $id ? $this->categoryModel->getById($id, $this->id_lang) : [];
        if ($id && empty($category)) {
            return redirect()->to($this->url('pricing'));
        }
        $flashdata = [];
        $post      = $this->request->getPost();

        if (! empty($post)) {
            $errors = $this->validateLangName($post, 'Pricing.CategoryNameError');
            $result = empty($errors) ? $this->categoryModel->saveCategory($id, $post) : false;

            if ($result) {
                $this->session->setFlashdata('pricing', [
                    'status' => true,
                    'msg'    => ($id ? lang('Pricing.CategoryEditSuccess') : lang('Pricing.CategoryAddSuccess')) . '!',
                ]);

                return redirect()->to($this->url('pricing/category/' . $this->categoryModel->id));
            }
            $flashdata = [
                'status' => false,
                'msg'    => ($id ? lang('Pricing.CategoryEditError') : lang('Pricing.CategoryAddError')) . '!',
                'list'   => $errors,
            ];
            $category       = $post;
            $category['id'] = $id;
        } else {
            $flashdata = $this->session->getFlashdata('pricing');
        }

        if ($id) {
            $this->breadcrumb->add(lang('Pricing.CategoryEdit') . (! empty($category['name']) ? ': ' . $category['name'] : ''), $this->url('pricing/category-edit/' . $id));
        } else {
            $this->breadcrumb->add(lang('Pricing.CategoryAdd'), $this->url('pricing/category-add'));
        }

        echo view('Modules\Pricing\Views\admin\category_add', [
            'action'      => $action === 'category-edit' ? 'category-edit' : 'category-add',
            'category'    => $category,
            'languages'   => $this->languages,
            'flashdata'   => $flashdata,
            'locale'      => $this->locale,
            'breadcrumbs' => $this->breadcrumb->render(),
        ]);

        return null;
    }

    // ──────────────────────────────── Usługi ─────────────────────────────────

    private function serviceList(int $id_category)
    {
        $category = $this->categoryModel->getById($id_category, $this->id_lang);
        if (empty($category)) {
            return redirect()->to($this->url('pricing'));
        }

        $get = $this->request->getGet();
        if (empty($get['order'])) {
            $get['order'] = 'order,asc';
        }
        $get['order_array'] = $this->orderArray($get['order']);

        $this->breadcrumb->add(lang('Pricing.Category') . ': ' . $category['name'], $this->url('pricing/category/' . $id_category));

        echo view('Modules\Pricing\Views\admin\service_list', [
            'category'     => $category,
            'services'     => $this->serviceModel->getListForAdmin($id_category, $get, $this->id_lang),
            'filters'      => $get,
            'order_list'   => $this->orderList(),
            'on_page_list' => $this->onPageList(),
            'pager'        => $this->serviceModel->pager,
            'flashdata'    => $this->session->getFlashdata('pricing'),
            'locale'       => $this->locale,
            'breadcrumbs'  => $this->breadcrumb->render(),
        ]);

        return null;
    }

    private function serviceForm(string $action, int $id_category, int $id)
    {
        $category = $this->categoryModel->getById($id_category, $this->id_lang);
        if (empty($category)) {
            return redirect()->to($this->url('pricing'));
        }
        $this->breadcrumb->add(lang('Pricing.Category') . ': ' . $category['name'], $this->url('pricing/category/' . $id_category));

        $service = $id ? $this->serviceModel->getById($id, $this->id_lang) : [];
        if ($id && empty($service)) {
            return redirect()->to($this->url('pricing/category/' . $id_category));
        }
        $flashdata = [];
        $post      = $this->request->getPost();

        if (! empty($post)) {
            $errors = $this->validateLangName($post, 'Pricing.ServiceNameError');
            $result = empty($errors) ? $this->serviceModel->saveService($id, $id_category, $post) : false;

            if ($result) {
                $this->session->setFlashdata('pricing', [
                    'status' => true,
                    'msg'    => ($id ? lang('Pricing.ServiceEditSuccess') : lang('Pricing.ServiceAddSuccess')) . '!',
                ]);

                return redirect()->to($this->url('pricing/service/' . $this->serviceModel->id));
            }
            $flashdata = [
                'status' => false,
                'msg'    => ($id ? lang('Pricing.ServiceEditError') : lang('Pricing.ServiceAddError')) . '!',
                'list'   => $errors,
            ];
            $service       = $post;
            $service['id'] = $id;
        } else {
            $flashdata = $this->session->getFlashdata('pricing');
        }

        if ($id) {
            $this->breadcrumb->add(lang('Pricing.ServiceEdit') . (! empty($service['name']) ? ': ' . $service['name'] : ''), $this->url('pricing/service-edit/' . $id_category . '/' . $id));
        } else {
            $this->breadcrumb->add(lang('Pricing.ServiceAdd'), $this->url('pricing/service-add/' . $id_category));
        }

        echo view('Modules\Pricing\Views\admin\service_add', [
            'action'      => $action === 'service-edit' ? 'service-edit' : 'service-add',
            'category'    => $category,
            'service'     => $service,
            'languages'   => $this->languages,
            'flashdata'   => $flashdata,
            'locale'      => $this->locale,
            'breadcrumbs' => $this->breadcrumb->render(),
        ]);

        return null;
    }

    // ──────────────────────────────── Modele ─────────────────────────────────

    private function modelList(int $id_service)
    {
        $service = $this->serviceModel->getWithCategory($id_service, $this->id_lang);
        if (empty($service)) {
            return redirect()->to($this->url('pricing'));
        }

        $get = $this->request->getGet();
        if (empty($get['order'])) {
            $get['order'] = 'order,asc';
        }
        $get['order_array'] = $this->orderArray($get['order']);

        $this->breadcrumb->add(lang('Pricing.Category') . ': ' . $service['category_name'], $this->url('pricing/category/' . $service['id_category']));
        $this->breadcrumb->add(lang('Pricing.Service') . ': ' . $service['name'], $this->url('pricing/service/' . $id_service));

        echo view('Modules\Pricing\Views\admin\model_list', [
            'service'      => $service,
            'models'       => $this->modelModel->getListForAdmin($id_service, $get, $this->id_lang),
            'filters'      => $get,
            'order_list'   => $this->orderList(),
            'on_page_list' => $this->onPageList(),
            'pager'        => $this->modelModel->pager,
            'flashdata'    => $this->session->getFlashdata('pricing'),
            'locale'       => $this->locale,
            'breadcrumbs'  => $this->breadcrumb->render(),
        ]);

        return null;
    }

    private function modelForm(string $action, int $id_service, int $id)
    {
        $service = $this->serviceModel->getWithCategory($id_service, $this->id_lang);
        if (empty($service)) {
            return redirect()->to($this->url('pricing'));
        }
        $this->breadcrumb->add(lang('Pricing.Category') . ': ' . $service['category_name'], $this->url('pricing/category/' . $service['id_category']));
        $this->breadcrumb->add(lang('Pricing.Service') . ': ' . $service['name'], $this->url('pricing/service/' . $id_service));

        $model = $id ? $this->modelModel->getById($id, $this->id_lang) : [];
        if ($id && empty($model)) {
            return redirect()->to($this->url('pricing/service/' . $id_service));
        }
        $flashdata = [];
        $post      = $this->request->getPost();

        if (! empty($post)) {
            $errors = $this->validateLangName($post, 'Pricing.ModelNameError');
            $result = empty($errors) ? $this->modelModel->saveModel($id, $id_service, $post) : false;

            if ($result) {
                $this->session->setFlashdata('pricing', [
                    'status' => true,
                    'msg'    => ($id ? lang('Pricing.ModelEditSuccess') : lang('Pricing.ModelAddSuccess')) . '!',
                ]);

                return redirect()->to($this->url('pricing/service/' . $id_service));
            }
            $flashdata = [
                'status' => false,
                'msg'    => ($id ? lang('Pricing.ModelEditError') : lang('Pricing.ModelAddError')) . '!',
                'list'   => $errors,
            ];
            $model       = $post;
            $model['id'] = $id;
        } else {
            $flashdata = $this->session->getFlashdata('pricing');
        }

        if ($id) {
            $this->breadcrumb->add(lang('Pricing.ModelEdit') . (! empty($model['name']) ? ': ' . $model['name'] : ''), $this->url('pricing/model-edit/' . $id_service . '/' . $id));
        } else {
            $this->breadcrumb->add(lang('Pricing.ModelAdd'), $this->url('pricing/model-add/' . $id_service));
        }

        echo view('Modules\Pricing\Views\admin\model_add', [
            'action'      => $action === 'model-edit' ? 'model-edit' : 'model-add',
            'service'     => $service,
            'model'       => $model,
            'languages'   => $this->languages,
            'flashdata'   => $flashdata,
            'locale'      => $this->locale,
            'breadcrumbs' => $this->breadcrumb->render(),
        ]);

        return null;
    }

    /** Szybkie dodawanie modeli z pola tekstowego (jedna linia = jeden model). */
    private function modelImport(int $id_service)
    {
        $service = $this->serviceModel->getWithCategory($id_service, $this->id_lang);
        if (empty($service)) {
            return redirect()->to($this->url('pricing'));
        }

        $text  = (string) $this->request->getPost('models_text');
        $added = trim($text) !== '' ? $this->modelModel->importModels($id_service, $text, $this->languages) : 0;

        $this->session->setFlashdata('pricing', [
            'status' => $added > 0,
            'msg'    => $added > 0 ? str_replace('{no}', (string) $added, lang('Pricing.ImportSuccess')) : lang('Pricing.ImportEmpty'),
        ]);

        return redirect()->to($this->url('pricing/service/' . $id_service));
    }

    // ───────────────────────────────── AJAX ──────────────────────────────────

    public function ajax($action = '', $id = 0, $id2 = 0)
    {
        switch ($action) {
            case 'category-order':
                return $this->changeOrder($this->categoryModel);
            case 'category-publish':
                return $this->changePublish($this->categoryModel, (int) $id);
            case 'category-delete':
                return $this->remove($this->categoryModel->deleteCategory((int) $id), (int) $id);

            case 'service-order':
                return $this->changeOrder($this->serviceModel, ['id_category' => (int) $id]);
            case 'service-publish':
                return $this->changePublish($this->serviceModel, (int) $id);
            case 'service-delete':
                return $this->remove($this->serviceModel->deleteService((int) $id), (int) $id);

            case 'model-order':
                return $this->changeOrder($this->modelModel, ['id_service' => (int) $id]);
            case 'model-publish':
                return $this->changePublish($this->modelModel, (int) $id);
            case 'model-delete':
                return $this->remove($this->modelModel->deleteModel((int) $id), (int) $id);
        }

        return null;
    }

    private function changeOrder($model, array $where = [])
    {
        $post    = $this->request->getPost();
        $old_pos = isset($post['old_pos']) ? (int) $post['old_pos'] : 0;
        $new_pos = isset($post['new_pos']) ? (int) $post['new_pos'] : 0;
        $result  = $old_pos && $new_pos ? $model->moveOrder($old_pos, $new_pos, $where) : false;

        return $this->response->setJSON([
            'status'  => true,
            'result'  => $result,
            'msg'     => $result ? lang('Pricing.OrderChanged') : lang('Pricing.Error'),
            'old_pos' => $old_pos,
            'new_pos' => $new_pos,
        ]);
    }

    private function changePublish($model, int $id)
    {
        $row = $model->select('id,publish')->where('id', $id)->first();
        if (empty($row)) {
            return $this->response->setJSON(['status' => false, 'publish' => 0, 'msg' => lang('Pricing.Error')]);
        }
        $publish = $row['publish'] ? 0 : 1;
        $result  = $model->where('id', $id)->set('publish', $publish)->update();

        return $this->response->setJSON([
            'status'  => (bool) $result,
            'publish' => $publish,
            'msg'     => $publish ? lang('Pricing.Published') : lang('Pricing.Republished'),
        ]);
    }

    private function remove(bool $result, int $id)
    {
        return $this->response->setJSON([
            'status' => true,
            'result' => $result,
            'id'     => $id,
            'msg'    => $result ? lang('Pricing.Removed') : lang('Pricing.Error'),
        ]);
    }

    // ──────────────────────────────── Pomocnicze ─────────────────────────────

    /** Nazwa jest wymagana w każdym języku — reszta pól cennika może zostać pusta. */
    private function validateLangName(array $post, string $error_key): array
    {
        $errors     = [];
        $validation = \Config\Services::validation();

        foreach (($post['lang'] ?? []) as $id_lang => $lang) {
            $validation->reset();
            $lang_name = ! empty($this->languages[$id_lang]) ? '<b>[' . $this->languages[$id_lang]['short_name'] . ']</b> ' : '';
            $validation->setRules([
                'name' => ['rules' => 'required', 'errors' => ['required' => $lang_name . lang($error_key)]],
            ]);
            if (! $validation->run($lang)) {
                $errors[] = array_merge($validation->getErrors());
            }
        }

        return $errors;
    }

    /** Zaznaczenie kierunku sortowania w nagłówku listy. */
    private function orderArray(string $order): array
    {
        $tmp = explode(',', $order);

        return [$tmp[0] => ($tmp[1] ?? 'asc') === 'desc' ? 'desc' : 'asc'];
    }

    private function orderList(): array
    {
        return [
            ['field' => 'order,asc', 'name' => lang('Pricing.sort.OrderAsc')],
            ['field' => 'order,desc', 'name' => lang('Pricing.sort.OrderDesc')],
            ['field' => 'name,asc', 'name' => lang('Pricing.sort.NameAsc')],
            ['field' => 'name,desc', 'name' => lang('Pricing.sort.NameDesc')],
            ['field' => 'publish,asc', 'name' => lang('Pricing.sort.PublishAsc')],
            ['field' => 'publish,desc', 'name' => lang('Pricing.sort.PublishDesc')],
        ];
    }

    private function onPageList(): array
    {
        return [50 => 50, 100 => 100, 200 => 200];
    }
}
