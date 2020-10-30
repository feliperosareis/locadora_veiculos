<?php
/**
 * Description of nt_global_facebook_feeds
 *
 * @author Felipe Rosa
 */
require_once APPPATH . "third_party/facebook/facebook.php";

class nt_global_facebook_feeds extends NT_Model {

    public $fbobj;
    
    private $validation = array(
        array('field' => 'FB_ID', 'label' => 'Id no facebook', 'rules' => ''),
        array('field' => 'MESSAGE', 'label' => 'Mensagem', 'rules' => ''),
        array('field' => 'PICTURE', 'label' => 'Figura', 'rules' => ''),
        array('field' => 'LINK', 'label' => 'Link', 'rules' => ''),
        array('field' => 'ICON', 'label' => 'Icone', 'rules' => ''),
        array('field' => 'TYPE', 'label' => 'Tipo', 'rules' => ''),
        array('field' => 'CREATED_TIME', 'label' => 'Criado no FB', 'rules' => ''),
        array('field' => 'UPDATED_TIME', 'label' => 'Atualizado no FB', 'rules' => ''),
        array('field' => 'IMPORTED_TIME', 'label' => 'Importado', 'rules' => ''),
        array('field' => 'SHARES_COUNT', 'label' => 'Shares', 'rules' => ''),
        array('field' => 'COMMENTS_COUNT', 'label' => 'Comments', 'rules' => ''),
        array('field' => 'PUBLIC', 'label' => 'Ativo', 'rules' => ''),
        array('field' => 'LIKES_COUNT', 'label' => 'Likes', 'rules' => '')
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
     * Inicializa e devolve um objeto da classe Facebook, da SDK original
     * 
     * @return Facebook
     */
    public function init() {
        $app_id = $this->nt_global_parametros->q('api_facebook_app_id');
        $secret = $this->nt_global_parametros->q('api_facebook_secret');

        $this->fbobj = new Facebook(array('appId' => $app_id, 'secret' => $secret));
        return $this->fbobj;
    }

    
    /**
     * Retorna a url para permitir a aplicação
     * 
     * @return string
     */
    public function getLoginUrl() {
        
        $params = array(
            'scope' => $this->nt_global_parametros->q('api_facebook_app_scope'),
            'redirect_uri' => base_url()."manager/facebook_feeds/allow/"
        );

        return $this->fbobj->getLoginUrl();
    }

    
    /**
     * Implementa o método getUser da SDK do FB
     * 
     * @return boolean/int false se deu problema, ID do usuário.
     */
    public function getUser(){
        try {
            $user = $this->fbobj->getUser();
        } catch (Exception $exc) {
            $user = false;
        }
        
        return $user;
    }
    
    
    /**
     * Implementa o método para getAccessToken da SDK do Facebook
     * 
     * @return boolean/string false se não conseguir o token, o token se deu certo.
     */
    public function getAccessToken(){
        try {
            $token = $this->fbobj->getAccessToken();
        } catch (Exception $exc) {
            $token = false;
        }
        
        return $token;
    }
    
    
    public function setAccessToken(){
        $at = $this->nt_global_parametros->q("api_facebook_return_access_token");
        $this->fbobj->setAccessToken($at);        
    }
    
    
    /**
     * Lê as os parametros do sistema e devolve true/false se a app já está aceita pelo
     * usuário
     * 
     * @return boolean
     */
    public function isAccepted() {
        
        $res = $this->nt_global_parametros->q('api_facebook_app_accepted');
        if ($res == 'true')
            return true;
        else
            return false;
    }
    
    /**
     * 
     * @return boolean
     */
    public function isValidCache() {
        
        $now = time();
        
        $secsLimit = $this->nt_global_parametros->q("api_facebook_cache_seconds_valid_time");
        $lastUpdate = $this->nt_global_parametros->q("api_facebook_time_last_updated");
        
        // quando foi atualizado + o tempo é maior do que agora. Cache válido.
        if(($secsLimit+$lastUpdate) > $now){
            return true;
        }else{
            return false;
        }
    }    
    

    /**
     * Recebe o pedido que deve atualizar, se tiver cache invalido atualiza
     * retrna true se fez o update e false se o cache estava valido ainda
     */
    public function decideAndMaybeUpdate() {
        
        // do a real Update
        if(!$this->isValidCache()){
            $this->doRequestUpdateData();
            return true;
            
        }else{
            return false;
        }
        
    }

    
    private function doRequestUpdateData() {
        
        $this->init();
        $this->setAccessToken();
        
        $from = $this->nt_global_parametros->q('api_facebook_grab_data_from');
        
        try {
            $rs = $this->fbobj->api("/$from/feed");
            
        } catch (Exception $exc) {
            $rs['data'] = array();
        }

        
        if(count($rs['data']) > 0){
            
            $this->saveStremToDb($rs['data']);
            
            $this->nt_global_parametros->update('api_facebook_time_last_updated', time());
        }
    }
    
    private function saveStremToDb($fetchedStream){
        
        $deffPublic = $this->nt_global_parametros->q('api_facebook_publicar_padrao');
        $deffPublic = ($deffPublic == 'ativo')?1:0;
        
        $set = array();
        $inserts=0;
        $updates= 0;
        $index=-1;
        
        foreach($fetchedStream as $row){
            
            // se precisar ṕegar mais campos, veja o que tem em row e da para aproveitar
            
            // se nao esta definido esse id, pula para o proximo deste laco
            if(!isset($row['id']))
                continue;
            
            
            $index++;
            
            // este registro ja existe?
            $res = $this->db->where("FB_ID", $row['id'])->get($this->sft)->result_array();
            
            $set[$index]['FB_ID'] = $row['id'];
            $set[$index]['MESSAGE'] = (isset($row['message']))?$row['message']:null;
            $set[$index]['PICTURE'] = (isset($row['picture']))?$row['picture']:null;
            $set[$index]['LINK'] = (isset($row['link']))?$row['link']:null;
            $set[$index]['ICON'] = (isset($row['icon']))?$row['icon']:null;
            $set[$index]['TYPE'] = (isset($row['type']))?$row['type']:null;
            $set[$index]['CREATED_TIME'] = (isset($row['created_time']))?$row['created_time']:null;
            $set[$index]['UPDATED_TIME'] = (isset($row['updated_time']))?$row['updated_time']:null;
            $set[$index]['IMPORTED_TIME'] = date("Y-m-d H:i:s");
            $set[$index]['LIKES_COUNT'] = (isset($row['likes']['count']))?$row['likes']['count']:null;
            $set[$index]['SHARES_COUNT'] = (isset($row['shares']['count']))?$row['shares']['count']:null;
            $set[$index]['COMMENTS_COUNT'] = (isset($row['comments']['data']))?count($row['comments']['data']):null;
            $set[$index]['PUBLIC'] = $deffPublic;
            
            // o registro ja existe, atualiza seus dados
            if(count($res) == 1){
                    $set[$index]['ID'] = $res[0]['ID'];
                    
                    // se eh update, nao mexe no que tinha no campo PUBLIC
                    unset($set[$index]['PUBLIC']);
                    
                    $this->db->where("ID", $set[$index]['ID'])->update($this->sft,$set[$index]);
                    $updates++;
            }else{
                $this->db->insert($this->sft,$set[$index]);
                $inserts++;
            }
        }
        
        // grava em log a operação
        $this->nt_global_logs->s("facebook update", "Atualizado feed, $inserts novos registros e $updates atualizados");
        
        return array('inserts'=>$inserts,'updates'=>$updates);        
    }
    
    
    
    /**
     * Busca do Fb (faz rquest na rebe buscando as informações do /me ou /id
     * entre elas o nro de likes de uma Fp + dados básicos, nome, descrição, cover photo..
     * 
     * @return null Nulo se der problema, array se voltar informações
     */
    public function getAboutMeFromFB(){
        
        $this->init();
        $this->setAccessToken();
        
        $from = $this->nt_global_parametros->q('api_facebook_grab_data_from');
        
        try {
            $rs = $this->fbobj->api("/$from");
            
        } catch (Exception $exc) {
            $rs['data'] = null;
        }

	return $rs;        
    }

}