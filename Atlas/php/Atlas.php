<?php

/**
 * Atlas Toolkit
 *
 * @link  https://github.com/xperimentx/atlas
 * @link  https://xperimentX.com
 *
 * @copyright 2017 Roberto González Vázquez
 */


namespace Xperimentx;
use Xperimentx\Atlas\Cfg;


/**
 * Main atlas class
 */
class Atlas
{
    /** @var Db     Default database       */  static public $db   = null;
    /** @var string                        */  static public $root_path = null;

    ///** @var string Current language       */  static public $lang = null;


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


    /**
     * Initialize Atlas.
     *
     * Load configurations files.
     * Opens main database connection.
     */
    function Initialize()
    {
        //¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨ Configuration files
        self::Load_file_if_exists(Cfg::$cfg_file            );
        self::Load_file_if_exists(Cfg::$cfg_file_autoloader );


        //¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨ Database
        if (Cfg::$db->user_name)
        {
            Atlas::$db = new Db(Db\Cfg::$db);
        }
    }
}
