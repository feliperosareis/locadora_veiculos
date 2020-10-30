<?php

/**
 * Image path
 *
 * Devolve base_url + /assets/img/site/common/img/
 *
 * @return string caminho padrão da imagem site
 */
function imgp($var = null) {
    if ($var) {
        return (base_url("assets/img/site/common/img/" . $var . '?v=' . VERSION));
    }
    return (base_url() . "assets/img/site/common/img/");
}


/**
 * Image Upload
 *
 * Devolve base_url + /assets/uploads/
 *
 * @return string caminho padrão da imagem site
 */
function imgu($var = null){
    if ($var) {
        return (base_url("assets/uploads/" . $var . '?v=' . VERSION));
    }
    return (base_url() . "assets/uploads/");
}



/**
 * Passe o nome do grupo CSS ver aquivo "min/groupsConfig.php" que
 * quer retornar o include dele compactado.
 *
 * Retorna
 * <pre> <link rel="stylesheet" type="text/css" href="url do grupo de arquivos compactados" media="all" /> </pre>
 *
 * @param string $name
 * @param boolean $workFake Passa true se quiser que ele não agrupe e compacte e chame item a item
 */
function cssGroup($name,$workFake=false){
    $grupos = require('min/groupsConfig.php');
    $index = $grupos['css_'.$name];

    if(ENVIRONMENT == 'production'){
        $cssFiles = array();
        foreach($index as $file){
            $cssFiles[] = $url = getcwd()."/".substr($file, 2);
       }

        $buffer = "";
        foreach ($cssFiles as $cssFile) {
          $buffer .= file_get_contents($cssFile);
        }

        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
        $buffer = str_replace(': ', ':', $buffer);

        $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
        $buffer = str_replace('//', base_url(), $buffer);

        echo '<style type="text/css" media="screen">';
        echo ($buffer);
        echo '</style>';

    }else{

        foreach($index as $file){
            $url = getcwd()."/".substr($file, 2);
            
            if(file_exists(str_replace('//', '', $file))){

                $buffer = file_get_contents($url);                
                $buffer = str_replace('//', base_url(), $buffer);

                echo '<!-- '.$file.' -->';
                echo "\n";
                echo '<style type="text/css" media="screen">';
                    echo "\n";
                    echo($buffer);
                    echo "\n";
                echo '</style>';
                echo "\n\n";
            }
        }
    }

}


/**
 * Passe o nome do grupo JS ver aquivo "min/groupsConfig.php" que
 * quer retornar o include dele compactado.
 *
 * Retorna
 * <pre> <script type="text/javascript" src="url do grupo de arquivos compactados" ></script> </pre>
 *
 * @param string $name
 * @param boolean $workFake True se quiser que ele não agrupe
 */
function jsGroup($name,$workFake = false){
    $js = "js_".$name;
    $url = (base_url() . "min/?g=$js");  // add &debug to url to enable debug mode

    if(ENVIRONMENT == 'production')
        echo('<script type="text/javascript" src="'.$url.'&v=' . VERSION . '"></script>'."\n");

    else{

        // se estamos trabalhando em fake mode, requesta os arquivos diretamente
        // sem passar pela compactação ou grupo
        $grupos = require('min/groupsConfig.php');
        $index = $grupos['js_'.$name];
        foreach($index as $file){
            $url = base_url().substr($file, 2);
            echo('<script type="text/javascript" src="'.$url.'?v=' . VERSION . '" async></script>'."\n");
        }
    }
}

/**
 * Adiciona masca em php, caso seja para exibição de ceps,telefones....
 *
 * @param string $mascara, EX: (##) ####-#### OU #####-###
 * @param string $string, Valor a ser recebido!
 * @return string
 */

function mascara_string($mascara,$string){
        $string = str_replace(" ","",$string);
        for($i=0;$i<strlen($string);$i++){
                $mascara[strpos($mascara,"#")] = $string[$i];
        }
        $mascara = str_replace("#","",$mascara); // Tira SObras
        return $mascara;
}

/**
 * Retira todos caracteres da string, deixa apena os numeros de 0-9
 *
 * @param string $string valor a ser recebido!
 * @return string
 */
function somente_numeros($string){
    return preg_replace("/[^0-9]/","", $string);
}

/**
 * Retira todos acentos e caracteres especiais,
 * recomendado para montar URL amivagel
 *
 * @param string $palavra palavra/texto a ter caracteres especiais retirados.
 * @param string $troca Troca especifica por espaço em branco, pode ser um array.
 * @param string $separador Sepadores das palavras.
 * @return string
 */
function toURL($palavra, $troca = array(), $separador = '-') {
    if (!empty($troca)) {
        $palavra = str_replace((array) $troca, ' ', $palavra);
    }

    $limpa = iconv('UTF-8', 'ASCII//TRANSLIT', $palavra);
    $limpa = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $limpa);
    $limpa = strtolower(trim($limpa, '-'));
    $limpa = preg_replace("/[\/_|+ -]+/", $separador, $limpa);

    return $limpa;
}


/**
 * Le para linha de comando a entrada do usuário.
 *
 * @author nissius Ritter <nissius@noiatec.com.br>
 * @return string
 */
function clir(){
    $text = trim(fgets(STDIN));
    return $text;
}

function traducao_mes($mes){
    $pt = array('Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');
    $eng = array('January','February','March','April','May','June','July','August','September','October','November','December');
    return str_replace($eng, $pt, $mes);
}

function print_r2($var, $die = true){
    echo '<pre>';
    print_r($var);
    echo '</pre>';

    if($die){
        die();
    }
}

function localiza_palavra($texto, $palavras, $offset=0) {
    foreach($palavras as $opcao) {
        $resultado = stripos($texto, $opcao, $offset);
        if ($resultado !== false) {
            return true;
            break;
        }
    }
    return false;
}

function moeda($vl){
    return number_format($vl,2,',','.');
}

function moeda_bd($vl){
    return (str_replace(',','.',(str_replace('.','',$vl))));
}

function data_bd($data_recebida, $para_bd = true){
    if($para_bd == true){
        $data = substr($data_recebida, 0,10);
        $hora = substr($data_recebida,10);
        return implode('-',array_reverse(explode('/',$data))).$hora;
    }else{
        if(strlen($data_recebida) > 10){
            return date("d/m/Y H:i", strtotime($data_recebida));
        }else{
            return date("d/m/Y", strtotime($data_recebida));
        }
    }
}

function trata_dados_bd($dados,$nl2br = false){
    foreach($dados as $chave => $valor){
        if(stripos($chave,'VALOR') !== false || stripos($chave,'RENDA') !== false ){
            $dados[$chave] = moeda_bd($valor);
        }
        if(stripos($chave,'DATA_') !== false){
            $dados[$chave] = data_bd($valor);
        }
        if(stripos($chave,'MENSAGEM') !== false){
            $dados[$chave] = nl2br($valor);
        }
        if(stripos($chave,'ARQUIVO') !== false){
            $dados[$chave] = nome_arquivo_formatado($valor);
        }
    }
    return $dados;
}

function posicao_array($array, $qnt = 2){
    $arr_tmp = array();
    $count = 0;
    foreach ($array as $key => $value) {
        if($key && ($key%$qnt==0)){
            $count++;
        }

        $arr_tmp[$count][] = $value;
    }

    return $arr_tmp;
}

function get_column($dados,$campo){
    $retorno = array();
    foreach($dados as $valor){
            $retorno[] = trim(is_object($valor) ? $valor->$campo : $valor[$campo]);
    }
    $retorno = array_unique($retorno);
    asort($retorno);
    return array_filter($retorno);
}


function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false)
{
    $lmin = 'abcdefghijklmnopqrstuvwxyz';
    $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $num = '1234567890';
    $simb = '!@#$%*-';
    $retorno = '';
    $caracteres = '';
    $caracteres .= $lmin;
    if ($maiusculas) $caracteres .= $lmai;
    if ($numeros) $caracteres .= $num;
    if ($simbolos) $caracteres .= $simb;
    $len = strlen($caracteres);
    for ($n = 1; $n <= $tamanho; $n++) {
        $rand = mt_rand(1, $len);
        $retorno .= $caracteres[$rand-1];
    }
    return $retorno;
}

function splita($palavra,$limite){
    if (preg_match('/^.{1,'.$limite.'}\b/s', trim(strip_tags($palavra)), $retorno)){
        return $retorno[0].'...';
    }else{
        return trim(strip_tags($palavra));
    }
}

function nome_arquivo_formatado($arquivo){
    $ext = pathinfo($arquivo, PATHINFO_EXTENSION);
    return str_replace($ext,'',  toURL($arquivo)).'.'.$ext;
}


function ford($palavra){
    return str_ireplace('ford', '<B>Ford</B>', $palavra);
}

function get_column_repplica_marcas($result = array()){
    $result_tmp = array();
    $arr_tmp = array();
    
    foreach ($result as $value) {
        $result_tmp[$value['MARCA']][$value['MODELO']][] = $value['VERSAO'];
    }
    
    ksort($result_tmp);

    foreach ($result_tmp as $marca => $modelos) {   

        ksort($modelos);

        foreach ($modelos as $modelo => $versoes) {
            
            sort($versoes);
            
            $arr_tmp[$marca][$modelo] = $versoes;

        }
        
    } 
    
    return $arr_tmp;

}

function get_column_modelos($seminovos) {
    $arr_tmp = array();
    foreach ($seminovos as $key => $value) {

        $arr_tmp[$value['MODELO_ID']] = array('ID' => $value['MODELO_ID'], 'MODELO' => $value['MODELO'], 'MARCA_ID' => $value['MARCA_ID'], 'MARCA' => $value['MARCA']);
    }

    return $arr_tmp;
}

function seminovosPreco($arr_seminovos = array(), $preco = 0)
{
    $arr_tmp = array();

    foreach ($arr_seminovos as $seminovo) {

        if (($seminovo['PRECO'] >= ($preco - 10000)) && $seminovo['PRECO'] <= ($preco + 10000)){
     
            $arr_tmp[] = $seminovo;

        }

    }

    return $arr_tmp;
}
 
function resumeTexto($txt = '', $qnt = 300)
{

    if (strlen($txt) > $qnt)
    {
        return substr(strip_tags($txt), 0 , $qnt). '...';
    }

    return $txt;
}

function dateMask($date, $mask){
    return date($mask, strtotime($date));
}

function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];    
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

?>