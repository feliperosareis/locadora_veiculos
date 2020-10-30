<?php
$this->nt_manager_menus->resetSecond();

// sub menus específicos da tela de parametros
$lista = $this->nt_manager_menus->getSubMenusFrom("configs-parametros");

foreach ($lista as $level2){
    
    $Linkreplace = ($level2['LINK']!= '')?$level2['LINK']:'REPLACE';
    
    $htmlLevel2 = "<div class=\"item_submenu_manager\"> 
                          <a target=\"{$level2['TARGET']}\" href=\"$Linkreplace\">{$level2['TEXTO']}</a>
                  </div>";
    $this->nt_manager_menus->add($level2['IDENTIFICADOR'], $htmlLevel2);
}

echo($this->nt_manager_menus->getHtmlSecond());
?>
