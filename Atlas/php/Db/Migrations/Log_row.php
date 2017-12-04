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
use Xperimentx\Atlas\Db\Create_table;

/**
 * @author    Roberto González Vázquez
 */
class Log_row
{
    /**@var  int    Id       */ public $id;
    /**@var  string Date     */ public $date;
    /**@var  string   Status: 'UP', 'DOWN', 'UP_ERROR', 'DOWN_ERROR' , 'ERROR', 'INFO' */ public $status;
    /**@var  int    Step     */ public $step;
    /**@var  string  Details */ public $date_modified ;


    /**
     * @param string $table
     * @param Db $db
     */
    static public  function Create_table_if_not_exists($table, $db)
    {
        $l = new Create_table($table, $db);
        $l->Add_column_id();
        $l->Add_column('BIGINT'      , 'step'  )->Set_unsigned();
        $l->Add_column('DATETIME'    , 'date');
        $l->Add_column("ENUM"        , 'status')->type.="('UP', 'DOWN', 'UP_ERROR', 'DOWN_ERROR', 'ERROR', 'INFO')";
        $l->Add_column('INT'         , 'microseconds');
        $l->Add_column('TEXT'        , 'details');
        $l->Add_column('TEXT'        , 'exception');

        return $l->Run_if_not_exists();
    }
     
          
    /**
     *
     * @param string $table
     * @param Db $db
     * @param int $step     
     * @param string $status 'UP', 'DOWN', 'UP_ERROR', 'DOWN_ERROR' , 'ERROR', 'INFO'
     * @param int $microseconds
     * @param string|null $details
     */
    static function Add($table, $db, $step, $status, $microseconds, $details=null, $exception=null)
    {
        return $db->Insert
        (
            $table,
            [
                'step'         => $step,
                'status'       => $status,
                'date'         => date('Y-m-d H:i:s'),
                'microseconds' => $microseconds,
                'details'      => $details,
                'exception'    => $exception,
            ],
            true                
        );
    }   
    
    
    /**
     *
     * @param string $table
     * @param Db $db
     * @param int $step     
     * @param string $status 'UP', 'DOWN', 'UP_ERROR', 'DOWN_ERROR' , 'ERROR', 'INFO'
     * @param int $microseconds
     * @param string|null $details
     */
    static function Clean($table, $db)
    {
        return $db->Truncate_table($table);
    }   
}


