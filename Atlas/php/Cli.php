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

namespace Xperimentx\Atlas;

/**
 * Cli - Command line tools
 *
 * $cli->fg_color.$text.end_color;
 * $cli->fg_color.$cli->bg_color.$text.$cli->reset;
 *
 * @author Roberto González Vázquez
 */
class Cli
{
    /** @var string[[] color available                 */   public $colors = [];

    /** @var string reset                              */   public $reset           = "\033[0m";

    /** @var string bold / bright                      */   public $bold            = "\033[1m";
    /** @var string bold / bright  unset               */   public $bold_unset      = "\033[2m";
    /** @var string italic                             */   public $italic          = "\033[3m";
    /** @var string underline                          */   public $underline       = "\033[4m";
    /** @var string inverse                            */   public $inverse         = "\033[7m";
    /** @var string bold / bright unset alternative    */   public $bold_unset_2    = "\033[22m";
    /** @var string italic unset                       */   public $italic_unset    = "\033[23m";
    /** @var string underline unset                    */   public $underline_unset = "\033[24m";
    /** @var string normal colors                      */   public $normal_colors   = "\033[27m";
    /** @var string foreground reset                   */   public $fg_reset        = "\033[39m";
    /** @var string background reset                   */   public $bg_reset        = "\033[49m";

    /** @var string foreground black                   */   public $fg_black        = "\033[0;30m";
    /** @var string foreground red                     */   public $fg_red          = "\033[0;31m";
    /** @var string foreground green                   */   public $fg_green        = "\033[0;32m";
    /** @var string foreground brown                   */   public $fg_brown        = "\033[0;33m";
    /** @var string foreground blue                    */   public $fg_blue         = "\033[0;34m";
    /** @var string foreground purple                  */   public $fg_purple       = "\033[0;35m";
    /** @var string foreground cyan                    */   public $fg_cyan         = "\033[0;36m";
    /** @var string foreground gray                    */   public $fg_gray         = "\033[0;37m";
    /** @var string foreground light black, dark gray  */   public $fg_light_black  = "\033[1;30m";
    /** @var string foreground light red               */   public $fg_light_red    = "\033[1;31m";
    /** @var string foreground light green             */   public $fg_light_green  = "\033[1;32m";
    /** @var string foreground yellow                  */   public $fg_yellow       = "\033[1;33m";
    /** @var string foreground light blue              */   public $fg_light_blue   = "\033[1;34m";
    /** @var string foreground light purple            */   public $fg_light_purple = "\033[1;35m";
    /** @var string foreground light cyan              */   public $fg_light_cyan   = "\033[1;36m";
    /** @var string foreground white                   */   public $fg_white        = "\033[1;37m";

    /** @var string background black                   */   public $bg_black        = "\033[40m";
    /** @var string background red                     */   public $bg_red          = "\033[41m";
    /** @var string background green                   */   public $bg_green        = "\033[42m";
    /** @var string background yellow                  */   public $bg_yellow       = "\033[43m";
    /** @var string background blue                    */   public $bg_blue         = "\033[44m";
    /** @var string background magenta                 */   public $bg_magenta      = "\033[45m";
    /** @var string background cyan                    */   public $bg_cyan         = "\033[46m";
    /** @var string background gray                    */   public $bg_gray         = "\033[47m";


    public function __construct()
    {
        $this->Activate_colors();
    }


    public function Activate_colors()
    {
        // Set up shell colors
        $this->colors['reset'           ] = $this->reset            = "\033[0m";
        $this->colors['bold'            ] = $this->bold             = "\033[1m";
        $this->colors['bold_unset'      ] = $this->bold_unset       = "\033[2m";
        $this->colors['italic'          ] = $this->italic           = "\033[3m";
        $this->colors['underline'       ] = $this->underline        = "\033[4m";
        $this->colors['inverse'         ] = $this->inverse          = "\033[7m";
        $this->colors['bold_unset_2'    ] = $this->bold_unset_2     = "\033[22m";
        $this->colors['italic_unset'    ] = $this->italic_unset     = "\033[23m";
        $this->colors['underline_unset' ] = $this->underline_unset  = "\033[24m";
        $this->colors['normal_colors'   ] = $this->normal_colors    = "\033[27m";
        $this->colors['fg_reset'        ] = $this->fg_reset         = "\033[39m";
        $this->colors['bg_reset'        ] = $this->bg_reset         = "\033[49m";
        $this->colors['fg_black'        ] = $this->fg_black         = "\033[0;30m";
        $this->colors['fg_red'          ] = $this->fg_red           = "\033[0;31m";
        $this->colors['fg_green'        ] = $this->fg_green         = "\033[0;32m";
        $this->colors['fg_brown'        ] = $this->fg_brown         = "\033[0;33m";
        $this->colors['fg_blue'         ] = $this->fg_blue          = "\033[0;34m";
        $this->colors['fg_purple'       ] = $this->fg_purple        = "\033[0;35m";
        $this->colors['fg_cyan'         ] = $this->fg_cyan          = "\033[0;36m";
        $this->colors['fg_gray'         ] = $this->fg_gray          = "\033[0;37m";
        $this->colors['fg_light_black'  ] = $this->fg_light_black   = "\033[1;30m";
        $this->colors['fg_light_red'    ] = $this->fg_light_red     = "\033[1;31m";
        $this->colors['fg_light_green'  ] = $this->fg_light_green   = "\033[1;32m";
        $this->colors['fg_yellow'       ] = $this->fg_yellow        = "\033[1;33m";
        $this->colors['fg_light_blue'   ] = $this->fg_light_blue    = "\033[1;34m";
        $this->colors['fg_light_purple' ] = $this->fg_light_purple  = "\033[1;35m";
        $this->colors['fg_light_cyan'   ] = $this->fg_light_cyan    = "\033[1;36m";
        $this->colors['fg_white'        ] = $this->fg_white         = "\033[1;37m";
        $this->colors['bg_black'        ] = $this->bg_black         = "\033[40m";
        $this->colors['bg_red'          ] = $this->bg_red           = "\033[41m";
        $this->colors['bg_green'        ] = $this->bg_green         = "\033[42m";
        $this->colors['bg_yellow'       ] = $this->bg_yellow        = "\033[43m";
        $this->colors['bg_blue'         ] = $this->bg_blue          = "\033[44m";
        $this->colors['bg_magenta'      ] = $this->bg_magenta       = "\033[45m";
        $this->colors['bg_cyan'         ] = $this->bg_cyan          = "\033[46m";
        $this->colors['bg_gray'         ] = $this->bg_gray          = "\033[47m";
    }


    /**
     * Deactivate colors.
     */
    public function Deactivate_colors()
    {
        foreach ($this->colors as $index=>$value)
        {
            $this->colors[$index] = $this->$index= '';
        }
    }


    /**
     * Returns colored string
     *
     * @param string $string Text to be colored
     * @param string $foreground_color Index of colors array or any special formating text.
     * @param string $background_color Index of colors array or any special formating text.
     * @return type
     */
    public function Color_string($string, $foreground_color=null, $background_color=null)
    {
        $fore = $this->colors[$foreground_color] ?? $foreground_color;
        $back = $this->colors[$background_color] ?? $background_color;

        return $fore.$back.$string.$this->reset;
    }


    /**
     * Checks that the environment is cli
     * @return bool
     */
    public function  Is_cli_environment ()
    {
        global  $argv;

        return isset($argv[0]);
    }


    /**
     * Ensures that the environment is cliD.
     * Dies the program if not is cli whit 403.
     */
    public function  Require_cli_environment ()
    {
        global  $argv;
        $argv[0] ?? header(($_SERVER["SERVER_PROTOCOL"]??'HTTP/1.1').' 403 Forbidden') & exit();
    }
}
