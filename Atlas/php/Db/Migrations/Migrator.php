<?php

/**
 *  Atlas Toolkit
 *
 * @link  https://github.com/xperimentx/atlas
 * @link  https://xperimentX.com
 *
 * @copyright 2017 Roberto González Vázquez
 * @license MIT
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


    protected function Update_to($number)
    {

    }


    protected function Get_migration_files()
    {
        $vector = glob(rtrim($this->cfg->root,'\\/').'/*.php');

        foreach ($vector as $item)
        {
            $pos_file  = strrpos($item,'/');
            $num       = (int)substr($item, $pos_file+1);

            if ($num>0)
            {
                $this->files       [$num]=$item;
                $this->file_titles [$num]= substr($item, strpos($item,'-', $pos_file)+1);
            }
        }

        ksort($this->files);
        ksort($this->file_titles);
    }
}