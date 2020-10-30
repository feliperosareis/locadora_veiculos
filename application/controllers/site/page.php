<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class page extends NT_Controller {

	public function __construct() {
		parent::__construct();

		$this->data['breadcrumb'][] = array(
            'titulo' => 'Page',
            'link' => site_url("page")
		);
	}

    public function index() {
        $data['pages'] = array('page_conteudo');
        $this->render($data);
    }

    public function interna() {
        $this->data['breadcrumb'][] = array(
            'titulo' => 'Interna',
            'link' => site_url("page/interna")
        );

        $data['pages'] = array('interna/page_interna_conteudo');
        $this->render($data);
    }
}