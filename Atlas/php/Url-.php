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

/**
 * @author Roberto González Vázquez
 */
class Enviroment_item
{
    /** @var string host_development     */ public $host_development = 1;
    /** @var string host_stage           */ public $host_stage       = null;
    /** @var string host_production      */ public $host_production  = null;
    /** @var string Scheme https, http */   public $scheme           = null;
    /** @var string Host */                 public $host             = null;
    /** @var string Port */                 public $port             = null;
    /** @var string Accept lang */          public $accept_lang      = null;
    /** @var Database Config           */   public $db               = null;


    function __construct()
    {
        $this->db_cfg = new Xperimentx\Atlas\Db\Db_cfg();
    }
}

class Enviroment_cfg
{
    public $duplicate_production = true;
    /** @var Enviroment_item  configuration */ public  $development;
    /** @var Enviroment_item  configuration */ public  $production ;
    /** @var Enviroment_item  configuration */ public  $testing    ;

    function __construct()
    {
        self::$production  = new Enviroment_item();
        self::$development = new Enviroment_item();
        self::$testing     = new Enviroment_item();
    }
}
class Cfg extends Enviroment_cfg

Class Enviroments
{
    function Load
    {
        Cfg->production
        $cfg->db->user_name = 'atlas_db_user';
        $cfg->db->password  = 'atlas_db_passwd';
        $cfg->db->   $this->db_name   = 'atlas_demo_db';
    }
}


class Url
{
    /** @var string host_development     */ static public $host_development  = 1;
    /** @var string host_stage           */ static public $host_stage        ;
    /** @var string host_production      */ static public $host_production   ;


    /** @var string Scheme https, http */   static public $scheme   = null;
    /** @var string Host */                 static public $host     = null;
    /** @var string Port */                 static public $port     = null;
    /** @var string Accept lang */          static public $accept_lang = null;



    public function Parse()
    {
        self::$host        = self::$host        ?? $_SERVER['HTTP_HOST']            ?? null;
        self::$accept_lang = self::$accept_lang ?? $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? null; //  => 'es-ES,es;q=0.9' (length=14)
        self::$protocol    = self::$scheme      ?? $_SERVER['REQUEST_SCHEME']       ?? null; // 'http'
        self::$protocol =


        //self::$protocol = isset($_SERVER['HTTPS'])?'https://':'http://';

                    //ngix  fcgi, sacado de foros $redirect_location = $protocol . ($_SERVER['HTTP_HOST'] ?: $_SERVER['SERVER_NAME']) . $_GET['wptouch_redirect'];
            self::$host     = Cgi::Server('HTTP_HOST');
            self::$root     = &\Atlas::$root_url;
            self::$root_with_host = self::$protocol.self::$host.self::$root;



                 if (isset($_REQUEST['atlas-url']))    self::$current = $_REQUEST['atlas-url'];
            else if (isset($_SERVER['REDIRECT_URL']))  self::$current = $_SERVER['REDIRECT_URL'];
            else if (isset($_SERVER['PHP_SELF']))      self::$current = $_SERVER['PHP_SELF'];
            else                                       self::$current = null;

    }




/*
comun

      'SERVER_NAME'           => 'xatlas' (length=6)
      'SERVER_PORT'           => '80' (length=2)

 *
      'DOCUMENT_ROOT'         => 'C:/Proyectos/xperimentx/atlas-www' (length=33)
      'CONTEXT_PREFIX'        => '' (length=0)
      'CONTEXT_DOCUMENT_ROOT' => 'C:/Proyectos/xperimentx/atlas-www' (length=33)
      'SCRIPT_FILENAME'       => 'C:/Proyectos/xperimentx/atlas-www/index.php' (length=43)
      'SERVER_PROTOCOL'       => 'HTTP/1.1' (length=8)
      'REQUEST_METHOD'        => 'GET' (length=3) GET', 'HEAD', 'POST', 'PUT'.
      'QUERY_STRING'          => 'ss=32423&ssddsf=234&sdfwe=werr' (length=30)
      'PATH_INFO'             => '/ddfggf/kjldkjfdg/werkñlewrkñlkewr/lkjsadk' (length=44)
      'SCRIPT_NAME'           => '/index.php' (length=10)
      'PHP_SELF'              => '/index.php/ddfggf/kjldkjfdg/werkñlewrkñlkewr/lkjsadk' (length=54)
   __FILE__ 'REQUEST_TIME_FLOAT'
 * El timestamp del inicio de la solicitud, con precisión microsegundo. Disponible desde PHP 5.4.0.
 * 'REQUEST_TIME_FLOAT'

* rewrite off
      'REQUEST_URI'     => '/index.php/ddfggf/kjldkjfdg/werk%C3%B1lewrk%C3%B1lkewr/lkjsadk?ss=32423&ssddsf=234&sdfwe=werr' (length=93)
      'PATH_TRANSLATED' => 'C:\Proyectos\xperimentx\atlas-www\ddfggf\kjldkjfdg\werkñlewrkñlkewr\lkjsadk' (length=77)


rewirte on
      'REDIRECT_STATUS' => '200' (length=3)
      'REDIRECT_URL'    => '/ddfggf/kjldkjfdg/werkñlewrkñlkewr/lkjsadk' (length=44)

      'REQUEST_URI'     => '/ddfggf/kjldkjfdg/werk%C3%B1lewrk%C3%B1lkewr/lkjsadk?ss=32423&ssddsf=234&sdfwe=werr' (length=83)
      'PATH_TRANSLATED' => 'redirect:\index.php\ddfggf\kjldkjfdg\werkñlewrkñlkewr\lkjsadk\kjldkjfdg\werkñlewrkñlkewr\lkjsadk' (length=100)



'SCRIPT_NAME'     => '/index.php' (length=10)
'PHP_SELF'        => '/index.php/ddfggf/kjldkjfdg/werkñlewrkñlkewr/lkjsadk' (length=54)
'REQUEST_URI'     => '/index.php/ddfggf/kjldkjfdg/werk%C3%B1lewrk%C3%B1lkewr/lkjsadk?ss=32423&ssddsf=234&sdfwe=werr' (length=93)
 REQUEST_URI'     => '/ddfggf/kjldkjfdg/werk%C3%B1lewrk%C3%B1lkewr/lkjsadk?ss=32423&ssddsf=234&sdfwe=werr' (length=83)
con Rewrite REQUEST_URI /ddfggf/kjld
sin Rewrite REQUEST_URI /ddfggf/kjld
 *
 *
 */


        /**
         * slug.
         * Si se pasa Request['atlas_url] contendrá ese valor
         */
        static public $url_slug_1 ;




        /**
         * Url propia de la pagina actual. Con dominio y protocolo
         * @var string
         */
        static public $current_with_host;


        /**
         * Url propia de la pagina actual. Sin dominio ni protocolo
         * @var string
         */
        static public $current;





        static public $root_with_host;






        /**
         * Concatena a la url actual los parámetros cgi pasados por query uniéndolos con '&' o '?' según sea necesario
         * @param string $url Url previa, con o sin parámetros, null=>URL SELF
         * @param string|array[string]string $query Párametos,
         *                                   si string deberán esta codificados URLencode, si es cadena vacía la url devuelta acabará en & o ?,
         *                                   si es array el índice indica el nombre del parámetro, el valor se auto codificará URLencode
         * @return string
         */
        static function Query ($url, $query='')
        {
            if ($url===null)
                $url = self::$current_with_host;

            $cgi = is_array($query)
                 ? http_build_query($query)
                 : $query ;

            $end = substr($url, -1);

            if ($end=='?' or $end=='&')
                return $url.$cgi;

            return strpos($url, '?') ? $url.'&'.$cgi : $url.'?'.$cgi ;
        }


}

