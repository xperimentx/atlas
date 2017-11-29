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
    /** @var Db            Migration main Db object. */ protected $db  = null;
    /** @var string[]      Migration files           */ protected $files = [];
    /** @var string[]      Migration files title     */ protected $file_titles = [];
    /** @var Migrator_cfg  Configuration             */ protected $cfg;


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
    protected function Init()
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


    protected function Init_db()
    {
        $migration_tables_prefix= 'xx-migrator-';
        $n = new Create_table($this->table_step, self::$db);
        $n->Add_column('VARCHAR(250)', 'step');
        $n->Run_if_not_exists();


        $n = new Create_table($this->table_log, self::$db);
        $n->Add_column("DATETIME"    , 'date');
        $n->Add_column('VARCHAR(250)', 'step');
        $n->Add_column('TEXT'        , 'msg' );
        $n->Add_column("ENUM('ERROR' , 'BEGIN', 'SUCCES', 'INFO')", 'status');
        $n->Add_index ('date', 'date');
        $n->Run_if_not_exists();
    }


    /**
     * Shows an error running Init().
     * @var string $msg
     */
    abstract protected function Show_init_error($msg);


    /**
     * Shows a notice running Init().
     * @var string $msg
     */
    abstract protected function  Show_init_notice ($msg);



    protected function Update_to($number)
    {

    }
}