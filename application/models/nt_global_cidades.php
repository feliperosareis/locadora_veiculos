<?php

/**
 * modelo de dados para nt_global_cidades. Porvê acesso reverso dos dados das cidades
 * e lista de cidades do estado para uso em selects, dropdown-lists, combo-boxes
 *
 * @author Felipe Rosa
 */
class nt_global_cidades extends NT_Model {

    private $validation = array(
        array('field' => 'CIDADE', 'label' => 'Cidade', 'rules' => 'required'),
        array('field' => 'NT_GLOBAL_ESTADO_ID', 'label' => 'Estado', 'rules' => 'required'),
        array('field' => 'CEP_INICIAL', 'label' => 'CEP inicial', 'rules' => ''),
        array('field' => 'CEP_FINAL', 'label' => 'CEP final', 'rules' => '')
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
     * Devolve a lista de cidades de um estado
     * 
     * @param int $estado ID do estado que se deseja obter as cidades
     * @return array lista de cidades com os indices: id e cidade
     */
    public function getCidadesFromEstado($estado) {
        
        $this->db->where("NT_GLOBAL_ESTADO_ID", $estado);
        $rs = $this->db->from($this->sft)->order_by("CIDADE")->get()->result_array();
        
        $r = array();
        foreach ($rs as $linha)
            $r[] = array('id' => $linha['ID'], 'cidade' => trim($linha['CIDADE']));

        return $r;
    }
    
    
    /**
     * Devolve a string dos options html para o estado informado
     * @param int $estado
     */
    public function getCidadesHtmlOptionsFromEstado($estado){
        $lista = $this->getCidadesFromSiglaEstado($estado);
        $ac = '<option value="">Selecione sua cidade:</option>';
        foreach($lista as $lis){
            $ac.= ("<option>{$lis['cidade']}</option>\n");
        }
        return $ac;
    }
    
    
    /**
     * Retorna o caminho reverso, ou seja, estado e país de uma dada cidade
     * 
     * @param int $cidade_id ID da cidade da qual deve ser feita a consulta do estado e país
     * @return array com os índices cidade, estado, pais
     */
    public function getBackwardTreeFrom($cidade_id){
        
        $cityInfos = $this->db->query("select * from {$this->sft} where ID=$cidade_id")->result_array();
        
        $estado =$cityInfos[0]['NT_GLOBAL_ESTADO_ID'];
        
        $estateInfos = $this->db->query("select * from nt_global_estados where ID=$estado")->result_array();
        
        $pais = $estateInfos[0]['NT_GLOBAL_PAIS_ID'];
        $countryInfos = $this->db->query("select * from nt_global_paises where ID=$pais")->result_array();
        
        $back = array("cidade"=>$cityInfos[0],'estado'=>$estateInfos[0],'pais'=>$countryInfos[0]);
        return $back;
    }

    public function getCidadesFromSiglaEstado($sigla) {
        
        $sql = "SELECT
                    cida.ID,cida.CIDADE
                FROM
                    nt_global_cidades cida,
                    nt_global_estados esta
                WHERE
                    esta.ID = cida.NT_GLOBAL_ESTADO_ID
                    and esta.UF_ESTADO = '$sigla'
                ORDER BY
                    cida.CIDADE";

        $rs = $this->db->query($sql)->result_array();
        
        $r = array();
        foreach ($rs as $linha)
            $r[] = array('id' => $linha['ID'], 'cidade' => trim($linha['CIDADE']));

        return $r;
    }

    public function getByID($id){
        return $this->db->select("ID, CIDADE")
                        ->from($this->getSft())
                        ->where("ID", $id)
                        ->get()->row_array();
    }
}
