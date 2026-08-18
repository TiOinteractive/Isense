<?php

namespace Modules\Flavors\Models;  
use CodeIgniter\Model;
use App\Libraries\Link;
use \App\Validation\CustomRules;



class FlavorsParametersModel extends Model{
	
			protected $table = 'flavors_parameters';
			protected $allowedFields = [
				'publish',
				'edited_at',
				'id',
				'created_at',
			];
	
	    public function checkParametersUnique($field, $id_lang, $action) {
        $data = $this->db->table('flavors_parameters_lang')->where('name', $field)->where('id_lang', $id_lang)->select('id');
        if ($action == "add-parameter") {
            return $data->countAllResults();
        } else {
            return $data->countAllResults() - 1;
        }
    }
	
	    public function saveParameter($id, $post) {
        if (empty($post))
            return false;
        $data = array(
            'publish' => !empty($post['publish']) ? $post['publish'] : 0
        );
        $this->db->transStart();
        if ($id) {
            $result = $this->set($data)->where('id', $id)->update();
            $this->id = $id;
        } else {
            $result = $this->insert($data);
            $this->id = $this->getInsertID();
        }
        $this->saveParameterLang($this->id, $post['lang']);
        $this->db->transComplete();
        return $this->db->transStatus();
    }

	private function saveParameterLang($id_parameter, $lang_data) {
        if (!empty($lang_data)) {
            foreach ($lang_data as $id_lang => $lang) {
                $linkClass = new Link();
                $data = array(
                    'id_parameter' => $id_parameter,
                    'id_lang' => $id_lang,
                    'name' => $lang['name'],
                    'filter_name' => $lang['filter_name']
                );
                $lang = $this->db->table('flavors_parameters_lang')->select('id')->where('id_parameter', $id_parameter)->where('id_lang', $id_lang)->get()->getRowArray();
                if (!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('flavors_parameters_lang')->set($data)->where('id_parameter', $id_parameter)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('flavors_parameters_lang')->insert($data);
                }
            }
        }
    }	
	
	public function getParameterById($id, $id_lang) {
        $parameter = $this->where('id', $id)->first();
        if (!empty($parameter)) {
            $parameter['lang'] = $this->getParameterLang($id);
            if(!empty($parameter['lang']) && !empty($parameter['lang'][$id_lang]) && !empty($parameter['lang'][$id_lang]['name'])) {
                $parameter['name'] = $parameter['lang'][$id_lang]['name'];
            } else {
                $parameter['name'] = '';
            }
			if(!empty($parameter['lang']) && !empty($parameter['lang'][$id_lang]) && !empty($parameter['lang'][$id_lang]['filter_name'])) {
                $parameter['filter_name'] = $parameter['lang'][$id_lang]['filter_name'];
            } else {
                $parameter['filter_name'] = '';
            }
        }
        return $parameter;
    }

    private function getParameterLang($id_parameter) {
        $linkClass = new Link();
        $langs = array();
        $data = $this->db->table('flavors_parameters_lang')->where('id_parameter', $id_parameter)->orderBy('id_lang')->get()->getResultArray();
        if (!empty($data)) {
            foreach ($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
	
	    public function valuesList($id, $id_lang) {
            $values = $this->db->table('flavors_parameters_value')->join('flavors_parameters_value_lang pl', 'flavors_parameters_value.id=pl.id_value')->orderBy('value')->select('flavors_parameters_value.id,value,id_parameter')->where('pl.id_lang', $id_lang)->where('id_parameter', $id)->get()->getResultArray();
        return $values;
    }
	
	public function getValueById($id) {
        $value = $this->db->table('flavors_parameters_value')->where('id', $id)->get()->getRowArray();
        if (!empty($value)) {
            $value['lang'] = $this->getValueLang($id);
        }
        return $value;
    }

    private function getValueLang($id_value) {
        $langs = array();
        $data = $this->db->table('flavors_parameters_value_lang')->where('id_value', $id_value)->orderBy('id_lang')->get()->getResultArray();
        if (!empty($data)) {
            foreach ($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }
	
	  public function checkValuesUnique($field, $id_lang, $id_parameter, $action) {
        $data = $this->db->table('flavors_parameters_value')->join('flavors_parameters_value_lang pl', 'flavors_parameters_value.id=pl.id_value')->where('value', $field)->where('id_lang', $id_lang)->where('id_parameter', $id_parameter)->select('flavors_parameters_value.id');
        if (!empty($_GET['id_value'])) {
            return $data->countAllResults() - 1;
        } else {
            return $data->countAllResults();
        }
    }
	
	
    public function saveValue($id, $post) {
        if (empty($post))
            return false;
        $this->db->transStart();
        $data = array(
            'id_parameter' => $id
        );
        if (!empty($_GET['id_value'])) {
            $this->id = $_GET['id_value'];
        } else {
            $result = $this->db->table('flavors_parameters_value')->insert($data);
            $this->id = $this->db->insertID();
        }
        $this->saveValuesLang($this->id, $post['lang']);
        $this->db->transComplete();
        return $this->db->transStatus();
    }

    private function saveValuesLang($id_value, $lang_data) {
        if (!empty($lang_data)) {
            foreach ($lang_data as $id_lang => $lang) {
                $linkClass = new Link();
                $data = array(
                    'id_value' => $id_value,
                    'id_lang' => $id_lang,
                    'value' => $lang['value']
                );
                $lang = $this->db->table('flavors_parameters_value_lang')->select('id')->where('id_value', $id_value)->where('id_lang', $id_lang)->get()->getRowArray();
                if (!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('flavors_parameters_value_lang')->set($data)->where('id_value', $id_value)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('flavors_parameters_value_lang')->insert($data);
                }
            }
        }
    }
	
	    public function deleteParameter($id) {
        if (empty($id))
            return false;
        $this->db->transStart();
        $this->db->table('flavors_parameters_lang')->where('id_parameter', $id)->delete();
        $this->where('id', $id)->delete();
        $values = $this->db->table('flavors_parameters_value')->select('id')->where('id_parameter', $id)->get()->getResultArray();
        if (!empty($values)) {
            foreach ($values as $val) {
                $this->db->table('flavors_parameters_value_lang')->where('id_value', $val['id'])->delete();
                $this->db->table('flavors_parameters_value')->where('id_parameter', $id)->delete();
            }
        }
        $this->db->transComplete();
        return $this->db->transStatus();
    }
	
	
	public function deleteParameterValue($id) {
        if (empty($id))
            return false;
        $this->db->transStart();
        $this->db->table('flavors_parameters_value_lang')->where('id_value', $id)->delete();
		$this->db->table('flavors_parameters_value')->where('id', $id)->delete();
        $this->db->table('flavors_restaurant_parameters')->where('id_value', $id)->delete();
        $this->db->transComplete();
        return $this->db->transStatus();
    }
	
	function GetParametersList($id_lang) {
		
		 $parameters_list=$this->db->table('flavors_parameters')->join('flavors_parameters_lang pl', 'flavors_parameters.id=pl.id_parameter')->orderBy('name')->select('flavors_parameters.id,name')->where('pl.id_lang', $id_lang)->get()->getResultArray();
		 if(!empty($parameters_list)) {
		    foreach($parameters_list as $k=>$v) {
		       $parameters_list[$k]['values']=$this->valuesList($v['id'],$id_lang);
		    }
		 }
		 return  $parameters_list;
	}	
	
}
?>	