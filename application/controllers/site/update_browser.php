<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class update_browser extends NT_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
		$this->data['breadcrumb'][] = array(
            'titulo' => 'Update Browser',
            'link' => site_url('index')
        );

        $this->load->view('site/update_browser/index', $this->data);
    }
}