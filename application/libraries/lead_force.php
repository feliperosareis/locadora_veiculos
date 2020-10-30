<?php
class lead_force {

    private $ws_link = 'http://rel.leadforce.com.br/ws/';        
    private $lista_colunas = array(
        'TOKEN', 'NOME', 'EMAIL', 'FK_MODELOS_ID', 'FK_MIDIAS_ID',
        'FK_EMPRESAS_ID', 'MENSAGEM', 'ESTADO', 'CIDADE',
        'DDD_TELEFONE_RESIDENCIAL', 'DDD_TELEFONE_COMERCIAL', 
        'DDD_TELEFONE_CELULAR', 'TELEFONE_RESIDENCIAL', 
        'TELEFONE_COMERCIAL', 'TELEFONE_CELULAR', 'ORIGEM', 'DISPOSITIVO'
        ); 

    public function geraLead($post = array()) {
        if(isset($_SERVER['REMOTE_ADDR'])){
            $post['IP_LEAD'] = $_SERVER['REMOTE_ADDR'];
        }

        $postdata = http_build_query(
            $post
            );

        $opts = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata,
                ),
            );

        $ch = curl_init( $this->ws_link . 'adicionar');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_POST, true);
        $retorno = curl_exec($ch);
        curl_close($ch);

        $retorno = json_decode($retorno);

        if ($retorno->codigo == 0) {
            return true;
        }

        return false;
    } 
  
    public function lista_modelos($empresa_id = 0){

        $options = '<option value="">Selecione um modelo</option>';

        $ch = curl_init( $this->ws_link . 'busca_modelos_carros/' . $empresa_id);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);        
        curl_setopt($ch, CURLOPT_POST, true);
        $result = curl_exec($ch);  

        if($result){
            return $result;
        }

        return $options;

    }

} 
