<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class error404 extends NT_Controller {

    public function __construct() {
        parent::__construct();

    }

    public function index() {
        
        $this->load->view('site/includes/error404', $this->data);

    }

}