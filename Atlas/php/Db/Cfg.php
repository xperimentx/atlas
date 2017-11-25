<?php
/**
 * Atlas Toolkit
 *
 * @link  https://github.com/xperimentx/atlas
 * @link  https://xperimentX.com
 *
 * @copyright 2017 Roberto González Vázquez
 */

namespace Xperimentx\Atlas\Db;

/**
 * Database configuration structure
 *
 * @author Roberto González Vázquez
 */

class Cfg
{
    /** @var string User name.                                        */ public $user_name      = null;

    /** @var string Password.                                         */ public $password       = null;

    /** @var string Database.                                         */ public $db_name        = null;

    /** @var string MySQL or MAariaDB Server host name or IP address.
     *              Prepend host by 'p:' for persistent connections   */ public $server         = 'localhost';

    /** @var string Port.                                             */ public $port           = 3306;

    /** @var string Socket.                                           */ public $socket         = null;

    /** @var string Charset.                                          */ public $charset        = 'utf8';

    /** @var string Collation.                                        */ public $collation      = 'utf8_general_ci';

    /** @var string Engine                                            */ public $engine         = 'InnoDB';
}
