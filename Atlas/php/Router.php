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

use Xperimentx\Atlas;
use Xperimentx\Atlas\Http\Methods;

/**
 * Description of Router

 * @author rogon
 */
class Router
{
 	/**
	 * @var string[] Uri Place holders
	 */
	protected static $placeholders = [
        ':alpha)'	 => '[a-zA-Z]+)',
		':alphanum)' => '[a-zA-Z0-9]+)',
		':alphaext)' => '[a-zA-Z0-9_-]+)',
		':any)'		 => '.*)',
		':num)'		 => '[0-9]+)',
		':segment)'	 => '[^/]+)'
    ];

    /** @vasr string[] */
    protected static $user_placeholders = [];

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

    protected static $pattern_prefix_lifo = [];
    protected static $call_to_prefix_lifo = [];
    protected static $uri_path_lifo       = [];

    public static function Add_placeholder($template, $regex)
    {
        self::$user_placeholders[$template]=$regex;
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
     * Gets the current URI path
     * @return string
     */
    public static function Get_uri_path(  )
    {
        return self::$uri_path;
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

        $wild        = self::$user_placeholders + self::$placeholders;
        $wild_keys   = array_keys   ($wild);
        $wild_values = array_values ($wild);

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
                // ---------------------------------------------------------------------------------

                case Router_item::BASIC:
                    $ok =  preg_match($reg_ex, self::$uri_path, $matches);

                    if (null===$i->data )
                    {
                        echo $ok ? "\n\n ok --- {$i->pattern}\n" : "\n\n ko --- {$i->pattern}\n";
                        if ($matches) print_r ($matches);
                        self::$continue_routing = !$i->stops_routing;
                    }

                    if ($ok)
                    {
                        Atlas::Call_to ($i->data, $matches);
                        self::$continue_routing = !$i->stops_routing;
                    }

                    break;

                // ---------------------------------------------------------------------------------

                case Router_item::REPLACE:
                    $result = preg_replace($reg_ex, $i->data, self::$uri_path);

                    if (null!==$result)
                        self::$uri_path = $result;

                    self::$continue_routing = !$i->stops_routing;
                    break;

                // ---------------------------------------------------------------------------------

                case Router_item::PUSH_URI_PATH:
                    array_push(self::$uri_path_lifo, self::$uri_path ) ;
                    break;

                // ---------------------------------------------------------------------------------

                case Router_item::POP_URI_PATH:
                    if (self::$uri_path_lifo)
                        self::$uri_path = array_pop(self::$uri_path_lifo);
                    break;

                // ---------------------------------------------------------------------------------

                case Router_item::REDIRECT:

                    $ok =  preg_match($reg_ex, self::$uri_path, $matches);

                    if (!$ok) break;

                    $result = preg_replace($reg_ex, $i->data, self::$uri_path);

                    if (null!==$result)
                        Atlas::Stop_url ($result);

                    self::$continue_routing = !$i->stops_routing;

                    break;
                default:
            }
        }
        /*
        if (self::$continue_routing)
            self::$item_default->Route ($method_code, self::$current_uri, self::$original_uri);*/
    }

    /**
     * Adds call_to step.
     * In this step $call_to is executed if the pattern is met
     * @return Router_item
     */
    public static function Add(string $pattern, $call_to, bool $is_raw_reg_exp=false)
    {
        self::$items[] = $i = new Router_item();
        $i->is_raw_exp = $is_raw_reg_exp;
        $i->pattern    = self::$pattern_prefix.$pattern;
        $i->data       = is_string($call_to) ?self::$call_to_prefix.$call_to : $call_to;
        $i->mode       = Router_item::BASIC;
        return $i;
    }


    /**
     * Adds a rewrite step.
     * In this step, the uri_path is rewritten if the pattern is met
     * @return Router_item
     */
    public static function Rewrite(string $pattern, string $replacement, bool $is_raw_reg_exp=false)
    {
        self::$items[]    = $i = new Router_item();
        $i->mode          = Router_item::REPLACE;
        $i->stops_routing = false;
        $i->pattern       = self::$pattern_prefix. $pattern;
        $i->is_raw_exp    = $is_raw_reg_exp;
        $i->data          = $replacement;
        return $i;
    }


    /**
     * Adds a redirect step.
     * In this step, redirects to a new url if the pattern is met
     * @return Router_item
     */
    public static function Redirect(string $pattern, string $replacement, bool $is_raw_reg_exp=false)
    {
        self::$items[] = $i = new Router_item();
        $i->mode       = Router_item::REDIRECT;
        $i->is_raw_exp = $is_raw_reg_exp;
        $i->pattern    = self::$pattern_prefix. $pattern;
        $i->data       = $replacement;
        return $i;
    }


    /**
     * Add a save uri to the lifo step.
     * In this step, pushes the current uri_path to the uri_path_lifo.
     * Saves the state.
     */
    public static function Push_uri_path()
    {
        self::$items[] = $i = new Router_item();
        $i->mode       = Router_item::PUSH_URI_PATH;
    }


    /**
     * Add a recover uri freom the lifo step.
     * Pops the uri_path from muri_path_lifo in this step.
     */
    public static function Pop_uri_path()
    {
        self::$items[] = $i = new Router_item();
        $i->mode       = Router_item::POP_URI_PATH;
    }


    /**
     * @return Router_item
     */
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


    public static function Pattern_prefix_begin(string $pattern_prefix='' )
    {
        array_push(self::$pattern_prefix_lifo, $pattern_prefix);
        self::$pattern_prefix = $pattern_prefix;
    }


    public static function Call_to_prefix_begin(string $call_to_prefix='')
    {
        array_push(self::$call_to_prefix_lifo, $call_to_prefix);
        self::$call_to_prefix = $call_to_prefix;
    }


    public static function Call_to_prefix_end()
    {
        self::$call_to_prefix = self::$call_to_prefix_lifo
                              ? array_pop(self::$call_to_prefix_lifo )
                              : '';
    }


    public static function Pattern_prefix_end()
    {
        self::$pattern_prefix = self::$pattern_prefix_lifo
                              ? array_pop(self::$pattern_prefix_lifo )
                              : '';
    }

}
