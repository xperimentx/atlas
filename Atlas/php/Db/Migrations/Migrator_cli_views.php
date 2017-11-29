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


    public function Show_error($text)
    {
        $cli = $this->cli;

        echo "{$cli->fg_white}{$cli->bg_red}  $text  {$cli->reset}\n\n";
    }


    public function Show_notice($text)
    {
        $cli = $this->cli;

        echo "{$cli->fg_light_blue}  $text  {$cli->reset}\n\n";
    }


    public function Show_status ($current_idx, $current_title, $num_pending, $last_idx, $last_title )
    {
        $cli = $this->cli;
            echo "{$cli->fg_gray}  Current step :{$cli->fg_light_cyan} $current_idx {$cli->fg_white}- $current_title\n";

        if ($last_idx)
            echo "{$cli->fg_gray}  Last step    :{$cli->fg_light_cyan} $last_idx {$cli->fg_white}- $last_title \n";

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
        $n_opt      = "{$cli->fg_green}[n]";
        $n_req      = "{$cli->fg_green}<n>";

        echo "
{$cli->fg_light_cyan}  Usage:{$cli->fg_yellow}  $executable  [nocolor] <command> [n: optional int value]

{$cmd}nocolor    $vbar Deactivates color output
{$cmd}           $vbar
{$cmd}list $n_opt   $vbar Lists avaliable migrations steps. If $n, from $n step.
{$cmd}listnew    $vbar Lists pending steps, greater than current step.
{$cmd}update $n_req $vbar Upgrades or downgrades migration to $n step.
{$cmd}           $vbar
{$cmd}log $n_opt    $vbar Shows the last $n logs. If not $n, last 10 logs.
{$cmd}logdelete  $vbar Delete log entries.           {$cli->reset}\n";
    }


    public function List_files($number, $file_titles)
    {
        $cli = $this->cli;

        $num_files = 0;
        $out       = '';

        if ($file_titles)
        {
            foreach ($file_titles as $num=>$value)
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

