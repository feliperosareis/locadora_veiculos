<?php

/**
 * Usa-se essa funcao quando se deseja mandar um comentário 
 * em HTML para o browser, não deve aparecer em tela, mas para debug
 * ao olhar o cod-fonte deve estar lá. Fácil de encontrar, apenas pesquisar
 * por ALERTA
 * 
 * @param string $valor texto a ser dado saida no browser
 */
function ccmt($valor){
    echo("<!-- ALERTA: $valor -->");
}