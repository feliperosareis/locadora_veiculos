<?php
$this->nt_manager_menus->resetSecond();

// sub menus específicos da tela de parametros
$lista = $this->nt_manager_menus->getSubMenusFrom($submenu);

foreach ($lista as $level2){
    
    $Linkreplace = ($level2['LINK']!= '')?$level2['LINK']:'REPLACE';
    $Linkreplace = str_replace('base_url()',  base_url(), $Linkreplace);
    
    $htmlLevel2 = "<div class=\"item_submenu_manager\"> 
                          <a target=\"{$level2['TARGET']}\" href=\"".str_replace('{ROOT}',  base_url(),$Linkreplace)."\">{$level2['TEXTO']}</a>
                  </div>";
//                          echo $htmlLevel2;
    $this->nt_manager_menus->add($level2['IDENTIFICADOR'], $htmlLevel2);
}

/*
// Jeito antigo, ainda funciona. Mas claro, se perde a possibilidade de ordenacao

$path_url = substr(base_url(), 0, -1); // precisa tirar a barra para poder usar concatenado

$html = <<<menu
<div class="item_submenu_manager"> <a href="$path_url/manager/parametros/index/emails">E-mail</a></div>
menu;
$this->wt_manager_menus->add('configs-parametros-email', $html);

*/

echo($this->nt_manager_menus->getHtmlSecond());
?>
