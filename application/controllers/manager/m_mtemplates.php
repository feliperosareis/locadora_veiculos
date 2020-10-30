<?php
/**
 * Description of m_emails_templates
 *
 * @author Felipe Rosa
 */
class m_mtemplates  extends NT_Manager_Controller{
    
    private $crud;
    
    public function __construct() {
        parent::__construct();

        $this->checkLogin();

        $this->load->library('grocery_CRUD');
        
        $this->crud = new grocery_CRUD();
        
        $this->load->model("nt_global_mtemplates");
        
        $this->crud->set_rules($this->nt_global_mtemplates->getRules())
                   ->special_for_id(1,false,false,true)
                   ->auto_label($this->nt_global_mtemplates->getRules())
                   ->set_table($this->nt_global_mtemplates->getSft())
                   ->unset_print()
                   ->set_relation("NT_GLOBAL_IDIOMA_ID", "nt_global_idiomas", "IDIOMA_TRADUZIDO");

        
        if (!$this->nt_manager_permissoes->isValid(array("manager", "mtemplates", "index", "export")))
            $this->crud->unset_export();
        
        if (!$this->nt_manager_permissoes->isValid(array("manager", "mtemplates", "index", "add")))
            $this->crud->unset_add();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "mtemplates", "index", "edit")))
            $this->crud->unset_edit();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "mtemplates", "index", "delete")))
            $this->crud->unset_delete();
        
    }

    public function index() {
        
        $this->load->config('grocery_crud');
        
        // basic 2 tem o campo de imagens
        $this->config->set_item('grocery_crud_text_editor_type','basic2');
        
        $this->crud->set_field_editor_upload_folder("assets/uploads/nt_global_mtemplates");
        
        $data['crud'] = $this->crud->render();
        $this->load->view("manager/m_default/index", $data);
    }

}

