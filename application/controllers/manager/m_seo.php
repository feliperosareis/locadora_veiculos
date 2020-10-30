<?php

/**
 * Description of m_seo
 *
 * @author Felipe Rosa
 */
class m_seo extends NT_Manager_Controller {

    private $crud;

    public function __construct() {
        parent::__construct();

        $this->checkLogin();

        $this->load->library('grocery_CRUD');
        $this->crud = new grocery_CRUD();
        
        $this->load->model("nt_global_seo");
        
        $this->crud->set_rules($this->nt_global_seo->getRules())
                   ->auto_label($this->nt_global_seo->getRules())
                   ->set_table($this->nt_global_seo->getSft())
                   ->set_subject("Search Engine Optimization")
                   ->columns('TITULO', 'DESCRICAO', 'PALAVRASCHAVES', 'IDENTIFICADOR')
                   ->change_field_type('DESCRICAO', 'text')
                   ->unset_texteditor("DESCRICAO")
                   ->unset_print();

        
        if (!$this->nt_manager_permissoes->isValid(array("manager", "seo", "index", "export")))
            $this->crud->unset_export();        
        
        if (!$this->nt_manager_permissoes->isValid(array("manager", "seo", "index", "add")))
            $this->crud->unset_add();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "seo", "index", "edit")))
            $this->crud->unset_edit();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "seo", "index", "delete")))
            $this->crud->unset_delete();
    }

    public function index() {
        
        $this->crud->add_tooltip_description('PALAVRASCHAVES', "Algo entre 10 e menos que 30 palavras, 
                                                                separadas por vírgula.");
        
        $this->crud->add_tooltip_description("IDENTIFICADOR",
                                                 "A parte da URL do site que difere de seu endereço base.<br/>
                                                 Ex.: http://www.site.com.br/pt/apartamentos <br/>
                                                 O identificador para esta página seria: <b>pt/apartamentos</b>
                                                 ");
        
        $data['crud'] = $this->crud->render();
        $this->load->view("manager/m_default/index", $data);
    }

}
