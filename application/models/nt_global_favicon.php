<?php

/**
 * modelo de dados para nt_global_paises. Porvê os dados dos países
 * em formato adequado para uso nos selects/dropdown lists/combo`s.
 *
 * @author Felipe Rosa
 */
class nt_global_favicon extends NT_Model {

    private $validation = array(
        array('field' => 'SITE_FAV', 'label' => 'Favicon do site', 'rules' => 'required'),
        array('field' => 'MANAGER_FAV', 'label' => 'Favicon do manager', 'rules' => 'required')
    );

    /**
     * Devolve o array com as validações para esta model
     * 
     * @return array com as regras de validação desta model
     */
    public function getRules() {
        return $this->validation;
    }
    
    
    public function getFav($manager=false){
        $rs = $this->db->get($this->sft)->row_array();
        
        if(!$manager)
            return imgu()."nt_global_favicon/".$rs['SITE_FAV'];
        else
            return imgu()."nt_global_favicon/".$rs['MANAGER_FAV'];
    }

}

