<?php
$column_width = (int) (80 / count($columns));
$noList = array();
$noEdit = array();
$noDelete = array();

if(isset($specialForID)){

    foreach ($specialForID as $key => $value) {

        if($value['noList'])
            $noList[] = $key;

        if($value['noEdit'])
            $noEdit[] = $key;

        if($value['noDelete'])
            $noDelete[] = $key;

    }
}
                        
                        
if (!empty($list)) {
    ?><div class="bDiv" >
        <table cellspacing="0" cellpadding="0" border="0" id="flex1">
            <thead>
                <tr class='hDiv'>
                    
                    
                <!-- Header do multiselect -->    
                    <?php if($multiselect == 1){
                        
                       // print_r($list);?>
                    <th class="multiselect">
                        <input type="checkbox" class="checkall" onclick="CheckAll()"/>
                    </th>
                    <?php } ?>
                <!-- fim do header do multiselect -->
                    
                    
                    
                <!-- Header dos switchers -->
                    <?php
                    foreach ($list as $num_row => $row) { break; }
                    
                    if($switcher) { 
                        if (!empty($row->action_urls)) {
                            foreach ($row->action_urls as $action_unique_id => $action_url) {
                                $action = $actions[$action_unique_id];
                                if($action->label == 'Switcher') {
                                    $dbInfo = explode("|",base64_decode($action->css_class));
                                ?>
                                <th id="customWtToolBox" width='4%'>
                                    <div class="text-left">                                    
                                        <?php echo($dbInfo[2]); ?>
                                      </div>
                                </th>
                        <?php } // se esta action for um switcher
                          } // foreach nas actions, passa as diversas actions
                       }// ha urls
                    }// ha um switcher
                    ?>
                    <!-- fim Header dos switchers -->

                    
                    
                     <!-- Header do order -->
                    <?php
                    foreach ($list as $num_row => $row) { break; }
                    
                    if($list_edit_order) {
                        if (!empty($row->action_urls)) {
                            foreach ($row->action_urls as $action_unique_id => $action_url) {
                                $action = $actions[$action_unique_id];
                                if($action->label == 'list_edit_order') {
                                    $dbInfo = explode("|",base64_decode($action->css_class));
                                ?>
                                <th id="customWtToolBox" width='4%'>
                                    <div class="text-left">                                    
                                        <?php echo($dbInfo[2]); ?>
                                      </div>
                                </th>
                        <?php } // se esta action for um list_edit_order
                          } // foreach nas actions, passa as diversas actions
                       }// ha urls
                    }// ha um list_edit_order
                    ?>
                    <!-- fim Header do order -->                       
                    
                    
                    
                    
                    
                    <!-- colunas normais de dados -->
                    <?php foreach ($columns as $column) { ?>
                        <th width='<?php echo $column_width ?>%'>
                            <div class="text-left field-sorting <?php if (isset($order_by[0]) && $column->field_name == $order_by[0]) { ?><?php echo $order_by[1] ?><?php } ?>" rel='<?php echo $column->field_name ?>'>
                                <?php echo $column->display_as ?>
                            </div>
                        </th>
                    <?php } ?>
                     <!-- fim colunas normais de dados -->  
                

                     
                    <!-- actions do metodo add_action -->
                    <?php if (!$unset_delete || !$unset_edit || !empty($actions) || !empty($detailbox)) { ?>
                        <th align="left" abbr="tools" axis="col1" class="" width='20%'>
                        <div class="text-right">
                            <?php echo $this->l('list_actions'); ?>
                        </div>
                        </th>
                    <?php } ?>
                    <!-- fim actions do metodo add_action -->

                    </tr>
            </thead>        
            
            
            
            
            
            
            <tbody>
            <?php foreach ($list as $num_row => $row) {
                        if(!in_array($row->ID, $noList)) {
                    ?>
                
                    <tr <?php if (!$unset_edit and !in_array($row->ID, $noEdit)) { ?> ondblclick="editMe('<?php echo $row->edit_url ?>')" <?php } if ($num_row % 2 == 1) { ?>class="erow"<?php } ?>>
                       
                        
                        
                        <?php if($multiselect == 1){ ?>
                        <td class="multiselect">
                            <input type="checkbox" name="custom_select" value="<?php echo $row->ID?>" />
                        </td>
                        <?php } ?>

                        
                        
                        
                        <?php if($switcher) { 
                            
                                if (!empty($row->action_urls)) {

                                    foreach ($row->action_urls as $action_unique_id => $action_url) {
                                        $action = $actions[$action_unique_id];

                                        if($action->label == 'Switcher') {

                                        // Entra neste else se o item em questao eh um switcher. Custom component from NoiaTec

                                        $figs = explode("|",$action->image_url);

                                        $time_img = $figs[0];
                                        $true_img = $figs[1];
                                        $false_img = $figs[2];
                                        $urlActionRequest = $figs[3];

                                        $dbInfo = explode("|",base64_decode($action->css_class));
                                        $fieldAtualValue = $row->{$dbInfo[0]};

                                        $compareLabel = strtoupper($this->l('form_inactive'));
                                        $compareValue = strtoupper($fieldAtualValue);

                                        if($compareLabel == $compareValue or ($compareValue === '0')){

                                            $figAtual = $false_img;

                                        }else{

                                            $figAtual = $true_img;

                                        }
                                        ?>    
                                          <td>
                                                <div class='text-left'>
                                                    <a href="javascript:void(0)" style="float: left; padding-left: 5px" onclick="switcher('<?php echo($action->css_class); ?>','<?php echo($dbInfo[0]); ?>' ,'<?php echo($row->ID); ?>','<?php echo($true_img); ?>','<?php echo($false_img); ?>','<?php echo($time_img); ?>','<?php echo($urlActionRequest); ?>')">
                                                        <img id="switcher-<?php echo($dbInfo[0].'-'.$row->ID); ?>" src="<?php echo $figAtual; ?>"/>
                                                    </a>                                    
                                                </div>
                                            </td>
                                            
                            <?php
                                        
                                        } // fim a URL eh um switcher
                                    }// fim do foreach das URL`
                                }// fim as actions_url` nao estao limpas
                            } 
                            ?>
                        
                            
                         
                                            
                                            
                        <?php if($list_edit_order) { 
                               if (!empty($row->action_urls)) {

                                   foreach ($row->action_urls as $action_unique_id => $action_url) {
                                       $action = $actions[$action_unique_id];

                                       if($action->label == 'list_edit_order') {

                                       // Entra neste else se o item em questao eh um list_edit_order. Custom component from NoiaTec By Nissius

                                           $figs = explode("|",$action->image_url);

                                           $dbInfo = explode("|",base64_decode($action->css_class));

                                           $fieldAtualValue = $row->{$dbInfo[0]};
                                       ?>   
                                           <td>
                                               <div class='text-left'>
                                                   <input style="text-align: center" onchange="alterar_ordem('<?php echo($dbInfo[0]); ?>' ,'<?php echo($row->ID); ?>',$(this).val(),'<?php echo bin2hex($dbInfo[1]); ?>','<?php echo ($dbInfo[3]); ?>')" class="numeric" type="text" size="2" id="ordem<?php echo($row->ID); ?>" value="<?php echo($row->{$dbInfo[0]}); ?>">
                                               </div>
                                           </td>

                           <?php
                                       } // fim a URL eh um list_edit_order
                                   }// fim do foreach das URL`
                               }// fim as actions_url` nao estao limpas
                           } 
                           ?>                                             
                                            
                                            
                                            
                            
                        <?php foreach ($columns as $column) { ?>
                            <td width='<?php echo $column_width ?>%' class='<?php if (isset($order_by[0]) && $column->field_name == $order_by[0]) { ?>sorted<?php } ?>'>
                                <div class='text-left'>
                                    <?php 
                                    $conteudo = ($row->{$column->field_name} != '' ? $row->{$column->field_name} : '&nbsp;');
                                   
                                   if( preg_match('#^http:\/\/(.*)\.(gif|png|jpg|bmp|jpeg)$#i', $conteudo)) {
                                        echo "<center><img class='tamanho_foto_padrao' src='$conteudo'></center>";
                                    }
                                    
                                    echo $conteudo;
                                    ?>
                                </div>
                            </td>
                        <?php } ?>

                            
  
                            
                            
                        <?php if (!$unset_delete || !$unset_edit || !empty($actions) || !empty($detailbox)) { ?>
                            
                            
                            <td align="left" width='20%'>
                                <div class='tools'>
                                    
                                    <?php if (!$unset_delete and !in_array($row->ID, $noDelete)) { ?>
                                        <a href='<?php echo $row->delete_url ?>' title='<?php echo $this->l('list_delete') ?> <?php echo $subject ?>' class="delete-row" >
                                            <span class='delete-icon'></span>
                                        </a>
                                    <?php } ?>

                                    
                                    
                                    <?php if (!$unset_edit and !in_array($row->ID, $noEdit)) { ?>
                                        <a href='<?php echo $row->edit_url ?>' title='<?php echo $this->l('list_edit') ?> <?php echo $subject ?>'><span class='edit-icon'></span></a>
                                    <?php } ?>

                                        
                                    <?php
                                    if ($detailbox != null) {
                                        if (count($detailbox) > 0) {
                                            foreach ($detailbox as $i => $url) {

                                                echo('<a class="crud-action" href="javascript:void(0)">');
                                                echo('<span onclick=\'detailBox("' . $url . $row->ID . '",' . $detailboxW[$i] . ',' . $detailboxH[$i] . ',"'.$detailboxWindowTitle[$i].'")\'>');
                                                echo('<img title="'.$detailboxWindowTitle[$i].'"  alt="'.$detailboxWindowTitle[$i].'" border="0" src="' . $detailboxIcon[$i] . '"/>');
                                                echo('</span>');
                                                echo('</a>');
                                            }
                                        }
                                    }
                                    ?>


                                    <?php
                                    if (!empty($row->action_urls)) {
                                        
                                        foreach ($row->action_urls as $action_unique_id => $action_url) {
                                            $action = $actions[$action_unique_id];
                                            
                                            if($action->label != 'Switcher' && $action->label != 'list_edit_order') {
                                            ?>
                                                <a href="<?php echo $action_url; ?>" class="<?php echo $action->css_class; ?> crud-action" title="<?php echo $action->label ?>">
                                                   <?php if (!empty($action->image_url)) { ?>
                                                    <img src="<?php echo $action->image_url; ?>" alt="<?php echo $action->label ?>" />
                                                   <?php } ?>
                                                </a>
                                            <?php
                                            }
                                        }// fim do foreach das URL`
                                    }// fim as actions_url` nao estao limpas
                                    ?>              
                                        
                                    <div class='clear'></div>
                                    
                                </div>
                            </td>
                        <?php } ?>
                    </tr>
            <?php } } ?>        
            </tbody>
        </table>
    </div>

<?php } else { ?>

    <br/>
    &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $this->l('list_no_items'); ?>
    <br/>
    <br/>
    
<?php } ?>