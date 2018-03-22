<?php
/**
 * xperimentX atlas php toolkit
 *
 * @link      https://github.com/xperimentx/atlas
 * @link      https://xperimentX.com
 *
 * @author    Roberto González Vázquez, https://github.com/xperimentx
 * @copyright 2017 - 2018 Roberto González Vázquez
 *
 * @license   MIT
 */

namespace Xperimentx\Atlas\Db;

/**
 * Database configuration structure
 *
 * @author Roberto González Vázquez
 */

class Db_cfg
{
    /** @var string Db host. 'p:host' for persistent   */  public $server           = 'localhost';
    /** @var string User name.                         */  public $user_name        = null;
    /** @var string Password.                          */  public $password         = null;
    /** @var string Database.                          */  public $db_name          = null;
    /** @var string Port.                              */  public $port             = 3306;
    /** @var string Socket.                            */  public $socket           = null;
    /** @var string Charset.                           */  public $charset          = 'utf8';
    /** @var string Collation.                         */  public $collation        = 'utf8_general_ci';
    /** @var string Engine                             */  public $engine           = 'InnoDB';
    /** @var bool   Throw exceptions on mysqli errors
     *              Do not affect to connect errors.   */  public $throw_exceptions            = false;
    /** @var bool   Throw exceptions on connect errors.*/  public $throw_exceptions_on_connect = false;


    /**
     * Maker similar to msqli constructor
     * @param type $server     MySQL or MariaDB Server host name or IP address.
     *                         Prepend host by 'p:' for persistent connections .
     * @param string $user_name  User name.
     * @param string $password   Password.
     * @param string $db_name    Database.
     * @param int    $port       Port.
     * @param string $socket     Socket.
     *
     * @return \static
     */
    public static function  Basic ( string $server, string $user_name, string $password,
                                    string $db_name=null, int $port=3306, string $socket=null)
    {
        $obj = new static;

        $obj->user_name   = $user_name ;
        $obj->password    = $password  ;
        $obj->db_name     = $db_name   ;
        $obj->server      = $server    ;
        $obj->port        = $port      ;
        $obj->socket      = $socket    ;

        return $obj;
    }
}
