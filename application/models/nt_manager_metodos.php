<?php

/**
 * modelo de dados para nt_manager_metodos
 *
 * @author Felipe Rosa
 */
class nt_manager_metodos extends NT_Model {

    private $validation = array(
        array('field' => 'METODO', 'label' => 'Método', 
                    'rules' => 'required|is_unique[nt_manager_metodos.METODO]')
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
     * Devolve a lista de métodos de um determinado papel
     * 
     * @param int $papel
     * @return array
     */
    public function getMetodosOf($papel){
        return $this->db->select("NT_MANAGER_METODO_ID")->where("NT_MANAGER_PAPEL_ID",$papel)
                                            ->get("nt_manager_metodos_papeis")->result_array();
    }
    
    
    /**
     * Volta o array do mapa de métodos cadastrados, usado na associação com os papéis do usuário.
     * Devolve uma árvore binária não balanceada.
     * 
     * ATENÇÃO! Limite de 7 níveis! Exemplo: manager/seo/index/update/properties/congigs/advanced
     * 
     * @return array
     */
    public function getTreeMetodos() {
        $lista = array();
        
        $l0 = $this->getMainMetodos();
        
        if (count($l0) > 0) {
            $i=0;
            foreach ($l0 as $r0) {
                $lista[$i] = $r0;
                $l1 = $this->getSubMetodosFrom($r0['METODO']);
                if(count($l1) > 0){
                    $j=0;
                    foreach($l1 as $r1){
                        $lista[$i]['sub'][$j] = $r1;
                        $l2 = $this->getSubMetodosFrom($r1['METODO']);
                        if(count($l2) > 0){
                            $k=0;
                            foreach ($l2 as $r2) {
                                $lista[$i]['sub'][$j]['sub'][$k] = $r2;
                                $l3 = $this->getSubMetodosFrom($r2['METODO']);
                                if(count($l3) > 0){
                                    $l = 0;
                                    foreach($l3 as $r3){
                                        $lista[$i]['sub'][$j]['sub'][$k]['sub'][$l] = $r3;
                                        $l4 = $this->getSubMetodosFrom($r3['METODO']);
                                        if(count($l4) > 0){
                                            $m=0;
                                            foreach($l4 as $r4){
                                                $lista[$i]['sub'][$j]['sub'][$k]['sub'][$l]['sub'][$m] = $r4;
                                                $l5 = $this->getSubMetodosFrom($r4['METODO']);
                                                if(count($l5) > 0){
                                                    $n=0;
                                                    foreach($l5 as $r5){
                                                        $lista[$i]['sub'][$j]['sub'][$k]['sub'][$l]['sub'][$m]['sub'][$n] = $r5;
                                                        $l6 = $this->getSubMetodosFrom($r5['METODO']);
                                                        if(count($l6)>0){
                                                            $o=0;
                                                            foreach($l6 as $r6){
                                                                $lista[$i]['sub'][$j]['sub'][$k]['sub'][$l]['sub'][$m]['sub'][$n]['sub'][$o] = $r6;
                                                                $o++;
                                                            }
                                                        }
                                                        $n++;
                                                    }
                                                }
                                                $m++;
                                            }
                                            
                                        }                                        
                                        $l++;
                                    }
                                }
                                $k++;
                            }
                        }
                        
                        $j++;
                    }
                }
                $i++;
            }
        }

        return $lista;
    }

    /**
     * Devolve os métodos principais, desses vai desencadear a árvore de permissoes depois
     * 
     * @return array
     */
    public function getMainMetodos() {
        $sql = "select * from nt_manager_metodos where METODO not like '%/%' ";
        return $this->db->query($sql)->result_array();
    }

    /**
     * Retorna os métodos UM nível abaixo (mais específico) do que o 
     * identificador (método) passado.
     * 
     * @param string $identificador
     * @return array
     */
    public function getSubMetodosFrom($identificador) {
        $exp = explode("/", $identificador);
        $maxTimes = count($exp);

        // os filhos de $identificados são os metodos com $identificador/?
        $sql = sprintf("select * from nt_manager_metodos where METODO like '%s'", "$identificador/%");
        $rs = $this->db->query($sql)->result_array();

        $lista = array();

        if (count($rs) > 0) {
            foreach ($rs as $row) {
                $actualTimes = substr_count($row['METODO'], "/");

                if ($actualTimes == $maxTimes)
                    $lista[] = $row;
            }
        }

        return $lista;
    }
    
    
    
    /**
     * Cria os métodos padrão para depois associar as permissões dos papéis
     * 
     * @param string $controller manager/????/aqui_gera
     * @param boolean $upload
     */
    public function createMetodosGroceryCRUD($controller,$metodo, $upload, $outras_opcoes){
           
            $mapeamentos[] = "manager/$controller";
            $mapeamentos[] = "manager/$controller/$metodo";
            $mapeamentos[] = "manager/$controller/$metodo/add";
            $mapeamentos[] = "manager/$controller/$metodo/ajax_list";
            $mapeamentos[] = "manager/$controller/$metodo/ajax_list_info";
            $mapeamentos[] = "manager/$controller/$metodo/delete";
            $mapeamentos[] = "manager/$controller/$metodo/edit";
            $mapeamentos[] = "manager/$controller/$metodo/insert";
            $mapeamentos[] = "manager/$controller/$metodo/insert_validation";
            $mapeamentos[] = "manager/$controller/$metodo/success";
            $mapeamentos[] = "manager/$controller/$metodo/update";
            $mapeamentos[] = "manager/$controller/$metodo/export";
            $mapeamentos[] = "manager/$controller/$metodo/update_validation";
            
            // se há upload ha dois metodos adicionais
            if($upload){
                
                $mapeamentos[] = "manager/$controller/$metodo/upload_file";
                $mapeamentos[] = "manager/$controller/$metodo/delete_file";
            }
            
            if(!empty($outras_opcoes)){
                foreach($outras_opcoes as $opcao){
                    $mapeamentos[] = "manager/$controller/$opcao";
                }
            }
            
            foreach($mapeamentos as $m){
                $ins['METODO'] = $m;
                $this->db->ignore()->insert("nt_manager_metodos",$ins);
            }
        
    }
    
    
    public function createMetodosImageCRUD($controller,$metodo){
        
        $mapeamentos[] = "manager/$controller/$metodo"; // ex. manager/figuras/user
        $mapeamentos[] = "manager/$controller/$metodo/delete_file";
        $mapeamentos[] = "manager/$controller/$metodo/ordering";
        $mapeamentos[] = "manager/$controller/$metodo/insert_title";
        $mapeamentos[] = "manager/$controller/$metodo/upload_file";
        
        foreach($mapeamentos as $m){
            $ins['METODO'] = $m;
            $this->db->ignore()->insert("nt_manager_metodos",$ins);
        }        
        
    }

}

