<?php

/**
 * Atlas Toolkit
 *
 * @link  https://github.com/xperimentx/atlas
 * @link  https://xperimentX.com
 *
 * @copyright 2017 Roberto González Vázquez
 */

namespace Xperimentx\Atlas\Db;

use Xperimentx\Atlas;
use Xperimentx\Atlas\Db;


/**
 * MySQL extension
 * @author Roberto González Vázquez
 */
abstract class Extension
{
    /** @var Db  Db Mysql object    */
    public $db ;

    /**
     * @param Db $db_object Instance or Db object, null:for default Db.
     */
    function __construct($db_object=null)
    {
        $this->db = $db_object ?? Atlas::$db;
    }
}

