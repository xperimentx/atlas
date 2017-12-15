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
 * @author    Roberto González Vázquez
 */
class Status
{
    /**@var  int    */ public $step;
    /**@var  string */ public $title;
    /**@var  string */ public $date_created  ;
    /**@var  string */ public $date_modified ;


    /**
     * @param string $table_prefix
     * @param Db $db
     */
    static public  function Create_table_if_not_exists(string $table_prefix, Db $db)
    {
        $n = new Db\Create_table($table_prefix.'status', $db);
        $n->Add_column('BIGINT'       , 'step'         , 0)->Set_unsigned();
        $n->Add_column('VARCHAR(250)' , 'title'        , NULL);
        $n->Add_column('DATETIME'     , 'date_created' , date('Y-m-d H:i:s'));
        $n->Add_column('DATETIME'     , 'date_modified', date('Y-m-d H:i:s'));

        return $n->Run_if_not_exists();
    }


    /**
     * @param string $table_prefix
     * @param Db $db
     */
    static public function Load(string $table_prefix, Db $db)
    {
        if ($obj = $db->Row("SELECT * FROM `{$table_prefix}status`", __CLASS__)) //:=
            return $obj;

        $obj = new static;
        $obj->step          = 0;
        $obj->title         = 'No current migration step';
        $obj->date_created  = date('Y-m-d H:i:s');
        $obj->date_modified = $obj->date_created;

        return $db->Insert  ($table_prefix.'status', (array)$obj) ? $obj : null;
    }


    /**
     *
     * @param string $table_prefix
     * @param Db $db
     * @param int $step
     * @param string $title
     * @return type
     */
    static function Save (string $table_prefix, Db $db, string $step, string $title)
    {
        return $db->Update
        (
            $table_prefix.'status',
            [
                'step'          => $step,
                'title'         => $title,
                'date_modified' => date('Y-m-d H:i:s')
            ]
        );
    }

}