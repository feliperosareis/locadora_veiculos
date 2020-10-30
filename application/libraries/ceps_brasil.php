<?php
/**
 * Busca cep, rua, bairro, cidade e endereco dado um nro de CEP no Brasil
 *
 * @author Felipe Rosa
 * 
 * Obs: Mais implementacões disponíveis em: www.noiatec.com.br/fi/viewtopic.php?f=4&t=12
 */
class ceps_brasil {
    
    /**
     * Busca dados da partir do cep
     * 
     * @param int $cep cep sem o traço
     * @return array com os indices cep, rua, bairro, cidade, estado
     */
    public function busca($cep){
        $this->html = file("http://www.qualocep.com/busca-cep/".$cep);
        $data['cep'] = trim(strip_tags($this->html[101])); 
        $data['rua'] = trim(strip_tags($this->html[102])); 
        $data['bairro'] = trim(strip_tags($this->html[103]));
        $data['cidade'] = trim(strip_tags($this->html[104]));
        $data['estado'] = trim(strip_tags($this->html[105]));
        return $data;
    }
}