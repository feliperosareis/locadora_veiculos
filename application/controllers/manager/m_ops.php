<?php
/**
 * Este controller é a tela de login e verificação se senha do test mode.
 * 
 * Se o test mode estiver ativado e o usuário não tiver logado ele vai cair
 * neste controller no método login.
 * 
 * Veja tambem em core/NT_Controller a classe NT_Default_Controller
 * 
 * @author Felipe Rosa
 */
class m_ops extends CI_Controller {

    public function login() {
        $this->load->view("manager/m_testmode/login");
    }

    
    public function verifica() {
        $testMode = $this->nt_manager_testmode->getTestMode();
        
        $usuario = $this->input->post("usuariotest");
        $senha = $this->input->post("senhatest");
        
        $urlOps = base_url()."manager/ops/login";
        $urlOk = base_url();
        
        if($usuario == '' or $senha == ''){
            redirect($urlOps);
        }else{
             if($testMode['USUARIO'] == $usuario and $senha == $testMode['SENHA']){
                 
               // set session que o usuário esta autorizado em test mode
               $controle = array('testmodeautorizado'=>true);
               $this->session->set_userdata($controle);
               
               redirect($urlOk);
             }else{
                 redirect($urlOps);
             }
        }
    }

}