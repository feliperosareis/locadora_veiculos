<?php
/**
 * Gerador Automático
 * @author Felipe Rosa
 * Gerado em: Tue, 27 Oct 20 06:42:23 -0300
 */
class m_marcas extends NT_Manager_Controller {

    private $crud;
                    
    public function __construct() {
        parent::__construct();

        $this->checkLogin();
        $this->load->library('grocery_CRUD');
        $this->crud = new grocery_CRUD();
        $this->tabela = 'nt_marcas';
        $this->load->model($this->tabela);
        $this->num_rows = $this->{$this->tabela}->get()->num_rows();
        $this->bUnique = false;
        if ($this->num_rows > 0 && $this->bUnique) {
            $id = $this->{$this->tabela}->getRow();
            $this->id = $id['ID'];
        }

        if (!$this->nt_manager_permissoes->isValid($this->uri->segment_array() + array("add")) || ($this->num_rows > 0 && $this->bUnique))
            $this->crud->unset_add();

        if (!$this->nt_manager_permissoes->isValid($this->uri->segment_array() + array("edit")))
            $this->crud->unset_edit();

        if (!$this->nt_manager_permissoes->isValid($this->uri->segment_array() + array("delete")) || ($this->num_rows > 0 && $this->bUnique))
            $this->crud->unset_delete();

        if (!$this->nt_manager_permissoes->isValid($this->uri->segment_array() + array("export")) || ($this->num_rows > 0 && $this->bUnique))
            $this->crud->unset_export();

        $this->crud->unset_print();
    }                    

    public function index() {
        
        if (in_array($this->crud->getState(), array('list', 'success')) && ($this->num_rows > 0 && $this->bUnique)) {
            redirect('/manager/marcas/index/edit/' . $this->id);
        }
        
        $this->crud ->set_rules($this->{$this->tabela}->getRules())
                    ->auto_label($this->{$this->tabela}->getRules())
                    ->set_table($this->tabela)
                    ->set_subject("Marcas")
                    ->add_multiselect(base_url() .'manager/marcas/selecao_multipla/',true)
			        ->add_bolean_status_switcher('STATUS', base_url().'manager/marcas/alterar_status/')
			        ->unset_columns('STATUS')
                    ->columns();
                    
        if ($this->num_rows > 0 && $this->bUnique)
            $this->crud->unset_back_to_list();
                    
        $crud = $this->crud->render();
                
        $data['jsexec'] = '';

        $data['crud'] = $crud;
        $this->load->view("manager/m_default/index", $data);
    }
                
    
}
    