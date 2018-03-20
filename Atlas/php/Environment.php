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

use Xperimentx\Atlas\Http;

/**
 * Environment info.
 *
 * @link https://github.com/xperimentx/atlas/blob/master/Atlas/doc/Enviroment.md
 * @author Roberto González Vázquez
 */
class Environment
{
    private static $__initialized = false;

    private static $host         = null;
    private static $host_uri     = null;
    private static $is_ajax_     = null;
    private static $is_https_    = null;
    private static $method ;
    private static $method_code ;
    private static $port;
    private static $protocol ;
    private static $query_string ;
    private static $request_time  ;
    private static $request_uri ;
    private static $uri          = null;
    private static $root_path    = null;


    /**
     * Checks is via is command line.
     * @return bool
     */
    public static function Is_cli() : bool
    {
        return defined('STDIN');
    }


    /**
     * Returns if https is used in the request of this page
     * @return bool
     */
    public static function Is_https():bool
    {
        if (null===self::$is_https_)
        {
            self::$is_https_    =  !empty($_SERVER['HTTPS'])                  &&  'off'   !== strtolower($_SERVER['HTTPS']                 )
                                || !empty($_SERVER['HTTP_FRONT_END_HTTPS'])   &&  'off'   !== strtolower($_SERVER['HTTP_FRONT_END_HTTPS']  )
                                ||  isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&  'https' === strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'])
                                || !empty($_SERVER['HTTP_X_FORWARDED_SSL'])   &&  'on'    === strtolower($_SERVER['HTTP_X_FORWARDED_SSL']  ) ;
        }

        return self::$is_https_;
    }


    /**
     * Sets host name.
     * For security reasons is recommend set this value in production stage.
     */
    public static  function Set_host(string $host_name)
    {
        self::$host     = $host_name;
        self::$host_uri = null; // must recalculate
        self::$uri      = null; // must recalculate
    }


    /**
     * Gets host name.
     * @return string
     */
    public static function Get_host() :string
    {
        if (null===self::$host)
        {
            self::$host  = $_SERVER['HTTP_HOST']
                         ?? $_SERVER['SERVER_NAME']

                         ?? $_ENV['HOSTNAME']
                         ?? $_ENV['SERVER_NAME']
                         ?? $_ENV['COMPUTERNAME']
                         ?? '';
        }

        return self::$host;
    }


    /**
     * Returns scheme://host[:port]
     * @return string
     */
    public static function Get_host_uri() :string
    {
        if (null===self::$host_uri)
        {
            if (self::Is_https())
            {
                self::$host_uri= 'https://'. self::Get_host();

                if (self::$port!=443)
                    self::$host_uri .=':'.self::$port;
            }
            else
            {
                self::$host_uri = 'http://'. self::Get_host();

                if (self::$port!=80)
                    self::$host_uri .=':'.self::$port;
            }
        }

        return self::$host_uri;
    }


    /**
     * Returns  the URI requested scheme://host[:port]
     * @return string
     */
    public static function Get_uri () :string
    {
        if (null===self::$uri)
        {
            self::$uri = self::Get_host_uri().self::$request_uri;
        }

        return self::$uri;
    }


    /**
     * Returns a new Uri object form the requested URI.
     * @return Http\Uri
     */
    public static function Get_uri_obj () :Http\Uri
    {
        return new Http\Uri(self::Get_uri());
    }


    /**
     * Returns a new Uri object form the requested URI.
     * @return Http\Uri_friendly
     */
    public static function Get_uri_friendly_obj () :Http\Uri_friendly
    {
        return new Http\Uri_friendly(self::Get_uri());
    }


    /**
     * Check if the page is requested via ajax. Unsafe.
     *
     * Uses  $_SERVER['HTTP_X_REQUESTED_WITH'], it is not a standard header, can be spoofed.
     * @return bool
     */
    public static function Is_ajax () :bool
    {
        if (null===self::$is_ajax_)
        {
            self:$is_ajax_ = strtolower($_SERVER['HTTP_X_REQUESTED_WITH']??'') === 'xmlhttprequest';
        }

        return self::$is_ajax_;
    }


    /**
     * Timestamp of the start of the request, with microsecond precision.
     * @return float
     * @see Time_from_Request()
     */
    public static function Get_request_time(): float
    {
        return self::$request_time;
    }


    /**
     * Seconds from  the star of the request, , with microsecond precision.
     * @return float
     * @see Requested_time()
     */
    public static function Time_from_Request(): float
    {
        return microtime(true) - self::$request_time;
    }


    /**
     * Protocol which the page was requested: ex: 'HTTP/1.1'.
     * @return string
     */
    public static function Get_protocol(): string
    {
        return self::$protocol;
    }


    /**
     * Method used to access the page: 'CLI', 'GET', 'HEAD', 'POST', 'PUT'...
     * @var string
     */
    public static function Get_method(): string
    {
        return self::$method;
    }


    /**
     * Int code corresponding to method code used to access the page: 'CLI', 'GET', 'HEAD', 'POST', 'PUT'...
     * @var int
     */
    public static function Get_method_code(): int
    {
        return self::$method_code;
    }


    /**
     * Gets server port.
     * @var int
     */
    public static function Get_port(): int
    {
        return self::$port;
    }


    /**
     * Sets root path
     * @param string $root_path
     */
    static public function Set_root_path (string $root_path) : string
    {
        self::$root_path = rtrim($root_path,'\\/');
    }


    /**
     * Returns root path
     * @return string
     */
    static public function Get_root_path () : string
    {
        if (null===self::$root_path)
        {
            if (class_exists('Xperimentx\Atlas\Autoloader', false))
            {
                self::$root_path = Autoloader::Get_root_path();
            }

            if (!self::$root_path)
                self::$root_path = $_SERVER['DOCUMENT_ROOT'] ?? '';
        }

        return self::$root_path;
    }


    /**
     * Calculates the Environment variables.
     * @internal
     */
    public static function __initialize()
    {
        if (self::$__initialized)
            return;

        self::$__initialized = true;

        self::$request_time     = $_SERVER['REQUEST_TIME_FLOAT'] ?? 0.0;
        self::$protocol         = $_SERVER['SERVER_PROTOCOL'   ] ?? '';
        self::$method           = $_SERVER['REQUEST_METHOD'    ] ?? '';
        self::$port             = (int)($_SERVER['SERVER_PORT' ] ?? 0);
        self::$request_uri      = $_SERVER['REQUEST_URI'       ] ?? '';
        self::$method_code      = Http\Methods::Get_code(self::$method);


  /*
        self::$query_string     = $_SERVER['QUERY_STRING'      ] ?? '';
        self::$php_self         = $_SERVER['PHP_SELF'          ] ?? '';
        self::$document_root    = $_SERVER['DOCUMENT_ROOT'       ] ?? '';
        self::$script_filename  = $_SERVER['SCRIPT_FILENAME'     ] ?? '';
        self::$script_name      = $_SERVER['SCRIPT_NAME'         ] ?? '';
        self::$http_accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'           ] ?? '';

*/
    }
}


Environment::__initialize();



