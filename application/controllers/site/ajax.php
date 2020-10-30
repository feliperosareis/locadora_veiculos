<?php

class ajax extends CI_Controller {

	public function __construct() {

		parent::__construct();
	}

	public function cidades($estado = "") {
		$this->load->model('nt_global_cidades');
		echo $this->nt_global_cidades->getCidadesHtmlOptionsFromEstado($estado);
	}

	public function modelos($empresa_id = 0) { 

		$this->load->library('lead_force');
		
		$options = $this->lead_force->lista_modelos($empresa_id);

		die($options);

	}

	public function comparador() {
        $this->load->model('nt_seminovos');

        $data = is_array($this->session->userdata('comparador')) ? $this->session->userdata('comparador') : array();

        $veiculo_id = $this->input->get('vid');
        $remove_veiculo_id = $this->input->get('rvid');

        if ($veiculo_id > 0) {
            array_unshift($data, $veiculo_id);
        }

        if ($remove_veiculo_id > 0) {
            $data = array_diff($data, array($remove_veiculo_id));
        }

        $data = array_unique($data);
        $data = array_splice($data, 0, 3);

        $this->session->set_userdata('comparador', $data);

        $result = $this->nt_seminovos->getVeiculosComparador();

        return $this->output->set_content_type('application/json')->set_status_header(200)->set_output(json_encode($result));
    }

    public function compare() {
        $inarray = !in_array($this->input->post('modelo_compare'), $this->session->userdata('veiculos_compare'));
        if ($inarray && count($this->session->userdata('veiculos_compare')) < 3) {
            $dados = $this->session->userdata('veiculos_compare');
            $dados[] = $this->input->post('modelo_compare');
            $this->session->unset_userdata('veiculos_compare');
            $this->session->set_userdata('veiculos_compare', $dados);
        }
        $this->load->model('nt_seminovos');
        $this->data['seminovos'] = $this->nt_seminovos->getVeiculosCompare($this->session->userdata('veiculos_compare'));

        echo json_encode($this->data['seminovos']);
    }
	
}