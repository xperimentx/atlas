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
        $table_status = $table = $this->cfg->db_prefix.'status';;
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



    protected function Update_to($number)
    {

    }
}