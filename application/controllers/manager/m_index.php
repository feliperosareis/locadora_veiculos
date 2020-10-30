<?php
/**
 * Tela inicial após login no manager. A ideia é usar esse controller
 * para disponiblizar ao usuário um "dashboard" de seu site. Tudo que é 
 * importante tem algo aqui, um indicador, o último registro, um gráfico
 * algo assim.
 * 
 * @author Felipe Rosa
 */
class m_index extends NT_Manager_Controller {

    public function __construct() {
        parent::__construct();

        $this->checkLogin();
    }
    

    public function index() {
        $this->nt_global_logs->s("acesso", "tela inicial do manager");

        $this->load->view('manager/m_index/index');
    }

}