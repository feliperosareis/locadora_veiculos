<?php

class repplica {
  
    private $url_estoque = 'https://www.repplica.com.br/api-client/api/0.2/IntegracaoVeiculos/estoque/format/json';    

    private function set_fields($fields = array()){

        $fields_string = null;

        foreach($fields as $key=> $value) { 
            $fields_string .= $key.'='.$value.'&'; 
        }

        rtrim($fields_string, '&');

        return $fields_string;
    }

    public function listaEstoque($token = null){

        $url = $this->url_estoque . '?X-API-KEY=' . $token;

        $result = $this->file_get($url);         
        
        if(count($result)){
            return $this->retorno(200, null, $result);
        }

        $msg = 'Nenhum resultado encontrado';
        return $this->retorno(404, $msg);

    }
 
    private function file_get($url = null){

        return (array)json_decode(file_get_contents($url));

    }

    private function curl($url = null, $fields_string = null){

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL, $url);         
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_POST, true);

        $result = (array)json_decode(curl_exec($ch));        
        curl_close($ch);

        return $result;

    }

    private function retorno($status = 404, $descricao = null, $result = null){

        $arr_return = array();

        $arr_return['status'] = $status;

        if($descricao){
            $arr_return['descricao'] = $descricao;
        }

        if($result){
            $arr_return['result'] = $result;
        }

        return $arr_return;

    }

}