<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class NT_Form_validation extends CI_Form_validation {	

    public function __construct($rules = array()) {
        parent::__construct();             
    }
 
    public function error_array() {
        if (count($this->_error_array) === 0){
            return FALSE;            
        } else {                        
            return $this->_error_array;            
        }
    }
    
    public function valid_url($url = "") {
        if ($url) {
            return (boolean) preg_match("/\b(?:(?:https?|ftp):\/\/.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url);
        }
        return true;
    }

    function valida_cpf($cpf = "") {
        
        if (empty($cpf)) {
            return false;
        }
        
        $cpf = preg_replace('/[^0-9]/i', '', $cpf);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);        
        if (strlen($cpf) != 11) {
            return false;
        }                
        else if ($cpf == '00000000000' ||
                $cpf == '11111111111' ||
                $cpf == '22222222222' ||
                $cpf == '33333333333' ||
                $cpf == '44444444444' ||
                $cpf == '55555555555' ||
                $cpf == '66666666666' ||
                $cpf == '77777777777' ||
                $cpf == '88888888888' ||
                $cpf == '99999999999') {
            return false;                        
        } else {

            for ($t = 9; $t < 11; $t++) {

                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf[$c] * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf[$c] != $d) {
                    return false;
                }
            }

            return true;
        }
    }

    function valida_cnpj($cnpj = "") {        
        $cnpj = preg_replace('/[^0-9]/i', '', $cnpj);
        
        $cnpj = (string) $cnpj;
        
        $cnpj_original = $cnpj;
        
        $primeiros_numeros_cnpj = substr($cnpj, 0, 12);
 
        function multiplica_cnpj($cnpj, $posicao = 5) {            
            $calculo = 0;
            
            for ($i = 0; $i < strlen($cnpj); $i++) {                
                $calculo = $calculo + ($cnpj[$i] * $posicao);
                
                $posicao--;
                
                if ($posicao < 2) {
                    $posicao = 9;
                }
            }            
            return $calculo;
        }
        
        $primeiro_calculo = multiplica_cnpj($primeiros_numeros_cnpj);
                
        $primeiro_digito = ($primeiro_calculo % 11) < 2 ? 0 : 11 - ($primeiro_calculo % 11);
                
        $primeiros_numeros_cnpj .= $primeiro_digito;
        
        $segundo_calculo = multiplica_cnpj($primeiros_numeros_cnpj, 6);
        $segundo_digito = ($segundo_calculo % 11) < 2 ? 0 : 11 - ($segundo_calculo % 11);
        
        $cnpj = $primeiros_numeros_cnpj . $segundo_digito;
        
        if ($cnpj === $cnpj_original) {
            return true;
        }
    }

    public function valida_nono_digito($value='') {

        if(strlen($value) == 9 && (substr($value, 0, 1) != 9))
        {
            return false;
        }
        else if ( strlen($value) == 8 && in_array(substr($value, 0, 1), array('8', '9')))
        {
            return false;
        }
        else if(strlen($value) == 14 && (substr($value, 4, 1) != 9))
        { 
            return false;
        }

        return true;
        
    }

}