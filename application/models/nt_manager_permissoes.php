<?php

/**
 * Classe para controle das permissões dos usuários no sistema
 * 
 * Em quais métodos, URl's, rotas um determinado usuário é permitido
 * em função dos papéis a ele atribuído e do mapeamento de permissões
 * dos papéis.
 *
 * @author Felipe Rosa
 */
class nt_manager_permissoes extends NT_Model {

    // basicamente uma lista de controllers e metodos que este usuario
    // tem direito a executar
    private $permissionMap = array();
    
    private $validation = array(
        array('field' => 'NT_MANAGER_USUARIO_ID', 'label' => 'Usuário', 'rules' => 'required|integer'),
        array('field' => 'NT_MANAGER_PAPEL_ID', 'label' => 'Papel', 'rules' => 'required|integer')
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
     * Retorna a lista de papeis de um determinado usuario
     * 
     * Consulta na tabela nt_manager_permissoes
     * 
     * @param int $userid ID do usuário que se deseja obter os papeis
     * @return array  Lista com 0, 1 ou quantos forem os papeis do usuário, array associativo
     */
    public function getPapeisFromUser($userid,$papel_desejado = '') {

        if(!empty($papel_desejado)){
            $sql = "SELECT 
                            perm.*
                    FROM
                            nt_manager_permissoes perm,
                            nt_manager_papeis pape
                    WHERE
                            perm.NT_MANAGER_PAPEL_ID = pape.ID
                            and pape.NOME = '$papel_desejado'
                    ORDER BY
                        perm.ID ASC
                    LIMIT 1";
            $q = $this->db->query($sql)->result_array();            
        }else{
            $userid = intval($userid); // trata a entrada se o programador tenha passado outro tipo de dados
            // so os papeis ativos que o usuario tem
            $sql = sprintf("select * from %s where NT_MANAGER_USUARIO_ID=%d
                            and NT_MANAGER_PAPEL_ID in (select ID from nt_manager_papeis where ATIVO=1)", $this->sft, $userid);
            $q = $this->db->query($sql)->result_array();
        }
        if (count($q) == 0) 
            return false;
        return $q;
    }

    /**
     * Verifica se já esta em memmória gerado mapa de permissões para um determinado
     * usuário
     * 
     * @return boolean Esta ja ou nao gerado o mapa de permissoes para este usuario
     */
    public function isPermissionMapDefined() {
        if (count($this->permissionMap) < 1) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Cria o mapa de permissoes para ser consultado pelos metodos
     * isPermissionMapDefined(). Os métodos getPermissionMap() e  isValid()
     * precisam que o mapa de permissoes esteja 
     * ja definido, setado.
     * 
     * @param int $userid ID do usuario que esta a se logar
     */
    public function buildPermissionMap($userid) {

        $papeis = $this->getPapeisFromUser($userid);
        $lista = array();

        if (count($papeis) > 0 and $papeis != false) {
            foreach ($papeis as $p)
                $lista[] = $p['NT_MANAGER_PAPEL_ID'];
        } else {
            // usuario nao tem papel definido
        }

        $lista[] = -400; // forçando que ele tenha pelo menus um papel
        // para nao dar erro na SQL

        $papeis_list = implode(',', $lista);


        $query = sprintf("select METODO from nt_manager_metodos
                            where ID in (select NT_MANAGER_METODO_ID from nt_manager_metodos_papeis 
                     where NT_MANAGER_PAPEL_ID in (%s))", $papeis_list);

        $rs = $this->db->query($query)->result_array();

        // limpa caso havia algo no mapa ja
        $this->permissionMap = array();

        if (count($rs) > 0) {
            foreach ($rs as $row)
                $this->permissionMap[] = $row['METODO'];
        }
    }

    /**
     * O mapa de permissoes ja deve ter sido montado - buildPermissionMap()
     * caso contratio este metodo devolve um arrau vazio
     * 
     * @return array retorna o mapa de permissoes
     */
    public function getPermissionMap() {
        return $this->permissionMap;
    }

    /**
     * Compara se o segmento de URL que se esta navegando agora 
     * está no mapa de permissoes do usuario logado
     * 
     * @return boolean true se esta no mapa, false se nao
     */
    public function isValid($path) {

        $pathC = count($path); // quantos segmentos tem o path de agora
        $compare = implode("/", $path);

        //echo($compare.'<hr/>');
        //echo("Main path: $compare <br/>");
        // se eu nao quebrei nada fora da URL, ela tem que ser igual a que esta nas minhas rotas!

        foreach ($this->getPermissionMap() as $route) {
            if (trim($compare) == trim($route)) {
                return true;
            }
        }// fim do foreach
        // se eu quebrei algo fora, tem que coincidir pelo menos 4 segmentos
        // o laco vai ate o 4o segmento... se for menos que isso igual o match nao vale
        for ($i = $pathC; $i > 2; $i--) {

            foreach ($this->getPermissionMap() as $nroute) {
                //echo($compare."<br/>");
                // ok! A comparação (esta url) fchou com alguma rota!
                if (trim($compare) == trim($nroute)) {
                    $temIndex = preg_match("(index)", $nroute);
                    $tamC = count(explode('/', $compare));

                    // tem que fechar pelo menos 4 segmentos, ou a rota é mesmo IGUAL a onde estamos
                    if ($tamC >= 4 and $temIndex == 1) {
                        //echo("Fechou com 4");
                        return true;
                    }
                    // se nao tem index na url, entao tem que fechar com 3 segmentos
                    if ($tamC >= 3 and $temIndex == 0) {
                        //echo("Fechou com 3");
                        return true;
                    }
                    // fechou a rota mas nao satisfaz o tamanho
                }// fim do if
            }// fim do foreach

            unset($path[$i]); // fora o ultimo indice
            $compare = implode("/", $path); // o compare eh o path, cada vez com um seg a menos
        }// fim do for que vai quebrando segmentos


        return false;
    }

    public function manipula_papel($papel) {
    
        $this->permissionMap = $papel;
    }
    
}