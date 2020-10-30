<?php
/**
 * modelo de dados para nt_manager_pappeis. Todos os papéis que estão disponíveis
 * para serem assissiados aos usuários
 *
 * @author Felipe Rosa
 */
class nt_manager_menus_papeis extends NT_Model {
    
    private $validation = array(
        array('field' => 'NT_MANAGER_PAPEL_ID', 'label' => 'Papel', 'rules' => 'required|integer'),
        array('field' => 'NT_MANAGER_MENU_ID', 'label' => 'Menu', 'rules' => 'required|integer'),
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

