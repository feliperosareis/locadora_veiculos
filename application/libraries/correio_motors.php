<?php

class correio_motors {
  
    private $url_estoque = 'http://solucoes.correiomotors.com/integrador/xml-veiculos.php';    

    private function set_fields($fields = array()){

        $fields_string = null;

        foreach($fields as $key=> $value) { 
            $fields_string .= $key.'='.$value.'&'; 
        }

        rtrim($fields_string, '&');

        return $fields_string;
    }

    public function listaEstoque($token = null){

        $url = $this->url_estoque . '?token=' . $token;
        
        $result = $this->curl($url);         
        
        if($result->codigo == 200){
            return $this->retorno(200, null, $result->estoque);
        }

        $msg = 'Nenhum resultado encontrado';
        return $this->retorno(404, $msg);

    }
 
    private function file_get($url = null){

        return json_decode(file_get_contents($url), true);

    }

    private function curl($url = null, $fields_string = null){

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL, $url);         
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

        $result = curl_exec($ch);
        curl_close($ch);

        return simplexml_load_string( $result , 'SimpleXMLElement' , LIBXML_NOCDATA );
        
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