<?php
 
class m_orcamentos extends NT_Manager_Controller {

    private $crud;

    public function __construct() {
        parent::__construct();

             $this->checkLogin();
        $this->load->library('grocery_CRUD');
        $this->crud = new grocery_CRUD();  

        if (!$this->nt_manager_permissoes->isValid(array("manager", "orcamentos", "index","add")))
            $this->crud->unset_add();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "orcamentos", "index","edit")))
            $this->crud->unset_edit();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "orcamentos", "index","delete")))
            $this->crud->unset_delete();
                
        if (!$this->nt_manager_permissoes->isValid(array("manager", "orcamentos", "index","export")))
            $this->crud->unset_export();
        
        $this->crud->unset_print();
    }

    public function index() {

        $this->tabela = 'nt_orcamentos';
        $this->load->model($this->tabela);
        $this->crud ->set_rules($this->{$this->tabela}->getRules())
                    ->auto_label($this->{$this->tabela}->getRules())
                    ->set_table($this->tabela)
                    ->set_subject("Orçamento")
                    ->unset_texteditor('MENSAGEM')                    
                    ->set_relation('NT_GLOBAL_ESTADOS_ID', 'nt_global_estados', 'NOME_ESTADO')
                    ->set_relation('NT_GLOBAL_CIDADES_ID', 'nt_global_cidades', 'CIDADE')
                    ->set_relation('NT_TRATAMENTOS_ID', 'nt_tratamentos', 'NOME')
                    ;        
      
        
        $crud = $this->crud->render();      
        $data['crud'] = $crud;        

        $this->load->view("manager/m_default/index", $data);
    }

}
