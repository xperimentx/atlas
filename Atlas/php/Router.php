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
    protected static $original_http_method=null;
    protected static $original_uri_path=null;
    protected static $uri_path =null;
    protected static $http_method = null;
    protected static $pattern_prefix = null;
    protected static $call_to_prefix = null;

    public static function Add_placeholder($template, $regex)
    {
        self::$place_holder[$template]=$regex;
    }


    /**
     * Sets the current URI path
     * @param string $uri
     */
    public static function Set_uri_path(string $uri)
    {
        self::$uri_path = $uri;
    }


    /**
     * Sets the current http method
     * @param int $method
     */
    public static function Set_http_method(int $method)
    {
        self::$http_method = $method;
    }



    /**
     * Stops the routing loop
     */
    public static function Stop_routing()
    {
        self::$continue_routing = false;
    }


    protected static function Load_environment()
    {
        if (null===self::$http_method)
            self::$http_method = Environment::Get_method_code();

        if (null===self::$uri_path)
        {
            $uri = Environment::Get_uri_friendly_obj();
            self::$uri_path = $uri->frienddly;
        }

        self::$original_http_method = self::$http_method;
        self::$original_uri_path    = self::$uri_path ;
    }


    public static function Run()
    {
        self::Load_environment();

        $matches = null;

        $wild_keys   = array_keys   (self::$placeholders);
        $wild_values = array_values (self::$placeholders);

        self::$continue_routing = true;

        foreach (self::$items as $i)
        {
            if (!self::$continue_routing)
                break;

            if (!(self::$http_method & $i->method_mask)) continue;

            $reg_ex = $i->is_raw_exp
                    ? $i->pattern
                    : '#^'.str_replace($wild_keys, $wild_values, $i->pattern). '$#';

            switch ($i->mode)
            {
                case Router_item::BASIC:
                    $ok =  preg_match($reg_ex, self::$uri_path, $matches);

                    if (null===$i->data )
                    {
                        echo $ok ? "\n\n ok --- {$i->pattern}\n" : "\n\n ko --- {$i->pattern}\n";
                        if ($matches) print_r ($matches);
                    }

                    elseif (is_string($i->data))
                    {
                        $aux= explode('::',$i->data);
                        if (count($aux)!=2)
                            continue;

                        $aux_obj = new $aux[0];
                        $aux_obj->{$aux[1]}($matches);
                    }

                    elseif ($i->data instanceof \Closure)
                    {
                        ($i->data)($matches);
                    }
                    break;

                case Router_item::REPLACE:
                    $result = preg_replace($reg_ex, $i->data, self::$uri_path);
                    
                    if (null!==$result)
                        self::$uri_path = $result;
                    break;

                default:

            }
        }
        /*

        if (self::$continue_routing)
            self::$item_default->Route ($method_code, self::$current_uri, self::$original_uri);*/

    }


    public static function Add(string $pattern, $call_to, bool $is_raw_reg_exp=false)
    {
        self::$items[] = $i = new Router_item();
        $i->is_raw_exp = $is_raw_reg_exp;
        $i->pattern    = self::$pattern_prefix.$pattern;
        $i->data       = is_string($call_to) ?self::$call_to_prefix.$call_to : $call_to;
        $i->mode       = Router_item::BASIC;
    }

    public static function Replace(string $pattern, string $replacement, bool $is_raw_reg_exp=false)
    {
        self::$items[] = $i = new Router_item();
        $i->is_raw_exp = $is_raw_reg_exp;
        $i->pattern    = self::$pattern_prefix. $pattern;
        $i->data       = $replacement;
        $i->mode       = Router_item::REPLACE;
    }


    public static function Add_methods(int $method_mask, string $pattern, $call_to, bool $is_raw_reg_exp=false)
    {
        $obj = $this->Add($pattern, $call_to, $is_raw_reg_exp);
        $obj->method_mask = $method_mask;
        return $obj;
    }

    public static function Add_connect(string $pattern, $call_to, bool $is_raw_reg_exp=false) {return self::Methods(Methods::CONNECT, $pattern, $call_to, $is_raw_reg_exp);}
    public static function Add_delete (string $pattern, $call_to, bool $is_raw_reg_exp=false) {return self::Methods(Methods::DELETE , $pattern, $call_to, $is_raw_reg_exp);}
    public static function Add_get    (string $pattern, $call_to, bool $is_raw_reg_exp=false) {return self::Methods(Methods::GET    , $pattern, $call_to, $is_raw_reg_exp);}
    public static function Add_head   (string $pattern, $call_to, bool $is_raw_reg_exp=false) {return self::Methods(Methods::HEAD   , $pattern, $call_to, $is_raw_reg_exp);}
    public static function Add_options(string $pattern, $call_to, bool $is_raw_reg_exp=false) {return self::Methods(Methods::OPTIONS, $pattern, $call_to, $is_raw_reg_exp);}
    public static function Add_patch  (string $pattern, $call_to, bool $is_raw_reg_exp=false) {return self::Methods(Methods::PATCH  , $pattern, $call_to, $is_raw_reg_exp);}
    public static function Add_post   (string $pattern, $call_to, bool $is_raw_reg_exp=false) {return self::Methods(Methods::POST   , $pattern, $call_to, $is_raw_reg_exp);}
    public static function Add_put    (string $pattern, $call_to, bool $is_raw_reg_exp=false) {return self::Methods(Methods::PUT    , $pattern, $call_to, $is_raw_reg_exp);}
    public static function Add_trace  (string $pattern, $call_to, bool $is_raw_reg_exp=false) {return self::Methods(Methods::TRACE  , $pattern, $call_to, $is_raw_reg_exp);}
    public static function Add_patch_put(string $pattern, $call_to, bool $is_raw_reg_exp=false) {return self::Methods(Methods::PATCH|Methods::PUT, $pattern, $call_to, $is_raw_reg_exp);}

    public static function Prefix(string $patter_prefix='', $call_to_prefix='')
    {
        self::$pattern_prefix = $patter_prefix;
        self::$call_to_prefix = $call_to_prefix;
    }
}
