<?php
/*
 * Helper para encurtar o a function que se chama para fazer as traduções
 *
 ** @author Felipe Rosa
 */

/**
 * Busca a string traduzida conforme a chave passada e o idioma atual
 * Pode ser chamada em qualquer controller e nas views.O Helper é carregado no autoload.php
 * 
 * @global type $LANG Global que guarda o idioma de agora. Controlado pelo $this->lang->load padrão do CodeIgniter
 * @param string $line nome da chave de qual se busca a tradução
 * @return string Devolve a string da tradução, ou se não encontrar, o que foi passado em $line
 */
function t($line) {
    global $LANG;
    return ($t = $LANG->line($line)) ? $t : $line;
}
