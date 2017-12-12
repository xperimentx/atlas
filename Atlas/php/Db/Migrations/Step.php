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

namespace Xperimentx\Atlas\Db\Migrations;

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
    function __construct($db=null)
    {
        $this->cli = new Cli();
        $this->db = $db ?? Db::$db;
    }
}
