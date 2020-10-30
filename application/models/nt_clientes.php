<?php
/**
 * Gerador Automático
 * @author Felipe Rosa
 * Gerado em: Tue, 27 Oct 20 06:40:48 -0300
 */
class nt_clientes extends NT_Model {
        private $validation = array(
			array('field' => 'NOME',     'label' => 'Nome',      'rules' =>  'required|'),
			array('field' => 'EMAIL',    'label' => 'E-mail',    'rules' =>  'required|max_length[255]'),
            array('field' => 'CPF',      'label' => 'CPF',       'rules' =>  'required|max_length[14]'),
			array('field' => 'STATUS',   'label' => 'Status',    'rules' =>  ''),
		
        );
    /**
    * Devolve o array com as validações para esta model
    * 
    * @return array com as regras de validação desta model
    */
    public function getRules(){
       return $this->validation;
    }
        
    public function get()
    {
        
        return $this->db->select("ID, NOME, EMAIL, CPF, STATUS")
                        ->from($this->getSft())
                        ->get();

	}
    public function getRow()
    {
        return $this->get()->row_array();
    }
    
    public function getResult()
    {
        return $this->get()->result_array();
    }      
}
    