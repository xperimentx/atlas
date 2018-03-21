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

namespace Xperimentx\Atlas;

namespace Atlas;

/**
 * Role-based security
 *
 * @author Roberto González Vázquez
 */
class Roles
{
    /**
     * Is super.
     * @var bool $is_super
     */
    protected $is_super = false;


    /**
     * Is an admin.
     * @var bool $is_super
     */
    protected $is_admin = false;


    /**
     * Array of roles assigned to the user.
     * @var array $roles
     */
    protected $roles = [];

    /**
     * Roles assigned to the user i csv.
     * @var array $roles
     */
    protected $roles_csv = [];


    /**
     * Cache of checks for Some and None
     * @var type
     */
    protected $rol_cache_some = [];

    /**
     * Cache of checks for All
     * @var type
     */
    private $rol_cache_all   = [];


	/**
     * Is a super
     * @return bool
     */
    public function Is_super()
    {
        return $this->is_super;
    }


	/**
     * Is an admin
     * @return bool
     */
    public function Is_admin()
    {
        return $this->is_admin;
    }



    /**
     * Checks if the user fulfills any of the requested roles or is super.
     * @param  string $roles_csv CSV with allowed roles. If roles are not required, it returns true.
     * @return bool
     */
     function Some (string $roles_csv)
    {
        if (!$roles_csv or $this->is_super)
            return true;

        if (array_key_exists($roles_csv, $this->rol_cache_some))
            return $this->rol_cache_some [$roles_csv];

        $v_roles = explode (',', $roles_csv);


        return $this->rol_cache_some [$roles] = count(array_intersect($v_roles, $this->roles))>0;
    }


    /**
     * Checks if the user complies with all the requested roles or is super.
     * @param  string $roles_csv  CSV with required roles. If roles are not required, it returns true.
     * @return bool
     */
    public function All (string $roles_csv)
    {
        if (!$roles_csv or $this->is_super)
            return true;

        if (array_key_exists($roles_csv, $this->rol_cache_all))
            return $this->rol_cache_all [$roles_csv];

        $v_roles = explode (',', $roles_csv);

        $comun = array_intersect($v_roles, $this->roles);

        return $this->rol_cache_all [$roles] = (count($comun)==count($v_roles));
    }


    /**
     * Checks if the user does not have any role in common.
     * @param  string_csv $roles_csv  CSV with forbidden roles. If roles are not required, it returns true.
     * @return bool
     */
    public function None  (string $roles_csv)
    {
        if (!$roles_csv)         return true;
        return !$this->Some($roles_csv);
    }


    /**
     * Assigns the allowed roles.
     * @param |string $roles_csv Allowed roles i CSV
     */
    public function Set_roles ($roles_csv)
    {
        $this->roles_csv = $roles_csv;
        $this->roles     = explode (',', $roles_csv);

        $this->is_super = in_array('super', $this->roles);
        $this->is_admin = in_array('admin', $this->roles);
    }
}
