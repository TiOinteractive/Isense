<?php

namespace Modules\Users\Controllers;
use App\Controllers\BaseController;
use Modules\Users\Models\UsersModel;
use App\Libraries\Breadcrumb;
use App\Libraries\Link;


class UsersAdmin extends BaseController
{
    public function __construct()
    {   
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->usersModel = new UsersModel();
    }
    
    public function index($action='', $id=0) 
    {
	  helper('text');	
	  $this->breadcrumb = new Breadcrumb();	
	  $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
     
	  switch ($action) {
		  
		   default:
			$flashdata = $this->session->getFlashdata('users');
			$this->breadcrumb->add(lang('Users.UsersList'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/users');
			$breadcrumb = $this->breadcrumb->render();
			$get = $this->request->getGet();
			$on_page_list = array(20 => 20,40 => 40,80 => 80);
		   	 $query = $this->usersModel->select('mail,name,surname,nick,active,city,phone,created_at,id');
		 if(!empty($get)) {
                    foreach($get as $name=>$value) {
                        switch($name) {
                            case 'name': 
                                if(!empty($value)) {
                                    $query->like('mail', $value);
									$query->orlike('name', $value);
									$query->orlike('nick', $value);
									$query->orlike('surname', $value);
                                }
                                break;
                        }
                    }
        }
				if (empty($get['order'])) {
					$get['order']='id,desc';
                }
                switch ($get['order']) {
                    case 'date,asc': $query->orderBy('created_at', 'ASC');
                        break;
                    case 'date,desc': $query->orderBy('created_at', 'DESC');
                        break;
                    case 'mail,asc': $query->orderBy('mail', 'ASC');
                        break;
                    case 'mail,desc': $query->orderBy('mail', 'DESC');
                        break;						
                     case 'name,asc': $query->orderBy('surname', 'ASC')->orderBy('name', 'ASC');
                        break;
                    case 'name,desc': $query->orderBy('surname', 'DESC')->orderBy('name', 'DESC');
                        break;
					case 'nick,asc': $query->orderBy('nick', 'ASC');
                        break;
                    case 'nick,desc': $query->orderBy('nick', 'DESC');
                        break;	
					case 'city,asc': $query->orderBy('city', 'ASC');
                        break;
                    case 'city,desc': $query->orderBy('city', 'DESC');
                        break;
					case 'phone,asc': $query->orderBy('phone', 'ASC');
                        break;
                    case 'phone,desc': $query->orderBy('phone', 'DESC');
                        break;
                    case 'newsletter,asc': $query->orderBy('newsletter', 'ASC');
                        break;
                    case 'newsletter,desc': $query->orderBy('newsletter', 'DESC');
                    break; 	
					case 'active,asc': $query->orderBy('active', 'ASC');
                        break;
                    case 'active,desc': $query->orderBy('active', 'DESC');
                    break; 						
          }
		 $users_list = $query->paginate(!empty($get['on_page']) ? $get['on_page'] : 20);
		 echo view('Modules\Users\Views\admin\users_list', array('action' => $action,'users_list'=>$users_list ,'breadcrumbs' => $breadcrumb, 'filters' => $get, 'on_page_list' => $on_page_list, 'pager' => $this->usersModel->pager));
		   
		   
		   break;
	  }
	 
	 
		
	}
	
	public function ajax($action = '', $id = 0) {
        if (!empty($action)) {
	         switch ($action) {
				case 'newsletteruser':
                    return $this->newsletterUser($id);
                break;
				case 'activeuser':
                    return $this->ActiveUser($id);
                break;
				case 'deleteuser':
				   return $this->deleteUser($id);
				break;
		}
	}	
	}
	
	private function deleteUser($id) 
    {
        $result = $this->usersModel->deleteUser($id);
        return $this->response->setJSON(array(
            'status' => true,
            'result' => $result,
            'id' => $id,
            'msg' => $result ? lang('Users.Removed') : lang('Users.DeleteError')
        ));
		HistoryStat($id,'','users','Users',$result ? lang('Users.Removed') : lang('Users.DeleteError'));
    }
	
		 private function newsletterUser($id) {
        $parameter = $this->usersModel->select('id,newsletter')->where('id', $id)->first();
        if (!empty($parameter)) {
            $r = $this->usersModel->where('id', $id)->set('newsletter', $parameter['newsletter'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $parameter['newsletter'] ? 0 : 1,
                'msg' => $parameter['newsletter'] ? lang('Users.NewsletterOff') : lang('Users.NewsletterOn')
            );
            HistoryStat($id, '', 'users', 'Users', $parameter['newsletter'] ? lang('Users.NewsletterOff') : lang('Users.NewsletterOn'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $parameter['newsletter'],
                'msg' => lang('Users.NewsletterError')
            );
        }
        return $this->response->setJSON($response);
    }
	
	 private function activeUser($id) {
        $parameter = $this->usersModel->select('id,active')->where('id', $id)->first();
        if (!empty($parameter)) {
            $r = $this->usersModel->where('id', $id)->set('active', $parameter['active'] ? 0 : 1)->update();
            $response = array(
                'status' => $r,
                'publish' => $parameter['active'] ? 0 : 1,
                'msg' => $parameter['active'] ? lang('Users.ActiveOff') : lang('Users.ActiveOn')
            );
            HistoryStat($id, '', 'users', 'Users', $parameter['active'] ? lang('Users.ActiveOff') : lang('Users.ActiveOn'));
        } else {
            $response = array(
                'status' => true,
                'publish' => $parameter['active'],
                'msg' => lang('Users.ActiveError')
            );
        }
        return $this->response->setJSON($response);
    }
	
	
	
	
}	