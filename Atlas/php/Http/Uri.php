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
 * Parses an URI string to part  and generates the URI string  from parts
 *
 * Uri RFC 3986
 * @link https://tools.ietf.org/html/rfc3986#section-3.2.2
 * @link https://en.wikipedia.org/wiki/Uniform_Resource_Identifier
 *
 * URI format:
 * scheme:[//[user[:password]@]host[:port]][/path][?query][#fragment]
 *
 * This class does not urlencode or urldecoded. Only splits or merges the URI parts.
 *
 * @author Roberto González Vázquez
 */
class Uri
{
    /**@var string  Full URI parsed or built,
     *      scheme:[//[user[:password]@]host[:port]][/path][?query][#fragment]
     */
    public $uri       = null;

    /**@var string Scheme         */ public $scheme    = '';
    /**@var string Host name.     */ public $host      = '';
    /**@var string Port           */ public $port      = '';
    /**@var string User           */ public $user      = '';
    /**@var string Password.      */ public $password  = '';
    /**@var string Path           */ public $path      = '';
    /**@var string Query          */ public $query     = '';
    /**@var string Fragment       */ public $fragment  = '';

    
    /**
     * @param string $uri Uri to parse
     */
    function __construct (string $url=null)
    {
        if ($url)
            $this->Parse($url);
    }


    /**
     * Parses an URI and assign the result in the object properties.
     * @param string $uri Uri to parse
     */
    function Parse (string $uri)
    {
       $this->uri = $uri;

       $x = parse_url($uri);

       $this->scheme    = $x['scheme'  ] ?? '';
       $this->host      = $x['host'    ] ?? '';
       $this->port      = $x['port'    ] ?? '';
       $this->user      = $x['user'    ] ?? '';
       $this->password  = $x['pass'    ] ?? '';
       $this->path      = $x['path'    ] ?? '';
       $this->query     = $x['query'   ] ?? '';
       $this->fragment  = $x['fragment'] ?? '';
    }


    /**@var int[] Defaults for schemes. */
    static protected $scheme_default_ports =
    [
		'ftp'	 => 21,
		'sftp'	 => 22,
        'http'	 => 80,
		'https'	 => 443,
    ];


     /**
     * Gets the user info. user[:password].
     *
     * @param bool $hide_password Hide password  in the user info
     * @param bool $add_at_symbol If true returns 'user[:password]@'
     * @return string 'user[:password]' , epmty string if not user
     */
    public function Get_user_information(bool $hide_password=true, bool $add_at_symbol=false) :string
    {
        if (!$this->user)
            return '';

        $at_symbol = $add_at_symbol ? '@':'';

        return  ($this->password && !$hide_password)
                ? $this->user.':'.$this->password.$at_symbol
                : $this->user                    .$at_symbol;
    }


    /**
     * Gets the authority. [user[:password]@]:]host[:port] .
     *
     * The port is hidden if is the default port for the current scheme.
     *
     * @param bool $hide_password Hide password  in authority
     *
     * @return string [user[:password]@]:]host[:port]
     */
    public  function Get_authority(bool $hide_password=true) :string
    {
        if (!$this->host)
            return '';


        $authority = $this->Get_user_information($hide_password, true)
                   . $this->host;

        if ($this->port &&  $this->port !=(self::$scheme_default_ports[$this->scheme] ?? null))
        {
           $authority .=  ':'.$this->port     ;
        }

        return $authority;
    }


    /**
     * Builds the URI string  from the parts and sets the $uri property.
     *
     * @return string  URI calculated
     */
    function Build(bool $hide_password=true) :string
    {
        $this->uri = '';

        if ($this->scheme)   $this->uri .=  $this->scheme   .'://'   ;

        $this->uri .= $this->Get_authority($hide_password);
        $this->uri .= $this->path         ;

        if ($this->query    ) $this->uri .=  '?'.$this->query    ;
        if ($this->fragment ) $this->uri .=  '#'.$this->fragment ;

        return $this->uri;
    }


    /**
     * Representation of the object as string.
     * CallsBuild() to ensure updated data, hiding the password.
     */
    public function __toString()
    {
        return $this->Build();
    }
}

