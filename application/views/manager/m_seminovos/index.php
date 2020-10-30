<?php

$this->load->view('manager/includes/header');
$this->load->view('manager/includes/menus');
?>
<?php 
    // INÍCIO DO GROCERY
?>
<?php
echo "<div style='width: 100%;display: inherit'>";
$this->load->view("manager/m_seminovos/menu_interno_parametros");
echo "</div>";

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

<script> 

    $(document).ready(function($) {
        
        var marca = $("[name=FK_NT_SEMINOVOS_MARCAS_ID]");
        if(marca.val() == ""){

            $("[name=FK_NT_SEMINOVOS_MARCAS_ID]").val(0).change()

        }

    });

</script>

<?php $this->load->view('manager/includes/footer');?>    

<?php if(!empty($opcoes_personalizadas)){ ?>
<script>
   <?php foreach($opcoes_personalizadas as $name => $titulo){ ?>
      $('#search_field').append("<option value='<?php echo $name;?>'><?php echo $titulo;?></option>");
      <?php } ?>
  </script>
  <?php   
}?>