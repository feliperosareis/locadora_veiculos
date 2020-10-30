<?php
/**
 * Description of m_tools
 *
 * @author Felipe Rosa, <heltonritter@web.de>
 */
class m_tools extends NT_Manager_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->checkLogin();
    }
    
    public function index(){
        
        $this->load->view("manager/m_tools/index");
    }
}
