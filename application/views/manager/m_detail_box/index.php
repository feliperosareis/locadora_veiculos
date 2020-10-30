<?php

$this->load->view('manager/includes/header',array('esconde'=>'sim'));
?>
<?php 
    // INÍCIO DO GROCERY
 ?>
<?php


if (isset($crud->dropdown_setup)) {
    $this->load->view('dependent_dropdown', $crud->dropdown_setup);
}

if (isset($crud)) {
    echo $crud->output;
}
?>
<?php 
    // FIM DO GROCERY
 ?>
<?php if(isset($jsexec)){ echo("\n"); ?>
    <script type="text/javascript">
    <?php echo($jsexec); ?>
        
    </script>
<?php } ?>

<?php if(isset($cssexec)){ ?>
    <style type="text/css">
    <?php echo($cssexec); ?>
    
    </style>
<?php } ?>

<?php $this->load->view('manager/includes/footer');?>    
    
<?php if(!empty($opcoes_personalizadas)){ ?>
    <script>
         <?php foreach($opcoes_personalizadas as $name => $titulo){ ?>
              $('#search_field').append("<option value='<?php echo $name;?>'><?php echo $titulo;?></option>");
         <?php } ?>
    </script>
<?php   
    }?>