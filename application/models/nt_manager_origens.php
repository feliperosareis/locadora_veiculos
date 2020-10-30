<?php
/**
 * modelo de dados para nt_manager_origens
 *
 * @author Felipe Rosa
 */
class nt_manager_origens extends NT_Model {
    
    // ip não usa validação "valid_ip" porque tem a excessão de poder cadastrar "não validar"
    private $validation = array(
        array('field' => 'IP_PERMITIDO_INI', 'label' => 'IP inicial permitido', 'rules' => 'required'),
        array('field' => 'IP_PERMITIDO_FIM', 'label' => 'IP final permitido', 'rules' => 'required'),
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

