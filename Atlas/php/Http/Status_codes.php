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
 * Http Status.
 *
 * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status
 * @link https://en.wikipedia.org/wiki/List_of_HTTP_status_codes
 * @author  Roberto González Vázquez
 */


class Status_codes
{
    /** @var string[] Family to string */
    static protected $family_str =
    [
        0 => 'Unknown status',
        1 => 'Informational',
        2 => 'Successful'   ,
        3 => 'Redirection'  ,
        4 => 'Client Error' ,
        5 => 'Server Error' ,
    ];

    /** @var string[] Family to string */
    static protected $family_xx_str =
    [
        0 => 'xxx Unknown status',
        1 => '1xx Informational',
        2 => '2xx Successful'   ,
        3 => '3xx Redirection'  ,
        4 => '4xx Client Error' ,
        5 => '5xx Server Error' ,
    ];

    /** @var string[] Status to string */
    static protected $status_str =
    [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',

        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',

        226 => 'IM Used',

        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',

        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        419 => 'Authentication Timeout',
        420 => 'Enhance Your Calm',
        421 => 'Misdirected Request',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',

        426 => 'Upgrade Required',

        428 => 'Precondition Required',
        429 => 'Too Many Requests',

        431 => 'Request Header Fields Too Large',

        444 => 'No Response',

        449 => 'Retry With',
        450 => 'Blocked by Windows Parental Controls',
        451 => 'Unavailable For Legal Reasons',

        494 => 'Request Header Too Large',
        495 => 'Cert Error',
        496 => 'No Cert',
        497 => 'HTTP to HTTPS',

        499 => 'Client Closed Request',

        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',

        598 => 'Network read timeout error',
        599 => 'Network connect timeout error',
    ];



    /** xx  Informational */ const STATUS_FAMILY_1_Informational    =   1 ;
    /** xx  Successful    */ const STATUS_FAMILY_2_Successful       =   2 ;
    /** xx  Redirection   */ const STATUS_FAMILY_3_Redirection      =   3 ;
    /** xx  Client Error  */ const STATUS_FAMILY_4_Client_Error     =   4 ;
    /** xx  Server Error  */ const STATUS_FAMILY_5_Server_Error     =   5 ;

    /** 100 Continue.                                              */ const STATUS_100_CONTINUE                             = 100 ;
    /** 101 Switching Protocols.                                   */ const STATUS_101_SWITCHING_PROTOCOLS                  = 101 ;
    /** 102 Processing.                               WebDAV       */ const STATUS_102_PROCESSING                           = 102 ;

    /** 200 OK.                                                    */ const STATUS_200_OK                                   = 200 ;
    /** 201 Created.                                               */ const STATUS_201_CREATED                              = 201 ;
    /** 202 Accepted.                                              */ const STATUS_202_ACCEPTED                             = 202 ;
    /** 203 Non-Authoritative Information.            HTTP/1.1     */ const STATUS_203_NON_AUTHORITATIVE_INFORMATION        = 203 ;
    /** 204 No Content.                                            */ const STATUS_204_NO_CONTENT                           = 204 ;
    /** 205 Reset Content.                                         */ const STATUS_205_RESET_CONTENT                        = 205 ;
    /** 206 Partial Content.                          RFC 7233     */ const STATUS_206_PARTIAL_CONTENT                      = 206 ;
    /** 207 Multi-Status.                             WebDAV       */ const STATUS_207_MULTI_STATUS                         = 207 ;
    /** 208 Already Reported.                         WebDAV       */ const STATUS_208_ALREADY_REPORTED                     = 208 ;

    /** 226 IM Used.                                  RFC 3229     */ const STATUS_226_IM_USED                              = 226 ;

    /** 300 Multiple Choices.                                      */ const STATUS_300_MULTIPLE_CHOICES                     = 300 ;
    /** 301 Moved Permanently.                                     */ const STATUS_301_MOVED_PERMANENTLY                    = 301 ;
    /** 302 Found.                                                 */ const STATUS_302_FOUND                                = 302 ;
    /** 303 See Other.                                HTTP/1.1     */ const STATUS_303_SEE_OTHER                            = 303 ;
    /** 304 Not Modified.                             RFC 7232     */ const STATUS_304_NOT_MODIFIED                         = 304 ;
    /** 305 Use Proxy.                                Deprecated   */ const STATUS_305_USE_PROXY                            = 305 ;
    /** 306 Switch Proxy.                             Not used     */ const STATUS_306_SWITCH_PROXY                         = 306 ;
    /** 307 Temporary Redirect.                       HTTP/1.1     */ const STATUS_307_TEMPORARY_REDIRECT                   = 307 ;
    /** 308 Permanent Redirect.                       RFC 7538     */ const STATUS_308_PERMANENT_REDIRECT                   = 308 ;

    /** 400 Bad Request.                                           */ const STATUS_400_BAD_REQUEST                          = 400 ;
    /** 401 Unauthorized.                             RFC 7235     */ const STATUS_401_UNAUTHORIZED                         = 401 ;
    /** 402 Payment Required.                         Future use   */ const STATUS_402_PAYMENT_REQUIRED                     = 402 ;
    /** 403 Forbidden.                                             */ const STATUS_403_FORBIDDEN                            = 403 ;
    /** 404 Not Found.                                             */ const STATUS_404_NOT_FOUND                            = 404 ;
    /** 405 Method Not Allowed.                                    */ const STATUS_405_METHOD_NOT_ALLOWED                   = 405 ;
    /** 406 Not Acceptable.                                        */ const STATUS_406_NOT_ACCEPTABLE                       = 406 ;
    /** 407 Proxy Authentication Required.            RFC 7235     */ const STATUS_407_PROXY_AUTHENTICATION_REQUIRED        = 407 ;
    /** 408 Request Timeout.                                       */ const STATUS_408_REQUEST_TIMEOUT                      = 408 ;
    /** 409 Conflict.                                 Apache       */ const STATUS_409_CONFLICT                             = 409 ;
    /** 410 Gone.                                                  */ const STATUS_410_GONE                                 = 410 ;
    /** 411 Length Required.                                       */ const STATUS_411_LENGTH_REQUIRED                      = 411 ;
    /** 412 Precondition Failed.                      RFC 7232     */ const STATUS_412_PRECONDITION_FAILED                  = 412 ;
    /** 413 Request Entity Too Large.                 RFC 7231     */ const STATUS_413_REQUEST_ENTITY_TOO_LARGE             = 413 ;
    /** 414 Request-URI Too Long.                     RFC 7231     */ const STATUS_414_REQUEST_URI_TOO_LONG                 = 414 ;
    /** 415 Unsupported Media Type.                                */ const STATUS_415_UNSUPPORTED_MEDIA_TYPE               = 415 ;
    /** 416 Requested Range Not Satisfiable.          RFC 7233     */ const STATUS_416_REQUESTED_RANGE_NOT_SATISFIABLE      = 416 ;
    /** 417 Expectation Failed.                                    */ const STATUS_417_EXPECTATION_FAILED                   = 417 ;
    /** 418 I\'m a teapot.                            RFC 2344     */ const STATUS_418_I_AM_A_TEAPOT                        = 418 ;
    /** 419 Authentication Timeout.                   Not standard */ const STATUS_419_AUTHENTICATION_TIMEOUT               = 419 ;
    /** 420 Enhance Your Calm.                        Twitter      */ const STATUS_420_ENHANCE_YOUR_CALM                    = 420 ;
    /** 421 Misdirected Request.                      RFC 7540     */ const STATUS_421_MISDIRECTED_REQUEST                  = 421 ;
    /** 422 Unprocessable Entity.                     WebDAV       */ const STATUS_422_UNPROCESSABLE_ENTITY                 = 422 ;
    /** 423 Locked.                                   WebDAV       */ const STATUS_423_LOCKED                               = 423 ;
    /** 424 Failed Dependency.                        WebDAV       */ const STATUS_424_FAILED_DEPENDENCY                    = 424 ;

    /** 426 Upgrade Required.                         RFC 2817     */ const STATUS_426_UPGRADE_REQUIRED                     = 426 ;

    /** 428 Precondition Required.                    RFC 6585     */ const STATUS_428_PRECONDITION_REQUIRED                = 428 ;
    /** 429 Too Many Requests.                        RFC 6585     */ const STATUS_429_TOO_MANY_REQUESTS                    = 429 ;

    /** 431 Request Header Fields Too Large.          RFC 6585     */ const STATUS_431_REQUEST_HEADER_FIELDS_TOO_LARGE      = 431 ;

    /** 444 No Response.                              nginx        */ const STATUS_444_NO_RESPONSE                          = 444 ;

    /** 449 Retry With.                               Microsoft    */ const STATUS_449_RETRY_WITH                           = 449 ;
    /** 450 Blocked by Windows Parental Controls.     Microsoft    */ const STATUS_450_BLOCKED_BY_WINDOWS_PARENTAL_CONTROLS = 450 ;
    /** 451 Unavailable For Legal Reasons.            RFC 7725     */ const STATUS_451_UNAVAILABLE_FOR_LEGAL_REASONS        = 451 ;

    /** 494 Request Header Too Large.                 nginx        */ const STATUS_494_REQUEST_HEADER_TOO_LARGE             = 494 ;
    /** 495 Cert Error.                               nginx        */ const STATUS_495_CERT_ERROR                           = 495 ;
    /** 496 No Cert.                                  nginx        */ const STATUS_496_NO_CERT                              = 496 ;
    /** 497 HTTP to HTTPS.                            nginx        */ const STATUS_497_HTTP_TO_HTTPS                        = 497 ;

    /** 499 Client Closed Request.                    nginx        */ const STATUS_499_CLIENT_CLOSED_REQUEST                = 499 ;

    /** 500 Internal Server Error.                                 */ const STATUS_500_INTERNAL_SERVER_ERROR                = 500 ;
    /** 501 Not Implemented.                                       */ const STATUS_501_NOT_IMPLEMENTED                      = 501 ;
    /** 502 Bad Gateway.                                           */ const STATUS_502_BAD_GATEWAY                          = 502 ;
    /** 503 Service Unavailable.                                   */ const STATUS_503_SERVICE_UNAVAILABLE                  = 503 ;
    /** 504 Gateway Timeout.                                       */ const STATUS_504_GATEWAY_TIMEOUT                      = 504 ;
    /** 505 HTTP Version Not Supported.                            */ const STATUS_505_HTTP_VERSION_NOT_SUPPORTED           = 505 ;
    /** 506 Variant Also Negotiates.                  RFC 2295     */ const STATUS_506_VARIANT_ALSO_NEGOTIATES              = 506 ;
    /** 507 Insufficient Storage.                     WebDAV       */ const STATUS_507_INSUFFICIENT_STORAGE                 = 507 ;
    /** 508 Loop Detected.                            WebDAV       */ const STATUS_508_LOOP_DETECTED                        = 508 ;
    /** 509 Bandwidth Limit Exceeded.                 Apache       */ const STATUS_509_BANDWIDTH_LIMIT_EXCEEDED             = 509 ;
    /** 510 Not Extended.                             RFC 2774     */ const STATUS_510_NOT_EXTENDED                         = 510 ;
    /** 511 Network Authentication Required.          RFC 6585     */ const STATUS_511_NETWORK_AUTHENTICATION_REQUIRED      = 511 ;

    /** 598 Network read timeout error.               Proxy        */ const STATUS_598_NETWORK_READ_TIMEOUT_ERROR           = 598 ;
    /** 599 Network connect timeout error.            Proxy        */ const STATUS_599_NETWORK_CONNECT_TIMEOUT_ERROR        = 599 ;


    /**
     * Returns a short description of the status code.
     *
     * If not found :
     * - $show_family_if_unknow==true  shows a generic description for status family.
     * - $show_family_if_unknow==false returns a empty string.
     * @param int $status_code
     * @param bool $show_family_if_unknow
     * @return string
     */
    public function Str(int $status_code, bool $show_family_if_unknow = true) : string
    {
        if ($show_family_if_unknow)
            return     self::$status_str[$status_code]
                    ?? self::$family_xx_str [intdiv($status,100)]
                    ?? self::$family_xx_str [0];

        return self::$status_str[$status_code]  ?? '';
    }
}

