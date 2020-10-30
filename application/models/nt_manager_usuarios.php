<?php

/**
 * Modelo de dados para nt_manager_usuarios, controla criptografia,
 * validade de acesso de determinada origem, faz login e logout,
 * busca as informações do usuário logado
 *
 * @author Felipe Rosa
 */
class nt_manager_usuarios extends NT_Model {
    
    private $validation = array(
        array('field' => 'NOME', 'label' => 'Nome', 'rules' => 'trim|required'),
        array('field' => 'USUARIO', 'label' => 'Usuário', 'rules' => 'trim|required'),
        array('field' => 'SENHA', 'label' => 'Senha', 'rules' => 'trim|required'),
        array('field' => 'EMAIL', 'label' => 'E-mail', 'rules' => 'trim|required|valid_email'),
        array('field' => 'ATIVO', 'label' => 'Ativo', 'rules' => 'required'),
        array('field' => 'FOTO_PERFIL', 'label' => 'Foto do perfil', 'rules' => ''),
        array('field' => 'URLPOSLOGIN', 'label' => 'URL pós login no manager', 'rules' => '')
    );
    
    /**
     * Devolve o array com as validações para esta model
     * 
     * @return array com as regras de validação desta model
     */
    public function getRules(){
        return $this->validation;
    }    
    

    /**
     * Criptografa a string passada de acordo com a criptografia
     * definida no arquivo de configuração em "passwords_hash"
     * 
     * Devolve a string criptografada
     * 
     * @param string $plainText
     * @return string
     */
    public function cryptText($plainText) {
        return hash($this->config->item('passwords_hash'), $plainText);
    }

    
    /**
     * Retorna true se o IP do usuário esta em uma das faixas de IP's
     * atribuidas para uma determinada lista de papeis (que o usuario tem atribuido a ele)
     * 
     * @param type $papeis no formato de saída da função nt_manager_permissoes->getPapeisFromUser()
     * @return boolean true se o IP do usuario está em uma das faixas
     */
    public function isValidIncoming($papeis) {
        $listaPapeis = array();
        foreach ($papeis as $p)
            $listaPapeis[] = $p['NT_MANAGER_PAPEL_ID'];

        $papeis = implode(',', $listaPapeis);

        $this->load->library("network");

        // quais sao as faixas de IP's que se aplicam aos papeis que o usuario tem?
        $sql = sprintf("select * from nt_manager_origens where ATIVO=1 and
            ID in (select NT_MANAGER_ORIGEN_ID from nt_manager_origens_papeis 
                    where NT_MANAGER_PAPEL_ID in (%s))", $papeis);

        $listas = $this->db->query($sql)->result_array();

        $meuIP = $this->input->ip_address();

        $countOK = 0;
        foreach ($listas as $range) {
            
            // se por acaso nao deva fazer checagem de IP
            if($range['IP_PERMITIDO_INI'] == 'nao-verificar'){
                return true;
            }
            
            $check = $range['IP_PERMITIDO_INI'] . "-" . $range['IP_PERMITIDO_FIM'];

            if ($this->network->ip_in_range($meuIP, $check))
                $countOK++;
        }

        // pelo menos em uma das faixas de permitidos meu IP deve estar
        if ($countOK > 0)
            return true;
        else
            return false;
    }

    
    /**
     * Faz login de um determinado usuário no sistema
     * 
     * Verifica se a origem deste logim é valida
     * Ao fazer login seta as sessions de controle também.
     * 
     * @param type $plainUser usuário em texto plano
     * @param type $plainPass senha plana, é criptografada antes da comparação
     * @return boolean/string true se pode autenticar, false se falhou, "outOfRange" se autenticou nas o local de acesso não é permitido
     */
    public function logInUser($plainUser, $plainPass) {

        $pass = $this->cryptText($plainPass);
        $sql = "SELECT
                        USUARIO, SENHA, ID, EMAIL, '0' as NIVEL
                FROM
                        %s
                WHERE
                        ATIVO = 1
                        AND SENHA = '%s'
                        AND USUARIO = '%s'
                ";
        $sql = sprintf($sql,$this->sft, $pass, addslashes($plainUser), $plainPass, addslashes($plainUser));
        
        $rs = $this->db->query($sql)->result_array();
        
        // apenas reseta a variavel
        $this->session->set_userdata(array('inc' => "ok"));

        // devolveu UMA linha
        if (count($rs) == 1) {

            // os dados que devolveu SAO os que o usuario informou
            if (($rs[0]['SENHA'] == $pass or $rs[0]['SENHA'] == md5($plainPass)) and $rs[0]['USUARIO'] == $plainUser) {
                
                 // tudo limpo :) Ja sabemos quem eh o usuario
                $id = $rs[0]['ID'];
                $this->load->model("nt_manager_permissoes");
                if($rs[0]['NIVEL'] == 0){
                    $papeis = $this->nt_manager_permissoes->getPapeisFromUser($id);
                }else{
                    $papeis = $this->nt_manager_permissoes->getPapeisFromUser($id,($rs[0]['NIVEL'] == 1 ? 'Sistema Administrador' : ''));
                }

                // nenhum dos papeis do usuario esta ativo
                if(!$papeis){
                    $this->session->set_userdata(array('inc' => "nopactive"));
                    $this->nt_global_logs->s('no papeis', 'Nenhum dos papeis do usuario esta ativo');
                    
                    return false;
                }
                    
                if ($this->isValidIncoming($papeis)) {

                    $dados['login']['id'] = $papeis[0]['NT_MANAGER_USUARIO_ID'];
                    $dados['login']['nome'] = $rs[0]['NOME'];
                    $dados['login']['usuario'] = $rs[0]['USUARIO'];
                    $dados['login']['email'] = $rs[0]['EMAIL'];
                    $dados['login']['time'] = time();

                    $this->session->set_userdata($dados);
                    $this->session->set_userdata(array("login_id" =>$dados['login']['id']));

                    // valid incomming
                    $this->session->set_userdata(array('inc' => "ok"));
                    $this->nt_global_logs->s('origem', 'Valida a partir de ' . $this->input->ip_address());
                    return true;
                    
                } else {
                    // not valid incomming, log alguma coisa
                    $this->session->set_userdata(array('inc' => "falso"));
                    $this->nt_global_logs->s('origem', 'Invalida a partir de ' . $this->input->ip_address());
                }
            }
        }

        return false;
    }

    
    /**
     * Faz logout de qualquer usuário logado no sistema.
     * Reseta toda a session.
     */
    public function logOutUser() {
        $this->session->unset_userdata("login");
        //$this->session->sess_destroy(); // assim causa problemas no resto do site, apaga TODAS as sessions
    }

    /**
     * Lê o array de informações do usuário logado e devolve
     * 
     * @return array com os idices: id, nome, usuario, email, time
     */
    public function getLogedUserInfos() {
        $dados = $this->session->userdata('login');
        return $dados;
    }

}
