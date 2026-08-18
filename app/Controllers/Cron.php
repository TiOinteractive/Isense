<?php

namespace App\Controllers;

use App\Models\ModuleModel;
use CodeIgniter\Controller;

class Cron extends Controller {

    public function index($module, $command) {
        if (!empty($module) && !empty($command)) {
            $moduleModel = new ModuleModel();
            $info = $moduleModel->db->table('cron_job')->select('id,limit_job,interval_sec,from_time,to_time,next_job')->Where('active', 1)->Where('module', $module)->Where('command', $command)->get()->getRowArray();

            if (!empty($info['id'])) {
                $class = 'Modules\\' . ucfirst($module) . '\\Libraries\\CronJob';
                if (is_dir(ROOTPATH . 'modules/' . ucfirst($module)) && class_exists($class)) {
                    $module = new $class();
                    if (strtotime($info['from_time']) <= strtotime(date("H:i:s")) and strtotime($info['to_time']) >= strtotime(date("H:i:s"))) {
                        if ($info['limit_job'] == 1) {
                            $this->makeCron($module, $command, $info);
                            if ((!empty($info['next_job']) and strtotime(date("Y-m-d H:i:s")) > strtotime($info['next_job'])) or empty($info['next_job'])) {
                                //$result = $moduleModel->db->table('cron_job')->set(array('next_job'=>date("Y-m-d",strtotime('+1days')).' '.$info['from_time']))->Where('id',$info['id'])->update(); 
                            }
                        } elseif ((!empty($info['next_job']) and strtotime(date("Y-m-d H:i:s")) > strtotime($info['next_job'])) or empty($info['next_job'])) {
                            $endtime = date("Y-m-d") . ' ' . $info['to_time'];
                            $next_job_time = date("Y-m-d H:i:s", strtotime('+' . $info['interval_sec'] . 'seconds'));
                            $this->makeCron($module, $command, $info);
                            if (strtotime($next_job_time) <= strtotime($endtime)) {
                                //$result = $moduleModel->db->table('cron_job')->set(array('next_job'=>$next_job_time))->Where('id',$info['id'])->update(); 
                            } else {
                                //$result = $moduleModel->db->table('cron_job')->set(array('next_job'=>date("Y-m-d",strtotime('+1days')).' '.$info['from_time']))->Where('id',$info['id'])->update(); 
                            }
                        }
                    }
                }
            }
        }
    }

    private function makeCron($module, $command, $info) {
        $moduleModel = new ModuleModel();
        if ($module->$command() == true) {
            $result = $moduleModel->db->table('cron_job')->set(array('last_job' => date("Y-m-d H:i:s")))->Where('id', $info['id'])->update();
        }
    }

}
