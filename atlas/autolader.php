<?php
namespace Atlas;

// Add root path to autoload
\set_include_path (Atlas\ROOT_PATH . PATH_SEPARATOR . \get_include_path());

/**
 * Autoloader.
 * 
 * PSR-4 compatible
 * @author Roberto González Vázquez
 */

class Autolader
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
     */
    static public $map = [];
            
            
    
    /**
     * Include php file for a given fully-qualified class name.      
     * @param string $class_name The fully-qualified class name.     
     */
    public function loadClass($class_name)
    {
        
        // Basic try to load direct full class name  to file name        
        
        if (file_exists( $filename = ATLAS\ROOT_PATH.'/'.strtolower(str_replace('\\','/',$class_name)).'.php'))
        {
            include_once ATLAS\ROOT_PATH.'/'.$filename;        
            return;
        }
        
        // Resolve namespaces fefixes
        if (self::$map)
        {
            $prefix = $filename;
            while (false !== $pos = strrpos($prefix, '/')) 
            {
                $sufix  = substr($prefix, $pos-1);
                $prefix = substr($prefix, 0, $pos );
                
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
