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
 * Atlas configuration file
 */
class Cfg
{
    /** @var string Main configuration file          , give value before calling Atlas :: Initialize ()     */ public static $cfg_file             = '/Cfg/Atlas.php';
    /** @var string Configuration file for autoloader, give value before calling Atlas :: Initialize ()     */ public static $cfg_file_autoloader  = null;
    /** @var string Use atlas autoloader             , give value before calling Atlas :: Initialize ()     */ public static $cfg_use_autoloader   = true;

    /** @var string MySQL user name.                                                                        */ public static $mysql_user_name      = null;
    /** @var string MySQL password.                                                                         */ public static $mysql_password       = null;
    /** @var string MySQL database.                                                                         */ public static $mysql_db_name        = null;
    /** @var string MySQL host name or an IP address MySQL server.                                          */ public static $mysql_server         = 'localhost';
    /** @var string MySQL port.                                                                             */ public static $mysql_port           = 3306;
    /** @var string MySQL socket.                                                                           */ public static $mysql_socket         = null;
    /** @var string MySQL charset.                                                                          */ public static $mysql_charset        = null;
}
