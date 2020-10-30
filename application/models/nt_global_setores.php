<?php

/**
 * modelo de dados para nt_global_setores
 *
 * @author Felipe Rosa
 */
class nt_global_setores extends NT_Model {

    private $validation = array(
        array('field' => 'SETOR', 'label' => 'Setor', 'rules' => 'required'),
        array('field' => 'ATIVO', 'label' => 'Ativo', 'rules' => 'required|integer'),
        array('field' => 'EMAIL_CONTATO', 'label' => 'E-mail para contato', 'rules' => 'required|valid_email')
    );
    
    // método publico para ler as regras de validacao deste objeto
    public function getRules(){
        return $this->validation;
    }
    
    

}
