<?php

/**
 * Description of m_instagramFeeds
 *
 * @author Felipe Rosa
 */
class m_instagram_feeds extends NT_Manager_Controller {

    private $crud;

    public function __construct() {
        parent::__construct();

        $this->checkLogin();

        $this->load->library('grocery_CRUD');
        $this->crud = new grocery_CRUD();

        $this->load->model("nt_global_instagram_feeds");

        $this->crud->set_rules($this->nt_global_instagram_feeds->getRules())
                ->auto_label($this->nt_global_instagram_feeds->getRules())
                ->set_table($this->nt_global_instagram_feeds->getSft())
                ->set_subject("Feeds do Instagram")
                ->columns("DATA_CRIADA", "LINK_EXTERNO", "IMAGEM_MINIATURA", "CURTIDAS")
                ->change_field_type('DATA_CRIADA', 'readonly')
                ->change_field_type('DESCRICAO', 'readonly')
                ->change_field_type('TAGS', 'readonly')
                ->change_field_type('LINK_EXTERNO', 'readonly')
                ->change_field_type('CURTIDAS', 'readonly')
                ->change_field_type('IMAGEM_MINIATURA', 'readonly')
                ->change_field_type('IMAGEM_BAIXA_RESOLUCAO', 'readonly')
                ->change_field_type('IMAGEM_PADRAO', 'readonly')
                ->change_field_type('ID_FOTO_INSTAGRAM', 'readonly')
                ->add_bolean_status_switcher('ATIVO', base_url() . "manager/instagram_feeds/booleanswitcher/")
                ->add_list_edit_order('ORDEM',  base_url()."manager/instagram_feeds/setorder/")
                ->add_multiselect(base_url()."manager/instagram_feeds/multiselect/",true)
                ->unset_print();

        $this->crud->unset_export();
        $this->crud->unset_add();



        if (!$this->nt_manager_permissoes->isValid(array("manager", "instagram_feeds", "index", "edit")))
            $this->crud->unset_edit();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "instagram_feeds", "index", "delete")))
            $this->crud->unset_delete();
    }

    
    /**
     * Implementa set order
     * 
     * @param type $field_name
     * @param type $id
     * @param type $ordem
     * @param type $tabela
     */
    public function setorder($field_name = false, $id = false, $ordem = false, $tabela = false){
        $this->load->model("nt_grocery");
        return $this->nt_grocery->ajxordem($field_name, $id, $ordem, $tabela);
    }
    
    
    /**
     * Faz a chamada ao método que executa o switcher, melhor que tenha um atalho
     * em cada controller (onde se consegue manipular permissoes) do que um geral (na internal)
     * em que se tinha que dar permissões a todos.
     * 
     * @param type $base64_table_and_field
     * @param type $row_ID
     */
    public function booleanswitcher($base64_table_and_field, $row_ID) {
        $this->load->model("nt_grocery");
        $this->nt_grocery->ajxBooleanReverseStatusDecode($base64_table_and_field, $row_ID);
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

        // Dependent dropdown, ref: http://demo.edynamics.co.za/grocery_crud/index.php/examples/customers_management/add
        $crud = $this->crud->render();
        $data['crud'] = $crud;
        $data['hashtag'] = $this->nt_global_parametros->q('api_instagram_hashtag');

        $this->load->view("manager/m_instagram/index", $data);
    }

    public function novos_feeds() {

        $access_token = $this->nt_global_parametros->q('api_instagram_access_token');
        $hashtag = $this->nt_global_parametros->q('api_instagram_hashtag');
        if (strtoupper($hashtag) == 'NULO') {
            $user_id = $this->nt_global_parametros->q('api_instagram_user_id');
            $ultima_data_criada = $this->nt_global_instagram_feeds->getLastDate();
            echo $this->nt_global_instagram_feeds->getFeeds($user_id, $access_token, NULL, $ultima_data_criada);
        } else {
            echo $this->nt_global_instagram_feeds->getFeedsByHashTag($hashtag, $access_token);
        }
    }

    public function atualiza_curtidas() {

        $access_token = $this->nt_global_parametros->q('api_instagram_access_token');
        $user_id = $this->nt_global_parametros->q('api_instagram_user_id');
        echo $this->nt_global_instagram_feeds->getUpdateCurtidas($user_id, $access_token);
    }

}
