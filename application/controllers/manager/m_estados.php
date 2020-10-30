<?php
/**
 * Description of m_estados
 *
 * @author Felipe Rosa
 */
class m_estados extends NT_Manager_Controller {

    private $crud;

    public function __construct() {
        parent::__construct();

        $this->checkLogin();

        $this->load->library('grocery_CRUD');
        $this->crud = new grocery_CRUD();

        $this->load->model("nt_global_estados");
        $this->crud->set_rules($this->nt_global_estados->getRules())
                    ->auto_label($this->nt_global_estados->getRules())
                    ->set_table($this->nt_global_estados->getSft())
                    ->set_subject('Estados')
                    ->unset_print()
                    ->set_relation('NT_GLOBAL_PAIS_ID', 'nt_global_paises', 'NOME_PT');

        if (!$this->nt_manager_permissoes->isValid(array("manager", "estados", "index", "export")))
            $this->crud->unset_export();        
        
        if (!$this->nt_manager_permissoes->isValid(array("manager", "estados", "index", "add")))
            $this->crud->unset_add();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "estados", "index", "edit")))
            $this->crud->unset_edit();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "estados", "index", "delete")))
            $this->crud->unset_delete();
    }

    public function index() {

        $data['crud'] = $this->crud->render();
        $this->load->view("manager/m_default/index", $data);
    }

}
