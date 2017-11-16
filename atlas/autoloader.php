<?php
namespace Atlas;

// Add root path to autoload
\set_include_path (ROOT_PATH . PATH_SEPARATOR . \get_include_path());

/**
 * Autoloader.
 * 
 * PSR-4 compatible
 * @author Roberto González Vázquez
 */

class Autoloader
{
    /**
     * Maps namespaces prefixes with ther base directories
     * 
     * [ 'Namespace_prefix'   => [base directories],
     *   '\Acme\Couso\Chimes  => ['./vendor/couso/chimes-src' ],
     *   'Name_space\Complex  => ['complex/src',
     *                            'complex/test' ],
     *  ...
     * ];      
     * 
     * Ex: Atlas\Autoloader::$map['App_namespace']=[Atlas\ROOT_PATH.'/app', Atlas\ROOT_PATH.'/app/src'];
     */
    static public $map = [];
            
            
    
    /**
     * Include php file for a given fully-qualified class name.      
     * @param string $class_name The fully-qualified class name.     
     */
    public static function Load_class($class_name)
    {     
        // Basic try to load direct full class name  to file name        
        
        if (file_exists( $filename = ROOT_PATH.'/'.strtolower(str_replace('\\','/',$class_name)).'.php'))
        {
            include_once $filename;        
            return;
        }
        
        // Resolve namespaces fefixes
        if (self::$map)
        {
            $prefix = $class_name;
            
            while (false !== $pos = strrpos($prefix, '\\')) 
            {
                $prefix = substr($prefix, 0 , $pos);
                $sufix  = substr($class_name, $pos);
     
                if (!isset(self::$map[$prefix])) continue;
                                             
                foreach (self::$map[$prefix] as $base_dir) 
                {                        
                    if (file_exists( $filename = $base_dir.strtolower(str_replace('\\','/',$sufix)).'.php'))
                    {
                            include_once $filename;
                            return;
                    }
                }                
            }        
        }
        
        // load from path
        include_once $filename;  
    }

            
}


spl_autoload_register('Atlas\Autoloader::load_class');
