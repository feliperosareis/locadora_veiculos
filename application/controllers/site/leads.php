<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class leads extends NT_Controller {

	public function __construct() {
		parent::__construct();		
		$this->load->model('nt_leads');

	}

	public function index()
	{
		return $this->nt_leads->salvar();

	}

	/*public function valida()
	{

		return $this->nt_leads->valida_lead();

	}

	public function salvar_trabalhe_conosco()
	{
		$status = 404;
		if($this->nt_leads->salvar_trabalhe_conosco())
		{
			$status = 200;
		}

		redirect( site_url('contato/' . $status . '/#trabalhe_conosco') ,'refresh');

	}*/

}