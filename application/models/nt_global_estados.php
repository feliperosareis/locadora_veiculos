<?php
/**
 * modelo de dados para nt_global_estados Provê a lista de estados conforme
 * o país passado para o método. Índices: id, estado
 *
 * @author Felipe Rosa
 */
class nt_global_estados extends NT_Model {
    
    
    private $validation = array(
        array('field' => 'NOME_ESTADO', 'label' => 'Nome do estado', 'rules' => 'required'),
        array('field' => 'NT_GLOBAL_PAIS_ID', 'label' => 'País', 'rules' => 'required|integer'),
        array('field' => 'UF_ESTADO', 'label' => 'Unidade federativa', 'rules' => 'required')
    );
    
    /**
     * Devolve o array com as validações para esta model
     * 
     * @return array com as regras de validação desta model
     */
    public function getRules(){
        return $this->validation;
    }   
    
    
    /**
     * Devolve um array de daos com os indices id e estado de
     * acordo com o país informado
     * 
     * @param int $pais
     * @return array
     */
    public function getEstadosFromPais($pais){
        
        $this->db->where("NT_GLOBAL_PAIS_ID", $pais);
        
        // $this->sft // this self table, o nome desta classe é
        // o nome da tabela em BD
        $rs = $this->db->from($this->sft)->order_by("NOME_ESTADO")
                                ->get()->result_array();
        
        $r = array();
        
        foreach ($rs as $linha) 
            $r[] = array('id'=>$linha['ID'],'estado'=>trim($linha['NOME_ESTADO']),'siglaestado'=>trim($linha['UF_ESTADO']));
        
        return $r;        
        
    }
    
    
    /**
     * Devolve a string dos options html para o pais informado
     * @param int $pais
     */    
    public function getEstadosHtmlOptionsSigla(){
        $lista = $this->getEstadosFromPais(1);
        $ac = "";
        foreach($lista as $lis){
            $ac.= ("<option>{$lis['siglaestado']}</option>\n");
        }
        return $ac;
    }
    
    
     /**
     * Devolve a string dos options html para o pais informado
     * @param int $pais
     */    
    public function getEstadosHtmlOptionsFromPais($pais){
        $lista = $this->getEstadosFromPais($pais);
        $ac = "";
        foreach($lista as $lis){
            $ac.= ("<option>{$lis['estado']}</option>\n");
        }
        return $ac;
    }

    public function getByID($id){
        return $this->db->select("ID, NOME_ESTADO, UF_ESTADO")
                        ->from($this->getSft())
                        ->where("ID", $id)
                        ->get()->row_array();
    }
    
}
