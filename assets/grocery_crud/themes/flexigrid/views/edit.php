<?php  
	$this->set_css($this->default_theme_path.'/flexigrid/css/flexigrid.css');
	$this->set_js($this->default_theme_path.'/flexigrid/js/jquery.form.js');
	$this->set_js($this->default_theme_path.'/flexigrid/js/flexigrid-edit.js');
?>
<div class="flexigrid crud-form" style='width: 100%;'>
	<div class="mDiv">
		<div class="ftitle">
			<div class='ftitle-left'>
				<?php echo $this->l('form_edit'); ?> <?php echo $subject?>
			</div>		
			<div class='clear'></div>				
		</div>
		<div title="<?php echo $this->l('minimize_maximize');?>" class="ptogtitle">
			<span></span>
		</div>	
	</div>
<div id='main-table-box'>
	<?php echo form_open( $update_url, 'method="post" id="crudForm" autocomplete="off" enctype="multipart/form-data"'); ?>
	<div class='form-div'>
		<?php
		$counter = 0; 
			foreach($fields as $field)
			{
				$even_odd = $counter % 2 == 0 ? 'odd' : 'even';
				$counter++;
		?>
			<div class='form-field-box <?php echo $even_odd?>' id="<?php echo $field->field_name; ?>_field_box">
                                <?php
                                $cursor = '';
                                $class = ''; 
                                if(isset($tooltipdescription[$field->field_name])){
                                    $cursor = 'style="" title="'.$tooltipdescription[$field->field_name].'" ';
                                    $class = "tooltip";
                                }
                                ?>
				<div <?php echo $cursor ?> class='form-display-as-box <?php echo($class); ?>' id="<?php echo $field->field_name; ?>_display_as_box">
                                        
                                        <?php if($class == 'tooltip') { ?> <img id="help_<?php echo $field->field_name; ?>" class="figHelp" src="<?php echo(base_url()."assets/img/manager/help_button.png"); ?>"/> <?php } ?>
					<?php echo $input_fields[$field->field_name]->display_as ?>
                                        
                                        <?php echo ($input_fields[$field->field_name]->required)? "<span class='required'>*</span> " : ""?> :
				</div>
                            
				<div class='form-input-box' id="<?php echo $field->field_name; ?>_input_box">
                                    <?php echo $input_fields[$field->field_name]->input;
                                    if(isset($comment[$field->field_name])){
                                        echo "<br>".$comment[$field->field_name];
                                    }
                                    ?></div>
				<div class='clear'></div>	
			</div>
		<?php }?>
		<?php if(!empty($hidden_fields)){?>
		<!-- Start of hidden inputs -->
			<?php 
				foreach($hidden_fields as $hidden_field){
					echo $hidden_field->input;
				}
			?>
		<!-- End of hidden inputs -->
		<?php }?>		
		<div id='report-error' class='report-div error'></div>
		<div id='report-success' class='report-div success'></div>		
	</div>
	<div class="pDiv">
		<div class='form-button-box'>
			<input type='submit' value='<?php echo $this->l('form_update_changes'); ?>' class="btn btn-large"/>
		</div>
<?php 	if(!$this->unset_back_to_list) { ?>			
		<div class='form-button-box'>
			<input type='button' value='<?php echo $this->l('form_update_and_go_back'); ?>' id="save-and-go-back-button" class="btn btn-large"/>
		</div>		
		<div class='form-button-box'>
			<input type='button' value='<?php echo $this->l('form_cancel'); ?>' onclick='javascript: return goToList()' class="btn btn-large" />
		</div>
<?php 	} ?>				
		<div class='form-button-box'>
			<div class='small-loading' id='FormLoading'><?php echo $this->l('form_update_loading'); ?></div>
		</div>		
		<div class='clear'></div>	
	</div>
	<?php echo form_close(); ?>
</div>
</div>
<script>
	var validation_url = '<?php echo $validation_url?>';
	var list_url = '<?php echo $list_url?>';

	var message_alert_edit_form = "<?php echo $this->l('alert_edit_form')?>";
	var message_update_error = "<?php echo $this->l('update_error')?>";
        
        $(document).ready(function() {
          $('.tooltip').tooltipster();
       }); 
   
<?php 
    if(!empty($this->actions)){
        foreach ($this->actions as $acao){
           $valor_definido = '';
            if($acao->label == 'add_value'){
                $valor_definido = explode("|",base64_decode($acao->css_class));
                echo "$('#field-".str_replace("'",'"',$valor_definido[0])."').val('".str_replace("'",'"',$valor_definido[1])."');";
                
                if($valor_definido[2] == '1'){
                         echo "$('#field-".str_replace("'",'"',$valor_definido[0])."').attr('readonly','true');";
                }                
            }
        }
   }
?>
</script>