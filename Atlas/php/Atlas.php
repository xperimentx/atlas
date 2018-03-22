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

namespace Xperimentx;

class Atlas
{
    protected static $stopper_404       = null;
    protected static $stop_can_redirect = true;

    public static function Call_to($call_to, $data=null)
    {
        if (!$call_to)
            return null;

        if (is_string($call_to) && strpos($call_to, '->'))
        {
            $aux= explode('->',$call_to);

            if (count($aux)!=2)
                return null;

            $aux_obj = new $aux[0];
            return $aux_obj->{$aux[1]}($data);
        }

        if (is_callable($call_to))
        {
            return call_user_func($call_to,$data);
        }

        return null;
    }


    public static function Set_stopper_404 (string $call_to)
    {
        self::$stopper_404 = $cal_to;
    }


    public static function Set_stop_can_redirect($can_redirect=true)
    {
        self::$stop_can_redirect = $can_redirect;
    }


    /**
     * Stops the execution and show 404
     * Sends  HTTP header Status 404
     * @see Atlas::Set_stopper_404()
     */
    static function Stop_404 (string $exit_message='Not found')
    {
        if (self::$stopper_404)
        {
            self::Call_to (self::$sttoper_404, $exit_message);
            exit();
        }

        header("HTTP/1.0 404 Not Found");
        header("Status: 404 Not Found");

        exit($exit_message);
    }


    /**
     * Stops execution and redirects.
     * Sends  Location HTTP header.
     * @param string $url URL to redirect.
     * @see Atlas::Set_stop_can_redirect()
     */
    public static function Stop_url($url)
    {

        if (!self::$stop_can_redirect)
        {
            //Atlas::Trace(1);
            exit("<br>\n Stop: <a href='$url' >$url</a>\n");
        }

        header('Location: '.$url);
        exit();
    }
 }
