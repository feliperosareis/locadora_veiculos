<?php

$this->load->view('manager/includes/header');
$this->load->view('manager/includes/menus');

echo "<div style='width: 100%;display: inherit'>";
$this->load->view("manager/m_usuarios/menu_interno");
echo "</div>";


if (isset($crud->dropdown_setup)) {
    $this->load->view('dependent_dropdown', $crud->dropdown_setup);
}

if (isset($crud)) {
    echo $crud->output;
}
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

<?php $this->load->view('manager/includes/footer'); ?>

