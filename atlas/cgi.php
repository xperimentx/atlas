<?php
namespace Atlas;

/**
 * CGI - Vectores superglobales.
 *
 * Funcionalidades básicas $_REQUEST, $_SESSION, $_GET, $_POST, $_COOKIE, $_ENV
 * CGI - Ayuda con el manejo de los vectores superglobales
 *
 * Common Gateway Interface.
 *
 * Maneja los vectores superglobales de manera sencilla:
 * - Verifica la existencia del valor
 * - Permite devolver un valor por defecto en caso de no existir el valor requerido
 *
 *
 * Superglobals  — Superglobals are built-in variables that are always available in all scopes
 * - $GLOBALS — References all variables available in global scope
 * - $_SERVER — Server and execution environment information
 * - $_GET — HTTP GET variables
 * - $_POST — HTTP POST variables
 * - $_FILES — Variables de Carga de Archivos HTTP
 * - $_REQUEST — HTTP Request variables
 * - $_SESSION — Session variables
 * - $_ENV — Variables de entorno
 * - $_COOKIE — Cookies HTTP
 * - $php_errormsg — The previous error message
 * - $HTTP_RAW_POST_DATA — Raw POST data
 * - $http_response_header — HTTP response headers
 * - $argc — The number of arguments passed to script
 * - $argv — Array of arguments passed to script
 *
 * @author 		Roberto González Vázquez  
 */
class Cgi
{
    /**
     * Obtiene el valor de un parámetro pasado por superglobal $_GET
     * @param string $index             Índice del vector superglobal pedido
     * @param misc   $default_value     Valor devuelto si no existe el valor pedido
     * @return misc Valor pedido, $default_value si no se encuentra
     */
    static function Get ($index, $default_value=null)
    {
        if (!isset ($_GET[$index]))
            return $default_value;

        return $_GET[$index];
    }




    /**
     * Obtiene el valor de un parámetro pasado por superglobal $_POST
     * @param string $index             Índice del vector superglobal pedido
     * @param misc   $default_value     Valor devuelto si no existe el valor pedido
     * @return misc Valor pedido, $default_value si no se encuentra
     */
    static function Post ($index, $default_value=null)
    {
        if (!isset ($_POST[$index]))
            return $default_value;

        return $_POST[$index];
    }

    
    /**
     * Obtiene el valor de un parámetro pasado por superglobal $_REQUEST
     * @param string $index             Índice del vector superglobal pedido
     * @param misc   $default_value     Valor devuelto si no existe el valor pedido
     * @return misc Valor pedido, $default_value si no se encuentra
     */
    static function Request ($index, $default_value=null )
    {
       if (!isset ($_REQUEST[$index]))
            return $default_value;

        return $_REQUEST[$index];
    }


    /**
     * Obtiene el valor de un parámetro pasado por superglobal $_COOKIE
     * @param string $index             Índice del vector superglobal pedido
     * @param misc   $default_value     Valor devuelto si no existe el valor pedido
     * @return misc Valor pedido, $default_value si no se encuentra
     */
    static function Cookie	($index, $default_value=null)
    {
        if (!isset ($_COOKIE[$index]))
            return $default_value;

        return $_COOKIE[$index];
    }



    /**
     * Obtiene el valor de una valiable de entorno, superglobal $_ENV.
     *
     * Estas variables son importadas ... desde el entorno bajo el que está siendo ejecutado el intérprete PHP...
     * Diferentes sistemas suelen tener diferentes tiposde intérpretes de comandos, una lista definitiva es imposible.
     *
     *
     * Esta documentación contiene extractos de de: {@link http://hoohoo.ncsa.uiuc.edu/cgi/env.html} y  {@link http://es.wikipedia.org/wiki/Common_Gateway_Interface}
     *
     * <b>Variables de entorno CGI</b>
     *
     * - <b>AUTH_TYPE:        </b> If the server supports user authentication, and the script is protects, this is the protocol-specific authentication method used to validate the user.
     * - <b>CONTENT_LENGTH:   </b> Longitud en bytes de los datos enviados al CGI utilizando el método POST. Con GET está vacía.
     * - <b>CONTENT_TYPE:     </b> Tipo MIME de los datos enviados al CGI mediante POST. Con GET está vacía. Un valor típico para esta variable es: Application/X-www-form-urlencoded.For queries which have attached information, such as HTTP POST and PUT, this is the content type of the data.
     * - <b>GATEWAY_INTERFACE:</b> Nombre y versión CGI usada por el servidor: CGI/version
     * - <b>HTTP_ACCEPT:      </b> The MIME types which the client will accept, as given by HTTP headers. Other protocols may need to get this information from elsewhere. Each item in this list should be separated by commas as per the HTTP spec. Format: type/subtype, type/subtype
     * - <b>HTTP_USER_AGENT:  </b> The browser the client is using to send the request. General format: software/version library/version.
     * - <b>PATH_INFO:        </b> Información adicional de la ruta (el "path") tal y como llega al servidor en el URL. The extra path information, as given by the client. In other words, scripts can be accessed by their virtual pathname, followed by extra information at the end of this path. The extra information is sent as PATH_INFO. This information should be decoded by the server if it comes from a URL before it is passed to the CGI script.
     * - <b>PATH_TRANSLATED:  </b> The server provides a translated version of PATH_INFO, which takes the path and does any virtual-to-physical mapping to it.
     * - <b>QUERY_STRING:     </b> Es la cadena de entrada del CGI cuando se utiliza el método GET sustituyendo algunos símbolos especiales por otros. Cada elemento se envía como una pareja Variable=Valor. Si se utiliza el método POST esta variable de entorno está vacía.The information which follows the ? in the URL which referenced this script. This is the query information. It should not be decoded in any fashion. This variable should always be set when there is query information, regardless of command line decoding.
     * - <b>REMOTE_ADDR:      </b> The IP address of the remote host making the request.
     * - <b>REMOTE_HOST:      </b> The hostname making the request. If the server does not have this information, it should set REMOTE_ADDR and leave this unset.
     * - <b>REMOTE_IDENT:     </b> If the HTTP server supports RFC 931 identification, then this variable will be set to the remote user name retrieved from the server. Usage of this variable should be limited to logging only.
     * - <b>REMOTE_USER:      </b> If the server supports user authentication, and the script is protected, this is the username they have authenticated as.
     * - <b>REQUEST_METHOD:   </b> Nombre del método (GET, POST, HEAD, etc) utilizado para la solicitud.
     * - <b>SCRIPT_NAME:      </b> Nombre del CGI invocado. A virtual path to the script being executed, used for self-referencing URLs.
     * - <b>SERVER_NAME:      </b> Nombre del servidor, DNS alias, or IP address as it would appear in self-referencing URLs.
     * - <b>SERVER_PORT:      </b> Puerto por donde se envió la solicitud.
     * - <b>SERVER_PROTOCOL:  </b> Nombre y versión del protocolo en uso. Formato: protocol/revision
     * - <b>SERVER_SOFTWARE:  </b> Nombre y versión del software servidor que esta ejecutando el cgi - Formato: name/version
     *

     * @param string $index             Índice del vector superglobal pedido
     * @param misc   $default_value     Valor devuelto si no existe el valor pedido
     * @return misc Valor pedido, $default_value si no se encuentra
     */
    static function Env	($index, $default_value=null )
    {
       if (!isset ($_ENV[$index]))
            return $default_value;

        return  $_ENV[$index];
    }


    /**
     * Obtiene el valor de un parámetro pasado por superglobal $_SERVER
     * @param string $index             Índice del vector superglobal pedido
     * @param misc   $default_value     Valor devuelto si no existe el valor pedido
     * @return misc Valor pedido, $default_value si no se encuentra
     * @see Cgi::Super_global()
     */
    static function Server	($index, $default_value=null)
    {
        if (!isset ($_SERVER[$index]))
            return $default_value;

        return $_SERVER[$index];
    }


    /**
     * Obtiene el valor de un parámetro pasado por superglobal $_SESSION
     * @param string $index             Índice del vector superglobal pedido
     * @param misc   $default_value     Valor devuelto si no existe el valor pedido
     * @return misc Valor pedido, $default_value si no se encuentra
     */
    static function Session ($index, $default_value=null)
    {
        if (!isset ($_SESSION[$index]))
            return $default_value;

        return $_SESSION[$index];
    }

    /**
     * Asigna automáticamente los valores desde Post a un objeto.
     * Primero se intenta usar índices post Set_{índice}, en su defecto se intenta con el atributo{índice}
     * @param csv|array $csv ´´
     * @see \Atlas\Fil_object
     */
    static function Post_fill($object, $csv_ignore=null, $csv_only=null)
    {
        //\Atlas::Fill_object($object, $_POST , $csv_ignore, $csv_only);
        //TODO:
    }
 
}

