<?php

/**
 * modelo de dados para nt_manager_papeis
 *
 * @author Felipe Rosa
 */
class nt_manager_papeis extends NT_Model{
    
    private $validation = array(
        array('field' => 'NOME', 'label' => 'Nome', 'rules' => 'required'),
        array('field' => 'ATIVO', 'label' => 'Ativo', 'rules' => 'required|integer'),
        array('field' => 'URLPOSLOGIN', 'label' => 'URL pós login no manager', 'rules' => '')
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

