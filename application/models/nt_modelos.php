<?php
/**
 * Gerador Automático
 * @author Felipe Rosa
 * Gerado em: Tue, 27 Oct 20 06:42:32 -0300
 */
class nt_modelos extends NT_Model {
        private $validation = array(
            array('field' => 'ID_MARCA', 'label' => 'Marca', 'rules' =>  'required'),
			array('field' => 'DESCRICAO', 'label' => 'Descrição', 'rules' =>  'required|max_length[255]'),
			array('field' => 'STATUS', 'label' => 'Status', 'rules' =>  ''),
		
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
        
        return $this->db->select("ID, ID_MARCA, DESCRICAO, STATUS")
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
    