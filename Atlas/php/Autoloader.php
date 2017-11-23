<?php
/**
 *  Atlas Toolkit
 *
 * @link  https://github.com/xperimentx/atlas
 * @link  https://xperimentX.com
 *
 * @copyright 2017 Roberto González Vázquez
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


    /** @var string Root path of atlas  */
    static private  $root_atlas = null;

    /** @var boll Is autoloader registered */
    static private $is_registered = false;


    /**
     * Maps namespaces prefixes with their base directories
     * @see add_map()
     */
    static private $map = [];



    /**
     * Include php file for a given fully-qualified class name.
     *
     * spl_autoload_register compatible
     *
     * @param string $class_name The fully-qualified class name.
     */
    public static function Load_class($class_name)
    {
        $translated     = str_replace('\\','/',$class_name).'.php';

        // First Atlas classe default mapping
        $filename_1st   = 'Xperimentx\\Atlas\\' === substr($class_name, 0, 17)
                        ? self::$root_atlas.substr($translated, 16)
                        : $translated;

        // load from include path
        if ($filename = stream_resolve_include_path($filename_1st))
        {
            include_once $filename;
            return;
        }


        // Resolve namespaces ptefixes
        if (self::$map)
        {
            $prefix = $class_name;

            while (false !== $pos = strrpos($prefix, '\\'))
            {
                $prefix = substr($prefix, 0 , $pos); // prefix part
                $sufix  = substr($prefix, 0 , $pos); // sufix part

                if (!isset(self::$map[$prefix.'\\'])) continue;

                foreach (self::$map[$prefix] as $base_dir)
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
     * @param string      $namespace_prefix Namespace prefix, must end in \\ to avoid conflicts between similar prefixes
     *
     * @param string|aray $base_dir         A Base directory or a array of directories for namespace prefix class files .
     *                                      If it is a array there will be no normalization,  base directories must not have trailing /
     *
     * @param bool        $prepend          If true, will prepend base_dir instead of appending it.
     */
    static public function Add_to_map($namespace_prefix, $base_dir, $prepend = false)
    {
        $namespace_prefix = trim($namespace_prefix, '\\').'\\' ;

        if (is_array($base_dir))
        {
            if     (!isset(self::$map[$namespace_prefix]))  self::$map[$namespace_prefix] = $base_dir;
            elseif ($prepend)                               self::$map[$namespace_prefix] = array_merge ($base_dir, self::$map[$namespace_prefix]);
            else                                            self::$map[$namespace_prefix] = array_merge (self::$map[$namespace_prefix], $base_dir);
        }


        else
        {
            $base_dir = rtrim($base_dir,'\\/') ;

            if (!isset(self::$map[$namespace_prefix]))
            {
                self::$map[$namespace_prefix] = [$base_dir];
            }
            else
            {
                if ($prepend)
                     array_unshift(self::$map[$namespace_prefix], $base_dir);
                else array_push   (self::$map[$namespace_prefix], $base_dir);
            }
        }
    }






    /**
     * Adds a base directory to the include path
     * @param string $base_dir         A base directory to include
     * @param bool   $prepend          If true, will prepend basedir instead of appending it.
     */
    static public function Add_to_include_path ($base_dir, $prepend = true)
    {
        if ($prepend)
              \set_include_path (rtrim($base_dir, '\\/'). PATH_SEPARATOR . \get_include_path());
        else  \set_include_path (\get_include_path()  . PATH_SEPARATOR .rtrim($base_dir, '/'))  ;

    }


    /**
     * Register autoloader in spl_autoload_register.
     * @see spl_autoload_register
     * @param $root_path Root of your application for includes, it will be added to include path.
     * @param int $dir_up_levels Uses path of the Parent directory's path that is $dir_up_leves levels up.
     */
    static public function Register($root_path=NULL, $dir_up_levels=0)
    {
        if (self::$is_registered) reuturn;
        dirname($root_path);
        self::$is_registered = true;
        self::$root_atlas    = __DIR__;

        if ($root_path)
        {
            self::$root_path= $dir_up_levels ? dirname($root_path, $dir_up_levels): $root_path;
            self::Add_to_include_path(self::$root_path , true);
        }

        // register
        spl_autoload_register('Xperimentx\Atlas\Autoloader::load_class');
    }
}


