<?php

/**
 *  Atlas Toolkit
 *
 * @link  https://github.com/xperimentx/atlas
 * @link  https://xperimentX.com
 *
 * @copyright 2017 Roberto González Vázquez
 */

namespace Atlas\Db;

/**
 * Table manipulation
 *
 * @author Roberto González Vázquez
 */

class Mysql_forge extends Mysql
{



    /**
     * Changes table engine
     * @param string $engine 'MyISAM', 'InnoDB'
     */
    public function Table_engine($table, $engine)
    {
        return $this->Query("ALTER TABLE `$table` ENGINE=$engine;");
    }



 


}


/*
 CREATE TABLE `aa` (
	`aa` INT(11) UNSIGNED NULL DEFAULT NULL COMMENT 'coment',
	`bb` INT(11) UNSIGNED NULL DEFAULT NULL,
	`cc` INT(11) UNSIGNED NULL DEFAULT NULL,
	`dd` INT(11) UNSIGNED NULL DEFAULT NULL,
	`ee` INT(11) UNSIGNED NULL DEFAULT NULL,
	`xx` INT(11) UNSIGNED NULL DEFAULT NULL
)
COMMENT='aa'
COLLATE='utf8_spanish_ci'
ENGINE=MyISAM
;

ALTER TABLE `aa`
	CHANGE COLUMN `cc` `cc` INT(3) UNSIGNED NULL DEFAULT NULL AFTER `bb`,
	CHANGE COLUMN `dd` `dd` INT(4) UNSIGNED NULL DEFAULT NULL AFTER `cc`,
	CHANGE COLUMN `ee` `ee` INT(5) UNSIGNED NULL DEFAULT NULL AFTER `dd`;
 *
 *
 */