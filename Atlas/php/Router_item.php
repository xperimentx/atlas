<?php

/**
 * xperimentX atlas php toolkit
 *
 * @link      https://github.com/xperimentx/atlas
 * @link      https://xperimentX.com
 *
 * @author    Roberto González Vázquez, https://github.com/xperimentx
 * @copyright 2017 Roberto González Vázquez
 *
 * @license   MIT
 */

namespace Xperimentx\Atlas;

use Xperimentx\Atlas\Http\Methods;

/**
 * Description of Router_item
 *
 * @author rogon
 */
class Router_item
{
    public $pattern ;
    public $is_raw_exp = false;
    public $reg_exp  ;
    public $method_mask = Methods::ALL;
    public $mode = null;
    public $stops_routing = true;

    /**
     * Set http method mask
     * @param int $method_mask ex: Http\Method::GET|Http\Method::POST
     * @return $this
     */
    public function Methods (int $method_mask)
    {
        $this->method_mask = $method_mask;
        return $this;
    }

}
