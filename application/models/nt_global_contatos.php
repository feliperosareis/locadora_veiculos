<?php
/**
 * modelo de dados para nt_global_contatos
 *
 * @author Felipe Rosa
 */
class nt_global_contatos extends NT_Model {
    
    private $validation = array(
        array('field' => 'NOME', 'label' => 'Nome', 'rules' => 'required'),
        array('field' => 'DATA_HORA', 'label' => 'Data', 'rules' => ''),
        array('field' => 'EMAIL_REMETENTE', 'label' => 'Remetente', 'rules' => ''),
        array('field' => 'RUA_REMETENTE', 'label' => 'Rua', 'rules' => ''),
        array('field' => 'NRO_REMETENTE', 'label' => 'Nro.', 'rules' => ''),
        array('field' => 'COMPLEMENTO', 'label' => 'Complemento', 'rules' => ''),
        array('field' => 'ARQUIVO_ANEXO', 'label' => 'Anexo', 'rules' => ''),
        array('field' => 'MENSAGEM', 'label' => 'Mensagem', 'rules' => ''),
        array('field' => 'TELEFONE', 'label' => 'Fone', 'rules' => ''),
        array('field' => 'STATUS', 'label' => 'Status', 'rules' => ''),
        array('field' => 'NT_GLOBAL_SETOR_ID', 'label' => 'Setor', 'rules' => ''),
        array('field' => 'NT_GLOBAL_PAIS_ID', 'label' => 'País', 'rules' => ''),
        array('field' => 'NT_GLOBAL_ESTADO_ID', 'label' => 'Estado/UF', 'rules' => ''),
        array('field' => 'NT_GLOBAL_CIDADE_ID', 'label' => 'Cidade', 'rules' => '')
    );
    
    /**
     * Devolve o array com as validações para esta model
     * 
     * @return array com as regras de validação desta model
     */
    public function getRules(){
        return $this->validation;
    }
    
}

