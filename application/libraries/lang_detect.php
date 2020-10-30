<?php
/**
 * According to the browser preferences and avaliable languages of the website, return
 * the best mach valiable/userPreference combination
 *
 * @author Felipe Rosa
 */
class lang_detect {

    public $langs;
    public $default = "pt";
    public $browser = "";

    public function __construct() {
        
        // fetch from DB the avaliable languages
        $ci = &get_instance();
        $rs = $ci->db->select('ABREVIATURA')->where("ATIVO", 1)->get("nt_global_idiomas")->result_array();
        
        $listos = array();
        
        foreach($rs as $row){
            $listos[] = $row['ABREVIATURA'];
        }
        
        $this->langs = $listos;
        
        $browserList = (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))?$_SERVER['HTTP_ACCEPT_LANGUAGE']:"";
        $this->browser = explode(",",$browserList);
    }

    public function get() {
        $found = null;

        // for each system language
        foreach ($this->browser as $blang) {

            // checek if they are in browser preferences. First mach in slang and blang is perfect!
            // it means that is the main language of website, and the most wished language of the user.
            foreach ($this->langs as $slang) {
                if (preg_match("/$slang/", $blang)) {
                    $found = $slang;
                    break;
                }
            }
            
            if($found != null)
                break;
        }

        
        if ($found == null)
            $found = $this->default;
        
        return $found;
    }

}