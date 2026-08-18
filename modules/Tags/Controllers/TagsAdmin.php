<?php

namespace Modules\Tags\Controllers;
use App\Controllers\BaseController;
use Modules\Tags\Models\TagsModel;
use App\Libraries\Breadcrumb;


class TagsAdmin extends BaseController
{
    public function __construct()
    {   
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->tagsModel = new TagsModel();
    }
	
	
	  public function ajax($action='', $id=0) 
    {
		switch ($action) {
			case 'searchtags':
			   return $this->searchTag($id);
			break;
			case 'deletetag':
               return $this->deleteTag($id);
            break;	
			case 'edittag':
			$tag=array();
			$tag=$this->tagsModel
                ->select('tag,id')
                ->where('id_lang',$this->id_lang)
				  ->where('id',$id)->get()->getRowArray();			
			$html = view('Modules\Tags\Views\admin\tag_edit', array('tag'=>$tag,'locale' => $this->locale));
                   $response = array(
            'status' => true,
            'html' => base64_encode(urlencode($html))
        );	
		  return $this->response->setJSON($response);
            break; 	
			case 'savetag':
             return $this->saveTag($id,$this->id_lang);
        break; 
			
			
		}
	}
	
	 public function index($action = '', $id_tag = 0, $id = 0) {		 
		$get = $this->request->getGet(); 
		$query = $this->tagsModel->select('tags.id,tag,tags.id_page_cont,pcl.title')
		 ->join('page_content_lang pcl', 'tags.id_page_cont = pcl.id_page_cont','left')
		 ->where('pcl.id_lang',$this->id_lang)
		 ->where('tags.id_lang',$this->id_lang);
		 if (!empty($get)) {
                    foreach ($get as $name => $value) {
                        switch ($name) {
                            case 'name':
                                if (!empty($value)) {
                                    $query->like('tag', $value);
                                }
                                break;
                        }
                    }
                }
		 $this->breadcrumb = new Breadcrumb();	
	     $this->breadcrumb->add('Home', ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG'));
         $this->breadcrumb->add(lang('Tags.Tags'), ($this->locale ? '/' . $this->locale : '') . '/' . env('ADMIN_PANEL_SLUG') . '/tags');  
		 $breadcrumb = $this->breadcrumb->render();
		 $on_page_list = array(20 => 20,40 => 40,80 => 80);
		$tags_list = $query->paginate(!empty($get['on_page']) ? $get['on_page'] : 20); 
		  echo view('Modules\Tags\Views\admin\list',array('TagsList'=>$tags_list,'breadcrumbs' => $breadcrumb, 'filters' => $get, 'on_page_list' => $on_page_list, 'pager' => $this->tagsModel->pager)); 
	 }	 
	
	public function searchTag($id) {
		$get = $this->request->getGet();
		if(!empty($get['term']) and strlen($get['term'])>2) {
			$tagsData = array();
			$lists = $this->tagsModel->db->table('tags')
            ->select('id,tag as value')
            ->like('tag', $get['term'])
            ->get()
            ->getResultArray();
			return $this->response->setJSON($lists);
		}
	}

	private function deleteTag($id) {
			$result = $this->tagsModel->deleteTag($id);
			return $this->response->setJSON(array(
						'status' => true,
						'result' => $result,
						'id' => $id,
						'msg' => $result ? lang('Flavors.TagRemoved') : lang('Flavors.DeleteTagError')
			));
			HistoryStat($id, '', 'tags', 'Tags', $result ? lang('Flavors.TagRemoved') : lang('Flavors.DeleteTagError'));
	}	
	
	public function saveTag($id,$id_lang) {
		 helper('text');
        $post = $this->request->getPost();
		 $r = $this->tagsModel->where('id', $id)->set('tag', $post['tag'])->update();		 
		 $response = array(
                'status' => $r,
                'msg' => $r ? lang('Flavors.TagSaved') : lang('Flavors.TagNotSaved'),
				'all'=>$post['tag'],
				'id'=>$id
            );
		
		return $this->response->setJSON($response);
    }
	
	
	
		
}	