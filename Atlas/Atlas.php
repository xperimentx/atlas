<?php
/**
 * Atlas Toolkit
 *
 * @link  https://github.com/xperimentx/atlas
 * @link  https://xperimentX.com
 *
 * @copyright 2017 Roberto González Vázquez
 */

use Atlas\Initializer;
use Atlas\Mysql;


include_once __DIR__.'/Cfg.php';
include_once __DIR__.'/Initializer.php';


class Atlas
{
    use Initializer;

    /** @var Mysql   Default database      */  static public $db   = null;
    /** @var string Current language       */  static public $lang = null;
    /** @var string                        */  static public $root_path = null;


    /**
     * Include once if exists a file
     * @param type $file_name from Atlas::$root
     */
    public static function Load_file_if_exists ($file_name)
    {
        if ($file_name  && file_exists(self::$root_path . $file_name))
            include_once self::$root_path . $file_name;
    }


    /**
     * Shows errors and warnings.
     * Helps to develop with display_errors=1, display_startup_errors=1 and error_reporting=E_ALL
     */
    public static function Display_errors()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
}



