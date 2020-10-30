<?php
/**
 * NT_Default_Controller é o controller do qual todas as páginas
 * do site normalmente vão extender. Inclusive as do manager.
 * 
 * Se necessário você pode especializar (extender) este controller e então
 * as páginas públicas/site/normal extenderem dele. Atenção que o que implementado
 * nesta classe atinge o manager também uma vez que 
 * NT_Manager_Controller extends NT_Default_Controller.
 *
 * @author Felipe Rosa
 */
class NT_Default_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $testMode = $this->nt_manager_testmode->getTestMode();
        // se o test mode esta ativo, pede usuario e senha para acessar o conteúdo
        if ($testMode['ATIVO']) {
            $authok = $this->session->userdata("testmodeautorizado");
            if ($authok != 1) {
                $url = base_url() . "manager/ops/login";
                redirect($url);
                die("Test mode is on. You are not alowed to see this content");
            }
        }// fim, o test mode está ativo
    }

}

/**
 * Classe pai da qual devem extender todos os controllers do manager.
 * 
 * Exceções a isso são controlers que não tem verificação de permissões/login
 * ou que não se caracteriazam com o manager padrão. Neste caso recomendo extender 
 * de NT_Default_Controller e implementar o restante da lógica de negócio.
 * 
 * URL's de referência: 
 * - http://stackoverflow.com/questions/12833504/how-to-check-user-permission-given-to-user-code-igniter
 * - http://philsturgeon.co.uk/blog/2010/02/CodeIgniter-Base-Classes-Keeping-it-DRY
 * - http://www.highermedia.com/articles/nuts_bolts/codeigniter_base_classes_revisited
 *
 * * @author Felipe Rosa
 */
class NT_Manager_Controller extends NT_Default_Controller {

    public function __construct() {
        parent::__construct();

        // pega os dados do usuario que esta logado
        $logado = $this->session->userdata('login');
        if (!isset($logado['id']))
            $logado['id'] = -400; // se nao tiver, o id usuario eh um que nao existe

        
// monta o mapa de permissoes, que vai ser usado cada vez que chamar $this->checkLogin()
        $this->nt_manager_permissoes->buildPermissionMap($logado['id']);

        $this->load->model("nt_manager_menus");
        $this->nt_manager_menus->buildMenuMapFor($logado['id']);

        $this->load->library("greetings");
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
     * Implementa set order
     * 
     * @param type $field_name
     * @param type $id
     * @param type $ordem
     * @param type $tabela
     */
    public function setorder($field_name = false, $id = false, $ordem = false, $tabela = false) {
        $this->load->model("nt_grocery");
        return $this->nt_grocery->ajxordem($field_name, $id, $ordem, $tabela);
    }

    /**
     * Chame este método nos contrutores dos controllers dos quais quer controllar
     * permissao de acesso
     * 
     * @param bollean $noRedirect passa true se quer que ele apenas de o erro die(), false (padrão) se ele deve redirecionar embora
     */
    public function checkLogin($noRedirect = false) {

        // verifica se essa url, segmento esta no mapa do usuario logado
        // se nao esta, perdeu!
        if (!$this->nt_manager_permissoes->isValid($this->uri->segments)) {

            $strTentativa = implode("/", $this->uri->segments);
            $this->nt_global_logs->s("permissoes", "Tentativa de acesso e foi negado em \'$strTentativa\'");

            // força logout
            $this->session->unset_userdata("login");
            // $this->session->sess_destroy(); // assim pode atrapalhar outras coisas
            if ($noRedirect) {
                die("Access denied");
            } else {
                redirect(base_url() . "manager/login");
                die("Access denied");
            }
        }
    }


    
     public function isLogin($noRedirect = false) {

        $this->load->model('nt_manager_usuarios');
        $row_usuario = $this->nt_manager_usuarios->getLogedUserInfos();

        if (!$row_usuario) { 

            if ($noRedirect) {
                die("Access denied");
            } else {
                redirect(base_url() . "manager/login");
                die("Access denied");
            }
            
        }
    }
    
    public function alterar_status($base64_table_and_field, $row_ID) {
        $this->load->model("nt_grocery");
        $this->nt_grocery->ajxBooleanReverseStatusDecode($base64_table_and_field, $row_ID);
    }

    public function alterar_ordem($field_name = false, $id = false, $ordem = false, $tabela = false) {
        $this->load->model("nt_grocery");
        return $this->nt_grocery->ajxordem($field_name, $id, $ordem, $tabela);
    }

    public function selecao_multipla($acao = false, $ids = false) {
        $this->load->model("nt_grocery");
        return $this->nt_grocery->ajxmultiselect($acao, $ids);
    }

    
    function nome_empresa($valor,$id,$campo,$field){
        return file_get_contents('http://rel.leadforce.com.br/ws/NomeEmpresaByID/'.$field->FK_EMPRESAS_ID).
               '<input type="hidden" maxlength="50" value="'.$valor.'" name="'.$campo->name.'">';
    }
    
    function nome_modelo($valor,$id,$campo,$field){
        return file_get_contents('http://rel.leadforce.com.br/ws/NomeModeloByID/'.$field->FK_MODELOS_ID).
               '<input type="hidden" maxlength="50" value="'.$valor.'" name="'.$campo->name.'">';
    }
    
    function nome_midia($valor,$id,$campo,$field){
        return file_get_contents('http://rel.leadforce.com.br/ws/NomeMidiaByID/'.$field->FK_MIDIAS_ID).
               '<input type="hidden" maxlength="50" value="'.$valor.'" name="'.$campo->name.'">';
    }

    public function resize($uploader_response, $field_info, $files_to_upload) {

        $this->load->library('compact_image'); 

        $tamanhos = array();
        //if($this->tabela == 'nt_banners'){       

            $tamanhos['desktop']['x'] = 280;
            $tamanhos['desktop']['y'] = 210;
            $tamanhos['desktop']['prefixo'] = ""; 

            $tamanhos['mobile']['x'] = 130;
            $tamanhos['mobile']['y'] = 105;
            $tamanhos['mobile']['prefixo'] = "m_";  

        //}
        
        $arquivo_imagem = $field_info->upload_path . '/' . $uploader_response[0]->name;

        foreach ($tamanhos as $key => $tamanho) {

            $nova_imagem = $field_info->upload_path . '/' . $tamanho['prefixo'] . $uploader_response[0]->name;
            $imagem = getimagesize($arquivo_imagem);

            if ($imagem[0] < $tamanho['x'] && $imagem[1] < $tamanho['y']) {
                $tamanhos['normal']['x'] = $imagem[0];
                $tamanhos['normal']['y'] = $imagem[1];
            }

            $novo_arquivo_imagem = ($tamanho['prefixo'] != '' ? $nova_imagem : $arquivo_imagem);
            $this->compact_image->compactar($arquivo_imagem, $novo_arquivo_imagem, $tamanho['x'], $tamanho['y']);

        }

    }

}

class NT_Controller extends CI_Controller {
    
    public $data;
    public $is_mobile;
    public $is_tablet;

    public function __construct() {
        parent::__construct();

        $this->data['seo'] = array();
        $this->data['seo']['titulo'] = "";
        $this->data['seo']['image'] = "";
        $this->data['seo']['descricao'] = "";

        $this->data['estados'] = $this->data['sistema_modelo_id'] = "";

        $this->data['is_mobile'] = $this->is_mobile = $this->mobile_detect->isMobile();
        $this->data['is_tablet'] = $this->is_tablet = $this->mobile_detect->isTablet();

        //$this->load->model('nt_cupom_desconto');   
        //$this->data['row_cupom_desconto'] = $this->nt_cupom_desconto->getRow();        
        $this->data['row_cupom_desconto'] = array();

        $utm_source = $this->input->get('utm_source') ? $this->input->get('utm_source') : $this->input->get('origem');    
        if($utm_source){
            $this->session->set_userdata('utm_source', $utm_source);
        }

        $utm_medium = $this->input->get('utm_medium') ? $this->input->get('utm_medium') : $this->input->get('midia');
        if($utm_medium){
            $this->session->set_userdata('utm_medium', $utm_medium);
        }

        $utm_campaign = $this->input->get('utm_campaign') ? $this->input->get('utm_campaign') : $this->input->get('campanha');
        if($utm_campaign){
            $this->session->set_userdata('utm_campaign', $utm_campaign);
        }

        $utm_content = $this->input->get('utm_content') ? $this->input->get('utm_content') : $this->input->get('grupo');
        if($utm_content){
            $this->session->set_userdata('utm_content', $utm_content);
        }

        $utm_term = $this->input->get('utm_term') ? $this->input->get('utm_term') : $this->input->get('pal');
        if($utm_term){
            $this->session->set_userdata('utm_term', $utm_term);
        }


        /* DESCOMENTAR, QUANDO O BANCO DE DADOS ESTIVER ATIVO!!! */
        //$this->load->model('nt_global_estados');
        //$this->data['estados'] = $this->nt_global_estados->getEstadosHtmlOptionsSigla();
        //$this->data['seo'] = $this->nt_global_seo->getSeoFor();
     
        if (!$this->session->userdata('midia')) {

            
            if(!empty($_GET['md'])){

                $this->data['midia'] = $_GET['md'];

            } elseif($utm_source == 'adwords' && $utm_campaign == 'remarketing') {

                $this->data['midia'] = 37; //Adwords - Remarketing

            } elseif($utm_source == 'adwords' && $utm_campaign == 'display') { 

                $this->data['midia'] = 38; //Adwords - Rede Display

            } elseif($utm_source == 'adwords' && $utm_campaign == 'gmail') { 

                $this->data['midia'] = 57; //Adwords - Gmail

            } elseif(!empty($_GET['gclid']) || $utm_source == 'adwords') { //Adwords
                
                $this->data['midia'] = 3;

            } else if (localiza_palavra(@$_SERVER['HTTP_REFERER'], array('bing.', 'google.', 'yahoo'))) {

                $this->data['midia'] = 7;

            } else if (strlen(@$_SERVER['HTTP_REFERER']) > 5 && strpos(@$_SERVER['HTTP_REFERER'], 'linkdosite.') === false) {

                $this->data['midia'] = 6;

            } else {

                $this->data['midia'] = 5;

            }

            $this->data['referencia'] = @$_SERVER['HTTP_REFERER'];
            $this->session->set_userdata('midia', $this->data['midia']);
            $this->session->set_userdata('referencia', $this->data['referencia']);

        } else {

            $this->data['midia'] = $this->session->userdata('midia');
            $this->data['referencia'] = $this->session->userdata('referencia');

        }

        $this->data['token_desconto'] = '0'; 

        $this->data['token_cotacao']['ga'] = 'solicitou-cotacao';
        $this->data['token_cotacao']['token'] = '';

        $this->data['token_financiamento']['ga'] = 'solicitou-financiamento';
        $this->data['token_financiamento']['token'] = '';

        if ($this->is_mobile) {

            $this->data['token_desconto'] = '0'; 
            
        }

        $this->data['form_midia'] = $this->data['midia'];
        $this->data['form_referencia'] = $this->data['referencia'];

        $this->data['breadcrumb'][] = array(
            'titulo' => 'Home',
            'link' => site_url()
        );

        $this->data['form_load'] = null;

        $this->data['section'] = strtolower(get_class($this));
        $this->data['show_form'] = true;
    }

    protected function render($data){
        $this->load->view('site/includes/head', $this->data);
        $this->load->view('site/includes/header');
                
        foreach ( $data['pages'] as $item) 
        {
            $this->load->view('site/'. $this->data['section'] . '/' . $item );
        }

        $this->load->view('site/includes/footer');
    }

}
