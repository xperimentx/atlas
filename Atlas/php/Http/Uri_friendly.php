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
 * Uri friendly parser and builder.
 *
 * @author Roberto González Vázquez
 */
class Uri_friendly extends Uri
{
    /**@var string  Friendly path part
     * If the path is /index.php/es/blog/last-news returns. Trims index.php
     * Returns: '/es/blog/last-news'
     */
    public $frienddly = '';

    /**@var string  Path of the main php file.
     * If the path is '/index.php/es/blog/last-news'. Trims index.php.
     * Returns: '/index.php'
     */
    public $php      = '';


    /**
     *
     * @param string $uri
     */
    public function Parse(string $uri)
    {
        parent::Parse($uri);

        if ($pos = strpos($this->path, '.php')) //:=
        {
            $this->php       = substr($this->path, 0, $pos+4);
            $this->frienddly = substr($this->path, $pos+4);
        }
        else
        {
            $this->php = '';
            $this->frienddly = $this->path;
        }
    }


    /**
     * Builds the URI string  from the parts and sets the $uri property.
     *
     * Recalculates path before building:
     * path = php . friendly
     *
     * @return string  URI calculated
     */
    function Build(bool $hide_password=true) :string
    {
        $this->path = $this->php.$this->frienddly;
        return parent::Build($hide_password);
    }
}