<?php
/**
 *  Atlas Toolkit
 *
 * @link  https://github.com/xperimentx/atlas
 * @link  https://xperimentX.com
 *
 * @copyright 2017 Roberto González Vázquez
 */

namespace Xperimentx\Atlas\Db\Migration;


/**
 * Migration step
 *
 * @author Roberto González Vázquez
 */

abstract class Step
{
    abstract public function Up();

    abstract public function Down();
}
