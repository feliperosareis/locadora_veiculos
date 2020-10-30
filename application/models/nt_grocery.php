<?php

/**
 * Description of nt_grocery
 *
 * @author Felipe Rosa
 */
class nt_grocery extends NT_Model {

    /**
     * Esta funcao responde ao request ajax disparado nas grids do manager
     * onde há um componente "add_bolean_status_switcher" do grocery crud
     * 
     * Como tabela a campo vem criptografado no request, tem que desmontar
     * a string para passa-la para a função que efetivamente faz o switch dos valores
     * a função desta classe reverseStatus
     * 
     * @param type $base64_table_and_field
     * @param type $row_ID
     * @return boolean
     */
    public function ajxBooleanReverseStatusDecode($base64_table_and_field, $row_ID) {

        $dbInfo = urldecode($base64_table_and_field);
        $dbInfo = base64_decode($base64_table_and_field);
        $dbInfo = explode("|", $dbInfo);

        $field = trim($dbInfo[0]);
        $table = $dbInfo[1];

        if ($this->reverseStatus($table, $field, $row_ID)) {

            echo("ok");
        } else {

            echo("problems");
        }

        return true;
    }

    
    /**
     * Para campos com valor 0 ou 1, cada vez que chamar esse metodo
     * vai alternar entre os valores.
     * 
     * @param type $table
     * @param type $field
     * @param type $row_ID
     * @return boolean
     */
    public function reverseStatus($table, $field, $row_ID) {
        $sql = sprintf("update %s set %s = mod((%s+1),2) where ID=%d", $table, $field, $field, $row_ID);
        return $this->db->query($sql);
    }
    
    
    
    /**
     * Função executada quando utlizado o multiselect do manager
     * 
     * @param string $acao com o seguinte padrão ([0-1],[NOME_TABELA],[NOME_CAMPO])
     * @param string $ids com o seguinte padrão (0,1,2,3,4.....)
     * @return boolean
     * 
     * PS....Parenteses não entram no valor da variavel.
     */
    public function ajxmultiselect($acao = false, $ids = false) {
        
        $acao_partida = explode(',', $this->hex2bin($acao));

        $registros = (str_replace('_', ',', (substr(urldecode($ids), 0, -1))));

        if ($acao_partida[1] == 'showjustSelectedIDS') {
            $this->setImplicitFilter($registros, $acao_partida[2]);
            exit();
        }


        if ($acao_partida[1] == 'delete') {

            $sql = sprintf("DELETE FROM %s where ID IN (%s)", $acao_partida[2], $registros);

            $this->nt_global_logs->s("delete multiplo", "da tabela {$acao_partida[2]} deletou os registros $registros", $sql);
        } else {

            $sql = sprintf("update %s set %s = %s where ID IN (%s)", $acao_partida[2], $acao_partida[1], $acao_partida[0], $registros);

            $this->nt_global_logs->s("update multiplo", "na tabela {$acao_partida[2]} para {$acao_partida[1]} o valor {$acao_partida[0]} nos registros $registros", $sql);
        }

        return $this->db->query($sql);
    }
    
    //DECODE HEX
    public function hex2bin($h) {
        
        if (!is_string($h))
            return null;
        $r = '';
        for ($a = 0; $a < strlen($h); $a+=2) {
            $r.=chr(hexdec($h[$a] . $h[($a + 1)]));
        }
        return $r;
    }    
    

    /**
     * Esta funcao responde ao request ajax disparado nas grids do manager
     * onde há um componente "add_list_edit_order" do grocery crud
     * 
     * O Campo tabela vem criptografado no request, tem que desmontar
     * a string.
     * 
     * @param type $field_name
     * @param type $id
     * @param type $ordem
     * @param type $tabela (criptografada)
     * @return boolean
     */
    public function ajxordem($field_name = false, $id = false, $ordem = false, $tabela = false) {
        
        $tabela = $this->hex2bin($tabela);

        $this->load->model($tabela);
        $obrigatorio = false;

        foreach ($this->$tabela->getRules() as $regra) {
            //print_r($regra);
            // echo $regra['field'].' == '.$field_name.' && '. $regra['rules'].' == required<Br>';
            if ($regra['field'] == $field_name && $regra['rules'] == 'required') {
                $obrigatorio = true;
                //echo "oi";
            }
        }

        if ($obrigatorio == true && $ordem == "_") {
            echo $this->db->query("SELECT " . $field_name . " FROM " . $tabela . " WHERE ID = " . $id)->row()->$field_name;
        } else if ($obrigatorio == false && $ordem == "_") {
            $this->db->query("UPDATE $tabela SET $field_name = NULL WHERE ID = " . $id);
        } else {
            $ordem = intval($ordem);
            $this->db->query("UPDATE $tabela SET $field_name = $ordem WHERE ID = " . $id);
        }
    }    

    
    
    /**
     * Seta uma session com uma lista de ID's. Esses ID's são o filtro
     * que deve ser aplicado na tela de listagem do controller.
     * 
     * @param string $ids separados por vírgula. ex.: 1,10,87487,11,54
     * @param string $tabela nome da tabela para qual este filtro deve ser aplicado
     */
    private function setImplicitFilter($ids, $tabela) {
        $this->session->set_userdata($tabela . '-managerIDFilter', $ids);
    }
    
}