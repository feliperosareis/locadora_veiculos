<?php

/**
 * É recomendada a utilização desta função para quando for necessária a criação de pastas.
 * Ela permite a criação do arquivo index.html, para evitar que os arquivos do diretório sejam listados.
 * 
 * 
 * @author Lucas Baumgarten <baumgarten@noiatec.com.br>
 * @param string $pathname Caminho do diretório a ser criado. Deve ser passado como para a função mkdir();
 * @param int $mode Modo de criação do diretório. Idêntico ao mkdir();
 * @param type $recursive O padrão é FALSE. Idêntico ao mkdir();
 * @param type $context Contexto do arquivo, idêntico ao mkdir();
 * @param boolean $create_html_index Se for true, cria o arquivo index.html dentro do diretório criado. 
 */
function create_directory($pathname,$mode = 0777, $recursive = false, $context = null,$create_html_index = true){
    if(!is_dir($pathname))
        if($context)
            mkdir($pathname, $mode, $recursive, $context);
        else
            mkdir($pathname, $mode, $recursive);

    if($create_html_index){
        $indexFile = realpath($pathname).'\index.html'; 
        if(!file_exists($indexFile)){
            $fp = fopen($indexFile, 'w');
            fwrite($fp, '<html>
                            <head>
                                <title>403 Forbidden</title>
                            </head>
                            <body>
                                <p>Directory access is forbidden.</p>
                            </body>
                        </html>');
            fclose($fp);
        }
    }
        
}