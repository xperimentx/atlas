<?php
/**
 * Atlas Toolkit
 *
 * @link  https://github.com/xperimentx/atlas
 * @link  https://xperimentX.com
 *
 * @copyright 2017 Roberto González Vázquez
 */
namespace Atlas;



/**
 * Atlas main configuration file
 */
class Cfg
{
    /** @var string Main configuration file,
      * give value before calling Atlas::Initialize ()     */ public static $cfg_file             = '/Cfg/Atlas.php';
      
    /** @var string Configuration file for autoloader,
     *  give value before calling Atlas::Initialize ()     */ public static $cfg_file_autoloader  = null;

    /** @var Db\Cfg MySQL or MariaDBcon figuration         */ public static $db                   ;
}

