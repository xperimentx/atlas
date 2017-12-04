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
    /** @var Db  Migration main Db object.   */
    protected $db  = null;

    /** @var string[]  Migration files       */
    protected $files = [];

    /** @var string[]  Migration files title */
    protected $file_titles = [];

    /** @var Migrator_cfg  Configuration     */
    protected $cfg;

    /** @var Status_row Current status       */
    protected $current = null;


    /**
     * @param Migrator_cfg $cfg
     * @param Db $db
     */
    function __construct($cfg, $db=null)
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
        $this->db->throw_exceptions = true;

        $ko_txt       = '';
        $table_status = $table = $this->cfg->db_prefix.'status';
        $table_log    = $table = $this->cfg->db_prefix.'log';

        try
        {
            // Create table status if not exists

            $ko_txt = 'Error creating the status table for migrations';

            if (Status_row::Create_table_if_not_exists($table_status, $this->db))
                $this->Show_init_notice ('Status table for migrations created');


            // Create table log if not exists

            $ko_txt = 'Error creating the status table for migrations';

            if (Log_row::Create_table_if_not_exists($table_log, $this->db))
                $this->Show_init_notice ('Log table for migrations created');


            // Load current status
            $ko_txt='Unable to load status row';

            $this->current = Status_row::Load($table_status, $this->db);
        }

        catch (Db\Db_exception $ex)
        {
             $this->Show_init_error($ko_txt, $ex->Get_error_item());
             die();
        }

        // We must have the status at this point
        if (!$this->current)
            die();

    }






    /**
     * Shows an error running Init().
     * @var string $title   Error title
     * @var string|null $details Details
     */
    abstract protected function Show_init_error($title, $details=null);


    /**
     * Shows a notice running Init().
     * @var string $title   Error title
     * @var string|null $details Details
     */
    abstract protected function  Show_init_notice ($title, $details=null);


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
            $this->Show_init_notice ('No step migrations found' );
            return null;
        }

        if ('last'===$option)
        {
            end($this->file_titles);
            $last=key($this->file_titles);
            
            if ($last === (int)$this->current->step)
            {
                $this->Show_init_notice  ("The current migration step is already the last: $last");
                return null;
            }
            return $last;
        }

        if (!isset($this->file_titles[$number]) && $number !==0)
        {
            $this->Show_init_error ('Incorrect step number', null);
            return null;
        }

        if ($number === (int)$this->current->step)
        {
            $this->Show_init_notice  ("The current migration step is already $number");
            return null;
        }

        return $number;
    }


    protected function Update_to($number_or_last)
    {
        $number = $this->Update_to_check($number_or_last);

        if (null===$number) return;
        
        $step            = 0;
        $log_status      = 'ERROR';
        $error_details   = null;
        $error_exception = null;
        
        try 
        {
            // desc - down
            if ($number<$this->current->step)
            {   
                $log_status = 'DOWN_ERROR';
                $file_titles = array_reverse (['Zero migration']+$this->file_titles, true);               

                foreach ($file_titles as $step=>$title)
                {
                    if  ($step>$this->current->step) continue;
                    
                    Status_row::Save ($this->cfg->db_prefix.'status', $this->db, $step, $title);
                    
                    if ($number==$step) break;
                    $microseconds =1;
                    
                    Log_row::Add     ($this->cfg->db_prefix.'log'   , $this->db, $step, 'DOWN', $microseconds);
                    $this->Show_init_notice("Step down $step : $title");
                }
            }

            // asc - up
            else
            {
                $log_status = 'UP_ERROR';
                
                foreach ($this->file_titles as $step=>$title)
                {
                    if ($step<=$this->current->step) continue;
                    $microseconds =1;

                    $this->Show_init_notice("Step up $step : $title");
                    
                    Log_row::Add     ($this->cfg->db_prefix.'log'   , $this->db, $step, 'UP', $microseconds);
                    Status_row::Save ($this->cfg->db_prefix.'status', $this->db, $step, $title);
                    
                    if ($number==$step) break;
                }
            }            
        }
        
        catch (Db\Db_exception $ex)
        {
            $error_details   = (string)$ex->Get_error_item();
            $error_exception = print_r($ex,true);           
        }
        
        catch (Exception $ex)
        {
            $error_details   = (string)$ex->getMessage();
            $error_exception = print_r($ex,true);  
        }  
        
        
        if ($error_details)
        {
            $this->Show_init_error($log_status, $error_details.'\n\n'. $error_exception);
            Log_row::Add     
            (
                $this->cfg->db_prefix.'log'   , 
                $this->db, 
                $step, 
                $log_status, 
                1, 
                $error_details,  
                print_r($ex,true)
            );
        }
    }
}