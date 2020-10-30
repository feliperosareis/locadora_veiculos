<?php

/**
 * modelo de dados para nt_global_seo. Disponibiliza os métodos para buscar as 
 * tags, título e descriição para uma determinada página quando passa o identificador.
 * Quando não encontrar, devolve as informações padrão de SEO que estão na config.php
 * 
 * @author Felipe Rosa
 */
class nt_global_seo extends NT_Model {

    private $validation = array(
        array('field' => 'TITULO', 'label' => 'Título', 'rules' => 'required'),
        array('field' => 'DESCRICAO', 'label' => 'Descrição', 'rules' => 'required'),
        array('field' => 'PALAVRASCHAVES', 'label' => 'Palavras chaves', 'rules' => 'required'),
        array('field' => 'IDENTIFICADOR', 'label' => 'Identificador', 'rules' => 'required'),
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
     * Devolve titulo, descricao, keys de acordo com o identificador 
     * enviado (array). Se não encontrar devolve as informacoes 
     * de seo padrao deste projeto/site.
     * 
     * @param type $identificador opcional, se nao passado busca o segmento atual
     * @return array valores nos indices: titulo, descricao, keys
     */
    public function getSeoFor($identificador = '') {

        $data = array();

        // se nao passado o identificador, pega a URL atual como indentificador
        if ($identificador == '')
            $identificador = implode('/', $this->uri->segments);



        $sql = sprintf("select * from %s where IDENTIFICADOR = '%s'",$this->sft, $identificador);
        $rs = $this->db->query($sql)->result_array();

        // nao encontrou nada no bd na tabela de seo para essa pagina
        if (count($rs) < 1) {

            // assim pode se ver as paginas que estao faltando tags, pelos logs
            $this->nt_global_logs->s("seo", "Buscado para \'$identificador\' mas nao encontrado");
            
            // busca das configuracoes as info corretas
            $defTitle = $this->db->where('IDENTIFICADOR', 'seo_default_title')->get("nt_global_parametros")->result_array();
            $defKeys = $this->db->where('IDENTIFICADOR', 'seo_default_keywords')->get("nt_global_parametros")->result_array();
            $defDesc = $this->db->where('IDENTIFICADOR', 'seo_default_description')->get("nt_global_parametros")->result_array();


            $data['titulo'] = $defTitle[0]['VALOR_PARAM'];
            $data['descricao'] = $defDesc[0]['VALOR_PARAM'];
            $data['keys'] = $defKeys[0]['VALOR_PARAM'];
            
        } else {


            $data['titulo'] = $rs[0]['TITULO'];
            $data['descricao'] = $rs[0]['DESCRICAO'];
            $data['keys'] = $rs[0]['PALAVRASCHAVES'];
        }

        $data['url'] = site_url($this->uri->uri_string());
        return $data;
    }

}

