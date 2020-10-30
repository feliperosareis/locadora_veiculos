<?php

/**
 * Description of m_contato
 *
 * @author Felipe Rosa
 */
class m_contatos extends NT_Manager_Controller {

    private $crud;

    public function __construct() {
        parent::__construct();

        //$this->checkLogin();
        $this->load->library('grocery_CRUD');
        $this->crud = new grocery_CRUD();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "contatos") + array("add")))
            $this->crud->unset_add();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "contatos") + array("edit")))
            $this->crud->unset_edit();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "contatos") + array("delete")))
            $this->crud->unset_delete();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "contatos") + array("export")))
            $this->crud->unset_export();

        $this->crud->unset_print();
    }

    public function index() {

        $this->tabela = 'nt_global_contatos';
        $this->load->model($this->tabela);
        $this->crud ->set_rules($this->{$this->tabela}->getRules())
        ->auto_label($this->{$this->tabela}->getRules())
        ->set_table($this->tabela)
        ->set_subject("Contatos")
        ->columns('NOME','TELEFONE','CIDADE','ESTADO')
        ->unset_texteditor('MSG')
        ->set_field_upload("NOME", "assets/uploads/nt_global_contatos")
        ->callback_after_upload(array($this,'resize'))
        ->add_multiselect(base_url() .'manager/contatos/selecao_multipla/',true);

        $crud = $this->crud->render();

        $data['crud'] = $crud;
        $this->load->view("manager/m_default/index", $data);
    } 
}
