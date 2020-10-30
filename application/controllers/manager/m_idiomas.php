<?php
/**
 * Description of m_idiomas
 *
 * @author Felipe Rosa
 */
class m_idiomas extends NT_Manager_Controller {

    private $crud;

    public function __construct() {
        parent::__construct();

        $this->checkLogin();

        $this->load->library('grocery_CRUD');

        $this->crud = new grocery_CRUD();

        $this->load->model("nt_global_idiomas");
        $this->crud->set_rules($this->nt_global_idiomas->getRules())
                    ->auto_label($this->nt_global_idiomas->getRules())
                    ->set_table($this->nt_global_idiomas->getSft())
                    ->unset_columns("ATIVO")
                    ->add_bolean_status_switcher("ATIVO", base_url()."manager/idiomas/booleanswitcher/")
                    ->unset_print();

        // no insert e update, a abreviatura vai para minúscula!
        $this->crud->callback_before_insert(array($this, 'abreviatura_lower'));
        $this->crud->callback_before_update(array($this, 'abreviatura_lower'));

        
        if (!$this->nt_manager_permissoes->isValid(array("manager", "idiomas", "index", "export")))
            $this->crud->unset_export();        
        
        if (!$this->nt_manager_permissoes->isValid(array("manager", "idiomas", "index", "add")))
            $this->crud->unset_add();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "idiomas", "index", "edit")))
            $this->crud->unset_edit();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "idiomas", "index", "delete")))
            $this->crud->unset_delete();
    }
    
    
    

    /**
     * Faz a chamada ao método que executa o switcher, melhor que tenha um atalho
     * em cada controller (onde se consegue manipular permissoes) do que um geral (na internal)
     * em que se tinha que dar permissões a todos.
     * 
     * @param type $base64_table_and_field
     * @param type $row_ID
     */
    public function booleanswitcher($base64_table_and_field, $row_ID){
        $this->load->model("nt_grocery");
        $this->nt_grocery->ajxBooleanReverseStatusDecode($base64_table_and_field, $row_ID);
        
    }
    

    public function index() {
        $data['crud'] = $this->crud->render();
        
        // o campo da abreviatuta deve ser menos largo que os demais
        $data['jsexec'] = "$('#field-ABREVIATURA').css('width','40px');";
        
        $this->load->view("manager/m_default/index", $data);
    }

    
    public function abreviatura_lower($post_array) {
        $post_array['ABREVIATURA'] = strtolower($post_array['ABREVIATURA']);
        return $post_array;
    }

}

