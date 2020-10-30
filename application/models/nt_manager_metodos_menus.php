<?php

/**
 * modelo de dados para nt_manager_menus
 *
 * @author Felipe Rosa
 */
class nt_manager_metodos_menus extends NT_Model {
    
    private $validation = array(
        array('field' => 'NT_MANAGER_METODO_ID', 'label' => 'Método', 'rules' => 'required|integer'),
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

