<?php

/**
 * Description of m_favicon
 *
 * @author Felipe Rosa
 */
class m_favicon extends NT_Manager_Controller {

    private $crud;

    public function __construct() {
        parent::__construct();

        $this->checkLogin();

        $this->load->library('grocery_CRUD');
        
        $this->crud = new grocery_CRUD();

        $this->load->model("nt_global_favicon");
        
        $this->load->config('grocery_crud');
        $this->config->set_item('grocery_crud_file_upload_allow_file_types','png|ico|gif|jpg');
        
        
        $this->crud->set_rules($this->nt_global_favicon->getRules())
                   ->auto_label($this->nt_global_favicon->getRules())
                    ->set_table($this->nt_global_favicon->getSft())
                    ->set_subject("Favicon")
                    ->set_field_upload("SITE_FAV", "assets/uploads/nt_global_favicon")
                    ->set_field_upload("MANAGER_FAV", "assets/uploads/nt_global_favicon")
                    ->unset_print()->unset_export()->unset_delete()->unset_add();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "favicon", "index", "edit")))
            $this->crud->unset_edit();
    }

    public function index() {

        $crud = $this->crud->render();
        $data['crud'] = $crud;

        $data['cssexec'] = ".extgif img{ height: 16px; cursor: auto; } ";
        $data['cssexec'].= ".extico img{ height: 16px; cursor: auto; } ";
        
        $this->load->view("manager/m_default/index", $data);
    }

}
