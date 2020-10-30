<?php
/**
 * Description here
 * 
 ** @author Felipe Rosa
 */

class Greetings {

    /**
     * Obter o dia da semana por extenso em pt-Br
     * 
     * @param boolean $quick true para "Quarta" false para "Quarta-feira". Opcional
     * @param int $nroDia Número do dia da semana, se o interesse não é o dia de hoje
     * @return string dia da semana por extenso
     */
    public static function getDayOfWeek($quick = true, $nroDia = 8) {
        if ($nroDia == 8)
            $dia = date('w'); // dia da semana com 1 digito
        else
            $dia = $nroDia;
        switch ($dia) {
            case 0 :
                $d_semana = "Domingo";
                break;
            case 1 :
                $d_semana = "Segunda";
                break;
            case 2 :
                $d_semana = "Terça";
                break;
            case 3 :
                $d_semana = "Quarta";
                break;
            case 4 :
                $d_semana = "Quinta";
                break;
            case 5 :
                $d_semana = "Sexta";
                break;
            default : // dia 6 ou qualquer outra asneira que o programador passar
                $d_semana = "Sábado";
        }

        if (!$quick)
            $d_semana = $d_semana . "-feira";

        return $d_semana;
    }

    /**
     * De acordo com a hora atual retorno bom dia, tarde, noite
     * em pt-BR
     * 
     * @return string Bom dia/Boa tarde/Boa noite
     */
    public static function getSaudacaoTurno() {
        $hora = date("H");

        if ($hora < 12) {
            $str = "Bom dia";
        } else if ($hora < 18) {
            $str = "Boa tarde";
        } else {
            $str = "Boa noite";
        }
        
        return $str;
    }
    
    
    /**
     * Mês atual ou informado de um determinado nro. Ex.: passa 9 no parametro
     * e ele volta "Setembro". Não passa nada no param e ele volta a descrição
     * do mes atual. pt-BR
     * 
     * @param int $month Opcional, nro do mês que se quer a descrição. Se não informar é o mês atual
     * @return string nome do mês por extenso em pt-BR "Janeiro" por exemplo
     */
    public static function getMonthText($month = 13){
        
        // mes menor que 1 ou maior que 12 (asneira), retorna o mes atual
        if($month < 1 or $month > 12)
            $month = date ("n"); // mes atual com 1 dígito
        
        $lista = array(1 => "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho",
           "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
        return $lista[$month];
    }

}

