<?php

/**
 * modelo de dados para nt_global_paises. Porvê os dados dos países
 * em formato adequado para uso nos selects/dropdown lists/combo`s.
 * 
 * Icon pack usado nos cadastros: http://www.iconarchive.com/show/all-country-flag-icons-by-custom-icon-design.html
 * 
 * Referencia de tradução de nomes
 * ES http://www.nationsonline.org/oneworld/countrynames_spanish.htm
 * DE http://www.nationsonline.org/oneworld/countrynames_german.htm
 * FR http://www.nationsonline.org/oneworld/countries_of_the_world.htm
 * IT http://www.nationsonline.org/oneworld/countrynames_italian.htm
 * RU http://www.nationsonline.org/oneworld/countrynames_russian.htm
 * CH http://www.nationsonline.org/oneworld/countrynames_chinese.htm 
 * AR http://www.nationsonline.org/oneworld/countrynames_arabic.htm
 *
 * @author Felipe Rosa
 */
class nt_global_paises extends NT_Model {

    private $validation = array(
        array('field' => 'FIGURA_FLAG', 'label' => 'Bandeira', 'rules' => ''),
        array('field' => 'NOME_LOCAL', 'label' => 'Nome Nativo', 'rules' => 'required'),
        array('field' => 'NOME_PT', 'label' => 'Português', 'rules' => 'required'),
        array('field' => 'NOME_EN', 'label' => 'Inglês', 'rules' => ''),
        array('field' => 'NOME_ES', 'label' => 'Espanhol', 'rules' => ''),
        array('field' => 'NOME_DE', 'label' => 'Alemão', 'rules' => ''),
        array('field' => 'NOME_FR', 'label' => 'Francês', 'rules' => ''),
        array('field' => 'NOME_IT', 'label' => 'Italiano', 'rules' => ''),
        array('field' => 'NOME_RU', 'label' => 'Russo', 'rules' => ''),
        array('field' => 'NOME_CH', 'label' => 'Chinês', 'rules' => ''),
        array('field' => 'NOME_AR', 'label' => 'Árabe', 'rules' => ''),
        array('field' => 'REQUERCEP', 'label' => 'Requer CEP', 'rules' => ''),
        array('field' => 'COD_COI', 'label' => 'Código COI', 'rules' => 'min_length[3]|max_length[3]|trim'),
        array('field' => 'COD_ISO_3166-2', 'label' => 'Código ISO 3166-2', 'rules' => 'min_length[2]|max_length[2]|trim')
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
     * Devolve a lista de todos os países cadastrados.
     * Lang's aceitas: pt, en, es, de, fr, it, ru, ch e ar
     * 
     * @param string $lang Opcional, letra do idioma que se quer. pt é default
     * @return array com os índices: id e pais
     */
    public function getPaisesLista($lang='pt') {

        $upper = strtoupper($lang);
        
        $rs = $this->db->from($this->sft)->order_by("NOME_$upper")
                                              ->get()->result_array();
        $r = array();
        foreach ($rs as $linha)
            $r[] = array('id' => $linha['ID'], 'pais' => trim($linha["NOME_$upper"]));

        return $r;
    }
    
    
    /**
     * Devolve a lista de options de países no idioma passado.
     * Padrão é PT, pode passar o param maiúsculo ou minúsculo, tanto faz.
     * <br>
     * Lang's aceitas: pt, en, es, de, fr, it, ru, ch e ar
     * 
     * @param type $lang
     * @return string <option value='ID'>NOME_$lang ...
     */
    public function getPaisesListaHtmlOptions($lang = 'pt'){
        
        $lista = $this->getPaisesLista($lang);
        
        $ac = "";
        foreach($lista as $lis){
            $ac.= ("<option value='{$lis['id']}'>{$lis['pais']}</option>\n");
        }
        return $ac;
    }    

}

