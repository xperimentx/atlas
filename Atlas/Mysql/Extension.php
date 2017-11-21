<?php

/**
 * Atlas Toolkit
 *
 * @link  https://github.com/xperimentx/atlas
 * @link  https://xperimentX.com
 *
 * @copyright 2017 Roberto González Vázquez
 */

namespace Atlas\Mysql;

use Atlas;
use Atlas\Db\Mysql;


/**
 * MySQL extension
 * @author Roberto González Vázquez
 */
abstract class Extension
{
    /** @var Mysql  Mysql object    */
    protected $db          = NULL;

    /**
     * @param Mysql $db_mysqli_object
     */
    function __construct($db_mysqli_object=null)
    {
        $this->db = $db_mysqli_object ?? Atlas::$db;
    }
}

