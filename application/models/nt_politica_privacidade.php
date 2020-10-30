<?php

class nt_politica_privacidade extends NT_Model {
    
    private $validation = array(
        array('field' => 'TEXTO', 'label' => 'Título', 'rules' =>  'required'), 
        array('field' => 'STATUS', 'label' => 'Status', 'rules' =>  'required'), 
        );

    public function getRules(){
        return $this->validation;
    }

    public function get(){
        return $this->db->select("TEXTO")
                        ->from($this->getSft())
                        ->where("STATUS", 1)
                        ->order_by("ID", "DESC")     
                        ->get()->row_array(); 
    }
 
}
