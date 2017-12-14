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

namespace Xperimentx\Atlas;

/**
 * Enviroment info
 * @link https://github.com/xperimentx/atlas/blob/master/Atlas/doc/Enviroment.md
 * @author Roberto González Vázquez
 */
class Environment
{
    const STAGE_PRODUCTION  = 'production';
    const STAGE_DEVELOPMENT = 'development';
    const STAGE_TESTING     = 'testing';
    const STAGE_UNKNOW      =  null;

    const VIA_CLI        = 'cli';
    const VIA_HTPX       = 'httpx';
    const VIA_WEB        = 'web';

    static public $via    = null;
    static public $stage = null;

    static public  $host = null;

    public static function Initialize()
    {
        global  $argv;
        if (!self::$via)
        {
            if (isset($argv[0]))       self::$via = self::CLI;
            if (isset($_SERVER['ss'])) self::$via = self::HTPX;
            else                       self::$via = self::WEB;
        }

        if (!self::$stage)
        {
            self::$stage = self::SERVER_DEVELOPMENT;
        }
    }


    public static function Set_stage($stage, $host_name, $report_all_errors)
    {
        self::$stage = self::SERVER_DEVELOPMENT;

        if ($host_name)
            self::$host = $host_name;

        if ($report_all_errors)
        {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        }
    }

    public static function Set_development_stage($report_all_errors=true)
    {
        self::Set_stage(self::STAGE_DEVELOPMENT, null, $report_all_errors);
    }

    public static function Set_testing_stage($report_all_errors=true)
    {
        self::Set_stage(self::STAGE_TESTING, null, $report_all_errors);
    }

    public static function Set_production_stage($host_name=null,$report_all_errors=false)
    {
        self::Set_stage(self::STAGE_TESTING, $host_name, $report_all_errors);
    }
}

