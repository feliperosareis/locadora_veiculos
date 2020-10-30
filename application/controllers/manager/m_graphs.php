<?php

/**
 * Description of m_graphs
 *
 * @author Felipe Rosa
 */
class m_graphs extends NT_Manager_Controller {

    private $crud;

    public function __construct() {
        parent::__construct();

        $this->load->library('grocery_CRUD');
        $this->crud = new grocery_CRUD();

        $this->checkLogin();

        $this->load->model("nt_global_graphs");

        $this->crud->set_rules($this->nt_global_graphs->getRules())
                ->auto_label($this->nt_global_graphs->getRules())
                ->set_table($this->nt_global_graphs->getSft())
                ->set_subject('Configurações de imagens')
                ->columns("IDENTIFICADOR", "DISKPATH", "ALLOWED_SIEZES")
                ->unset_print();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "graphs", "index", "export")))
            $this->crud->unset_export();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "graphs", "index", "add")))
            $this->crud->unset_add();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "graphs", "index", "edit")))
            $this->crud->unset_edit();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "graphs", "index", "delete")))
            $this->crud->unset_delete();
    }

    public function index() {

        $this->crud->add_tooltip_description("IDENTIFICADOR", "Este valor estará na URL ao chamar uma destas imagens");

        $this->crud->add_tooltip_description("DISKPATH", "Informe a partir do raiz do projeto. O que está antes ignore que ele monta sozinho");

        $this->crud->add_tooltip_description("ALLOWED_SIEZES", "Ex.: 800x600|253x160");

        $data['crud'] = $this->crud->render();
        $this->load->view("manager/m_default/index", $data);
    }

}