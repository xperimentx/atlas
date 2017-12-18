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

namespace Xperimentx\Atlas\Http;

/**
 * Http Methods
 *
 * @author Roberto González Vázquez
 */
class Methods
{
    const ALL     = 0b111111111111 ;
    const NONE    = 0b000000000000 ;

    const CONNECT = 0b000000000001 ;
    const DELETE  = 0b000000000010 ;
    const GET     = 0b000000000100 ;
    const HEAD    = 0b000000001000 ;
    const OPTIONS = 0b000000010000 ;
    const PATCH   = 0b000000100000 ;
    const POST    = 0b000001000000 ;
    const PUT     = 0b000010000000 ;
    const TRACE   = 0b000100000000 ;


    /**
     * Return method name for an atlas int method code
     * @param int $method_code
     * @return string  GET, POST...
     */
    public static function Str(int $method_code ) :string
    {
        switch ($method_code)
        {
            case self::ALL     : return 'ALL'     ;
            case self::CONNECT : return 'CONNECT' ;
            case self::DELETE  : return 'DELETE'  ;
            case self::GET     : return 'GET'     ;
            case self::HEAD    : return 'HEAD'    ;
            case self::OPTIONS : return 'OPTIONS' ;
            case self::PATCH   : return 'PATCH'   ;
            case self::POST    : return 'POST'    ;
            case self::PUT     : return 'PUT'     ;
            case self::TRACE   : return 'TRACE'   ;

            default:           return self::NONE     ;
        }

    }

    /**
     * Returns the atlas int code for a Http method.
     * @param string $method_name GET, POST...
     * @return int
     */
    public static function Get_code (string $method_name) :int
    {
        switch (strtoupper($method_name ))
        {
            case 'ALL'     : return self::ALL      ;
            case 'CONNECT' : return self::CONNECT  ;
            case 'DELETE'  : return self::DELETE   ;
            case 'GET'     : return self::GET      ;
            case 'HEAD'    : return self::HEAD     ;
            case 'OPTIONS' : return self::OPTIONS  ;
            case 'PATCH'   : return self::PATCH    ;
            case 'POST'    : return self::POST     ;
            case 'PUT'     : return self::PUT      ;
            case 'TRACE'   : return self::TRACE    ;

            default:         return self::NONE     ;
        }
    }


    /**
     * Checks i a method code match the mask.
     * @param int|string $method_code_or_name
     * @param int $mask Mask, ex: Method::GET|Method::POST
     * @return bool
     */
    public static function  Match($method_code_or_name, int $mask) :bool
    {
        return is_numeric($method_code_or_name)
                ? ((int)$method_code_or_name        & $mask) != 0
                : (self::Code($method_code_or_name) & $mask) != 0;
    }
}

