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
 * Router item structure
 *
 * @author Roberto González Vázquez
 */
class Router_item
{
    const REPLACE = 1;
    const BASIC   = 2;

    public $data ;
    public $is_raw_exp = false;
    public $method_mask = Methods::ALL;
    public $mode = null;
    public $pattern ;
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

    public function Last()
    {
        $this->stops_routing = true;
    }

    public function Cc()
    {
        $this->stops_routing = false;
    }



}
