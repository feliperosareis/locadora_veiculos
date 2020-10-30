<?php
/**
 * Busca lat e lnd dado um certo cep
 *
 * @author Felipe Rosa
 * 
 * http://maps.googleapis.com/maps/api/geocode/json?address=98910-000&sensor=false
 * http://gmaps-samples-v3.googlecode.com/svn/trunk/geocoder/getlatlng.html
 * 
 */

class geo_from_zipcode {
    private $url = "http://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false";
    

    private function clean($value){
        $nospaces = trim($value);
        $len = strlen($nospaces);
        
        // Brazil, without -
        if($len == 8){
            
            $ini = substr($nospaces, 0,5);
            
            $fim = substr($nospaces, 5,3);
            
            $nospaces = $ini."-".$fim;
            return $nospaces;
            
        }else { 
            return $nospaces;
        }
    }
    
    
    public function getFromCEP($zip){
        
        $zip = $this->clean($zip);
        $url = sprintf($this->url,$zip);
        
        try {
            $json = file_get_contents($url);
            
        } catch (Exception $exc) {
            $json = "";
        }
        
        $xml = json_decode($json);
        
        foreach($xml->results as $res){
            return array('lat'=>$res->geometry->location->lat,'lng'=>$res->geometry->location->lng);
        }
        
    }
    
    
    public function getFromEndereco($endereco){
        $endereco = urlencode($endereco);
        $url = sprintf($this->url,$endereco);
        
        try {
            $json = file_get_contents($url);
            
        } catch (Exception $exc) {
            $json = "";
        }
        
        $xml = json_decode($json);
        
        foreach($xml->results as $res){
            return array('lat'=>$res->geometry->location->lat,'lng'=>$res->geometry->location->lng);
        }        
    }
    
}