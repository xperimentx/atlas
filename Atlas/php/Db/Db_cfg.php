<?php
/**
 * xperimentX Atlas Toolkit
 *
 * @link      https://github.com/xperimentx/atlas
 * @link      https://xperimentX.com
 *
 * @author    Roberto González Vázquez, https://github.com/xperimentx
 * @copyright 2017 Roberto González Vázquez
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
    /** @var bool   Throw exceptions on mysqli errors. */  public $throw_exceptions = false;


    /**
     * Maker similar to msqli constructor
     * @param type $server     MySQL or MariaDB Server host name or IP address.
     *                         Prepend host by 'p:' for persistent connections .
     * @param type $user_name  User name.
     * @param type $password   Password.
     * @param type $db_name    Database.
     * @param type $port       Port.
     * @param type $socket     Socket.
     *
     * @return \static
     */
    public static function  Basic ( $server, $user_name, $password,
                                    $db_name=null, $port=3306, $socket=null)
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
