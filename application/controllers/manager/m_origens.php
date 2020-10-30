<?php

/**
 * Description of m_origens
 *
 * @author Felipe Rosa
 */
class m_origens extends NT_Manager_Controller {

    private $crud;

    public function __construct() {
        parent::__construct();

        $this->checkLogin();

        $this->load->library('grocery_CRUD');
        $this->crud = new grocery_CRUD();

        $this->load->model("nt_manager_origens");

        $this->crud->set_rules($this->nt_manager_origens->getRules())
                ->auto_label($this->nt_manager_origens->getRules())
                ->set_table($this->nt_manager_origens->getSft())
                ->columns("IP_PERMITIDO_INI", "IP_PERMITIDO_FIM")
                ->set_subject("Origens")
                ->add_bolean_status_switcher('ATIVO', base_url() . "manager/origens/booleanswitcher/")
                ->unset_print();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "origens", "index", "export")))
            $this->crud->unset_export();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "origens", "index", "add")))
            $this->crud->unset_add();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "origens", "index", "edit")))
            $this->crud->unset_edit();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "origens", "index", "delete")))
            $this->crud->unset_delete();
    }

    /**
     * Faz a chamada ao método que executa o switcher, melhor que tenha um atalho
     * em cada controller (onde se consegue manipular permissoes) do que um geral (na internal)
     * em que se tinha que dar permissões a todos.
     * 
     * @param type $base64_table_and_field
     * @param type $row_ID
     */
    public function booleanswitcher($base64_table_and_field, $row_ID) {
        $this->load->model("nt_grocery");
        $this->nt_grocery->ajxBooleanReverseStatusDecode($base64_table_and_field, $row_ID);
    }

    public function index() {

        $crud = $this->crud->render();
        $data['crud'] = $crud;

        $data['cssexec'] = '#field-IP_PERMITIDO_INI { width:150px; } ';
        $data['cssexec'] .= '#field-IP_PERMITIDO_FIM { width:150px; } ';

        $this->load->view("manager/m_default/index", $data);
    }

}
