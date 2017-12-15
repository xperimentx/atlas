<?php
/**
 * xperimentX Atlas Toolkit
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
 * Profile item and error info item
 *
 * @author Roberto González Vázquez
 */

class Profile_item
{
    /**@var string Method             */  public $method             = null;
    /**@var int    Error code         */  public $error_code         = null;
    /**@var string Error description  */  public $error_description  = null;
    /**@var query                     */  public $query              = null;
    /**@var float  Seconds            */  public $seconds            = null;


    function __construct(string $method, string $query, float $prev_microtime, int $error_code=null, string $error_description=null)
    {
        $this->method       = $method      ;
        $this->query        = $query       ;
        $this->seconds      = microtime(true)-$prev_microtime;
        $this->error_code         = $error_code        ;
        $this->error_description  = $error_description ;
    }

    public function __toString()
    {
        if (!$this->error_code)
        {
            return (sprintf(    "Db profile - %.6f s - Method: %s\nQuery:\n%s",
                                $this->seconds,
                                $this->method,
                                $this->query));
        }

        return (sprintf(    "Db error (%d) - %.6f s - Method: %s\n%s\nQuery:\n%s",
                            $this->error_code,
                            $this->seconds,
                            $this->method,
                            $this->error_description,
                            $this->query));
    }
}
