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

namespace Xperimentx\Atlas;

/**
 * Autoloader, PSR-4 compatible.
 *
 * @link /Atlas/doc/Autoloader.md
 * @author Roberto González Vázquez
 */
class Autoloader
{
    /** @var string Root path  */
    static private  $root_path = null;

    /** @var string Root path of Xperimentx  */
    static private  $root_xperimentx = null;

    /** @var string Root path of Xperimentx Atlas */
    static private  $root_atlas = null;

    /** @var boll Is autoloader registered */
    static private $is_registered = false;

    /**
     * @var string[] Maps namespaces prefixes with their base directories.
     *
     * index= namespace prefixes
     * value= array of base directories
     * @see Add_namespace()
     */
    static private $namespace_map = [];

    /**
     * @var string[]  Maps classes with their file name with path.
     *
     * index= full qualified class name
     * value= file name with path.
     *
     * @see Add_class()
     * @see Add_class_aray()
     */
    static private $class_map = [];


    /**
     * Include php file for a given fully-qualified class name.
     *
     * spl_autoload_register compatible
     *
     * @param string $class_name The fully-qualified class name.
     */
    public static function Load_class($class_name)
    {
        // Check if is a mapped class
        if (isset(self::$class_map[$class_name]))
        {
            include_once self::$class_map[$class_name];
            return;
        }

        $translated     = str_replace('\\','/',$class_name).'.php';

        // Xperimentx Atlas default mapping, simply to be a little faster.
        if ('Xperimentx\\Atlas\\' === substr($class_name, 0, 17))
        {
            include_once self::$root_atlas.  substr($translated, 16);
            return;
        }

        // Xperimentx Packages default mapping, simply to be a little faster.
        if ('Xperimentx\\' === substr($class_name, 0, 11))
        {
            $pos = strpos($class_name, '\\', 12);
            include self::$root_xperimentx
                    . substr($translated, 10, $pos-9) // package
                    . 'php'
                    . substr($translated,$pos);
            return;
        }

        // load from include path
        if ($filename = stream_resolve_include_path($translated))
        {
            include_once $filename;
            return;
        }

        // Resolve namespaces prefixes
        if (self::$namespace_map)
        {
            $prefix = $class_name;

            while (false !== $pos = strrpos($prefix, '\\'))
            {
                $prefix = substr($prefix, 0 , $pos); // prefix part
                $sufix  = substr($prefix, 0 , $pos); // sufix part

                if (!isset(self::$namespace_map[$prefix.'\\'])) continue;

                foreach (self::$namespace_map[$prefix] as $base_dir)
                {
                    if ($filename = stream_resolve_include_path($base_dir.$sufix)) continue;
                    {
                        include_once $filename;
                        return;
                    }
                }
            }
        }
    }


    /**
     *  Adds a base directory for a namespace prefix.
     *
     * @param string          $namespace_prefix Namespace prefix, must end in \\ to avoid conflicts between similar prefixes
     *
     * @param string|string[] $base_dir         A Base directory or a array of directories for namespace prefix class files .
     *                                          If it is a array there will be no normalization,  base directories must not have trailing /
     *
     * @param bool            $prepend          If true, will prepend base_dir instead of appending it.
     */
    static public function Add_namespace($namespace_prefix, $base_dir, $prepend = false)
    {
        $namespace_prefix = trim($namespace_prefix, '\\').'\\' ;

        if (is_array($base_dir))
        {
            if     (!isset(self::$namespace_map[$namespace_prefix]))  self::$namespace_map[$namespace_prefix] = $base_dir;
            elseif ($prepend)                               self::$namespace_map[$namespace_prefix] = array_merge ($base_dir, self::$namespace_map[$namespace_prefix]);
            else                                            self::$namespace_map[$namespace_prefix] = array_merge (self::$namespace_map[$namespace_prefix], $base_dir);
        }


        else
        {
            $base_dir = rtrim($base_dir,'\\/') ;

            if (!isset(self::$namespace_map[$namespace_prefix]))
            {
                self::$namespace_map[$namespace_prefix] = [$base_dir];
            }
            else
            {
                if ($prepend)
                     array_unshift(self::$namespace_map[$namespace_prefix], $base_dir);
                else array_push   (self::$namespace_map[$namespace_prefix], $base_dir);
            }
        }
    }


    /**
     * Adds a filename for a full qualified class name.
     *
     * @param string      $full_qualified_class_name Full qualified class name.
     * @param string|aray $filename_with_path        File name with path.
     */
    static public function Add_class($full_qualified_class_name, $filename_with_path)
    {
        self::$class_map[$full_qualified_class_name]=$filename_with_path;
    }


    /**
     * Add an array with a class map to the current class map.
     * @param string[]      $items index: full qualified class name, value: File name with path.
     */
    static public function Add_class_array($items)
    {
        self::$class_map = array_merge(self::$class_map, $items);
    }


    /**
     * Adds a base directory to the include path
     * @param string $base_dir         A base directory to include
     * @param bool   $prepend          If true, will prepend basedir instead of appending it.
     */
    static public function Add_include_path ($base_dir, $prepend = true)
    {
        if ($prepend)
              \set_include_path (rtrim($base_dir, '\\/'). PATH_SEPARATOR . \get_include_path());
        else  \set_include_path (\get_include_path()  . PATH_SEPARATOR .rtrim($base_dir, '/'))  ;

    }


    /**
     * Registers autoloader in spl_autoload_register.
     * Include basic Atlas files.
     * @see spl_autoload_register
     * @param $root_path Root of your application for includes, it will be added to include path.
     * @param int $dir_up_levels Uses path of the Parent directory's path that is $dir_up_leves levels up.
     *
     */
    static public function Register($root_path=NULL, $dir_up_levels=0)
    {
        if (self::$is_registered) reuturn;

        self::$is_registered   = true;
        self::$root_atlas      = __DIR__;
        self::$root_xperimentx = dirname(self::$root_atlas, 2);

        if ($root_path)
        {
            self::$root_path= $dir_up_levels ? dirname($root_path, $dir_up_levels): $root_path;
            self::Add_include_path(self::$root_path , true);
        }

        // register
        spl_autoload_register('Xperimentx\Atlas\Autoloader::load_class');

        ///todo: include basic atlas files
    }
}

