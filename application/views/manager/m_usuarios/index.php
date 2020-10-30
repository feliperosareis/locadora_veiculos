<?php $this->load->view('manager/includes/header'); ?>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/password_strength/password_strength_plugin.js"></script> 

<?php 

$this->load->view('manager/includes/menus'); 

echo "<div style='width: 100%;display: inherit'>";
$this->load->view("manager/m_usuarios/menu_interno");
echo "</div>";

?>

<?php if (isset($crud)) {
    echo $crud->output;
} ?>
<?php echo($js); ?>


<script type="text/javascript">
    
    $('#field-SENHA').css('width','200px');
    
    $("#field-SENHA").passStrength({
        userid: "#field-USUARIO"
    });    
</script>    

<?php $this->load->view('manager/includes/footer'); ?>

