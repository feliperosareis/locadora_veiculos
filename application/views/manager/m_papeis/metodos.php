<script>
    
function allMethods(){
    $('.umMetodo').each(function(){
        $(this).attr('checked','checked');
    });
}    
    
</script>    
Selecione os métodos deste papel (permissões) - <a href="javascript:allMethods()">Marcar todos</a>
<?php
    $listagem_sem_formata = array("add","delete","edit","export");
    $listagem_formatada = array("Adicionar","Deletar","Editar","Exportar");
    
    $metodos_personalizados_sem_formata = array('Alterar_status','Selecao_multipla','Alterar_ordem','Booleanswitcher','Multiselect');
    $metodos_personalizados_formatado = array('Alterar Status','Seleção Multipla','Alterar Ordem','Alterar Status','Seleção Multipla');

?>
<div style="border-left: white solid">
    <ul>
        <?php foreach ($metodos as $m) { ?>
            <li> <input type="checkbox" name="metodos[]" value="<?php echo($m['ID']); ?>" <?php echo((in_array($m['ID'], $jr)) ? 'checked="checked"' : ''); ?> />
                <?php
                //echo($m['METODO']);
                echo 'Painel de Administração';
                $anterior0 = $m['METODO']."/";

                if (isset($m['sub'])) {
                    if (count($m['sub']) > 0) {
                        ?>
                        <ul>
                            <?php foreach ($m['sub'] as $m0) { ?>
                                <li> <input class="umMetodo" type="checkbox" name="metodos[]" value="<?php echo($m0['ID']); ?>" <?php echo((in_array($m0['ID'], $jr)) ? 'checked="checked"' : ''); ?> />
                                    <?php
                                    echo ucfirst(str_replace($anterior0, "", $m0['METODO'])).'';
                                    $anterior1 = $m0['METODO']."/";

                                    if (isset($m0['sub'])) {
                                        if (count($m0['sub']) > 0) {
                                            ?> 
                                            <ul>
                                                <?php foreach ($m0['sub'] as $m1) { ?>
                                                    <li> <input class="umMetodo" type="checkbox" name="metodos[]" value="<?php echo($m1['ID']); ?>" <?php echo((in_array($m1['ID'], $jr)) ? 'checked="checked"' : ''); ?> />
                                                        <?php
                                                        echo str_replace($metodos_personalizados_sem_formata,$metodos_personalizados_formatado,ucfirst(((str_replace($anterior1, "", $m1['METODO'])) == 'index' ? 'Interna' : (str_replace($anterior1, "", $m1['METODO'])))));
                                                        $anterior2 = $m1['METODO']."/";

                                                        if (isset($m1['sub'])) {
                                                            if (count($m1['sub']) > 0) {
                                                                ?>
                                                                <ul>
                                                                    <?php foreach ($m1['sub'] as $m2) { ?>
                                                                        <li> 
                                                                            <?php if(!in_array((str_replace($anterior2, "", $m2['METODO'])), $listagem_sem_formata)) { ?>
                                                                                <div style="display:none">
                                                                                    <input class="umMetodo" type="checkbox" checked="checked" name="metodos[]" value="<?php echo($m2['ID']); ?>"/>
                                                                                    <?php  echo str_replace($listagem_sem_formata,$listagem_formatada,(str_replace($anterior2, "", $m2['METODO'])));
                                                                                    $anterior3 = $m2['METODO']."/";?>                                                                                    
                                                                                </div>
                                                                        <?php }else{?>
                                                                                <input class="umMetodo" type="checkbox" name="metodos[]" value="<?php echo($m2['ID']); ?>" <?php echo((in_array($m2['ID'], $jr)) ? 'checked="checked"' : ''); ?> />
                                                                                <?php
                                                                                echo str_replace($listagem_sem_formata,$listagem_formatada,(str_replace($anterior2, "", $m2['METODO'])));
                                                                                $anterior3 = $m2['METODO']."/";
                                                                            }
                                                                            
                                                                            if(isset($m2['sub'])){
                                                                                if(count($m2['sub']) > 0){
                                                                                   ?>
                                                                                   <ul>
                                                                                       <?php foreach($m2['sub'] as $m3){ ?>
                                                                                       <li> <input class="umMetodo" type="checkbox" name="metodos[]" value="<?php echo($m3['ID']); ?>" <?php echo((in_array($m3['ID'], $jr)) ? 'checked="checked"' : ''); ?> />
                                                                                           <?php
                                                                                                echo(str_replace($anterior3, "", $m3['METODO']));
                                                                                                $anterior4 = $m3['METODO']."/";
                                                                                                
                                                                                                if(isset($m3['sub'])){
                                                                                                    if(count($m3['sub']) > 0){
                                                                                                        ?>
                                                                                                        <ul>
                                                                                                            <?php foreach($m3['sub'] as $m4) { ?>
                                                                                                                <li> <input class="umMetodo" type="checkbox" name="metodos[]" value="<?php echo($m4['ID']); ?>" <?php echo((in_array($m4['ID'], $jr)) ? 'checked="checked"' : ''); ?> />
                                                                                                                    <?php
                                                                                                                        echo(str_replace($anterior4, "", $m4['METODO']));
                                                                                                                        $anterior5 = $m4['METODO']."/";
                                                                                                                        
                                                                                                                        if(isset($m4['sub'])){
                                                                                                                            if(count($m4['sub']) > 0){
                                                                                                                                ?>
                                                                                                                                <ul>
                                                                                                                                    <?php foreach($m4['sub'] as $m5) { ?>
                                                                                                                                        <li> <input class="umMetodo" type="checkbox" name="metodos[]" value="<?php echo($m5['ID']); ?>" <?php echo((in_array($m5['ID'], $jr)) ? 'checked="checked"' : ''); ?> />
                                                                                                                                            <?php
                                                                                                                                                echo(str_replace($anterior5, "", $m5['METODO']));
                                                                                                                                                // OK chega, já foram 7 níveis
                                                                                                                                            ?>
                                                                                                                                        </li>
                                                                                                                                    <?php } ?>
                                                                                                                                </ul>
                                                                                                                    <?php     }
                                                                                                                        }
                                                                                                                    ?>
                                                                                                                </li>
                                                                                                            <?php } ?>
                                                                                                        </ul>
                                                                                                        
                                                                                              <?php  }
                                                                                                }
                                                                                           ?>
                                                                                       </li>
                                                                                       <?php }  ?>
                                                                                       
                                                                                   </ul>
                                                                            <?php    }
                                                                                
                                                                            }
                                                                            ?>
                                                                        </li>
                                                                    <?php } ?>
                                                                </ul>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </li>                                        
                                                <?php } ?>
                                            </ul>
                                            <?php
                                        }
                                    }
                                    ?>
                                </li>
                            <?php } ?>
                        </ul>   
                        <?php
                    }
                }
                ?></li>
        <?php } ?>
