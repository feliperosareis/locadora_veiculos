<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?php echo($this->config->item('titulo-manager')); ?></title>
        
        <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/img/favicon.ico" type="image/x-icon" />
        
        <link href="<?php echo base_url(); ?>assets/css/manager/normalize.css" media="all" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/css/manager/default.css" media="all" rel="stylesheet" type="text/css" />
        <style>
            body{
                background-color: #ffffff!important;
            }
            .coverInput{
                width: 90%;
            }
            .cover{
                padding:40px 0;
            }
        </style>
    </head>
    <body bgcolor="withe">
    <div class="row withe">
        
        <?php echo form_open('manager/login/recuperarsenha'); ?>
        <label class="recSenha row" for="usuario">Insira o seu usuário para recuperar a senha</label>
        <div class="cover">
            <label class="row coverInput" title="Usuário">
                <?php echo form_input(array('name' => 'usuario', 'tabindex' => "1", 'class' => 'form_login_input')); ?>
            </label>
        </div>
        <?php echo form_submit('recuperar', 'Recuperar', 'id="btn_recuperar_senha" tabindex=2'); ?>
        <?php echo form_close(); ?>       
    </div>
    </body>
</html>
