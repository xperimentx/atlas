<?php
/**
 *  Atlas Toolkit
 *
 * @link  https://github.com/xperimentx/atlas
 * @link  https://xperimentX.com
 *
 * @copyright 2017 Roberto González Vázquez
 */

namespace Xperimentx\Atlas\Db;

/**
 * Error info for Db::$errors and Db::$last_error
 *
 * @author Roberto González Vázquez
 */

class Error_item
{
    /**@var string Method    */  public $method       = NULL;
    /**@var int Error code   */  public $code         = NULL;
    /**@var int Description  */  public $description  = NULL;
    /**@var query            */  public $query        = NULL;

    function __construct($method, $code, $description, $query=null)
    {
        $this->method       = $method      ;
        $this->code         = $code        ;
        $this->description  = $description ;
        $this->query        = $query       ;
    }
}
