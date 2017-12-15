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

namespace Xperimentx\Atlas\Tools;

use Xperimentx\Atlas\Cli;
use Xperimentx\Atlas\Db;

/**
 * Active record tool
 * @author Roberto González Vázquez
 */
class Active_record
{
    /** @var Cli Cli tools */
    protected $cli;

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


    function Run()
    {
        global  $argv;

        $table_name = $argv[1] ?? null;
        $error      = '';

        if ($table_name==='nocolor')
        {
            $this->cli->Deactivate_colors ();
            $table_name = $argv[2]??null;
        }

        $tables_aux = $this->db->Show_tables();
        natcasesort($tables_aux);
        $tables = array_values($tables_aux);

        if (is_numeric($table_name))
        {
            if (isset($tables[$table_name]))
            {
                $table_name = $tables[$table_name];
            }
            else
            {
                $table_name = '';
                $error      = "Incorrect table number";
            }
        }
        elseif ($table_name && !in_array($table_name, $tables))
        {
            $table_name = '';
            $error      = "Incorrect table name";
        }


        if (!$table_name)
        {
            $this->Show_title();
            if ($error)
                echo "{$this->cli->fg_white}{$this->cli->bg_red}  $error {$this->cli->reset}\n";

            $this->Show_help();
            $this->Show_tables($tables);
        }
        else echo $this->db->Active_record_class_maker ($table_name, $table_name);
    }




    protected function Show_title()
    {
        $cli = $this->cli;

        echo "{$cli->fg_yellow}\n  xperiment{$cli->fg_light_red}X {$cli->fg_light_purple}atlas {$cli->fg_blue}- {$cli->fg_white}Active Record CLi Tool\n\n{$cli->reset}";
    }


    /**
     * Shows help
     */
    protected function Show_help ()
    {
        global  $argv;
        $cli = $this->cli;

        $vbar       = "{$cli->fg_blue}|{$cli->fg_gray}"; // vertical bar
        $cmd        = "{$cli->fg_white}  ";
        $executable = 'php '.$argv[0];


        echo "{$cli->fg_light_cyan}  Usage:{$cli->fg_yellow}  $executable  [nocolor] [table_name|table_number]

{$cmd}nocolor        $vbar Deactivates color output
{$cmd}[table_name]   $vbar Generates the code for {$cli->fg_green}table_name{$cli->fg_gray} table
{$cmd}[table_number] $vbar Generates the code for {$cli->fg_green}table_number{$cli->fg_gray} table
{$cli->reset}\n";
    }


    protected function Show_tables($tables)
    {
        $cli = $this->cli;

        if (!$tables)
        {
            $cli = $this->cli;
            echo "{$cli->fg_light_cyan}  No tables  {$cli->reset}\n";
            return;
        }

        echo "{$cli->fg_light_cyan}  Tables: {$cli->reset}\n";



        foreach ($tables as $idx=>$table)
        {
            //echo "  $table\n";

            printf("{$cli->fg_yellow}%3d{$cli->fg_gray} %-36s", $idx, $table);
            if (0==($idx%2)) echo "\n";
        }
        echo "{$cli->reset}\n";
    }
}