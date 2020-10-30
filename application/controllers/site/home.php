<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class home extends NT_Controller {

    public function __construct() {
        parent::__construct();

    	//Sub categoria sem link
    	/*
		$this->data['breadcrumb'][] = array(
            'titulo' => 'Sub Titulo',
            'link' => null
        );
        */
    }

    public function index() {
		$this->data['breadcrumb'][] = array(
            'titulo' => 'Titulo',
            'link' => site_url('index')
        );

        $data['pages'] = array('home_conteudo');
        $this->render($data);

        $this->getCarros();

    }

    public function getCarros()
    {

        $this->load->model('nt_carros');
        // $result = json_decode($this->nt_carros->getCarros());
        $result = $this->nt_carros->getCarros();
        // print_r2($result);
    }

    

}