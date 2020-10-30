<?php

/**
 * Description of m_setores
 *
 * @author Felipe Rosa
 */
class m_setores extends NT_Manager_Controller {

    private $crud;

    public function __construct() {
        parent::__construct();

        $this->checkLogin();

        $this->load->library('grocery_CRUD');
        
        $this->crud = new grocery_CRUD();

        $this->load->model("nt_global_setores");
        
        $this->crud->set_rules($this->nt_global_setores->getRules())
                   ->auto_label($this->nt_global_setores->getRules())
                    ->set_table($this->nt_global_setores->getSft())
                    ->set_subject("Setores")
                    ->unset_print();

        
        if (!$this->nt_manager_permissoes->isValid(array("manager", "setores", "index", "export")))
            $this->crud->unset_export();
        
        if (!$this->nt_manager_permissoes->isValid(array("manager", "setores", "index", "add")))
            $this->crud->unset_add();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "setores", "index", "edit")))
            $this->crud->unset_edit();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "setores", "index", "delete")))
            $this->crud->unset_delete();
    }

    public function index() {

        $crud = $this->crud->render();
        $data['crud'] = $crud;

        $this->load->view("manager/m_usuarios/default", $data);
    }

}
