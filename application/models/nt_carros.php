<?php
/**
 * Gerador Automático
 * @author Felipe Rosa
 * Gerado em: Tue, 27 Oct 20 06:40:48 -0300
 */
class nt_carros extends NT_Model {
        private $validation = array(
			array('field' => 'ID_MODELO', 'label' => 'Modelo', 'rules' =>  'required'),
			array('field' => 'DESCRICAO', 'label' => 'Descrição', 'rules' =>  'max_length[255]'),
            array('field' => 'PLACA', 'label' => 'Placa', 'rules' =>  'required|max_length[7]'),
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
        
        return $this->db->select("ID, ID_MODELO, DESCRICAO, PLACA, STATUS")
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

    public function getCarros()
    {
        $result = $this->db->select("c.ID, m.DESCRICAO AS MODELO")
                 ->from($this->getSft() . ' c')
                 ->join('nt_modelos m', 'm.ID = c.ID_MODELO')
                 ->get()
                 ->result_array();

        $carros = [];
        foreach ($result as $key => $value) 
        {
            $carros[$value['ID']] = $value['MODELO'];
        }

        return $carros;

    }


}
    