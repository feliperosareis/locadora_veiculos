<?php
$this->load->view('manager/includes/header');
$this->load->view('manager/includes/menus');

echo "<div style='width: 100%;display: inherit'>";
$this->load->view("manager/m_parametros/menu_interno_parametros");
echo "</div>";
?>

<!-- include para pegar os estilos de formatacao basica do formulario -->
<link type="text/css" rel="stylesheet" href="<?php echo(base_url()); ?>assets/grocery_crud/themes/flexigrid/css/flexigrid.css" />


<!-- include para pegar os arquivos necessarios pq se esta usando o text editor -->
<script src="<?php echo(base_url()); ?>assets/grocery_crud/texteditor/ckeditor/ckeditor.js"></script>
<script src="<?php echo(base_url()); ?>assets/grocery_crud/texteditor/ckeditor/adapters/jquery.js"></script>
<script src="<?php echo(base_url()); ?>assets/grocery_crud/js/jquery_plugins/config/jquery.ckeditor.config.js"></script>

<div class="flexigrid crud-form" style="margin-top: 33px">	

    <div class="mDiv">
        <div class="ftitle">
            <div class='ftitle-left'>
                Tela de  teste/conferência do envio de email
            </div>			
            <div class='clear'></div>
        </div>
    </div>

    <div id='main-table-box'>
        <form action="<?php echo(base_url()); ?>manager/parametros/mailform" method="post" id="crudForm" autocomplete="off">		
            <div class='form-div'>

                <?php if (isset($res)) { ?>
                    <div class='form-field-box even'>
                        Área de debug:
                        <pre>
                            <?php echo($res); ?> 
                        </pre>
                    </div>      
                <?php } ?>


                <div class='form-field-box odd'>


                    <div class='form-display-as-box'>
                        E-mail remetente :
                    </div>
                    <div class='form-input-box'>
                        <input  name='remetente' type='text' value="<?php echo($this->input->post('remetente')); ?>" maxlength='250' />
                    </div>
                    <div class='clear'></div>	
                </div>



                <div class='form-field-box even'>
                    <div class='form-display-as-box'>
                        E-mail destinatário :
                    </div>
                    <div class='form-input-box'>
                        <input name='destinatario' type='text' value="<?php echo($this->input->post('destinatario')); ?>" maxlength='250' />
                    </div>
                    <div class='clear'></div>	
                </div>


                <div class='form-field-box odd'>
                    <div class='form-display-as-box'>
                        Assunto :
                    </div>
                    <div class='form-input-box'>
                        <input  name='assunto' type='text' value="<?php echo($this->input->post('assunto')); ?>"  />
                    </div>
                    <div class='clear'></div>	
                </div>


                <div class='form-field-box even'>
                    <div class='form-display-as-box'>
                        Mensagem :
                    </div>
                    <div class='form-input-box'>
                        <textarea name='mensagem' class='basic2'><?php echo($this->input->post('mensagem')); ?></textarea>
                    </div>
                    <div class='clear'></div>	
                </div>                

            </div>

            <div class="pDiv">
                <div class='form-button-box'>
                    <input type='submit' value='Enviar email de teste'  class="btn btn-large"/>
                </div>
                <div class='clear'></div>	
            </div>
        </form>

    </div>
</div>


<?php $this->load->view('manager/includes/footer'); ?>

