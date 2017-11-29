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

namespace Xperimentx\Atlas\Db;

use Xperimentx\Atlas\Db;



/**
 * Migration step
 *
 * @author Roberto González Vázquez
 */
abstract class Migrations_draft
{
    /** @var string Directory of migration files */ public $migration_root;

    /** @var string[] Migration files */ protected $files;

    /** @var Db           Migration main Db object.    */  public static $db      = null;

    const ERROR   ='ERROR';
    const BEGIN   ='BEGIN';
    const SUCCESS ='SUCCESS';
    const INFO    ='INFO';

    public     $table_step        = 'atlas_migration_step';
    public     $table_log         = 'atlas_migration_log';
    protected  $current_step      = '';
    protected  $on_error_semaforo =  false;
    public     $namespace;



    public function Prepare_db()
    {
        if (!self::$db)
            self::$db =  Db::$db;

        self::$db->on_error_fn = $this->On_errpr_db();
    }


    public function Get_file_names ()
    {
        $this->migration_root = rtrim($this->migration_root,'\\/').'/';
        $this->namespace      = trim($this->namespace,'\\').'\\';

        $this->files = glob($this->migration_root.'*.php');
        natcasesort($this->files);
    }


    public $objetive_step = '99';


    public function Create_tables_is_needed()
    {
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

    public function Run()
    {
        $this->Prepare_db();
        $this->Get_file_names();
        $this->Create_tables_is_needed();

        if (!$this->files)
        {
            $this->Log(self::ERROR, 'No migration files');
            die();
        }

        $this->current_step = $this->current_step = self::$db->Scalar("SELECT step FROM `$this->table_step`;");

        $num_steps = 0;

        $is_back    = ($this->objetive_step<$this->current);
        $action_msg = $is_back ? 'DOWN':'UP';

        foreach ($this->files as $file)
        {
            if ($is_back)
            {
                if ($file>$this->objetive_step) break;
                if ($file<$this->current_step) continue;
            }
            else
            {
                if ($file<$this->objetive_step) break;
                if ($file<$this->current_step) continue;
            }

            $num_steps ++;

            $this->current_step = $file;

            include_once ($this->migration_root.$file);

            $class_name = $this->namespace.basename($file,'.php');

            if (!class_exists($class_name))
            {
               $this->Log (self::ERROR , ' Migration class incorret');
               $this->Log (self::BEGUIN, '');
            }
            $obj = new $class_name();

            if ($is_back)
                 $obj->Down();
            else $obj->Up();

            self::$db ->Update($this->table_step,[ 'step'=> $this->current_step ], null, true);
            $this->Log(self::SUCCESS, $action_msg);
        }

        self::$db ->Log(self::INFO, "Migration end, $num_steps steps  $action_msg");
    }

    /**
     * Log an event
     * @string string $status
     * @string string $message
     */
    protected function Log($status, $message)
    {
        self::$db ->Insert
        (
            $this->table_log,
            [
                'step'=> $this->current_step ,
                'date'=> date('Ymd hms'),
                'status'=>$status,
                'msg' => $message
            ],
            true
        );
    }



    /**
     * On error db handler.
     * @param Db $db
     */
    public function On_error_db ($db)
    {
        if ($this->on_error_semaforo)
            return;

        $this->on_error_semaforo = true;

        $this->Log(self::ERROR, join("\n\n", $db->last_error));

        die();
    }
}
