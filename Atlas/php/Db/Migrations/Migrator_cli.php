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
    /** @var Migrator_cli_views               */ protected $views;
    /** @var string|null  Command.            */ protected $command;
    /** @var int|false    Optional n argument.*/ protected $number;


    /** @var int  Current step                */ protected $current_step=0;




    /**
     * @param Migrator_cfg $cfg
     * @param Db $db
     */
    function __construct($cfg, $db=null)
    {
        $this->views = new Migrator_cli_views();
        parent::__construct($cfg,$db);
    }

    /**
     * Runs the migrator.
     */
    public function Run()
    {
        $this->Parse_arguments_ans_set_color_usage();

        $this->views->Show_title();

        $this->Init_configurator();


        $this->Get_migration_files();


        $method_name = 'On_'.$this->command;
        if (method_exists($this, $method_name))
        {
            $this->$method_name();
        }
        else
        {
            if ($this->command)
                $this->views->Show_error ('Incorrect command');

            $this->On_status();
            $this->On_help();
        }
    }


    /**
     * Shows an error running Init().
     * @var string $msg
     */
    protected function Show_init_error($msg)
    {
        $this->views->Show_error ('Incorrect command');
    }


    /**
     * Shows a notice running Init().
     * @var string $msg
     */
    protected function  Show_init_notice ($msg)
    {
         $this->views->Show_notice ('Incorrect command');
    }


    protected function Parse_arguments_ans_set_color_usage()
    {
        global  $argv;

        $this->command = $argv[1] ?? null;

        if ($this->command==='nocolor')
        {
            $this->views->Deactivate_colors();

            $this->command = $argv[2] ?? null;
            $number  = filter_var($argv[3] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range"=>0]]) ;
        }
        else
        {
            if (!$this->cfg->use_colors)
                $this->views->Deactivate_colors();

            $number  = filter_var($argv[2] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range"=>0]]) ;
        }
    }


    protected function On_list ()
    {
        $this->views->List_files($this->number, $this->file_titles);
    }


    protected function On_listnew ()
    {
        $this->views->List_files(2, $this->file_titles);
    }


    protected function On_status()
    {
        $current_title = 'No current migration step';

        if ($this->current_step)
            $current_title = $this->file_titles[$this->current_step] ?? '???';

        $cu=$last=0;

        foreach ($this->file_titles as $idx=>$value)
        {
            $last = $idx;
            if ($idx>$this->current_step) $cu++;
        }


        $this->views->Show_status
            (
                $this->current_step,
                $current_title,
                $cu,
                $last,
                $this->file_titles[$last] ?? '???'
            );
    }


    protected function On_help()
    {
        $this->views->Show_help();
    }
}

