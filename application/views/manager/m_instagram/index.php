<?php
$this->load->view('manager/includes/header');
?>
<script type="text/javascript" src="<?php echo(base_url()); ?>assets/js/manager/api.instagram.js" ></script>
<?php
$this->load->view('manager/includes/menus');


if (isset($crud->dropdown_setup)) {
    $this->load->view('dependent_dropdown', $crud->dropdown_setup);
}

if(strtoupper($hashtag) == 'NULO'){?>
<script type="text/javascript">
    $(document).ready(function() {
        $(".tDiv2").after("<div class='tDiv2' id='atualiza_feed'>\n\
                            <a class='jquery' href='../assets/img/ajaxloadergreen.gif' onclick='atualizar_curtidas_feeds()' title='Atualizando' class='add-anchor'>\n\
                                <div class='fbutton'>\n\
                                    <div>\n\
                                        <span class='instagram' title='Atualizar Curtidas'>Atualizar Curtidas</span>\n\
                                    </div>\n\
                        </div></a>\n\
                        <div class='btnseparator'></div>"); 
    });
</script>
<?php }
?>
    
<?php if(isset($cssexec)){ ?>
    <style type="text/css">
    <?php echo($cssexec); ?>
    
    </style>
<?php } 

if (isset($crud)) {

    echo $crud->output;
}

?>

<?php $this->load->view('manager/includes/footer'); ?>

