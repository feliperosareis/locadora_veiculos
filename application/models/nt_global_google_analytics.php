<?php
/**
 * Modelo de dados para controlar Google Analytics nas páginas públicas
 *
 * @author Felipe Rosa
 */
class nt_global_google_analytics extends NT_Model {

    private $validation = array(
        array('field' => 'CODIGO', 'label' => 'Código do Google', 'rules' => 'required'),
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
            return ccmt("Nenhum google anaytics code configurado no manager");
        }
    }

}
