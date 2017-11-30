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

use Xperimentx\Atlas\Cli;


/**
 * Migrator cli  views.
 *
 * @author Roberto González Vázquez
 */
class Migrator_cli_views
{
    /** @var Cli Cli tools */
    protected $cli;


    function __construct()
    {
        $this->cli = new Cli();
    }


    public function Deactivate_colors()
    {
        $this->cli->Deactivate_colors ();
    }


    public function Show_title()
    {
        $cli = $this->cli;

        echo "{$cli->fg_yellow}\n  xperiment{$cli->fg_light_red}X {$cli->fg_light_purple}atlas {$cli->fg_blue}- {$cli->fg_white}Migrator CLi Tool\n\n{$cli->reset}";
    }

    /**
     * @var string $title   Error title
     * @var string|null $details Details
     */
    public function Show_error($title, $details)
    {
        $cli = $this->cli;

        echo "{$cli->fg_white}{$cli->bg_red}  $title {$cli->reset}\n";

        if ($details)
            echo "{$cli->fg_light_red}sdsdf  {$details}\n";

        echo "{$cli->reset}\n";

    }


    /**
     * @var string $title   Error title
     * @var string|null $details Details
     */
    public function Show_notice($title, $details)
    {
        $cli = $this->cli;

        echo "{$cli->fg_light_blue}  $title  {$cli->reset}\n";

        if ($details)
            echo "{$cli->fg_light_gray}  {$details}\n";

        echo "{$cli->reset}\n";
    }


    /**
     *
     * @param Status_row $status
     * @param int $num_pending
     * @param int $last_step
     * @param string $last_title
     */
    public function Show_status ($status, $num_pending, $last_step, $last_title )
    {
        $cli = $this->cli;
        echo "{$cli->fg_gray}  Current step :{$cli->fg_light_cyan} $status->step"
             ."{$cli->fg_blue}  -{$cli->fg_white}  $status->title"
             ."{$cli->fg_blue}  -{$cli->fg_light_blue}  $status->date_modified\n";

        if ($last_step)
            echo "{$cli->fg_gray}  Last step    :{$cli->fg_light_cyan} $last_step {$cli->fg_white}- $last_title \n";

        if ($num_pending>0)
            echo "{$cli->fg_gray}  Pending steps:{$cli->fg_white} $num_pending steps\n";
        else
            echo "{$cli->fg_green}  No pendding steps\n";

        echo "{$cli->reset}\n";
    }


    /**
     * Shows help
     */
    public function Show_help ()
    {
        global  $argv;
        $cli = $this->cli;

        $vbar       = "{$cli->fg_blue}|{$cli->fg_gray}"; // vertical bar
        $cmd        = "{$cli->fg_white}  ";
        $executable = 'php '.$argv[0];
        $n          = "{$cli->fg_green}n{$cli->fg_gray}";
        $green      = "{$cli->fg_green}";

        echo "
{$cli->fg_light_cyan}  Usage:{$cli->fg_yellow}  $executable  [nocolor] <command> [opcional-value]

{$cmd}nocolor       $vbar Deactivates color output
{$cmd}              $vbar
{$cmd}list   {$green}       $vbar Lists avaliable migrations steps.
{$cmd}list   {$green}new    $vbar Lists pending steps.
{$cmd}list   {$green}<n>    $vbar Lists migrations steps greater than $n.
{$cmd}              $vbar
{$cmd}update {$green}<n>    $vbar Upgrades or downgrades migration to $n step.
{$cmd}update {$green}last   $vbar Upgrades to the last stepd.
{$cmd}              $vbar
{$cmd}log    {$green}<n>    $vbar Shows the $n last logs.
{$cmd}log    {$green}delete $vbar Delete log entries.  {$cli->reset}\n";
    }


    public function List_files($number, $file_titles)
    {
        $cli = $this->cli;

        $num_files = 0;
        $out       = '';

        if ($file_titles)
        {
            foreach ($file_titles as $num=>$value)
                if ($num>$number)
                {
                    $num_files++;
                    $out.=sprintf("{$cli->fg_light_cyan}%15d {$cli->fg_gray}%s\n", $num, $value);
                }
        }

        $out .= sprintf("\n{$cli->fg_yellow}%15d migration files found.\n", $num_files);

        echo $out;
    }
}

