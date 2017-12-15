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

namespace Xperimentx\Atlas\Db;

/**
 * Exception class for Db operations.
 * @see Get_profile()
 * @author    Roberto González Vázquez,
 */
class Db_exception extends \Exception
{
    /** @var Profile_item */
    protected  $db_profile = null;

    /**
     *
     * @param Profile_item $db_profile
     * @param \Exception $previous
     */
    public function __construct(Profile_item $db_profile, $previous = null)
    {
        parent::__construct($db_profile->error_description, $db_profile->error_code, $previous);

        $this->db_profile = $db_profile;
    }


    /**
     * Return Db error item
     * @return Profile_item
     */
    function Get_profile() :Profile_item
    {
        return $this->db_profile;
    }
}

