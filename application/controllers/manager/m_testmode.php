<?php

/**
 * Tela em que se habilita e desabilita o modo de teste do site, 
 * a questão de restringir por uma segunda senha a visualização do 
 * site
 *
 * @author Felipe Rosa
 */
class m_testmode extends NT_Manager_Controller{
    
    private $crud;
    
    public function __construct() {
        parent::__construct();
        
        $this->checkLogin(true);
        
        $this->load->library('grocery_CRUD');
        $this->crud = new grocery_CRUD();

        $this->load->model("nt_manager_testmode");
        
        $this->crud->set_rules($this->nt_manager_testmode->getRules())
                   ->auto_label($this->nt_manager_testmode->getRules())
                    ->set_table($this->nt_manager_testmode->getSft())
                    ->set_subject("Configs do _test mode")
                    ->unset_add()
                    ->unset_delete()
                    ->unset_export() // aqui nao tem export mesmo, nem tem lista
                    ->unset_back_to_list();
                    
    }
    
    public function index(){

        
        $crud = $this->crud->render();
        $data['crud'] = $crud;

        $this->load->view("manager/m_testmode/index", $data);        
        
    }
}
