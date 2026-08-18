<?php

namespace Modules\Isense\Controllers;

use App\Controllers\BaseController;
use Modules\Isense\Models\IsenseSectionModel;

/**
 * Panel: edycja sekcji strony głównej iSense.
 *
 * $slug = element_slug (typ sekcji), decyduje który formularz admina renderować
 * (modules/Isense/Views/admin/<slug>.php).
 */
class IsenseAdmin extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->request  = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session  = \Config\Services::session();
        $this->model    = new IsenseSectionModel();
    }

    /** Assety admina (repeater list w formularzach sekcji). */
    public function assets($action = '')
    {
        return ['js' => ['/adm/js/isense-admin.js']];
    }

    public function pageContent($id_content, $slug = '')
    {
        $fields = $this->model->getFieldsAllLang($id_content);
        return [
            'form_data'    => ['lang' => $fields, 'section' => $slug],
            'pc_templates' => [],
            'form_view'    => 'Modules\Isense\Views\admin\\' . $slug,
        ];
    }

    public function savePageContent($id_content, $post)
    {
        $lang = $post['lang'] ?? [];
        $this->model->saveFields($id_content, $lang);
    }

    public function deletePageModule($data)
    {
        $this->model->deleteByContentId($data['id']);
    }
}
