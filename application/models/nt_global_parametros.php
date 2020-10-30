<?php

/**
 * modelo de dados para nt_global_paremetros. Busca um determinado parâmetro, 
 * devolve todos os parâmwtros prontos para a função initialize da classe de 
 * email do CodeIgniter
 *
 * @author Felipe Rosa
 */
class nt_global_parametros extends NT_Model {

    private $validation = array(
        array('field' => 'IDENTIFICADOR', 'label' => 'Identificador', 'rules' => 'required'),
        array('field' => 'DESCRICAO', 'label' => 'Descrição', 'rules' => 'required'),
        array('field' => 'VALOR_PARAM', 'label' => 'Valor do parâmetro', 'rules' => 'required')
    );

    /**
     * Devolve o array com as validações para esta model
     * 
     * @return array com as regras de validação desta model
     */
    public function getRules() {
        return $this->validation;
    }

    /**
     * Busca o valor de um determinado identificador de configuração
     * 
     * Conforme o valor em $key passado, busca o valor desta chave na tabela
     * nt_global_parametros. Devolve false se não encontrar.
     * 
     * @param string $key identificador do parametro cujo valor se busca
     * @return string Valor do parâmetro ou false
     */
    public function q($key) {
        $sql = sprintf("select VALOR_PARAM from %s where 
                        IDENTIFICADOR='%s'",$this->sft, $key);
        $rs = $this->db->query($sql)->result_array();

        if (count($rs) == 0)
            return false;

        return $rs[0]['VALOR_PARAM'];
    }

    /**
     * Busca do BD e auto-seta, ou dexa pronto para usar em initialize
     * da classe Email do Codeigniter
     * 
     * @return array chave valor de configuracao de email
     */
    public function getMailConfs() {
        $rs = $this->db->query("select * from {$this->sft} 
                             where IDENTIFICADOR like 'mail_%'")->result_array();

        $useme = array();
        foreach ($rs as $value) {
            $k = substr($value['IDENTIFICADOR'], 5);
            $useme[$k] = $value['VALOR_PARAM'];
        }
        return $useme;
    }
    
    
    /**
     * Atualiza valor de um parametro
     * 
     * @author Felipe Rosa
     * @param string $key identificador do parametro cujo valor deseja ser atualizado
     * @param string $valor Novo valor para a key informada
     * @return boolean resultado da query de update
     */
    public function update($key = [],$valor = []) {
        $sql = sprintf("UPDATE %s SET VALOR_PARAM = '%s' where 
                        IDENTIFICADOR='%s'",$this->sft, $valor, $key);

        return $this->db->query($sql);
    }

}
