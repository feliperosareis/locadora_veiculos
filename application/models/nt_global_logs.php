<?php

/**
 * modelo de dados para nt_global_logs. Disponibiliza a função/método: s
 * para gravar algo em log. O log é em banco de dados
 *
 * @author Felipe Rosa
 */
class nt_global_logs extends NT_Model {

    private $validation = array(
        array('field' => 'OPERACAO', 'label' => 'Operação', 'rules' => 'required'),
        array('field' => 'NT_MANAGER_USUARIO_ID', 'label' => 'Uid', 'rules' => 'required'),
        array('field' => 'DATA_HORA', 'label' => 'Data', 'rules' => 'required'),
        array('field' => 'CONSULTA_SQL', 'label' => 'SQL', 'rules' => 'required'),
        array('field' => 'DESCRICAO', 'label' => 'Descrição', 'rules' => 'required'),
        array('field' => 'IP_ORIGEM', 'label' => 'IP', 'rules' => 'required')
    );
    
                            
    public function __construct() {
        parent::__construct();
        $this->load->helper('security');
    }
    
    
    /**
     * Devolve o array com as validações para esta model
     * 
     * @return array com as regras de validação desta model
     */
    public function getRules(){
        return $this->validation;
    }   
    

    /**
     * Grava em log uma determida ação. A model desse método é carregada no
     * autoload.php, assim em qualquer lugar do framework pode-se chamar essa 
     * função com apenas uma linha $this->nt_global_logs->s()
     * 
     * Todos os parametros sao opcionais.
     * 
     * @param type $operacao nome da operação
     * @param type $descricao descricao mais detalhada
     * @param type $sql sql executada
     */
    public function s($operacao = null, $descricao = null, $sql = null) {

        $operacao = addslashes(htmlspecialchars(htmlentities($operacao)));        

        $descricao = addslashes(htmlspecialchars(htmlentities($descricao)));

        $sql = addslashes(htmlspecialchars(htmlentities($sql)));

        $l = $this->session->userdata('login');

        if (!isset($l['id'])) {
            $logado = 0;
        } else {
            $l = $this->session->userdata('login');
            $logado = $l['id'];
        }

        $meuIP = $this->input->ip_address();


        $sqlInsert = sprintf("insert into %s (NT_MANAGER_USUARIO_ID,
                    DATA_HORA, OPERACAO, DESCRICAO, CONSULTA_SQL, IP_ORIGEM)
                    values ('%s', now(), '%s', '%s', '%s', '%s')",$this->sft, $logado, $operacao, $descricao, $sql, $meuIP);

        $this->db->query($sqlInsert);
        
        // se deve excluir o ultimo registro de log ao dar insert no mais recente
        if($this->nt_global_parametros->q('delete_last_log_on_insert_new') == '1'){
            
            $r = $this->db->query("select min(ID) as del from nt_global_logs")->result_array();
            
            $del = "delete from {$this->sft} where ID = {$r[0]['del']}";
            $this->db->query($del);
        }
    }
    
}

