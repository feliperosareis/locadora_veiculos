<?php
$this->load->view('manager/includes/header');
$this->load->view('manager/includes/menus');

echo "<div style='width: 100%;display: inherit'>";
$this->load->view("manager/m_usuarios/menu_interno");
echo "</div>";


if (isset($crud)) 
    echo $crud->output;

?>

<style>
    #elContainerDePapeis{
        width: 100%;
        background-color: #EFEFEF;
        clear: both;
        overflow-y: auto;
        overflow-x: hidden;
    }
    
    #c1{
        float: left;
        width: 450px;
        padding-left: 10px;
        height: 100%;
    }
    
    #c2{
        float: left;
        width: 450px;
        padding-right: 10px;
        height: 100%;
        padding-left: 10px;
    }
    
    #crudForm .pDiv{
        clear: both;
    }
    
    li{
        list-style: none;
    }

</style>

<script type="text/javascript">
<?php
// esta mo modo de edit ou de add, nonta a tree de menus e métodos
if ($trees) {
    ?>  
        
        $('#menus_field_box').remove(); // remove o padrao do Grocery para relacao com Menus
        $('#metodos_field_box').remove(); // remove o padrao do Grocery para relacao com Menus
        
        $('#origens_field_box').after("<div id='elContainerDePapeis'> <div id='c1'></div> <div id='c2'></div> </div>");
        
        var urlMenus = "<?php echo(base_url()."manager/papeis/menus/$valor"); ?>";
        var urlMetodos = "<?php echo(base_url()."manager/papeis/metodos/$valor"); ?>";
        
        $.get(urlMenus,function(data){
            $('#c1').append(data);
        });
        
        $.get(urlMetodos,function(data){
            $('#c2').append(data);
        });
        
        
    <?php
} // fim esta no modo de add ou edit
// se houver algum JS que venha do controller.. é aqui que se executa
if (isset($jsexec)) {
    echo($jsexec);
}
?>
</script>

<?php $this->load->view('manager/includes/footer'); ?>

