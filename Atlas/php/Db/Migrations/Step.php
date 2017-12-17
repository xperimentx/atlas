<?php
/**
 * xperimentX atlas php toolkit
 *
 * @link      https://github.com/xperimentx/atlas
 * @link      https://xperimentX.com
 *
 * @author    Roberto González Vázquez, https://github.com/xperimentx
 * @copyright 2017 Roberto González Vázquez
 *
 * @license   MIT
 */

namespace Xperimentx\Atlas\Db\Migrations;

use Xperimentx\Atlas\Db;

/**
 * Migration step base.
 *
 * @author Roberto González Vázquez
 */

abstract class Step
{
    abstract public function Up();

    abstract public function Down();


    /** @var Db  Db object. */
    protected $db  = null;

    /**
     * @param Db $db Db object. null=> Default db.
     */
    function __construct(Db $db=null)
    {
        $this->db = $db ?? Db::Obj();
    }
}
