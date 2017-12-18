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

/**
 * Stage of the project
 */
class Stage
{
    const PRODUCTION  = 'production';
    const DEVELOPMENT = 'development';
    const TESTING     = 'testing';

    static private $stage   = self::PRODUCTION;


    /**
     * Sets stage, trusted host name and error reporting.
     * @param string $stage             Stage  PRODUCTION, DEVELOPMENT TESTING   = 'testing';
     * @param string $host_name         If not empty sets the host_name
     * @param bool   $report_all_errors If true Indicates to php  report and display all errors.
     */
    private static  function Set(string $stage, string $host_name=null, bool $report_all_errors=true)
    {
        self::$stage = $stage;

        if ($host_name)
            Environment::Set_host ($host_name);

        if ($report_all_errors)
        {
            error_reporting(-1);
            ini_set('display_errors', 1);
        }
    }


    /**
     * Sets environment to DEVELOPMENT stage.
     * $host_name         If not empty sets the host_name
     * @param bool   $report_all_errors If true Indicates to php  report and display all errors.
     */
    public static function Set_development(string $host_name=null, bool $report_all_errors=true)
    {

        self::Set(self::DEVELOPMENT, $host_name, $report_all_errors);
    }


    /**
     * Sets environment to TESTING stage.
     * @param string $host_name         If not empty sets the host_name
     * @param bool   $report_all_errors If true Indicates to php  report and display all errors.
     */
    public static function Set_testing(string $host_name=null, bool $report_all_errors=true)
    {
        self::Set(self::TESTING, $host_name, $report_all_errors);
    }


    /**
     * Sets environment to PRODUCTION stage.
     *
     * For security reasons is recommend set host name  this value in production stage.
     *
     * @param string $host_name         If not empty sets the host_name
     * @param bool   $report_all_errors If true Indicates to php  report and display all errors.
     */
    public static function Set_production(string $host_name=null, bool $report_all_errors=true)
    {
        self::Set(self::PRODUCTION, $host_name, $report_all_errors);
    }


    /**
     * Gets the stage
     * @return string
     */
    public static function Get() :string
    {
        return self::$stage;
    }


    /**
     * Checks is stage is DEVELOPMENT.
     * @return bool
     */
    public static function Is_development() :bool
    {
        return self::DEVELOPMENT === self::$stage;
    }


    /**
     * Checks is stage is TESTING.
     * @return bool
     */
    public static function Is_testing() :bool
    {
        return self::TESTING === self::$stage;
    }


    /**
     * Checks is stage is PRODUCTION.
     * @return bool
     */
    public static function Is_production() :bool
    {
        return self::TESTING === self::$stage;
    }

}
