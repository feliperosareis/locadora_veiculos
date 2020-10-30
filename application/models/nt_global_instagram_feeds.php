<?php
/**
 * modelo de dados para nt_global_instagram_feeds
 *
 * @author Felipe Rosa
 */
class nt_global_instagram_feeds extends NT_Model {
    
    private $validation = array(
        array('field' => 'DATA_CRIADA', 'label' => 'Data', 'rules' => ''),
        array('field' => 'DESCRICAO', 'label' => 'Descrição', 'rules' => ''),
        array('field' => 'LINK_EXTERNO', 'label' => 'Link do Instagram', 'rules' => ''),
        array('field' => 'CURTIDAS', 'label' => 'Curtidas', 'rules' => ''),
        array('field' => 'IMAGEM_MINIATURA', 'label' => 'Miniatura da imagem.', 'rules' => ''),
        array('field' => 'IMAGEM_BAIXA_RESOLUCAO', 'label' => 'Imagem baixa resolução ', 'rules' => ''),
        array('field' => 'IMAGEM_PADRAO', 'label' => 'Imagem Padrão', 'rules' => ''),
        array('field' => 'ATIVO', 'label' => 'Ativo', 'rules' => '')
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
     * @param integer $user_id          ID do usuário   
     * @param string  $access_token	Access_token de autorização
     * @param integer $limite           Quantidade de registro que deve retornar.
     * @param date $data_inicio         Resultados a partir da $data_fim Ex: 22-02-2008 22:50:20
     * @param date $data_fim            Resultados até a $data_fim Ex: 22-09-2008 22:50:20
     * @param date $hashtag             Ele procura SOMENTE na descrição a hashtag, e NÃO nas tags que é o que a outra faz.
     */
    
    public function getFeeds($user_id,$access_token = NULL, $limite = '500', $data_inicio = false, $data_fim = false, $hashtag = false){
        $limite = (string) $limite; //NÃO ME PERGUNTE PORQUE, MAS SÓ FUNCIONA ASSIM! :@
        $this->load->library('instagram');
        $retorno = $this->instagram->getFeeds($user_id, $access_token, $limite, $data_inicio, $data_fim, $hashtag);
        return $this->db->ignore()->insert_batch('nt_global_instagram_feeds', $retorno);
    }
    
     /**
      * Atualiza as curtidas de um usuário especifico, 
      * lembrando que não faz atualização caso os feedas 
      * capturados tenham sidos por hashtag
      * 
     * @param integer $user_id          ID do usuário   
     * @param string  $access_token	Access_token de autorização
     */
    public function getUpdateCurtidas($user_id,$access_token = NULL){
        $this->load->library('instagram');
        $primeira_data_criada = $this->getFirstDate();
        $retorno = $this->instagram->getUpdateCurtidas($user_id, $access_token, $primeira_data_criada);
        return $this->db->update_batch('nt_global_instagram_feeds', $retorno, 'ID_FOTO_INSTAGRAM'); 
         
    }
    
    
     /**
      * Captura feeds dos instagram, que possuem a hashtag
      * conforme configurado nos parâmetros em: api_instagram_hashtag
      * 
     * @param string  $hashtag          hashtag que deve conter nos feeds  
     * @param string  $access_token	Access_token de autorização
     * @param string     $limite           Número de retornos desejados (Max 500).
     */
    
    public function getFeedsByHashTag($hashtag, $access_token = NULL, $limite = '500'){
        $limite = (string) $limite; //NÃO ME PERGUNTE PORQUE, MAS ´SO FUNCIONA ASSIM! :@
        $hashtag = str_replace(' ','',strtolower($hashtag));
        $this->load->library('instagram');
        $retorno = $this->instagram->getFeedsByHashTag($hashtag, $access_token, $limite);
        return $this->db->ignore()->insert_batch('nt_global_instagram_feeds', $retorno);
    }
    
   /**
     * Devolve a última data criada
     * 
     * @return data
     */
    
     public  function getLastDate(){
        $LAST_DATE = $this->db->query('SELECT DATA_CRIADA FROM nt_global_instagram_feeds ORDER BY DATA_CRIADA DESC LIMIT 1')->row();
        if(sizeof($LAST_DATE) == 0){
            return '0';
        }else{
            return $LAST_DATE->DATA_CRIADA;
        }
    }
    
    /**
     * Devolve a primeira data criada
     * 
     * @return data
     */
    
     public  function getFirstDate(){
        $LAST_DATE = $this->db->query('SELECT DATA_CRIADA FROM nt_global_instagram_feeds ORDER BY DATA_CRIADA ASC LIMIT 1')->row();
        if(sizeof($LAST_DATE) == 0){
            return '0';
        }else{
            return $LAST_DATE->DATA_CRIADA;
        }
    }
    
     /**
     * Devolve "X" imagens dos feeds do instagram
     * 
     * @param integer  $limite	Quantidade de imagens
     */
    
     public  function getListaFeed($limite){
         
        $sql =  "SELECT IMAGEM_BAIXA_RESOLUCAO, IMAGEM_PADRAO, LINK_EXTERNO, DESCRICAO FROM nt_global_instagram_feeds ORDER BY DATA_CRIADA DESC LIMIT $limite";

        $arrayData = $this->db->query($sql)->result_array();

        return $arrayData;
    }
    
}