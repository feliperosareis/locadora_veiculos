<?php
/**
 * Gerador Automático
 * @author Felipe Rosa
 * Gerado em: Tue, 27 Oct 20 06:41:44 -0300
 */
class nt_leads extends NT_Model {
        private $validation = array(
            array('field' => 'TOKEN', 'label' => 'Token', 'rules' =>  'max_length[50]'),
			array('field' => 'FK_MIDIAS_ID', 'label' => 'Midia', 'rules' =>  ''),
			array('field' => 'FK_EMPRESAS_ID', 'label' => '', 'rules' =>  ''),
			array('field' => 'NOME', 'label' => 'Nome', 'rules' =>  'max_length[50]'),
			array('field' => 'EMAIL', 'label' => 'E-mail', 'rules' =>  'max_length[255]|valid_email'),
			array('field' => 'DDD_TELEFONE_RESIDENCIAL', 'label' => 'DDD Residêncial', 'rules' =>  '|min_length[2]|integer'),
			array('field' => 'TELEFONE_RESIDENCIAL', 'label' => 'Telefone Residêncial', 'rules' =>  'max_length[9]|min_length[8]|integer'),
			array('field' => 'DDD_TELEFONE_CELULAR', 'label' => '', 'rules' =>  'max_length[2]|min_length[2]|integer'),
			array('field' => 'TELEFONE_CELULAR', 'label' => '', 'rules' =>  'max_length[9]|min_length[8]|integer'),
			array('field' => 'ESTADO', 'label' => 'Estado', 'rules' =>  'max_length[2]'),
			array('field' => 'CIDADE', 'label' => 'Cidade', 'rules' =>  'max_length[50]'),
			array('field' => 'CPF_CNPJ', 'label' => 'CPF/CNPJ', 'rules' =>  'max_length[18]'),
			array('field' => 'DATA_CRIACAO', 'label' => 'Data criação', 'rules' =>  'required'),
			array('field' => 'FK_MODELOS_ID', 'label' => 'Modelo', 'rules' =>  ''),
			array('field' => 'MENSAGEM', 'label' => 'Mensagem', 'rules' =>  'max_length[65535]'),
			array('field' => 'ORIGEM', 'label' => 'Origem', 'rules' =>  'max_length[500]'),
			array('field' => 'DISPOSITIVO', 'label' => '', 'rules' =>  'max_length[1]'),
			array('field' => 'ASSUNTO', 'label' => '', 'rules' =>  'max_length[255]'),
			array('field' => 'UTM_SOURCE', 'label' => '', 'rules' =>  'max_length[100]'),
			array('field' => 'UTM_MEDIUM', 'label' => '', 'rules' =>  'max_length[100]'),
			array('field' => 'UTM_CAMPAIGN', 'label' => '', 'rules' =>  'max_length[100]'),
			array('field' => 'UTM_CONTENT', 'label' => '', 'rules' =>  'max_length[100]'),
			array('field' => 'UTM_TERM', 'label' => '', 'rules' =>  'max_length[100]'),
		
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
        
        return $this->db->select("ID,  TOKEN, FK_MIDIAS_ID, FK_EMPRESAS_ID, NOME, EMAIL, DDD_TELEFONE_RESIDENCIAL, TELEFONE_RESIDENCIAL, DDD_TELEFONE_CELULAR, TELEFONE_CELULAR, ESTADO, CIDADE, CPF_CNPJ, DATA_CRIACAO, FK_MODELOS_ID, MENSAGEM, ORIGEM, DISPOSITIVO, ASSUNTO, UTM_SOURCE, UTM_MEDIUM, UTM_CAMPAIGN, UTM_CONTENT, UTM_TERM")
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
    