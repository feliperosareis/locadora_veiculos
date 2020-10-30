<?php
 
class m_politica_privacidade extends NT_Manager_Controller {

    private $crud;

    public function __construct() {
        parent::__construct();

        $this->checkLogin();
        $this->load->library('grocery_CRUD');
        $this->crud = new grocery_CRUD();  

        if (!$this->nt_manager_permissoes->isValid(array("manager", "politica_privacidade", "index","add")))
            $this->crud->unset_add();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "politica_privacidade", "index","edit")))
            $this->crud->unset_edit();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "politica_privacidade", "index","delete")))
            $this->crud->unset_delete();
                
        if (!$this->nt_manager_permissoes->isValid(array("manager", "politica_privacidade", "index","export")))
            $this->crud->unset_export();
        
        $this->crud->unset_print();
    }

    public function index() {
 
        $this->tabela = 'nt_politica_privacidade';
        $this->load->model($this->tabela);
        $this->crud ->set_rules($this->{$this->tabela}->getRules())
                    ->auto_label($this->{$this->tabela}->getRules())
                    ->set_table($this->tabela)
                    ->set_subject("Política de Privacidade")  
                    ->unset_columns("STATUS")          
                    ->add_bolean_status_switcher("STATUS", base_url()."manager/politica_privacidade/alterar_status/");
        
        $crud = $this->crud->render();

        $data['crud'] = $crud;
        $this->load->view("manager/m_default/index", $data);
    }
 
}
