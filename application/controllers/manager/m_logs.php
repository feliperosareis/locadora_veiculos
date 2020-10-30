<?php

/**
 * Description of m_logs
 *
 * @author Felipe Rosa
 */
class m_logs extends NT_Manager_Controller {

    private $crud;

    public function __construct() {
        parent::__construct();

        $this->checkLogin();

        $this->load->library('grocery_CRUD');
        $this->crud = new grocery_CRUD();

        $this->load->model("nt_global_logs");
        
        $this->crud->set_rules($this->nt_global_logs->getRules())
                    ->auto_label($this->nt_global_logs->getRules())
                    ->set_table($this->nt_global_logs->getSft())
                    ->set_subject("Logs do sistema")
                    ->change_field_type('CONSULTA_SQL', 'string')
                    ->columns("NT_MANAGER_USUARIO_ID", "DATA_HORA", "OPERACAO", "DESCRICAO", "IP_ORIGEM")
                    ->set_relation("NT_MANAGER_USUARIO_ID", "nt_manager_usuarios", "USUARIO")
                    ->order_by("ID", "DESC")
                    ->unset_print()
                    ->add_multiselect(base_url()."manager/logs/multiselect/")
                    ->unset_texteditor("DESCRICAO");

        $this->crud->change_field_type("CONSULTA_SQL", "text");
        $this->crud->unset_texteditor("CONSULTA_SQL");

        if (!$this->nt_manager_permissoes->isValid(array("manager", "logs", "index", "add")))
            $this->crud->unset_add();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "logs", "index", "edit")))
            $this->crud->unset_edit();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "logs", "index", "delete")))
            $this->crud->unset_delete();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "logs", "index", "export")))
            $this->crud->unset_export();
    }

    
    /**
     * Por questão de segurança, o grocery está jogando de volta para o controller
     * que chamou, assim aqui neste metodo se pode constrolar segurança e permissões
     * se for implementado na camada do grocery, seria um permenitr ou  não para todos
     * 
     * @param type $acao
     * @param type $ids
     */
    public function multiselect($acao = false, $ids = false){
        $this->load->model("nt_grocery");
        return $this->nt_grocery->ajxmultiselect($acao, $ids);
    }    
    
    
    public function index() {

        //$this->crud->set_theme('datatables');
        $crud = $this->crud->render();
        $data['crud'] = $crud;

        $this->load->view("manager/m_logs/index", $data);
    }

}
