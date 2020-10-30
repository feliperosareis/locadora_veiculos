<?php
/**
 * modelo de dados para nt_global_idiomas
 *
 * @author Felipe Rosa
 */
class nt_global_idiomas extends NT_Model {
    
    private $validation = array(
        array('field' => 'IDIOMA_TRADUZIDO', 'label' => 'Idioma traduzido', 'rules' => 'required'),
        array('field' => 'IDIOMA', 'label' => 'Idioma', 'rules' => 'required'),
        array('field' => 'ABREVIATURA', 'label' => 'Abreviatura', 'rules' => 'required|min_length[2]|max_length[2]'),
        array('field' => 'ATIVO', 'label' => 'Ativo', 'rules' => 'required|integer')
    );
    
    /**
     * Devolve o array com as validações para esta model
     * 
     * @return array com as regras de validação desta model
     */
    public function getRules(){
        return $this->validation;
    }       
}
