<?php

/**
 * Description of mlogin
 *
 * @author Felipe Rosa
 */
class m_login extends NT_Default_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model("nt_manager_usuarios");
        $this->load->library('form_validation');
        $this->load->model("nt_manager_permissoes");
        $this->load->model("nt_manager_papeis");
        
        $this->load->library('email');
    }

    
    /**
     * Devolve a URL para onde deve redirecionar pós login de
     * um determinado usuário. URL da página de entrada pós login.
     * 
     * @param int $userID
     * @return string
     */
    private function getEingGangSeite($userID){

        // tem conf individual para este usuário
        $meIndividual = $this->nt_manager_usuarios->getWhereId($userID);
        if($meIndividual['URLPOSLOGIN'] != "")
            return base_url().$meIndividual['URLPOSLOGIN'];
        
        
        // tem conf para o papel desse usuário
        $meusPapeis = $this->nt_manager_permissoes->getPapeisFromUser($userID);
        $idPapel = $meusPapeis[0]['NT_MANAGER_PAPEL_ID'];
        $meusPapeis = $this->nt_manager_papeis->getWhereId($idPapel);
        if($meusPapeis['URLPOSLOGIN'] != "")
            return base_url().$meusPapeis['URLPOSLOGIN'];        

        
        $geral = $this->nt_global_parametros->q("global-url-pos-manager-login");
        return base_url().$geral;
    }
    
    
    public function index($n = 0) {

        $data = array();
        $captchaFlag = false;
        $tentativas = $this->session->userdata("tentativas-login");
        
        // se nao consegui errar a senha mais que 30x, capcha nele!
        if($tentativas >= 30) {
            $data['chatearUsuario'] = true;
            $captchaFlag = true;
        }

        $this->form_validation->set_rules('usuario', 'Usuário', 'trim|xss_clean|required');
        $this->form_validation->set_rules('senha', 'Senha', 'trim|xss_clean|required');

        if ($n == 404) {
            $data['message'] = "Usuário ou senha incorreta";
        }

        if ($n == 401) {
            $data['message'] = "Nenhum papel ativo para este usuário";
        }
        
        if ($n == 403) {
            $data['message'] = "Origem do acesso  não permitida";
        }

        if ($n == 408) {
            $data['message'] = "Captcha informado incorretamente.";
        }
        
        // nao validou o form, penaliza o user
        if ($this->form_validation->run() == FALSE) {
            $this->countPlusTentativaLogin();
            $this->load->view('manager/m_login/index', $data);
            
        } else {
            
            if(!$captchaFlag){
                
                $this->check(); // agora tenta validar usuário ou senha
                
            } else {
                require_once($this->config->item('local_disk_url').'application/third_party/recaptcha-php/recaptchalib.php');
                
                if($this->input->post('recaptcha_response_field') != "") {
                    
                    $resp = recaptcha_check_answer ($this->config->item("recaptcha-private-key"),
                                                  $_SERVER["REMOTE_ADDR"],
                                                  $this->input->post('recaptcha_challenge_field'),
                                                  $this->input->post('recaptcha_response_field'));

                    if (!$resp->is_valid) {
                      // What happens when the CAPTCHA was entered incorrectly
                      $this->countPlusTentativaLogin();  
                      redirect("manager/login/index/408");

                    } else {
                      // Your code here to handle a successful verification
                      $this->check(); // agora tenta validar usuário ou senha
                    }
                }else{
                    $this->load->view('manager/m_login/index', $data);
                }
                
            }
        }
    }

    public function check() {

        if ($this->nt_manager_usuarios->logInUser(
                        $this->input->post('usuario'), $this->input->post('senha')
                )
           ) {

            // ok! Logou, bem vindo ao manager!
            $this->nt_global_logs->s("login", "ok com o usuario " . $this->input->post('usuario'));
            // manda para a pagina de entrada deste usuario
            $meineID = (int) $this->session->userdata("login_id");
            
            $goToThen = $this->getEingGangSeite($meineID);
            // no logo do manager logado tem link para o valor desta session
            $this->session->set_userdata("url_pos_login", $goToThen);
            
            $this->resetCountTentativasLogin();
            redirect($goToThen);
            
        } else {
            
            $this->countPlusTentativaLogin();
            
            // try again :(
            // na classe de login, se for invalid incoming, ele seta para falso
            if ($this->session->userdata("inc") == "falso")
                redirect("manager/login/index/403");
            elseif($this->session->userdata("inc") == "nopactive"){
                redirect("manager/login/index/401");
            }else {
                $this->nt_global_logs->s("login", "falhou com o usuario " . $this->input->post('usuario'));
                redirect("manager/login/index/404");
            }
        }
    }

    public function logout() {

        $this->nt_global_logs->s("login", $this->session->userdata('login_id') . ' fez logout do sistema');

        $this->nt_manager_usuarios->logOutUser();
        redirect("manager/login");
    }

    
    
    private function resetCountTentativasLogin(){
            $tentativas = 0;
            $this->session->set_userdata("tentativas-login",$tentativas);         
    }
    
    private function countPlusTentativaLogin(){
        
            $tentativas = (int) $this->session->userdata("tentativas-login");
            $tentativas++;
            $this->session->set_userdata("tentativas-login",$tentativas);        
    }
    
    /* no controller o metodo é camel-cases, na url sempre minusculo e junto */

    public function esqueciSenha() {
        $this->load->view('manager/m_login/esquecisenha');
    }

    /**
     * Método usado para gerar uma senha, para cadastrar o primeiro usuário no BD
     * por exemplo, quando ainda nao se tem acesso ao manager.
     * 
     * @param string $senha texto plano que se deseja criptografar
     */
    public function genpassword($senha=""){
        echo('<style> body{font-family: sans-serif; font-size: 12px;} </style>');
        echo('<title>Generate a password</title>');
        echo("Valor de hash para a senha <b>$senha</b> => ");
        echo($this->nt_manager_usuarios->cryptText($senha));
        exit();
    }
    
    public function recuperarSenha() {

        $this->load->model("nt_global_mtemplates");
        
        $usuario = $this->input->post("usuario");
        
        if($usuario == ''){
            die("<font face='sans-serif' size='3' color='black'>Usuário não informado. <a href='".  base_url()."manager/login/esquecisenha/"."'>Voltar</a></font>");
        }
        

        $r = $this->db->where("ATIVO", "1")->where("USUARIO", $usuario)->from("nt_manager_usuarios")->get()->result();

        // se voltar vazio, evita notices abaixo
        if(!isset($r[0]->USUARIO))
            $r[0]->USUARIO = null;
        
        // ok, esse usuario existe e esta ativo
        if ($r[0]->USUARIO == $usuario) {
            
            if(!isset($r[0]->EMAIL))
                $r[0]->EMAIL = "email_nao_encotrado@usuario.com";
            
            
            // guarda LOG!
            $this->nt_global_logs->s("reset de senha", "para o usuario $usuario enviado nova para o email {$r[0]->EMAIL} ");

            // gera nova senha
            $newSenha = ucfirst(substr(md5(time()), 5, 9)) . "-" . rand(0, 300) . "*" . substr(md5(time()), 0, 5);
            $senhaCrypted = $this->nt_manager_usuarios->cryptText($newSenha);
            
            
            // troca ela
            $data = array("SENHA"=>$senhaCrypted);
            $this->db->where("ID",$r[0]->ID);
            $this->db->update("nt_manager_usuarios", $data);

            // envia por email que mudou a senha
            // 
            // passa o array de params para inicializar o email
            $this->email->initialize($this->nt_global_parametros->getMailConfs());
            $this->email->to($r[0]->EMAIL);

            $this->email->from($this->nt_global_parametros->q("default_mailer_sender"));
            
            
            /* Busca o email já no formato adequado do manager */
            $replaces = array('cliente'=>$this->config->item("cliente"),
                              'nova-senha'=>$newSenha);
            $mailContent = $this->nt_global_mtemplates->getEmail('manager-login-recuperar-senha', 'pt', $replaces);
            
            
            // ok, agora em $mailContent tem o que deve cair para o usuário prontinho!
            $this->email->subject($mailContent['subject']); // assunto já parseado pela classe de templates de email
            $this->email->message($mailContent['body']); // corpo já parseado pela classe de email
            
            $this->email->send(); // go Jonny!
            
            $this->load->view('manager/m_login/recuperar_senha');
        } else {
            $this->load->view('manager/m_login/recuperar_senha_error');
        }
        
        
    }// fim metodo
    
    
    /**
     * Devolve em tela os dados de versionamento da versão
     * que está em execução neste host
     */
    public function version(){
        $base = $this->config->item("local_disk_url");
        
        $site = file_get_contents($base."site-version.txt");
        $data['site'] = trim($site);
        
        $framework = file_get_contents($base."framework-version.txt");
        $data['framework'] = nl2br(trim($framework));
        
        $this->load->view("manager/m_login/versioninfo",$data);
    }
    
    
    /**
     * Mostra informações de phpinfo do projeto em execução
     */
    public function phpinfo(){
        phpinfo();
    }

}// fim classe

