<?php

/**
 * Description of m_parametros
 *
 * @author Felipe Rosa
 */
class m_parametros extends NT_Manager_Controller {

    private $crud;

    public function __construct() {
        parent::__construct();

        $this->checkLogin();

        $this->load->library('grocery_CRUD');
        $this->load->library('email');
        $this->load->library('instagram');

        $this->crud = new grocery_CRUD();

        $this->load->model("nt_global_parametros");
        
        $this->crud->set_rules($this->nt_global_parametros->getRules())
                   ->auto_label($this->nt_global_parametros->getRules())
                   ->set_table($this->nt_global_parametros->getSft())
                   ->set_subject("Parâmetros do sistema")
                   ->columns('IDENTIFICADOR', 'VALOR_PARAM')
                   ->unset_print();

        $this->crud->unset_texteditor("VALOR_PARAM");

        if (!$this->nt_manager_permissoes->isValid(array("manager", "parametros", "index", "export")))
            $this->crud->unset_export();        
        
        if (!$this->nt_manager_permissoes->isValid(array("manager", "parametros", "index", "add")))
            $this->crud->unset_add();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "parametros", "index", "edit")))
            $this->crud->unset_edit();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "parametros", "index", "delete")))
            $this->crud->unset_delete();
    }

    public function index($filtro = "") {
                                                
        // conforme o filtro que veio, traz apenas alguns params na tela
        switch ($filtro) {

            case 'seo':
                $this->crud->like("IDENTIFICADOR", 'seo_');
                break;

            case 'images':
                $this->crud->like("IDENTIFICADOR", 'image_');
                break;

            case 'emails':
                $this->crud->or_like("IDENTIFICADOR", 'mail_');
                $this->crud->or_like("IDENTIFICADOR", 'default_mailer_');
                break;

            case 'seguranca':
                $this->crud->or_like("IDENTIFICADOR", 'nt_tam_min_password');
                $this->crud->or_like("IDENTIFICADOR", 'nt_tam_max_password');
                $this->crud->or_like("IDENTIFICADOR", 'delete_last_log_on_insert_new');
                break;
            
            case 'instagram':
                $this->crud->or_like("IDENTIFICADOR", 'api_instagram');
                break;
            
            case 'facebook':
                $this->crud->or_like("IDENTIFICADOR", 'api_facebook_');
                break;
            
            case 'twitter':
                $this->crud->or_like("IDENTIFICADOR", 'twitter_');
                break;            
            default:
            // não filtra por nada, do 
        }

        
         $id = end($this->uri->segments);
         
        switch ($id) {
             case 35:                        
                 $client_id = $this->nt_global_parametros->q('api_instagram_client_id');
                 $redirect_url = $this->nt_global_parametros->q('api_instagram_redirect_uri');
                 $link = "https://instagram.com/oauth/authorize/?scope=likes+comments+relationships+basic&response_type=code&client_id=$client_id&redirect_uri=$redirect_url";
                 $this->crud->add_comment('VALOR_PARAM', 'Caso não esteja preenchido o valor do parâmetro: <Br><b>1º)</b> <a href="'.$link.'" target="_blank">Clique Aqui</a> <Br><b>2º)</b> Faça o login com os dados do cliente. (caso esteja logado com outro usuário, altere para o correto)<Br><b>3º)</b> Aceite as permissões<Br><b>4º)</b> Anote o valor do CODE que é exibido na URL e preenchena no campo "Valor do Parâmetro"<Br><b>5º)</b> Salve, clicando em "Aplicar alterações e voltar para listagem"');                        
                 break;
             case 36:                        
                 $client_id = $this->nt_global_parametros->q('api_instagram_client_id');
                 $redirect_url = $this->nt_global_parametros->q('api_instagram_redirect_uri');
                 $link = "https://instagram.com/oauth/authorize/?client_id=$client_id&redirect_uri=$redirect_url&response_type=token";
                 $this->crud->add_comment('VALOR_PARAM', 'Caso não esteja preenchido o valor do parâmetro: <Br><b>1º)</b> <a href="'.$link.'" target="_blank">Clique Aqui</a> <Br><b>2º)</b> Faça o login com os dados do cliente. (caso esteja logado com outro usuário, altere para o correto)<Br><b>3º)</b> Aceite as permissões<Br><b>4º)</b> Anote o valor do ACCESS_TOKEN que é exibido na URL e preenchena no campo "Valor do Parâmetro"<Br><b>5º)</b> Salve, clicando em "Aplicar alterações e voltar para listagem"');                        
                 break;
             case 37:
                 $username = $this->nt_global_parametros->q('api_instagram_username');
                 $access_token = $this->nt_global_parametros->q('api_instagram_access_token');
                 $user_id = $this->nt_global_parametros->q('api_instagram_user_id');                
                 if (empty($user_id)){
                    $user_id = $this->instagram->getUserId($username,$access_token);
                    if($user_id > 0){
                        $this->nt_global_parametros->update('api_instagram_user_id', $user_id);
                    }
                 }
                 $link = "https://api.instagram.com/v1/users/search?q=$username&access_token=$access_token";
                 $this->crud->add_comment('VALOR_PARAM', 'Caso não esteja preenchido o valor do parâmetro: <Br><b>1º)</b> <a href="'.$link.'" target="_blank">Clique Aqui</a> <Br><b>2º)</b> Anote o valor do ID que for exibido"');                        
                 break;
             case 38:
                 $this->crud->add_comment('VALOR_PARAM', '  <b>AVISOS IMPORTANTE</b><Br>
                                                            <b>Obs¹</b>: Caso queira que a busca dos feeds não utilize HashTag, deixe o valor do parâmetro = NULO<br>
                                                            <b>Obs²</b>: Caso a HashTag esteja preenchida, os feeds serão capturadores de todos os usuários e não será possível atualizar o número de "Curtidas"!');                        
                 break;
        }


        $data['cssexec'] = "";
        
        // fim filtro dos dados de parametros conforme ha filtro ou não

        // quando esta em edicao, nao deixa mexer na descrico do
        // parametros   
        if($this->crud->getState() == 'edit'){
            $data['jsexec'] = '$("#field-DESCRICAO").attr("readonly","true"); ';   
            $data['cssexec'] .= '#field-DESCRICAO { ';
            $data['cssexec'] .= '  box-shadow:none;';
            $data['cssexec'] .= '  border:0;';
            $data['cssexec'] .= '  background-color: #EFEFEF;';
            $data['cssexec'] .= '}';
        }
        
        
        // Fix visual para o estrago causado pela string, token muito 
        // grande do FB qu estraga a alisragem um pouco.
        $data['cssexec'] .= ".flexigrid div.bDiv td div {";
        $data['cssexec'] .= "width: 54px;";
        $data['cssexec'] .= "}";
        
        $data['cssexec'] .= ".flexigrid .tools{";
        $data['cssexec'] .= "position:relative;";
        $data['cssexec'] .= "left:138px;";
        $data['cssexec'] .= "}";
                
        
        $this->crud->add_tooltip_description("IDENTIFICADOR", "Por esta string que será feita a query quando se precisar do valor deste parametro (método ->q('identifer-string'); )");
        
        $crud = $this->crud->render();
        $data['crud'] = $crud;

        $this->load->view("manager/m_parametros/index", $data);
    }
   

    /*
     * Tela de teste de email
     */
    public function mailForm() {

        // eh uma tela de crud, mas sem gravar nada em banco
        $data = array();

        // mostra o sub menu de parametros
        $data['showSubMenusParams'] = true;

        if ($this->input->post("remetente") and $this->input->post("destinatario")
                and $this->input->post("assunto") and $this->input->post("mensagem")) {

            $this->email->initialize($this->nt_global_parametros->getMailConfs());

            $this->email->to($this->input->post("destinatario"));

            $this->email->from($this->input->post("remetente"));

            $this->email->subject($this->input->post("assunto"));

            $this->email->message($this->input->post("mensagem"));

            $res = "";

            try {
                $this->email->send();
            } catch (Exception $exc) {
                $res.= $exc->getTraceAsString();
            }

            $res .= $this->email->print_debugger();
            $data['res'] = $res;
        }

        $this->load->view("manager/m_parametros/mail_form", $data);
    }

    
    
    /*
     * Tela dentro dos parametros para controlar google analytics
     * Campo ativo, busca o primeiro ativo e usa no site
     */
    public function analytics() {

        $this->crud = new grocery_CRUD();

        $this->load->model("nt_global_google_analytics");
        
        $this->crud->set_rules($this->nt_global_google_analytics->getRules())
                   ->auto_label($this->nt_global_google_analytics->getRules())
                   ->set_table($this->nt_global_google_analytics->getSft())
                   ->unset_print()
                   ->unset_texteditor("CODIGO");

        
        if (!$this->nt_manager_permissoes->isValid(array("manager", "parametros", "analytics", "index", "export")))
            $this->crud->unset_export();        
        
        if (!$this->nt_manager_permissoes->isValid(array("manager","parametros", "analytics", "index", "add")))
            $this->crud->unset_add();

        if (!$this->nt_manager_permissoes->isValid(array("manager","parametros", "analytics", "index", "edit")))
            $this->crud->unset_edit();

        if (!$this->nt_manager_permissoes->isValid(array("manager","parametros", "analytics", "index", "delete")))
            $this->crud->unset_delete();


        $data['crud'] = $this->crud->render();

        $data['showSubMenusParams'] = false;
        if ($this->crud->getState() == 'list')
            $data['showSubMenusParams'] = true;

        $this->load->view("manager/m_analytics/index", $data);
    }

}
