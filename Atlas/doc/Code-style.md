[xperimentX atlas documentation](README.md) 
\ [About Atlas](About.md)

![xperimentx atlas](images/atlas.png) 

# Code Style

* File encoding in UTF-8 without BOM.

* Indenting and braces: **Allman style**.

* Using **snake_case**:
  * Classes, namespaces, methods and functions: with capital initial.
  * Variables in lower case.
  * Constants all in upper case.

* Using spaces: 
  * Indent using 4 spaces steps.  
  * Colunm identation for quick viewing:  I like big monitors, so we take advantage of them.

### Example


~~~php
<?php
namespace Vendor_name\Package_name;

use Foo_interface;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

/**
* Documentation for class
*/
class Foo extends Bar implements FooInterface
{
    /**
     * Documentation for method
     * @param int $a Doc 1st parameter
     * @param int $b Doc for 2nd
     * @return int
     */
    public function Sample_method($a, $b = 5)
    {
        // Allman style
        while ($a == $b)
        {
            Something();
            Something_else();
        }


        if ($a == $b) 
        {
            $a++;
            Foo_bar();
        } 
        else 
        {
            $a--;
            Bar_foo();
        }

        return $a + $b;
    }
}
 


/**
 * Many classes with a long list of attributes become more legible 
 * if they are organized in columns, as if they were tables.
 */
class In_columns
{     
    /** Status pending, booking pending     */  const STATUS_PENDING    = 10;  
    /** Status in room, check in done       */  const STATUS_IN         = 20;    
    /** Status out, checout done            */  const STATUS_OUT        = 30;
    /** Status cancelled, booking cancelled */  const STATUS_CANCELLED  = 90;

    /** @var string  Client name            */  public $name            = NULL;
    /** @var string  Client email           */  public $email           = NULL;
    /** @var string  Client phone           */  public $contact_phone   = NULL;

    public function Set_client_data()    
    {
        $this->$name          = $name          ;           
        $this->$email         = $email         ;
        $this->$contact_phone = $contact_phone ;
    }

    public function  Other_tastes ()
    {
        // Use multi when appropriate due to condition, operations or comments
        $ternary = $condition_very_lage    // maybe a comment
                 ? $a + $b + $c            // maybe a comment a
                 : $b;                     // maybe a comment b 


         // if short-blocks
        if ($result != $condition)
           Do_your_work(); 


        // if short blocks, never nested, simple operetions
        if     ($short_codition)   Do_your_work(); 
        elseif ($other_condition)  Do_your_dream();  
        else                       Do _waht_yoo_ned;                              

         
        if     ($short_codition)   $a = 10; 
        elseif ($other_condition)  $a = 13*$b;  
        else                       $a = sin($b*360)/13; 
    }
}    
~~~
