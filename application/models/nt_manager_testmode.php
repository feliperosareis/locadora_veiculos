<?php

/**
 * Model para o test mode, habilita ou desabilita a funcionalidade basicamente.
 * 
 * Há um usuário global de testes que é compartilhado entre as pessoas da homologação
 * 
 * @author Felipe Rosa
 */

class nt_manager_testmode extends NT_Model {

    private $validation = array(
        array('field' => 'ATIVO', 'label' => 'O _test mode está', 'rules' => 'required'),
        array('field' => 'USUARIO', 'label' => 'Usuário', 'rules' => 'required'),
        array('field' => 'SENHA', 'label' => 'Senha', 'rules' => 'required')
    );
    
    /**
     * Devolve o array com as validações para esta model
     * 
     * @return array com as regras de validação desta model
     */
    public function getRules(){
        return $this->validation;
    }
    
    
    public function getTestMode(){
        return $this->db->where("ID",1)->get($this->sft)->row_array();
    }
}
