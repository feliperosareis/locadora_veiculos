<?php
/**
 * Description of nt_global_graphs
 *
 * @author Felipe Rosa
 */
class nt_global_graphs extends NT_Model {
    
    private $validation = array(
        array('field' => 'TABELA', 'label' => 'Tabela desta imagem', 'rules' => 'required|trim'),
        array('field' => 'CAMPO', 'label' => 'Nome do campo', 'rules' => 'required|trim'),
        array('field' => 'IDENTIFICADOR', 'label' => 'Identificador de acesso', 'rules' => 'required|trim'),
        array('field' => 'DISKPATH', 'label' => 'Caminho em disco', 'rules' => 'required|trim'),
        array('field' => 'ALLOWED_SIEZES', 'label' => 'Tamanhos permitidos', 'rules' => 'required|trim')
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
