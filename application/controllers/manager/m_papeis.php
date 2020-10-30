<?php

/**
 * Description of m_papeis
 *
 * @author Felipe Rosa
 */
class m_papeis extends NT_Manager_Controller {

    private $crud;

    public function __construct() {
        parent::__construct();

        $this->checkLogin();

        $this->load->library('grocery_CRUD');
        $this->crud = new grocery_CRUD();

        $this->load->model("nt_manager_papeis");
        
        $this->crud->set_rules($this->nt_manager_papeis->getRules())
                   ->auto_label($this->nt_manager_papeis->getRules())
                   ->set_table($this->nt_manager_papeis->getSft())
                   ->unset_columns("URLPOSLOGIN")
                   ->set_subject("Papeis dos usuários")
                   ->unset_print();

        
        if (!$this->nt_manager_permissoes->isValid(array("manager", "papeis", "index", "export")))
            $this->crud->unset_export();
        
        if (!$this->nt_manager_permissoes->isValid(array("manager", "papeis", "index", "add")))
            $this->crud->unset_add();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "papeis", "index", "edit")))
            $this->crud->unset_edit();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "papeis", "index", "delete")))
            $this->crud->unset_delete();

        // origens
        $this->crud->set_relation_n_n("origens", "nt_manager_origens_papeis", "nt_manager_origens", "NT_MANAGER_PAPEL_ID", "NT_MANAGER_ORIGEN_ID", "{IP_PERMITIDO_INI} até {IP_PERMITIDO_FIM}");

        // metodos
        $this->crud->set_relation_n_n("metodos", "nt_manager_metodos_papeis", "nt_manager_metodos", "NT_MANAGER_PAPEL_ID", "NT_MANAGER_METODO_ID", "METODO");
        
        // menus
        $this->crud->set_relation_n_n('menus', 'nt_manager_menus_papeis', 'nt_manager_menus', "NT_MANAGER_PAPEL_ID", "NT_MANAGER_MENU_ID", "IDENTIFICADOR");
    }

    public function index() {
        
        $this->crud->add_tooltip_description("origens","A partir de qual endereço IP o usuário que tiver esse papel poderá fazer login?");
        
        
        
        $crud = $this->crud->render();

        $data['crud'] = $crud;

        $data['trees'] = false;
        if ($this->crud->getState() == 'edit' or $this->crud->getState() == 'add') {
            $s = $this->uri->segments;
            $c = count($s);
            $data['trees'] = true;
            $data['valor'] = intval($s[$c]);
        }

        $this->load->view("manager/m_papeis/index", $data);
    }

    
    // se o papel vem em branco, estou em modo de add, se nao tem que trazer marcado os que eu ja tenho
    public function menus($papel = 0) {
        
        $data = array();
        
        $this->load->model("nt_manager_menus");
        $data['menus'] = $this->nt_manager_menus->getTreeMenus();

        // quais sao os menus que ja estao checados, ja relacionados a este papel
        $lsView = array();
        if ($papel != 0) { // se eu estiver em modo de edicao
            
            $lsMenus = $this->nt_manager_menus->getMenusOf($papel);
            
            if (count($lsMenus) > 0) {
                foreach ($lsMenus as $row)
                    $lsView[] = $row['NT_MANAGER_MENU_ID'];
            }
        }
        
        $data['jr'] = $lsView;// jr -> Já Relacionados
        
        $this->load->view("manager/m_papeis/menus", $data);
    }

    // monta a lista de metodos (tree), se vier 0, eh modo de add, se outro, marca os metodos ja relacionados
    public function metodos($papel = 0) {
        
        $data = array();
        
        $this->load->model("nt_manager_metodos");
        
        $data['metodos'] = $this->nt_manager_metodos->getTreeMetodos();
        
        $lsView = array();
        
        if ($papel != 0) { // se eu estiver em modo de edicao
            
            $lsMetodos = $this->nt_manager_metodos->getMetodosOf($papel);
            
            if (count($lsMetodos) > 0) {
                foreach ($lsMetodos as $row)
                    $lsView[] = $row['NT_MANAGER_METODO_ID'];
            }
        }
        
        $data['jr'] = $lsView;// jr -> Já Relacionados        
        
        $this->load->view("manager/m_papeis/metodos", $data);
    }

}

