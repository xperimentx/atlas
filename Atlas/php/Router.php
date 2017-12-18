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

use Xperimentx\Atlas\Http\Status_codes;
use Xperimentx\Atlas\Http\Methods;

/**
 * Description of Router

 * @author rogon
 */
class Router
{
 	/**
	 * @var string[] Uri Placeholders
	 */
	protected static $placeholders = [
        ':alpha)'	 => '[a-zA-Z]+)',
		':alphanum)' => '[a-zA-Z0-9]+)',
		':alphaext)' => '[a-zA-Z0-9_-]+)',
		':any)'		 => '.*)',
		':num)'		 => '[0-9]+)',
		':segment)'	 => '[^/]+)'
    ];

    /** @var Router_item[] */
    protected static $items = [];

    /** @var Router_item */
    protected static $item_default = null;

    /** @var Router_item */
    protected static $item_404 = null;


    protected static $continue_routing;
    protected static $original_uri;
    protected static $current_uri;

    public static function Add_placeholder($template, $regex)
    {
        self::$place_holder[$template]=$regex;
    }


    /**
     * Sets the current uri
     * @param string $uri
     */
    public static function Set_uri(string $uri)
    {
        self::$current_uri = $uri;
    }


    /**
     * Stops the routing loop
     */
    public static function Stop_routing()
    {
        self::$continue_routing = false;
    }


    public static function Run()
    {
        $method_code = Environment::Get_method_code();

        $wild_keys   = array_keys   (self::$placeholders);
        $wild_values = array_values (self::$placeholders);

        self::$continue_routing = true;

        $method_code = Methods::GET;

        foreach (self::$items as $i)
        {
            echo self::$current_uri, "--\n";
            if (!self::$continue_routing)
                break;

            if (!($method_code & $i->method_mask)) continue;

            $reg_ex = $i->is_raw_exp
                    ? $i->pattern
                    : '#^'.str_replace($wild_keys, $wild_values, $i->pattern). '$#';

            switch ($i->mode)
            {
                default:
                $ok =  preg_match($reg_ex, self::$current_uri, $matches);
                echo $ok ? "\n\n ok --- {$i->pattern}\n" : "\n\n ko --- {$i->pattern}\n";
                 print_r ($matches);

            }
        }
        /*

        if (self::$continue_routing)
            self::$item_default->Route ($method_code, self::$current_uri, self::$original_uri);*/

    }


    public static function Add(string $pattern, $xx, bool $is_raw_reg_exp=false)
    {
        self::$items[] = $i = new Router_item();
        $i->is_raw_exp = $is_raw_reg_exp;
        $i->pattern    = $pattern;
    }

/*
    public static function Rewrite($uri_mask, $neo_uri)
    {
        $obj = new Router_item ($uri_mask, $control);
        $obj->stops_routing=false;
        return $obj;
    }



    public static function Redirect($uri_mask, $neo_uri, $status=Status_codes::STATUS_301_MOVED_PERMANENTLY)
    {
        self::$items[] = $i = new Router_item();
        $i->mode = 'redirect';

        return $i;
    }

    public static function Methods( $method_mask, $uri_mask, $control)
    {
        $obj = new Router_item ($uri_mask, $control);
        $obj->method_mask = $method_maskr;
        return $obj;
    }
    public static function Method_get   ( $uri_mask, $control) {return self::Methods(Methods::GET   , $uri_mask, $control);}
    public static function Method_post  ( $uri_mask, $control) {return self::Methods(Methods::POST  , $uri_mask, $control);}
    public static function Method_put   ( $uri_mask, $control) {return self::Methods(Methods::PUD   , $uri_mask, $control);}
    public static function Method_delete( $uri_mask, $control) {return self::Methods(Methods::DELETE, $uri_mask, $control);}

*/
}
/*
'/controller/(Action:acion)/(num:id)/(alpha:'


Router::Rewrite('/es/index' , 'spain/home');

Router::Prefix('', 'Controllers\');
    Router::Mx_controler ('/client/(:any)', 'Controllers\Client::Run/$1');
    Router::Mx_controler ('/client/(:any)', function()

$Router::Prefix('/api', 'Api\');
    Router::Mx_controler ('/client/(:any)', 'Controllers\Client::Run/$1');
    Router::Mx_controler ('/client/(:any)', function()

$Router::Prefix('', '');
;*/

