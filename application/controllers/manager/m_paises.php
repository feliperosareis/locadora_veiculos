<?php

/**
 * Description of m_paises
 *
 * @author Felipe Rosa
 */
class m_paises extends NT_Manager_Controller {

    private $crud;

    public function __construct() {
        parent::__construct();

        $this->checkLogin();

        $this->load->library('grocery_CRUD');
        $this->crud = new grocery_CRUD();

        $this->load->model("nt_global_paises");

        $this->crud->set_rules($this->nt_global_paises->getRules())
                ->auto_label($this->nt_global_paises->getRules())
                ->set_table($this->nt_global_paises->getSft())
                ->columns("NOME_LOCAL","FIGURA_FLAG","NOME_PT","NOME_EN","NOME_ES","NOME_FR")
                ->add_bolean_status_switcher("REQUERCEP", base_url()."manager/paises/booleanswitcher/")
                ->set_field_upload("FIGURA_FLAG", "assets/uploads/nt_global_paises")
                ->change_field_type("REQUERCEP", "true_false")
                ->unset_print();
        
        if (!$this->nt_manager_permissoes->isValid(array("manager", "paises", "index", "export")))
            $this->crud->unset_export();
        
        if (!$this->nt_manager_permissoes->isValid(array("manager", "paises", "index", "add")))
            $this->crud->unset_add();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "paises", "index", "edit")))
            $this->crud->unset_edit();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "paises", "index", "delete")))
            $this->crud->unset_delete();
    }

    public function booleanswitcher($base64_table_and_field, $row_ID){
        $this->load->model("nt_grocery");
        $this->nt_grocery->ajxBooleanReverseStatusDecode($base64_table_and_field, $row_ID);
        
    }
    
    public function index() {

        $description = "Código de três letras usados pelo Comité Olímpico Internacional
                        <br/>para designar as nações representadas pelos atletas que participam
                        <br/>em eventos desportivos. Por razões históricas, alguns dos códigos
                        <br/>são diferentes dos códigos padronizados na norma ISO 3166-1.";

        $this->crud->add_tooltip_description("COD_COI", $description);

        $data['cssexec'] = "#field-COD_COI{ width: 50px; }";
        $data['cssexec'] .= "#field-COD_ISO_3166-2 { width: 35px; } ";

        $data['crud'] = $this->crud->render();
        $this->load->view("manager/m_default/index", $data);
    }

}
