<?php

$this->load->view('manager/includes/header');
$this->load->view('manager/includes/menus');


if (isset($crud->dropdown_setup)) {
    $this->load->view('dependent_dropdown', $crud->dropdown_setup);
}

if (isset($crud)) {
    echo $crud->output;
}
?>

<script type="text/javascript">

    <?php if($estado == 'list' and $accepted == false) { ?>
        $(function() {
            $('.tDiv2').after($('#acceptAppFirst').html());
        });
    <?php } ?>


    // o usuario nao quer auto update, então ele precisa expressamente clicar em algo para mandar atualizar
    <?php if($estado == 'list' and $autoupdate == 0 and $accepted == true) { ?>
        $(function() {
            $('.tDiv2').after($('#updateFbStream').html());
        });
        
    <?php } ?>


    // o usuário quer auto update do feed, atualiza as coisas para ele
    <?php if($estado == 'list' and $autoupdate == 1 and $accepted == true) { ?>
        
        $(function() {
            $('.tDiv2').after($('#updateFbStream').html());
            updateFbFeeds(); // fly little bird!
        });
        
    <?php } ?>
     
     
     function updateFbFeeds(){
         var urlRequest = "<?php echo(base_url()."manager/facebook_feeds/ajx_update"); ?>";
         
         $('#ResStatus').html('Atualizando feed ...');
         
        $.get(urlRequest, function(data) {
            // resultado ao usuario
            $('#ResStatus').html(data);
            
            // atualiza a listagem de dados que o user esta vendo
            $('#ajax_refresh_and_loading').click();
        });

     }
     
     
     
     function winnew(url){
         // precisa ser poup up mesmo PQ o FB não deixa essa janela de autenticação ser aberta dentro
         // de iframe (pq limita a possibilidade do usuário fechar), por isso não pode ser fancy aqui
         window.open(url,'facebookaceitarapp','width=600,height=500')
     }
     
     
        function popUpClosed() {
            window.location.reload();
        }

    <?php if(isset($jsexec)){ echo("\n"); ?>
    <?php echo($jsexec); ?>
    <?php } ?>
</script>


<!-- este bloco fica escondido para pegar pedaços de html aqui de dentro e por no menu -->
<span style="display: none">
    
    
    <span id="acceptAppFirst">
        <a onclick="winnew('<?php echo(base_url()."manager/facebook_feeds/allow/"); ?>')" class="add-anchor" title="Conectar a App do Facebook" href="javascript:void(0)">
            <div class="fbutton">
                <div>
                    <img style="float: left" src="<?php echo(base_url()); ?>assets/img/manager/alertIcon.gif"/>
                    <span>Conectar a App do Facebook</span>
                </div>
            </div>
        </a>
    </span>


    <span id="updateFbStream">
        <a onclick="updateFbFeeds()" class="add-anchor" title="Atualizar feed do FB" href="javascript:void(0)">
            <div class="fbutton">
                <div>
                    <img style="float: left" src="<?php echo(base_url()); ?>assets/img/manager/facebook-icon.jpg"/>
                    <span>Atualizar feed do FB</span>
                </div>
            </div>
        </a>
        
        <div class="btnseparator"> </div>
        
        <span id="ResStatus" style="position: relative; top: 5px; left: 3px">
            <!-- here come via JS and Ajax result of request, messages to user -->
        </span>
    </span>
    
</span>    

<style type="text/css">
    <?php if(isset($cssexec)){ ?>
    <?php echo($cssexec); ?>
    <?php } ?>
</style>

<?php $this->load->view('manager/includes/footer'); ?>

