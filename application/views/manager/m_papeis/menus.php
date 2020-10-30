<script>
    
function allMenus(){
    $('.umMenu').each(function(){
        $(this).attr('checked','checked');
    });
}    
    
</script>   
Selecione os menus deste papel - <a href="javascript:allMenus()">Marcar todos</a>
<div>
    <ul>
        <?php foreach ($menus as $m) { ?>
            <li> <input type="checkbox" name="menus[]" value="<?php echo($m['ID']); ?>" <?php echo((in_array($m['ID'], $jr)) ? 'checked="checked"' : ''); ?> />
                <?php
                echo($m['IDENTIFICADOR']);
                $anterior0 = $m['IDENTIFICADOR']."-";

                if (isset($m['sub'])) {
                    if (count($m['sub']) > 0) {
                        ?>
                        <ul>
                            <?php foreach ($m['sub'] as $m0) { ?>
                                <li> <input class="umMenu" type="checkbox" name="menus[]" value="<?php echo($m0['ID']); ?>" <?php echo((in_array($m0['ID'], $jr)) ? 'checked="checked"' : ''); ?> />
                                    <?php
                                    echo(str_replace($anterior0, "", $m0['IDENTIFICADOR']));
                                    $anterior1 = $m0['IDENTIFICADOR']."-";

                                    if (isset($m0['sub'])) {
                                        if (count($m0['sub']) > 0) {
                                            ?> 
                                            <ul>
                                                <?php foreach ($m0['sub'] as $m1) { ?>
                                                    <li> <input  class="umMenu" type="checkbox" name="menus[]" value="<?php echo($m1['ID']); ?>" <?php echo((in_array($m1['ID'], $jr)) ? 'checked="checked"' : ''); ?> />
                                                        <?php
                                                        echo(str_replace($anterior1, "", $m1['IDENTIFICADOR']));
                                                        $anterior2 = $m1['IDENTIFICADOR']."-";
                                                        //echo($m1['IDENTIFICADOR']);

                                                        if (isset($m1['sub'])) {
                                                            if (count($m1['sub']) > 0) {
                                                                ?>
                                                                <ul>
                                                                    <?php foreach ($m1['sub'] as $m2) { ?>
                                                                        <li> <input  class="umMenu" type="checkbox" name="menus[]" value="<?php echo($m2['ID']); ?>" <?php echo((in_array($m2['ID'], $jr)) ? 'checked="checked"' : ''); ?> />
                                                                            <?php
                                                                            echo(str_replace($anterior2, "", $m2['IDENTIFICADOR']));
                                                                            // echo($m2['IDENTIFICADOR']);
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
