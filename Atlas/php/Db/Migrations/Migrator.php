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
 * Migrator base.
 *
 * @author Roberto González Vázquez
 */
abstract class Migrator
{
    /** @var Db  Migration db object.   */
    protected $db  = null;

    /** @var string[]  Migration files       */
    protected $files = [];

    /** @var string[]  Migration files title */
    protected $file_titles = [];

    /** @var Migrator_cfg  Configuration     */
    protected $cfg;

    /** @var Status Current status       */
    protected $status = null;


    /**
     * @param Migrator_cfg $cfg Configuration.
     * @param Db           $db  Db object. null=> Default db.
     */
    function __construct(Migrator_cfg$cfg, Db $db=null)
    {
        $this->cfg = $cfg;
        $this->db = $db ?? Db::$db;
    }


    /**
     * - Initialize the migrator
     * - Gets the current step an migration files.
     * - Create if needed the migration tables in the database
     */
    protected function Init_migrator()
    {
        $this->Init_db();
        $this->Get_migration_files();
    }


    private function Get_migration_files()
    {
        $vector = glob(rtrim($this->cfg->root,'\\/').'/*.php');

        foreach ($vector as $item)
        {
            $pos_file  = strrpos($item,'/');
            $num       = (int)substr($item, $pos_file+1);

            if ($num>0)
            {
                $this->files       [$num]=$item;
                $this->file_titles [$num]= basename(substr($item, strpos($item,'-', $pos_file)+1),'.php');
            }
        }

        ksort($this->files);
        ksort($this->file_titles);
    }


    private function Init_db()
    {
        $this->db->Throw_exceptions();

        $ko_txt       = '';

        try
        {
            // Create table status if not exists

            $ko_txt = 'Error creating the status table for migrations';

            if (Status::Create_table_if_not_exists($this->cfg->db_prefix, $this->db))
                $this->Show_notice ('Status table for migrations created');


            // Create table log if not exists

            $ko_txt = 'Error creating the status table for migrations';

            if (Log::Create_table_if_not_exists($this->cfg->db_prefix, $this->db))
                $this->Show_notice ('Log table for migrations created');


            // Load current status
            $ko_txt='Unable to load status row';

            $this->status = Status::Load($this->cfg->db_prefix, $this->db);
        }

        catch (Db\Db_exception $ex)
        {
             $this->Show_error($ko_txt, $ex->Get_profile());
             die();
        }

        // We must have the status at this point
        if (!$this->status)
            die();
    }


    /**
     * Shows an error running Init().
     * @var string $title   Error title
     * @var string  $details Details
     */
    abstract protected function Show_error(string $title, string $details='');


    /**
     * Shows a notice running Init().
     * @var string $title   Error title
     * @var string $details Details
     */
    abstract protected function  Show_notice(string $title, string $details='');


    /**
     *
     * @param int|string $option Number or 'last'
     * @return int|null
     */
    private function Update_to_check($option)
    {
        $number = filter_var($option ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range"=>0]]) ;

        if (!$this->file_titles)
        {
            $this->Show_notice ('No step migrations found' );
            return null;
        }

        if ('last'===$option)
        {
            end($this->file_titles);
            $last=key($this->file_titles);

            if ($last === (int)$this->status->step)
            {
                $this->Show_notice  ("The current migration step is already the last: $last");
                return null;
            }
            return $last;
        }

        if (!isset($this->file_titles[$number]) && $number !==0)
        {
            $this->Show_error ('Incorrect step number', null);
            return null;
        }

        if ($number === (int)$this->status->step)
        {
            $this->Show_notice  ("The current migration step is already $number");
            return null;
        }

        return $number;
    }


    protected function Update_to($number_or_last)
    {
        $number = $this->Update_to_check($number_or_last);
        if (null===$number) return;

        $name_space      = trim ($this->cfg->namespace,'\\' ).'\\';
        $step            = 0;

        $error_details   = null;
        $error_exception = null;


        if ($number<$this->status->step)
        {
            $desc        = true;
            $file_titles = array_reverse (['Zero migration']+$this->file_titles, true);
            $direction   = 'DOWN';
        }
        else
        {
            $desc        = false;
            $file_titles = $this->file_titles;
            $direction   = 'UP';
        }

        try
        {
            foreach ($file_titles as $step=>$title)
            {
                if  ( $desc && ($step > $this->status->step)) continue;
                if  (!$desc && ($step <=$this->status->step)) continue;

                $ms = microtime(true);

                if ($step>0)
                {
                    include_once $this->files[$step];
                    $step_class = $name_space. $title;
                    $step_obj   = new $step_class($this->db);

                    if ($desc)
                         $step_obj->Down();
                    else $step_obj->Up();
                }

                $ms = round(microtime(true)-$ms,6);

                Status::Save ($this->cfg->db_prefix, $this->db, $step, $title);
                Log::Add     ($this->cfg->db_prefix, $this->db, $step, $direction, $ms);

                $this->Show_notice(sprintf("Step %3d %s - %.6f s: %s", $step, $direction, $ms, $title));

                if ($number==$step) break;
            }
        }

        catch (Db\Db_exception $ex)
        {
            $error_details   = (string)$ex->Get_profile();
            $error_exception = print_r($ex,true);
        }

        catch (Exception $ex)
        {
            $error_details   = (string)$ex->getMessage();
            $error_exception = print_r($ex,true);
        }


        if ($error_details)
        {
            $this->Show_error("ERROR step $step $direction", $error_details.'\n\n'. $error_exception);

            Log::Add
            (
                $this->cfg->db_prefix  ,
                $this->db,
                $step,
                $direction.'_ERROR',
                1,
                $error_details,
                print_r($ex,true)
            );
        }
    }
}