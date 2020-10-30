<?php

/**
 * Description of m_metodos
 *
 * @author Felipe Rosa
 */
class m_metodos extends NT_Manager_Controller {

    private $crud;

    public function __construct() {
        parent::__construct();

        $this->checkLogin();

        $this->load->library('grocery_CRUD');
        $this->crud = new grocery_CRUD();

        $this->load->model("nt_manager_metodos");

        $this->crud->set_rules($this->nt_manager_metodos->getRules())
                ->auto_label($this->nt_manager_metodos->getRules())
                ->set_table($this->nt_manager_metodos->getSft())
                ->set_subject("Métodos")
                ->add_multiselect(base_url()."manager/metodos/multiselect/")
                ->unset_print();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "metodos", "index", "export")))
            $this->crud->unset_export();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "metodos", "index", "add")))
            $this->crud->unset_add();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "metodos", "index", "edit")))
            $this->crud->unset_edit();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "metodos", "index", "delete")))
            $this->crud->unset_delete();
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

        $this->crud->add_tooltip_description("METODO", "A URL a partir de <b>manager/</b> que responde
                                                        ao controller/método criado. <br/>
                                                        Tem que ser a árvore toda. Exemplo: <br/>
                                                        <b>/fabrica/processos/recalcular</b>
                                                        <br>
                                                        Deveria ser cadastrado: <br/>
                                                        <b>/fabrica/processos</b> e <br/>
                                                        <b>/fabrica/processos/recalcular </b>");

        $crud = $this->crud->render();
        $data['crud'] = $crud;

        if ($this->crud->getState() == 'list') {

            $figura = base_url() . "assets/img/manager/more_photos.gif";
            $url = base_url() . "manager/metodos/grocery/";

            $data['jsexec'] = "$('.tDiv2').append('<div id=\'inneradobtn\' onclick=\"win(\'$url\',400,280,\'Assistente para criar métodos\')\" style=\"cursor:pointer; padding-top: 3px; height:20px; width:317px\"><img style=\"float:left;\" src=\"$figura\"/>";
            $data['jsexec'] .= "<div style=\"padding-top:4px\">Cadastrar um controller padrão";
            $data['jsexec'] .= "</div></div>');";
        }

        $this->load->view("manager/m_default/index", $data);
    }

    public function grocery() {
        
        $this->load->model("nt_manager_metodos");
        $metodos = $this->nt_manager_metodos->getAll();
        $metodos_existentes = get_column($metodos, 'METODO');
        foreach($metodos_existentes as $metodo){
            $metodo = @explode('/',$metodo)[1];
            if($metodo){
                $lista_metodo['lista'][$metodo] = $metodo;
            }
        }
        
        $lista_metodo['lista']['index'] = 'index';
        $lista_metodo['lista']['login'] = 'login';
        $lista_metodo['lista']['ops'] = 'ops';
        $lista_metodo['lista']['upload'] = 'upload';
        $lista_metodo['lista']['uploadup'] = 'uploadup';
       
        if (!empty($this->input->post('controller'))) {            
            $_POST['controller'] = array_filter($_POST['controller']);
            foreach($this->input->post('controller') as $controller){
                
                $metodo = $this->input->post('metodo');

                $controller = preg_replace("/m_/", "", $controller,0);

                $user_choice = $this->input->post('upload');

                $array_personalizado = $this->input->post('opcoes');

                switch ($user_choice) {
                    case 'S':
                        $this->nt_manager_metodos->createMetodosGroceryCRUD($controller,$metodo, true, $array_personalizado);
                        break;
                    case 'N':
                        $this->nt_manager_metodos->createMetodosGroceryCRUD($controller,$metodo, false, $array_personalizado);
                        break;
                    case 'I':
                        $this->nt_manager_metodos->createMetodosImageCRUD($controller,$metodo);
                        break;
                }
            
            }
            
            $url = base_url() . "manager/metodos/grocery/#close";
            header("Location: $url");
        }

        $this->load->view("manager/m_metodos/grocery",$lista_metodo);
    }

}
