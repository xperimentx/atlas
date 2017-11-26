<?php

/**
 *  Atlas Toolkit
 *
 * @link  https://github.com/xperimentx/atlas
 * @link  https://xperimentX.com
 *
 * @copyright 2017 Roberto González Vázquez
 * @license MIT
 */

namespace Xperimentx\Atlas\Db\Migrations;

use Xperimentx\Atlas\Cli;
use Xperimentx\Atlas\Db;

/**
 * Migrator cli
 *
 * Command line version of migrator.
 *
 * @author Roberto González Vázquez
 */
class Migrator_cli extends Migrator
{
    /** @var Cli Cli tools */ protected $cli;

    /**
     * @param Migrator_cfg $cfg
     * @param Db $db
     */
    function __construct($cfg, $db=null)
    {
        parent::__construct($cfg,$db);

        $this->cli = new Cli();

        if (!$cfg->use_colors)
            $this->cli->Deactivate_colors ();
    }

    function Run()
    {
        global  $argv;

        // Only cli is allowed
        $this->cli->Require_cli_environment();

        // Parse arguments
        $command = $argv[1] ?? null;

        if ($command==='nocolor')
        {
            $this->cli->Deactivate_colors();
            $command = $argv[2] ?? null;
            $number  = filter_var($argv[3] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range"=>0]]) ;
        }
        else
        {
            $number  = filter_var($argv[2] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range"=>0]]) ;
        }

        $this->Show_title();

        $this->Route($command, $number);
    }


    /**
     * Routes the order to the responsible method.
     * @param string|null $command Command.
     * @param int|false   $number  Optional second argument .
     */
    protected function Route ($command, $number)
    {
        $cli = $this->cli;

        switch ("$command")
        {
            case '':
            case 'help': $this->Show_help();           break;

            case 'list': $this->List_files($number);  break;

            default: $this->Show_help( "{$cli->fg_light_red}Incorrect command \n\n{$cli->reset}");

        }
    }


    protected function Show_title()
    {
        $cli = $this->cli;

        echo "{$cli->fg_yellow}\nxperiment{$cli->fg_light_red}X {$cli->fg_light_purple}Atlas {$cli->fg_white}Migration Tool\n\n{$cli->reset}";
    }


    /**
     * Shows help
     * @param string $message Optional message
     */
    protected function Show_help ($message='')
    {
        global  $argv;
        $cli = $this->cli;

        $executable = 'php '.$argv[0];
        echo "{$message}{$cli->fg_light_cyan}Usage:            {$cli->fg_yellow}
        $executable  [nocolor] <command> [n: optional int value]
{$cli->fg_white}{$cli->bold}
        nocolor    {$cli->reset}{$cli->fg_blue}|{$cli->fg_gray} Deactivates color output  {$cli->fg_white}

        force  <n> {$cli->reset}{$cli->fg_blue}|{$cli->fg_gray} Sets migration step without execute any migration.{$cli->fg_white}
        help       {$cli->reset}{$cli->fg_blue}|{$cli->fg_gray} Shows this help.                            {$cli->fg_white}
        list       {$cli->reset}{$cli->fg_blue}|{$cli->fg_gray} Lists all avaliable migrations              {$cli->fg_white}
        list   <n> {$cli->reset}{$cli->fg_blue}|{$cli->fg_gray} Lists migrations fron n                     {$cli->fg_white}
        log    <n> {$cli->reset}{$cli->fg_blue}|{$cli->fg_gray} Shows the last n logs.                      {$cli->fg_white}
        status     {$cli->reset}{$cli->fg_blue}|{$cli->fg_gray} Shows the curren estatus of migrations.     {$cli->fg_white}
        update <n> {$cli->reset}{$cli->fg_blue}|{$cli->fg_gray} Upgrades or downgrades migration to n step. {$cli->fg_white}

{$cli->fg_light_cyan}
Examples:                     {$cli->fg_gray}
        $executable status
        $executable update 15
        $executable nocolor update 16
        {$cli->reset}\n";
    }


    protected function List_files ($number)
    {
        $this->Get_migration_files();

        $cli = $this->cli;

        $num_files = 0;
        $out       = '';

        if ($this->files)
        {
            foreach ($this->file_titles as $num=>$value)
                if ($num>=$number)
                {
                    $num_files++;
                    $out.=sprintf("{$cli->fg_light_cyan}%15d {$cli->fg_gray}%s\n", $num, $value);
                }
        }



        $out .= sprintf("\n{$cli->fg_yellow}%15d migration files found.\n", $num_files);

        echo $out;
    }
}

