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
    /** @var Migrator_cli_views               */
    protected $views;

    /** @var string|null  Command.            */
    protected $command;

    /** @var int|false    Optional n argument as int.   */
    protected $number;

    /** @var string  e    Optional n argument as string.*/
    protected $opt;


    /**
     * @param Migrator_cfg $cfg
     * @param Db $db Db object. null=> Default db
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
        $this->Parse_arguments_and_set_color_usage();

        $this->views->Show_title();

        $this-> Init_migrator();


        $method_name = 'On_'.$this->command;
        if (method_exists($this, $method_name))
        {
            $this->$method_name();
        }
        else
        {
            if ($this->command)
                $this->views->Show_error ('Incorrect command',null);

            $this->On_status();
            $this->On_help();
        }
    }


    /**
     * Shows an error running Init().
     * @var string $title   Error title
     * @var string|null $details Details
     */
    protected function Show_error($title, $details=null)
    {
        $this->views->Show_error ($title, $details);
    }


    /**
     * Shows a notice running Init().
     * @var string $title   Error title
     * @var string|null $details Details
     */
    protected function  Show_notice ($title, $details=null)
    {
         $this->views->Show_notice ($title, $details);
    }


    protected function Parse_arguments_and_set_color_usage()
    {
        global  $argv;

        $this->command = $argv[1] ?? null;

        if ($this->command==='nocolor')
        {
            $this->views->Deactivate_colors();

            $this->command = $argv[2]??null;
            $this->opt     = $argv[3]??null;
        }
        else
        {
            if (!$this->cfg->use_colors)
                $this->views->Deactivate_colors();

            $this->opt = $argv[2]??null;
        }

        $this->number =  filter_var($this->opt ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range"=>0]]) ;
    }


    protected function On_status()
    {
        $pend = 0;
        $last = 0;

        if ($this->file_titles)
        {
            foreach ($this->file_titles as $idx=>$title)
            {
                if ($idx>$this->status->step)
                {
                    $last = $idx;
                    $pend++;
                }
            }
        }

        $this->views->Show_status
        (
            $this->status,
            $pend,
            $last,
            $this->file_titles[$last] ?? '???'
        );
    }


    protected function On_help()
    {
        $this->views->Show_help();
    }


    protected function On_list ()
    {
        $n=0;

        if     ('new'===$this->opt   ) $n=$this->status->step;
        elseif (false!==$this->number) $n=(int)$this->number;
        elseif ($this->opt)            $this->views->Show_error ('Incorrect step number', null);

        $this->views->List_files($n, $this->file_titles);
    }

    protected function On_log ()
    {
        if ('delete'===$this->opt)
        {
            $num = Log_row::Add ( $this->cfg->db_prefix.'log', $this->db);
            $this->views->Show_notice("$num rows of log deleted", null);
        }
        elseif (!$this->opt)
        {
            $this->views->Show_notice("Last log", null);
            print_r($this->db->Row ("SELECT date, step, status, microseconds, details, exception
                                    FROM `{$this->cfg->db_prefix}log`
                                    ORDER BY id DESC LIMIT 1"));
        }
        elseif (false!==$this->number)
        {
            $this->views->Show_notice("Last $this->number logs", null);
            print_r($this->db->Rows ("SELECT date, step, status, microseconds
                                     FROM `{$this->cfg->db_prefix}log`
                                     ORDER BY id DESC LIMIT $this->number"));
        }
        else
        {
            $this->views->Show_error ('Incorrect option', null);
        }
    }



    protected function On_update ()
    {
        $this->Update_to($this->opt) ;
    }
}

