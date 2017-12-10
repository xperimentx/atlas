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
namespace Atlas\Html;

/**
 * @author    Roberto González Vázquez
 */
class Meta_tags
{

    public $big_image      = '';
    public $canonical      = '' ;
    public $description    = '' ;
    public $robots_follow  = true;
    public $robots_index   = true;
    public $site_name      = '';
    public $title          = '' ;
    public $lang           = '';


    /**
     * Assign data from an array
     * @param string[] $data index= metatag property
     * @param string $prefix Fields prefix. 'mt_'
     * @param string $sufix  Fields sufix. Indicated for langs. ex: '_es', '_en', '_01';
     */

    public function Assign ( $data, $prefix='mt_', $sufix='' )
	{
        $mt = $prefix.'big_image'    .$sufix; if (isset($data[$mt])) $this->big_image     = $data[$mt];
        $mt = $prefix.'canonical'    .$sufix; if (isset($data[$mt])) $this->canonical     = $data[$mt];
        $mt = $prefix.'description'  .$sufix; if (isset($data[$mt])) $this->description   = $data[$mt];
        $mt = $prefix.'robots_follow'.$sufix; if (isset($data[$mt])) $this->robots_follow = $data[$mt];
        $mt = $prefix.'robots_index' .$sufix; if (isset($data[$mt])) $this->robots_index  = $data[$mt];
        $mt = $prefix.'site_name'    .$sufix; if (isset($data[$mt])) $this->site_name     = $data[$mt];
        $mt = $prefix.'title'        .$sufix; if (isset($data[$mt])) $this->title         = $data[$mt];
	}


    /** Return Html code of metatags
     * @return string HTML
     */
    protected function Calculate_metatags()
    {
        $mt_big_image    ? htmlspecialchars($this->big_image     , ENT_QUOTES):'';
        $mt_canonical    ? htmlspecialchars($this->canonical     , ENT_QUOTES):'';
        $mt_description  ? htmlspecialchars($this->description   , ENT_QUOTES):'';
        $mt_site_name    ? htmlspecialchars($this->site_name     , ENT_QUOTES):'';
        $mt_title        ? htmlspecialchars($this->title         , ENT_QUOTES):'';


        if ($mt_title)         $out .="<title>$mt_title</title>\n"
                                    . "<meta  property='og:title'       content='$mt_title'/>\n"
                                    . "<meta name='twitter:title'       content='$mt_title'/>\n";



        if ($mt_canonical)     $out .="<link rel='canonical'            href='$mt_canonical'/>\n"
                                    . "<meta property='og:url'          content='$mt_canonical' />\n";

        if ($mt_site_name)     $out .="<meta property='og:site_name'    content='$mt_site_name'/>\n";

        if ($mt_big_image)     $out .="<meta property='og:image'        content='$this->mt_big_image'/>\n"
                                    . "<meta itemprop='image'           content='$this->mt_big_image' />\n"
                                    . "<meta name='twitter:image:src'   content='$this->mt_big_image' />\n"
                                    . "<meta name='twitter:card'        content='summary_large_image' />\n"
                                    ;

        if ($mt_description)   $out .="<meta name='description'         content='$mt_description'/>\n"
                                    . "<meta itemprop='description'     content='$mt_description'/>\n"
                                    . "<meta name='twitter:description' content='$mt_description'/>\n"
                                    . "<meta property='og:description'  content='$mt_description'/>\n";


        if ($this->lang)  $out.= "<meta http-equiv='content-language' content='$this->lang'/> \n";

        $out.=  "    <meta name='robots' content='".
                    ($this->robots_index ?'index,':'noindex,').
                    ($this->robots_follow?'follow':'no_folow')    ."'/>\n";

        return $out;

    }
}
