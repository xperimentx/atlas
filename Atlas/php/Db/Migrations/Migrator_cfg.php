<?php
/**
 * xperimentX atlas php toolkit
 *
 * @link      https://github.com/xperimentx/atlas
 * @link      https://xperimentX.com
 *
 * @author    Roberto González Vázquez, https://github.com/xperimentx
 * @copyright 2017 - 2018 Roberto González Vázquez
 *
 * @license   MIT
 */

namespace Xperimentx\Atlas\Db\Migrations;

/**
 * Migrator Configuration
 *
 * @author Roberto González Vázquez
 */
class Migrator_cfg
{
    /** @var string Directory of migration files */ public $root       = null;
    /** @var string Namespace                    */ public $namespace  = null;
    /** @var bool   Use colors                   */ public $use_colors = true;
    /** @var bool   Migration db tables prefix   */ public $db_prefix  = 'xx-migrator-';
}