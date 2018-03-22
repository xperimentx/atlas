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

namespace Xperimentx\Atlas\Http;

/**
 * Ip tools
 *
 * @author Roberto González Vázquez
 */
class Ip
{
    private static $client_ip=null;
    private static $client_ips=null;


    /**
     * Returns IP from remote address. Not good for proxies but safe.
     * uses  $_SERVER['REMOTE_ADDR']
     * @see Get_ip_client()
     */
    function Get_ip_remote() :string
    {
        return $_SERVER['REMOTE_ADDR']??'';
    }



    /**
     * Returns IP of client sent in the Http request, unsafe, can be spoofed  but good for proxies.
     * @see Get_ip_remote()
     * @return I
     */
   static public function Get_ip_client():string
   {
       if (self::$client_ip)
           return self::$client_ip;

        // get Candidate IPS
        $ip_string = $out_ip
                  . ',' . ($_SERVER['HTTP_CLIENT_IP'           ] ??'')
                  . ',' . ($_SERVER['HTTP_X_CLIENT_IP'         ] ??'')
                  . ',' . ($_SERVER['HTTP_X_CLUSTER_CLIENT_IP' ] ??'')
                  . ',' . ($_SERVER['HTTP_X_FORWARDED_FOR'     ] ??'')
                  . ',' . ($_SERVER['HTTP_X_FORWARDED'         ] ??'')
                  . ',' . ($_SERVER['HTTP_FORWARDED_FOR'       ] ??'')
                  . ',' . ($_SERVER['HTTP_FORWARDED'           ] ??'')
                  . ',' . ($_SERVER['REMOTE_ADDR'              ] ??'');

        $ips = explode(',', $ip_string);


        foreach ($ips as $ip)
        {
            $ip=trim($ip);

            if (filter_var($ip, FILTER_VALIDATE_IP | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false)
            {
                self::$client_ip = $ip;
                return $ip;
            }
        }
   }


    /**
     * Returns all IPs from client sent in Http request, unsafe, these values can be spoofed.
     * @return string[] array index: server variable used for get this ip,  value valid ip.
     */
   static public function Get_all_ip_client() :array
   {
       if (self::$client_ips)
           return self::$client_ips;

        $candidates =   [
                            'HTTP_CLIENT_IP'           ,
                            'HTTP_X_CLIENT_IP'         ,
                            'HTTP_X_CLUSTER_CLIENT_IP' ,
                            'HTTP_X_FORWARDED_FOR'     ,
                            'HTTP_X_FORWARDED'         ,
                            'HTTP_FORWARDED_FOR'       ,
                            'HTTP_FORWARDED'           ,
                            'REMOTE_ADDR'              ,
                        ];


        foreach ($candidates as $candidate)
        {
            $ips = explode(',', $_SERVER[$candidate]&&'');
            $a = 0;
            foreach ($ips as $ip)
            {
                $ip=trim($ip);

                if (filter_var($ip, FILTER_VALIDATE_IP | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false)
                {
                    self::$client_ips[$candidate.' '.$a] =  $ip;$
                    $a++;
                }
            }
        }

        return self::$client_ips;
    }
}

