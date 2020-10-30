<?php
/**
 * modelo de dados para nt_setores_usuarios
 *
 * @author Felipe Rosa
 */
class nt_global_setores_usuarios extends NT_Model {
    
    private $validation = array(
        array('field' => 'NT_SETOR_ID', 'label' => 'País', 'rules' => 'required|integer'),
        array('field' => 'NT_USUARIO_ID', 'label' => 'Unidade federativa', 'rules' => 'required|integer')
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
