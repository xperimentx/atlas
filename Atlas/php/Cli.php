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
 * Cli - Command line tools
 *
 * $cli->fg_color.$text.end_color;
 * $cli->fg_color.$cli->bg_color.$text.$cli->reset;
 *
 * @author Roberto González Vázquez
 */
class Cli
{
    /** @var string[] color available                  */   public $colors = [];

    /** @var string reset                              */   public $reset           ;

    /** @var string bold / bright                      */   public $bold            ;
    /** @var string bold / bright  unset               */   public $bold_unset      ;
    /** @var string italic                             */   public $italic          ;
    /** @var string underline                          */   public $underline       ;
    /** @var string inverse                            */   public $inverse         ;
    /** @var string bold / bright unset alternative    */   public $bold_unset_2    ;
    /** @var string italic unset                       */   public $italic_unset    ;
    /** @var string underline unset                    */   public $underline_unset ;
    /** @var string normal colors                      */   public $normal_colors   ;
    /** @var string foreground reset                   */   public $fg_reset        ;
    /** @var string background reset                   */   public $bg_reset        ;

    /** @var string foreground black                   */   public $fg_black        ;
    /** @var string foreground red                     */   public $fg_red          ;
    /** @var string foreground green                   */   public $fg_green        ;
    /** @var string foreground brown                   */   public $fg_brown        ;
    /** @var string foreground blue                    */   public $fg_blue         ;
    /** @var string foreground purple                  */   public $fg_purple       ;
    /** @var string foreground cyan                    */   public $fg_cyan         ;
    /** @var string foreground gray                    */   public $fg_gray         ;
    /** @var string foreground light black, dark gray  */   public $fg_light_black  ;
    /** @var string foreground light red               */   public $fg_light_red    ;
    /** @var string foreground light green             */   public $fg_light_green  ;
    /** @var string foreground yellow                  */   public $fg_yellow       ;
    /** @var string foreground light blue              */   public $fg_light_blue   ;
    /** @var string foreground light purple            */   public $fg_light_purple ;
    /** @var string foreground light cyan              */   public $fg_light_cyan   ;
    /** @var string foreground white                   */   public $fg_white        ;

    /** @var string background black                   */   public $bg_black        ;
    /** @var string background red                     */   public $bg_red          ;
    /** @var string background green                   */   public $bg_green        ;
    /** @var string background brown, dark yellow      */   public $bg_yellow       ;
    /** @var string background blue                    */   public $bg_blue         ;
    /** @var string background purple, magenta         */   public $bg_magenta      ;
    /** @var string background cyan                    */   public $bg_cyan         ;
    /** @var string background gray                    */   public $bg_gray         ;
    /** @var string background light black, dark gray  */   public $bg_light_black  ;
    /** @var string background light red               */   public $bg_light_red    ;
    /** @var string background light green             */   public $bg_light_green  ;



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
     * Returns a colored string.
     *
     * @param string $string Text to be colored
     * @param string $foreground_color Index of colors array.
     * @param string $background_color Index of colors array.
     * @return string
     */
    public function Color_string(string $string, string $foreground_color=null, string $background_color=null) : string
    {
        $fore = $this->colors[$foreground_color] ?? '';
        $back = $this->colors[$background_color] ?? '';

        return $fore.$back.$string.$this->reset;
    }


    /**
     * Checks that the environment is Cli.
     * @return bool
     */
    public function  Is_cli_environment () :bool
    {
        return defined('STDIN');
    }


    /**
     * Ensures that the environment is Cli.
     * Terminates the program if not is Cli with 403.
     */
    public function  Require_cli_environment ()
    {
        if (defined('STDIN'))
            return;

        header(($_SERVER["SERVER_PROTOCOL"]??'HTTP/1.1').' 403 Forbidden') ;
        exit();
    }


    /**
     * Returns the pallete demo
     * @return string
     */
    public function Pallete_demo() :string
    {
        $idx =
        [
            'black'       ,  'gray'       , 'red'         ,   'brown'       ,
            'green'       ,  'cyan'       , 'blue'        ,   'purple'      ,
            'light_black' ,  'white'      , 'light_red'   ,   'yellow'      ,
            'light_green' ,  'light_cyan' , 'light_blue'  ,   'light_purple',
        ];

        $bg_idx = ['blue' ,'magenta' ,'red' ,'black' ,'cyan' ,'green' ,'yellow' ,'gray' ];

        $names =
        [
            ' black  '      , ' gray   '      , ' red    '      , ' brown  '      ,
            ' green  '      , ' cyan   '      , ' blue   '      , ' purple '      ,
            ' light_black  ', ' white        ', ' light_red    ', ' yellow       ',
            ' light_green  ', ' light_cyan   ', ' light_blue   ', ' light_purple ',
        ];

        $out = "\n".
             "{$this->reset}  Normal {$this->reset}" .
             "{$this->bold } Bold   {$this->reset}".
             "{$this->underline} Underline {$this->reset}".
             "{$this->italic} Italic {$this->reset}".
             "{$this->inverse} Inverse {$this->reset}\n\n";


        $clr = $this->colors;

        for ($b=0;$b<4;$b++)
        {
            $bn1 = $bg_idx[$b ];
            $bn2 = $bg_idx[$b+4];
            $bc1 = $clr['bg_'.$bn1];
            $bc2 = $clr['bg_'.$bn2];

            $out .= sprintf ("\n  %s%-22s  %-20s\n",$this->reset, 'bg_'.$bn1,'bg_'.$bn2);

            for ($f=0; $f<8; $f++)
            {
                $fn1 = $names[$f  ];
                $fn2 = $names[$f+8];
                $fc1 = $clr['fg_'.$idx[$f  ]];
                $fc2 = $clr['fg_'.$idx[$f+8]];

                $out.=  '  '.$fc1.$bc1 .$fn1 . $fc2.$bc1. $fn2.
                        $this->reset.'  '.
                        $fc1.$bc2 .$fn1 .$fc2.$bc2 .$fn2.
                        "$this->reset\n";
            }
        }

        return $out;
    }
}

