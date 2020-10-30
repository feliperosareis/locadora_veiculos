<?php

/**
 * Description of m_cidades
 *
 * @author Felipe Rosa
 */
class m_cidades extends NT_Manager_Controller {

    private $crud;

    public function __construct() {
        parent::__construct();

        $this->checkLogin();

        $this->load->library('grocery_CRUD');
        $this->crud = new grocery_CRUD();

        $this->load->model("nt_global_cidades");
        
        $this->crud->set_rules($this->nt_global_cidades->getRules())
                   ->auto_label($this->nt_global_cidades->getRules())
                   ->set_table($this->nt_global_cidades->getSft())
                   ->set_subject("Cidades")
                   ->columns('CIDADE', 'NT_GLOBAL_ESTADO_ID', 'CEP_INICIAL', 'CEP_FINAL')
                   ->set_relation('NT_GLOBAL_ESTADO_ID', 'nt_global_estados', 'NOME_ESTADO')
                   ->unset_print();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "cidades", "index", "export")))
            $this->crud->unset_export();
        
        if (!$this->nt_manager_permissoes->isValid(array("manager", "cidades", "index", "add")))
            $this->crud->unset_add();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "cidades", "index", "edit")))
            $this->crud->unset_edit();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "cidades", "index", "delete")))
            $this->crud->unset_delete();
    }

    public function index() {
        $crud = $this->crud->render();
        $data['crud'] = $crud;

        $this->load->view("manager/m_default/index", $data);
    }
}
