<?php
/**
 * Gerador Automático
 * @author Felipe Rosa
 * Gerado em: Tue, 27 Oct 20 06:41:52 -0300
 */
class nt_locacao extends NT_Model {
        private $validation = array(
			array('field' => 'ID_CARRO',         'label' => 'Carro',             'rules' =>  'required'),
			array('field' => 'ID_CLIENTE',       'label' => 'Cliente',           'rules' =>  'required'),
			array('field' => 'DATA_RETIRADA',    'label' => 'Data da Retirada',  'rules' =>  'required'),
			array('field' => 'DATA_DEVOLUCAO',   'label' => 'Data da Devolução', 'rules' =>  'required'),
			array('field' => 'VALOR_DIARIA',     'label' => 'Valor da Diária',   'rules' =>  'required'),
            array('field' => 'VALOR_TOTAL',      'label' => 'Valor Total',       'rules' =>  ''),
			array('field' => 'OBSERVACOES',      'label' => 'Observações',       'rules' =>  'max_length[65535]'),
            array('field' => 'STATUS',           'label' => 'Status',            'rules' =>  'required'),
		
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
        return $this->db->select("ID, ID_CARRO, ID_CLIENTE, DATA_RETIRADA, DATA_DEVOLUCAO, VALOR_DIARIA, VALOR_TOTAL, OBSERVACOES, STATUS")
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
    