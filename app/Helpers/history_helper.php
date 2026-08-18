<?php

if (!function_exists("HistoryStat")) {

    function HistoryStat($id_element, $id_page, $element, $module, $action) {
        $db = \Config\Database::connect();
        $data['id_element'] = $id_element;
        $data['id_page'] = $id_page;
        $data['element'] = $element;
        $data['module'] = $module;
        $data['action'] = $action;
        $data['admin'] = $_SESSION['id'];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $data['ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else
            $data['ip'] = $_SERVER['REMOTE_ADDR'];
        $db->table('history_changes')->insert($data);
    }

}
?>