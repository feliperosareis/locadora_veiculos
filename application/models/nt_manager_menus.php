<?php

/**
 * modelo de dados para nt_manager_menus. Monta os menus, verifica se um determinado
 * menu é válido para um usuário, gera mapa de permissões de msnus do usuário
 *
 * @author Felipe Rosa
 */
class nt_manager_menus extends NT_Model {

    private $menus_map;
    private $htmlSecond = "";
    private $htmlMaster = "";
    private $masterCount = 0;
    
    private $validation = array(
        array('field' => 'IDENTIFICADOR', 'label' => 'Identificador', 'rules' => 'required'),
        array('field' => 'TEXTO', 'label' => 'Texto', 'rules' => 'required'),
        array('field' => 'ICONE', 'label' => 'Ícone', 'rules' => ''),
        array('field' => 'ORDEM', 'label' => 'Ordem de exibição', 'rules' => 'required'),
        array('field' => 'TARGET', 'label' => 'Target', 'rules' => 'required'),
        array('field' => 'LINK', 'label' => 'Link fixo?', 'rules' => '')
    );

    public function __construct() {
        parent::__construct();
        $this->menus_map = array();
        $this->load->model("nt_manager_permissoes");
    }

    /**
     * Devolve o array com as validações para esta model
     * 
     * @return array com as regras de validação desta model
     */
    public function getRules() {
        return $this->validation;
    }
    
    /**
     * Devolve a lista de ID's filhos + este elemento de menu
     * 
     * @param int $id
     * @return boolean/string
     */
    public function getFilhosDe($id){
        $eu = $this->getWhereId($id);
        $rs = $this->db->select('ID')->like("IDENTIFICADOR", $eu['IDENTIFICADOR'])->get($this->sft)->result_array();
        if(count($rs) == 0)
            return false;
        
        $limp = array();
        foreach($rs as $row)
            $limp[] = $row['ID'];
        
        
        $lista = implode(",", $limp);
        return $lista;
    }
    

    /**
     * Volta os dados da tabela nt_manager_menus, menus
     *
     * Devolve a lista de menus principais que o array de papeis tem acesso
     * e deve ser exibido em sua tela. Apenas menus principais.
     * 
     * @param array $papeis O array de entada deve estar no formato array(1,2,3) onde 1,2,3 são os papeis atribuidos ao usuario logado por exemplo.
     * @return array
     */
    public function getMainMenusOf($papeis) {
        if (count($papeis) < 1)
            $papeis[] = -400; // prevent SQL errors

        $papeis_list = implode(",", $papeis);

        $rs = $this->db->query(sprintf("select * from nt_manager_menus 
                     where IDENTIFICADOR not like '%%-%%' 
                     and ID in (select NT_MANAGER_MENU_ID from nt_manager_menus_papeis 
                           where NT_MANAGER_PAPEL_ID in (%s)) order by ORDEM asc", $papeis_list)
                )->result_array();
        return $rs;
    }

    /**
     * Volta os dados da tabela nt_manager_menus, submenus
     *
     * Devolve a lista de menus secundarios referentes ao menus principal informado.
     * 
     * 
     * @param array $papeis O array de entada deve estar no formato array(1,2,3) onde 1,2,3 são os papeis atribuidos ao usuario logado por exemplo.
     * @param string $identificador campo IDENTIFICADOR do menu principal
     * @return array
     */
    public function getSubMenusFromMainMenu($papeis, $identificador) {

        if (count($papeis) < 1)
            $papeis[] = -400; // prevent SQL erros
        $papeis_list = implode(",", $papeis);

        $rs = $this->db->query(sprintf("select * from nt_manager_menus where IDENTIFICADOR  
                like '%s-%%' 
                and ID in (select NT_MANAGER_MENU_ID from nt_manager_menus_papeis 
                                    where NT_MANAGER_PAPEL_ID in (%s)) 
                order by ORDEM asc", $identificador, $papeis_list)
                )->result_array();
        return $rs;
    }

    /**
     * Cria neste objeto o mapa de permissoes de um usuario.
     * Apos chamar este metodo, obtenha esse mapa sem consultas ao banco
     * chamando o metodo getMenuMap(). Veja tambem isValid()
     * 
     * @param type $userID  ID do usuário que esta logado/logando
     * @return void Apenas consulta e constroi o índice para as funcões getMenuMap() e isValid()
     */
    public function buildMenuMapFor($userID) {

        $perms = $this->nt_manager_permissoes->getPapeisFromUser($userID);

        if (!$perms)
            return array();

        if (count($perms) > 0) {

            $arr_perms = array(); // acumula os id's dos papeis deste usuario
            foreach ($perms as $p)
                $arr_perms[] = $p['NT_MANAGER_PAPEL_ID'];

            $m_principais = $this->getMainMenusOf($arr_perms);
            if (count($m_principais) > 0) {

                $menus = array();
                foreach ($m_principais as $mpv) {

                    $key = $mpv['IDENTIFICADOR'];

                    // busca os submenus deste menu principal
                    $sub_menus = $this->getSubMenusFromMainMenu($arr_perms, $key);

                    $subes = array();
                    if (count($sub_menus) > 0) {
                        foreach ($sub_menus as $isubmenu) {

                            // busca os metodos deste sub menu
                            $sql = sprintf("select METODO from nt_manager_metodos where ID in (
                                select NT_MANAGER_METODO_ID from nt_manager_metodos_menus
                                where NT_MANAGER_MENU_ID =%s)", $isubmenu['ID']);

                            $rsM = $this->db->query($sql)->result_array();
                            unset($metodos);
                            $metodos = array();
                            if (count($rsM) > 0) {
                                foreach ($rsM as $mSubmenu) {
                                    $metodos[] = $mSubmenu['METODO'];
                                }
                            }

                            $subes[] = array("menus" => $isubmenu['IDENTIFICADOR'], "metodos" => $metodos);
                        }
                    }

                    // metodos do menu principal
                    $sql = sprintf("select METODO from nt_manager_metodos where ID in (
                          select NT_MANAGER_METODO_ID from nt_manager_metodos_menus
                          where NT_MANAGER_MENU_ID =%s)", $mpv['ID']);

                    $rsM = $this->db->query($sql)->result_array();

                    unset($metodosPrincipais);
                    $metodosPrincipais = array();
                    if (count($rsM) > 0) {
                        foreach ($rsM as $mSubmenu) {
                            $metodosPrincipais[] = $mSubmenu['METODO'];
                        }
                    }
                    // monta o mapa de menus
                    $menus["$key"] = array("submenus" => $subes, "metodos" => $metodosPrincipais);
                }

                // seta em menus_map o array
                $this->menus_map = $menus;
            }

            return array(); //esse papel nao tem menus principais
        }

        return array(); // esse usuario nao tem permissoes cadastradas
    }

    /**
     * Devolve um array mapa de menus de um perfil ou conjunto de perfis de usuarios
     * 
     * Esse array é montado pelo método buildMenuMapFor().
     * Veja também isValid()
     * 
     * @return array no seguinte formato: array("main1"=>array("i 1","1 2"),"main2"=>array(...))
     */
    public function getMenuMap() {
        return $this->menus_map;
    }

    /**
     * Atenção! Antes de chamar este método o mapa de menus deve ser construído
     * chamando o método buildMenuMapFor()
     * 
     * Verifica se o índice passado é um dos valores do mapa de permissão. Este método
     * deve ser chamado ao montar o menu para o usuário, saber se determinado usuário 
     * pode ver determinado item de menu.
     * 
     * @param string $identificador Identificados do menu, string
     * @return boolean true se esse identificador estiver no mapa de menus do usuário
     */
    public function isValid($identificador) {

        $menus = array();

        foreach ($this->getMenuMap() as $master => $subs) {

            $menus[] = $master; // no primeiro nivel o identificador e a chave

            if (count($subs['submenus']) > 0) {
                foreach ($subs['submenus'] as $item) {
                    $menus[] = $item['menus'];
                }
            } // fim do if
        }// fim do foreach dos masters menus

        if (in_array($identificador, $menus))
            return true;
        else
            return false;
    }

    /**
     * Vai construindo o menu do site, adiciona menu ou submenu
     * Ele sabe se o que esta sendo adicionado é um submenu pelo identificador,
     * os identificadores principais não tem "-", os subs tem.
     * 
     * O retorno desse método é pelos métodos getHtmlMain() e getHtmlSecond()
     * 
     * @param int $identf identificador do item de menu a ser adicionado
     * @param string $html HTML do item de menu que deve ser adicionado
     */
    public function add($identf, $html) {

//        echo($identf);
//        print_r($this->getMenuMap());
//        echo('<hr/>');

        if ($this->isValid($identf)) {

            $tmp = explode("-", $identf);
            $seg = $this->uri->segments;
            
            $compare = $seg[1];

            if (isset($seg[2]))
                $compare.='/' . $seg[2];


            // menu secundario
            if (count($tmp) > 1) {

                // em qual main menu esta o sub-menu que foi passado?
                foreach ($this->getMenuMap() as $idPrincipal => $principais) {
                    $metodos = $principais['metodos'];
                    // se o compare estiver nos metodos, sei qual grupo de menus que 
                    // se trata
                    if (in_array($compare, $metodos)) {

                        // agora eu sei que posso aceitar o range de identificadores
                        // de menus que estao em este grupo
                        // monta o range
                        $aceitar = array();
                        foreach ($principais['submenus'] as $submenu)
                            $aceitar[] = array('identf' => $submenu['menus'], 'metodos' => $submenu['metodos']);

                        foreach ($aceitar as $m) {

                            // echo(implode(',',$seg). ' ');
                            // echo ("Rodando: ".$m['identf']." idf passado:".$identf." Compare $compare<br/> \n");

                            if ($m['identf'] == $identf) {

                                $newCompare = implode("/", $seg); // toda a URL
                                $tmpSeg = $seg; // desse cara camos tirando os pedacos ao fim
                                $quantosSegs = count($seg); // quantos segmentos tem a URL

                                $todosNew = count($m['metodos']); // quantos metodos estao associados a este identificador de menu
                                // cada passada aqui corta o ultimo segmento da URL fora
                                for ($topo = $quantosSegs; $topo > 0; $topo--) {

                                    if (in_array($newCompare, $m['metodos'])) {
                                        $html = str_replace("item_submenu_manager", "item_submenu_manager selected_sub", $html);
                                        break;
                                    }

                                    unset($tmpSeg[$topo]);
                                    $newCompare = implode("/", $tmpSeg);
                                } // fim do for
                                
                                
                                $noaddMaster = false;
                                // de deve tenatar fazer replace da URL
                                if(strpos($html,"REPLACE")){
                                    $urlReplace = $this->getURLToMenu($identf);
                                    if($urlReplace) {
                                        $html = str_replace("REPLACE", $urlReplace, $html);
                                    } else {
                                        $noaddMaster = true;
                                    }
                                }
                                
                                // se o noadd estiver falso - estado padrao
                                if(!$noaddMaster) { 
                                    // add do html passado
                                    $this->htmlSecond.= $html;
                                }
                                
                                
                            }
                        }
                    }
                }
            } else {
                
                $noaddMaster = false;
                // de deve tenatar fazer replace da URL
                if(strpos($html,"REPLACE")){
                    $urlReplace = $this->getURLToMenu($identf);
                    if($urlReplace) {
                        $html = str_replace("REPLACE", $urlReplace, $html);
                    } else {
                        $noaddMaster = true;
                    }
                }
                
                if(!$noaddMaster) { 
                    // menu primario
                    $this->htmlMaster.= $html;
                    $this->masterCount = $this->masterCount + 1;
                }
            }
        } //else {
        //     echo('menu nao valido para este usuario, nao adicionado');
        //}
    }

    
    
    private function getURLToMenu($menuidf){
        
        $metodo = $this->getFirstEndPointMethod($menuidf);
        
        if(!$metodo)
            return false;
        else 
            return base_url().$metodo;
    }
    
    
    public function getFirstEndPointMethod($menuidf){
        
        $menus = array();

        foreach ($this->getMenuMap() as $master => $subs) {

            $menus[] = $master; // no primeiro nivel o identificador e a chave

            if (count($subs['submenus']) > 0) {
                foreach ($subs['submenus'] as $item) {
                    $menus[] = $item['menus'];
                }
            } // fim do if
        }// fim do foreach dos masters menus
        
        // nao queremos menus principais junto
        if(!preg_match("/-/", $menuidf)){
            $menuidf = $menuidf."-";
        }
        
        $relacionados = array();
        foreach($menus as $row){
            if(preg_match("/$menuidf/", $row)){
                $relacionados[] = $row;
            }
        }
        
        
        $lista = implode("','", $relacionados);
        $lista = "'".$lista."'";
        
        $sqlMapaMetodosOrdem = sprintf("select * from (
                                select relacionados.*, nt_manager_metodos_menus.ORDEM
                                from (select ID as METODO_ID, METODO from nt_manager_metodos where ID in (
                                    select  NT_MANAGER_METODO_ID from nt_manager_metodos_menus where NT_MANAGER_MENU_ID in (
                                            select ID from nt_manager_menus where IDENTIFICADOR in 
                                                (%s)
                                        )
                                    )
                                ) as relacionados, nt_manager_metodos_menus

                                where relacionados.METODO_ID = nt_manager_metodos_menus.NT_MANAGER_METODO_ID
                                order by nt_manager_metodos_menus.ORDEM DESC
                                ) as mordem 
                                group by METODO_ID order by ORDEM ASC",$lista);
        
        // echo("\n\n".$sqlMapaMetodosOrdem."\n\n<hr/>");
        
        $listaMetodosOrdenados  = $this->db->query($sqlMapaMetodosOrdem)->result_array();
           
        if(count($listaMetodosOrdenados) == 0)
            return false;
        
        // o primeiro item que tem permissao valida. ok!
        foreach($listaMetodosOrdenados as $item){
            $comparar = explode("/", $item['METODO']);
            if($this->nt_manager_permissoes->isValid($comparar))
                return $item['METODO'];
        }
        
        return false;
    }
    
    
    /**
     * Devolve o HTML do menu construído, de acordo com a hierarquia que está
     * e conforme as permissões do usuário. Menu principal
     * 
     * @return string_html Volta o HTML montado do menu ou conjunto de menus
     */
    public function getHtmlMain() {
        return $this->htmlMaster;
    }

    /**
     * Devolve os  menus secundários conforme as permissões do usuário logado
     * 
     * @return string_html HTML do menu secindário ou conjunto de menus
     */
    public function getHtmlSecond() {
        return $this->htmlSecond;
    }

    /**
     * Limpa a construção de menu armazenada até agora.
     * Sempre que for fazer um novo menu, chame esta função para limpar 
     * rastros do menu anterior
     */
    public function resetMain() {
        $this->htmlMaster = "";
    }

    /**
     * Limpa as variáveis para que um segundo sub-menu seja criado.
     * Chame ao criar um menu novo. 
     * Veja exemplo de uso em application/views/manager/m_parametros/index.php
     */
    public function resetSecond() {
        $this->htmlSecond = '';
    }

    /**
     * Volta toda a hierarquia de nenus em formato de uma árvore binária não balanceada
     * ATENCAO! Suporta até 4 níveis de menu, ex.: config-usuario-sessao-logs 
     * Isso limita os menus do manager a 4 níveis, principal (cabeçalho com ícones),
     * secundario (primeiro abaixo do principal), e mais dois aí abaixo.
     * De momento o máximo que se tem sao 3 níveis em uso Configs->Parametros->Segurança por exemplo
     * 
     * @return array 
     */
    public function getTreeMenus() {
        $menus = array();
        
        $mains = $this->getMainMenus();
        
        $i = 0;
        foreach ($mains as $m) {
            $menus[$i] = $m; // principais
            
            $sub = $this->getSubMenusFrom($m['IDENTIFICADOR']);
            if(count($sub) > 0){
                $j = 0;
                foreach($sub as $s0){
                    $menus[$i]['sub'][$j] = $s0; // menus-secundarios
                    
                    $sub1 = $this->getSubMenusFrom($s0['IDENTIFICADOR']);
                    if(count($sub1) > 0){
                        $k = 0;
                        foreach ($sub1 as $s1) {
                            $menus[$i]['sub'][$j]['sub'][$k] = $s1; // isso-ser-terciario
                            
                            $sub2 = $this->getSubMenusFrom($s1['IDENTIFICADOR']);
                            if(count($sub2) > 0){
                                $m = 0;
                                foreach($sub2 as $s2){
                                    $menus[$i]['sub'][$j]['sub'][$k]['sub'][$m] = $s2; // isso-ser-menu-quaternario
                                    $m++;
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
        
        
        
        return $menus;
    }

    /**
     * Volta um array de menus principais, todos
     * 
     * @return array todos os menus de nível 0, os masters
     */
    public function getMainMenus() {
        $rs = $this->db->query("select * from nt_manager_menus where IDENTIFICADOR not like '%%-%%' order by ORDEM asc ")->result_array();
        return $rs;
    }

    
    /**
     * Passa o identificador do menu pai, e volta todos os seus filhos
     * 
     * @param string $identificador identificador de menus do qual se quer obter os menus filhos
     * @return array
     */
    public function getSubMenusFrom($identificador) {
        
        $rs = $this->db->query(sprintf("select * from nt_manager_menus where IDENTIFICADOR  like '%s' order by ORDEM asc ",
                                                $identificador.'-%' // deve fazer like
                                        )
                               )->result_array();
        
        // so quero filhos de primeira decendencia, então fora itens que não sao apenas UM nivel
        // a mais que o identificador passado
        $idf = explode("-", $identificador);
        $cnt = count($idf); // cnt eh EXATO o nro de maches que pode haver de - no elemento da lista
        
        $lista = array();
        
        if(count($rs)){
            foreach($rs as $row){
                $check = substr_count($row['IDENTIFICADOR'], '-');
                if($check == $cnt)
                    $lista[] = $row;
            }
        }
        
        return $lista;
    }
    

    /**
     * Busca da tabela de relacionamento entre papeis e menus (nt_manager_menus_papeis) 
     * a lista de menus relacionados a um determinado papel
     * 
     * @param int $papel
     * @return array
     */
    function getMenusOf($papel){
        
        $rs = $this->db->select("NT_MANAGER_MENU_ID")
                            ->where("NT_MANAGER_PAPEL_ID",$papel)
                            ->get("nt_manager_menus_papeis")
                            ->result_array();
        return $rs;
    }

}
