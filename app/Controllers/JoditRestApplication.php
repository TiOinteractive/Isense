<?php
namespace App\Controllers;

use Jodit\Application;

class JoditRestApplication extends Application {
    /**
     * @example Jodit connector in Joomla
     * ```php
     * function checkPermissions() {
     *      $user = JFactory::getUser();
     *      if (!$user->id) {
     *           trigger_error('You are not authorized!', E_USER_WARNING);
     *      }
     * }
     * ```
     */
    function checkAuthentication() {
        if (empty($_SESSION['filebrowser'])) {
            trigger_error('You do not have permission to view this directory', E_USER_WARNING);
        }

        //throw new \ErrorException('You need override `checkAuthentication` method in file `Application.php` more https://github.com/xdan/jodit-connectors#configuration', 501);
    }
}