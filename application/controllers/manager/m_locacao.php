<?php
/**
 * Gerador Automático
 * @author Felipe Rosa
 * Gerado em: Tue, 27 Oct 20 06:41:52 -0300
 */
class m_locacao extends NT_Manager_Controller {

    private $crud;
                    
    public function __construct() {
        parent::__construct();

        $this->isLogin();
        $this->load->library('grocery_CRUD');
        $this->crud = new grocery_CRUD();
        $this->tabela = 'nt_locacao';
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
            redirect('/manager/locacao/index/edit/' . $this->id);
        }
        
        $this->crud->set_rules($this->{$this->tabela}->getRules())
                   ->auto_label($this->{$this->tabela}->getRules())
                   ->set_table($this->tabela)
                   ->set_subject("Locacao")
                   ->callback_before_insert(array($this,'_calculaLocacao'))->callback_before_update(array($this,'_calculaLocacao'))
                   ->add_multiselect(base_url() .'manager/locacao/selecao_multipla/',true)
                   ->set_relation('ID_CLIENTE', 'nt_clientes', 'NOME')
                   ->unset_texteditor('OBSERVACOES')
                   ->unset_columns('OBSERVACOES')
                   ->field_type("ID_CARRO", "dropdown", $this->getCarros())
                   ->field_type('VALOR_TOTAL', 'invisible')
                   ->columns();
                    
        if ($this->num_rows > 0 && $this->bUnique)
            $this->crud->unset_back_to_list();
                    
        $crud = $this->crud->render();
                
        $data['jsexec']  = '$("#field-VALOR_DIARIA, #field-VALOR_TOTAL").mask("000.000.000,00", { reverse: true });';

        $data['cssexec'] = ".fa-calculator{ font-size: 16px; }";

        $data['crud'] = $crud;
        $this->load->view("manager/m_default/index", $data);
    }
                

    function _calculaLocacao($post_array)
    {
        $locacao = $this->{$this->tabela}->getRow();

        $data_inicio = new DateTime($locacao['DATA_RETIRADA']);
        $data_fim = new DateTime($locacao['DATA_DEVOLUCAO']);

        // Resgata diferença entre as datas
        $dateInterval = $data_inicio->diff($data_fim);

        $post_array['VALOR_TOTAL'] = $dateInterval->days * (double) moeda_bd($post_array["VALOR_DIARIA"]);
        
        return $post_array;
    }


    private function getCarros()
    {
        $this->load->model('nt_carros');
        $carros = $this->nt_carros->getCarros();

        return (array) $carros;
    }


}
    