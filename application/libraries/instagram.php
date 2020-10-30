<?php
/**
 * Description here
 * 
 * @author Felipe Rosa
 * 
 * $access_token não vem o padrão da API no construct,
 *  pois alguém pode precisar de diferentes access token futuramente
 */

class instagram {
    public function __construct() {
         $this->ci = &get_instance();
    }
    
    public function getUserId($username,$access_token = NULL){
        
        $this->ci->load->model('nt_global_parametros');
        if (!$access_token){
            $access_token =  $this->ci->nt_global_parametros->q('api_instagram_access_token');
        }
        $link = "https://api.instagram.com/v1/users/search?q=$username&access_token=$access_token";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        $dados = json_decode($response);
        if(!empty($dados->data[0]->id)){
            return $dados->data[0]->id;      
        }else{
            return false;
        }
    }
    
    
    /**
     * 
     * A API do facebook, traz os resultados em ordem DESCRESCENTE, não tem como alterar....
     * 
     * @param integer $user_id          ID do usuário   
     * @param string  $access_token	Access_token de autorização
     * @param integer $limite           Quantidade de registro que deve retornar Max(500)
     * @param date $data_inicio         Resultados a partir da $data_fim Ex: 22-02-2008 22:50:20 OU 2008-02-22 22:50:20 OU
     * @param date $data_fim            Resultados até a $data_fim Ex: 22-09-2008 22:50:20 OU 2008-09-22 22:12:20
     * @param date $hashtag             Ele procura SOMENTE na descrição a hashtag, e NÃO nas tags que é o que a outra faz.
     */
    public function getFeeds($user_id,$access_token = NULL, $limite = '500', $data_inicio = false, $data_fim = false, $hashtag = false){
         
        $this->ci->load->model('nt_global_parametros');
        if (!$access_token){
            $access_token =  $this->ci->nt_global_parametros->q('api_instagram_access_token');
        }
        $outros_filtros = '';
        if($data_inicio){
            $outros_filtros.= '&min_timestamp='.strtotime($data_inicio);
        }
        if($data_fim){
            $outros_filtros.= '&max_timestamp='.strtotime($data_fim);
        }   
        $link = "https://api.instagram.com/v1/users/$user_id/media/recent/?access_token=$access_token$outros_filtros";
        $continua_verificando = true;
        $feeds = array(); $contagem = 0;
        while ($continua_verificando == true){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $link);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            $dados = json_decode($response); 
            
            if ($hashtag == false) {
                foreach ($dados->data as $feed) {
                    $tags = '';
                    $feeds[$contagem]['DATA_CRIADA'] = date('Y-m-d h:i:s', $feed->created_time);
                    $feeds[$contagem]['ID_FOTO_INSTAGRAM'] = $feed->id;
                    $feeds[$contagem]['DESCRICAO'] = isset($feed->caption->text) ? $feed->caption->text : '';
                    $feeds[$contagem]['LINK_EXTERNO'] = $feed->link;
                    $feeds[$contagem]['CURTIDAS'] = $feed->likes->count;
                    $feeds[$contagem]['IMAGEM_MINIATURA'] = $feed->images->thumbnail->url;
                    $feeds[$contagem]['IMAGEM_BAIXA_RESOLUCAO'] = $feed->images->low_resolution->url;
                    $feeds[$contagem]['IMAGEM_PADRAO'] = $feed->images->standard_resolution->url;
                    $feeds[$contagem]['ATIVO'] = 0;
                    //TAGS DO POST//
                    foreach ($feed->tags as $tag) {
                        $tags .= $tag . ', ';
                    }
                    $feeds[$contagem]['TAGS'] = substr($tags, 0, -2);
                    $contagem++;
                    if ($contagem == $limite) {
                        $continua_verificando = false;
                        break;
                    }
                }
            } else {
                foreach ($dados->data as $feed) {
                    $tags = '';
                    if (substr_count(strtolower($feed->caption->text), strtolower('#' . $hashtag))) {
                        $feeds[$contagem]['DATA_CRIADA'] = date('Y-m-d h:i:s', $feed->created_time);
                        $feeds[$contagem]['ID_FOTO_INSTAGRAM'] = $feed->id;
                        $feeds[$contagem]['DESCRICAO'] = $feed->caption->text;
                        $feeds[$contagem]['LINK_EXTERNO'] = $feed->link;
                        $feeds[$contagem]['CURTIDAS'] = $feed->likes->count;
                        $feeds[$contagem]['IMAGEM_MINIATURA'] = $feed->images->thumbnail->url;
                        $feeds[$contagem]['IMAGEM_BAIXA_RESOLUCAO'] = $feed->images->low_resolution->url;
                        $feeds[$contagem]['IMAGEM_PADRAO'] = $feed->images->standard_resolution->url;
                        $feeds[$contagem]['ATIVO'] = 0;
                        //TAGS DO POST//
                        foreach ($feed->tags as $tag) {
                            $tags .= $tag . ', ';
                        }
                        $feeds[$contagem]['TAGS'] = substr($tags, 0, -2);
                        $contagem++;
                        if ($contagem == $limite) {
                            $continua_verificando = false;
                            break;
                        }
                    }
                }
            }
            if(sizeof(@$dados->pagination->next_url) == 0){
                $continua_verificando = false;
            }else if($continua_verificando != false){
                $link = @$dados->pagination->next_url;
           }
        }
        return $feeds;
    }
    
    
    /**
     * 
     *  MUITO IMPORTANTE // ATUALIZAÇÃO DAS CURTIDAS SÓ FUNCIONA SE TIVER FEEDS
     *   >>>>> SEM HASHTAG / TAG <<<<<<, ou seja, tem que ser de um usuário especifico.
     * 
     * @param integer $user_id          ID do usuário   
     * @param string  $access_token	Access_token de autorização
     */
    public function getUpdateCurtidas($user_id,$access_token = NULL, $data_inicio){
         
        //$user_id = '13460080';
        $this->ci->load->model('nt_global_parametros');
        if (!$access_token){
            $access_token =  $this->ci->nt_global_parametros->q('api_instagram_access_token');
        }
        
        $outros_filtros = '&min_timestamp='.strtotime($data_inicio);
        
        $link = "https://api.instagram.com/v1/users/$user_id/media/recent/?access_token=$access_token$outros_filtros";
       
        $continua_verificando = true;
        $feeds = array(); $contagem = 0;
        while ($continua_verificando == true){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $link);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            $dados = json_decode($response); 
            foreach($dados->data as $feed){
                $feeds[$contagem]['ID_FOTO_INSTAGRAM'] = $feed->id;
                $feeds[$contagem]['CURTIDAS'] = $feed->likes->count;
                $contagem++;
                
            }            
            if(sizeof(@$dados->pagination->next_url) == 0){
                $continua_verificando = false;
            }else if($continua_verificando != false){
                $link = @$dados->pagination->next_url;
           }
        }
        return $feeds;
    }
    
    /**
      * Captura feeds dos instagram, que possuem a hashtag
      * conforme configurado nos parâmetros em: api_instagram_hashtag
      * 
     * @param string  $hashtag          hashtag que deve conter nos feeds  
     * @param string  $access_token	Access_token de autorização
     * @param int     $limite           Número de retornos desejados (Max 500).
     */
    public function getFeedsByHashTag($hashtag, $access_token = NULL, $limite = '500'){
        $limite = ($limite > '500' ? '500' : $limite);
        $this->ci->load->model('nt_global_parametros');
        if (!$access_token){
            $access_token =  $this->ci->nt_global_parametros->q('api_instagram_access_token');
        }
        
        $link = "https://api.instagram.com/v1/tags/$hashtag/media/recent?access_token=$access_token";

        $continua_verificando = true;
        $feeds = array(); $contagem = 0; 
        while ($continua_verificando == true){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $link);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            $dados = json_decode($response); 
            foreach($dados->data as $feed){
                $tags = '';
                $feeds[$contagem]['DATA_CRIADA'] = date('Y-m-d h:i:s', $feed->created_time);
                $feeds[$contagem]['ID_FOTO_INSTAGRAM'] = $feed->id;
                $feeds[$contagem]['DESCRICAO'] = $feed->caption->text;
                $feeds[$contagem]['LINK_EXTERNO'] = $feed->link;
                $feeds[$contagem]['CURTIDAS'] = $feed->likes->count;
                $feeds[$contagem]['IMAGEM_MINIATURA'] = $feed->images->thumbnail->url;
                $feeds[$contagem]['IMAGEM_BAIXA_RESOLUCAO'] = $feed->images->low_resolution->url;
                $feeds[$contagem]['IMAGEM_PADRAO'] = $feed->images->standard_resolution->url;
                $feeds[$contagem]['ATIVO'] = 0;
                //TAGS DO POST//
                foreach ($feed->tags as $tag){
                    $tags .= $tag.', ';
                }
                $feeds[$contagem]['TAGS'] = substr($tags,0,-2);
                
                $contagem++;
                if($contagem == $limite){
                    $continua_verificando = false;
                    break;
                }
                
            } 
            if(sizeof(@$dados->pagination->next_url) == 0){
                $continua_verificando = false;
            }else if($continua_verificando != false){
                $link = @$dados->pagination->next_url;
           }
        }
        return $feeds;
    }
    
}