<?php

class nt_global_zopim extends NT_Model {

    private $validation = array(
        array('field' => 'CODIGO', 'label' => 'Código Zopim', 'rules' => 'required'),
        array('field' => 'ATIVO', 'label' => 'Ativo', 'rules' => 'required|integer')
    );

    /**
     * Devolve o array com as validações para esta model
     * 
     * @return array com as regras de validação desta model
     */
    public function getRules() {
        return $this->validation;
    }
    
    
    public function getAnalytics(){
        
        $rs = $this->db->where("ATIVO",1)->get($this->sft)->result_array();
        
        if(count($rs) > 0){
            return $rs[0]['CODIGO'];
        }else {
            return ccmt("Nenhum Código Zopim configurado no manager");
        }
    }

}
