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

namespace Xperimentx\Atlas\Db;

/**
 * Description of Uri
 *
 * @author rogon
 */
class Uri
{
    public $url       = null;
    public $scheme    = null;
    public $host      = null;
    public $port      = null;
    public $user      = null;
    public $pass      = null;
    public $path      = null;
    public $query     = null;
    public $fragment  = null;

    function __construct (string $url=null)
    {
        if ($url)
            $this->Set_url ($url);
    }


    function Set_url (string $url)
    {
       $this->url = $url;
       $x = parse_url($url);

       $this->scheme    = $x['scheme'  ] ?? null;
       $this->host      = $x['host'    ] ?? null;
       $this->port      = $x['port'    ] ?? null;
       $this->user      = $x['user'    ] ?? null;
       $this->password  = $x['pass'    ] ?? null;
       $this->path      = $x['path'    ] ?? null;
       $this->query     = $x['query'   ] ?? null;
       $this->fragment  = $x['fragment'] ?? null;

       if ('http' ==$this->scheme && !$this->port) $this->port=80;
       if ('https'==$this->scheme && !$this->port) $this->port=443;
    }


    function Make_url() :string
    {
        $this->url = '';
        if ($this->scheme   ) $this->url .=  $this->scheme.'://'   ;
        if ($this->user     ) $this->url .=  $this->user.':'     ;
        if ($this->password ) $this->url .=  $this->password . '@'     ;
        if ($this->host     ) $this->url .=  $this->host     ;

        if ($this->port  and  ('http' ==$this->scheme   &&  80!=$this->port)
                          ||  ('https'==$this->scheme   && 443!=$this->port))
                              $this->url .=  ':'.$this->port     ;

        if ($this->path     ) $this->url .=  $this->path     ;
        if ($this->query    ) $this->url .=  '?'.$this->query    ;
        if ($this->fragment ) $this->url .=  '#'.$this->fragment ;


        return $this->url;
    }
}

/*
var_dump(parse_url("http://username:password@hostname:9090/path?arg=value#anchor"));

print_r(parse_url("http://username:password@hostname:9090/path?arg=value#anchor"));
var_dump(parse_url("htts://username:password@hostname/path?arg=value#anchor"));
var_dump(parse_url("https://username:password@hostname/path?arg=value"));
var_dump(parse_url("username:password@hostname/path?arg=value#anchor"));
*/
$x =new Uri("http://www.hostname/index.php/hola/path?arg=value#anchor");
print_r(parse_url("http://www.hostname/index.php/hola/path?arg=value#anchor"));
print_r($x);
var_dump($x->Make_url());
//var_dump(new Uri("/index.php/hola/path?arg=value#anchor"));