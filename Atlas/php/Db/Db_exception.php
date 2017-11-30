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
 * Exception class for Db operations.
 * @see Get_error_item()
 * @author    Roberto González Vázquez,
 */
class Db_exception extends \Exception
{
    /** @var Error_item */
    protected  $db_error_item = null;

    /**
     *
     * @param Error_item $db_error_item
     * @param type $code
     * @param \Exception $previous
     */
    public function __construct($db_error_item, $previous = null)
    {
        parent::__construct($db_error_item->description, $db_error_item->code, $previous);

        $this->db_error_item = $db_error_item;
    }


    /**
     * Return Db error item
     * @return Error_item
     */
    function Get_error_item()
    {
        return $this->db_error_item;
    }
}

