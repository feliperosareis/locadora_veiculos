<div class="n-sidebar">
    <ul class="n-menu-sidebar">
     <?php
// zerando para começar um novo menu schema
$this->nt_manager_menus->resetMain();
$this->nt_manager_menus->resetSecond();


$fullTree = $this->nt_manager_menus->getTreeMenus();
foreach ($fullTree as $level0){
    
    $Linkreplace = ($level0['LINK']!= '')?$level0['LINK']:'REPLACE';
   

    $htmlLevel0 =  "<li>
    					<a target=\"{$level0['TARGET']}\" href=\"".str_replace('{ROOT}',  base_url(),$Linkreplace)."\" title=\"{$level0['TEXTO']}\">
                    	     <i class='".($level0['ICONE'] == '' ? 'fa fa-home' : $level0['ICONE'])."'></i>
                    	    <p>
	                       		{$level0['TEXTO']}
	                    	</p>
	                	</a>
	                </li>";
    
    
    $this->nt_manager_menus->add($level0['IDENTIFICADOR'], $htmlLevel0);
    
    
    if(isset($level0['sub'])){
        if(count($level0['sub']) > 0){
            
            foreach($level0['sub'] as $level1){
                
                // se o menu tem link, usa ele, senão passa o REPLACE para ser buscado o link adequado
                $Linkreplace = ($level1['LINK']!= '')?$level1['LINK']:'REPLACE';
                $htmlLevel1 = "<div class=\"item_submenu_manager\"> 
                                      <a target=\"{$level1['TARGET']}\" href=\"".str_replace('{ROOT}',  base_url(),$Linkreplace)."\">{$level1['TEXTO']}</a>
                              </div>";
                $this->nt_manager_menus->add($level1['IDENTIFICADOR'], $htmlLevel1);
                
            } // fim do foreach dos itens de nivel 1
            
            
        }// leve0 tem menu dentro e é mais que 0 itens
        
    }// fim if esse level 0 tem algum sumenu dentro isset

}// fim foreach level 0



/*


// exemplo de add um menu principal estaticamente. Onde REPLACE vai ser substituido pela
// url do metodo filho de mebor ordem que o usuario tem permissao
$html = <<<menu
<li class="menuItem">
    <div>
        <a href="REPLACE">
            <img src="$path_url/assets/img/manager/menus/basic.png" border="0"/>
            <br/>BÁSICO</a>
    </div>
</li>
menu;
$this->nt_manager_menus->add('global', $html);


// exemplo de um item de menu de segundo nivel
$html = <<<menu
<div class="item_submenu_manager"> <a href="REPLACE">Países</a></div>
menu;
$this->nt_manager_menus->add('global-paises', $html);

// ------------------------------------basicos------------------------------//

*/

/*
 * Aqui nao precisa mexer. Teoricamente o framework do it for all alone :)
 */
	echo($this->nt_manager_menus->getHtmlMain());
?>
   </ul>
</div>
<!-- Fim do sidebar -->



<!-- Iinicio do grocery -->
<div class="n-wrap">
    <div class="n-content">
    <!-- Inicio do submenu -->
		<div class="submenus_manager">
			<?php 
				echo($this->nt_manager_menus->getHtmlSecond());
			?>
		</div>
	<!-- Fim do submenu -->
