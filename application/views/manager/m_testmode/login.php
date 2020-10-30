<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?php echo($this->config->item('titulo-manager')); ?></title>

        <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/img/favicon.ico" type="image/x-icon" />

        <link href="<?php echo base_url(); ?>assets/css/manager/normalize.css" media="all" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/css/manager/default.css" media="all" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/jquery.fancybox.1.4.3/jquery-1.4.3.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/jquery.fancybox.1.4.3/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/jquery.fancybox.1.4.3/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/manager/login.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/jquery.fancybox.1.4.3/fancybox/jquery.fancybox-1.3.4.css" media="screen" />        
    </head>
    <body>

        <div id="linha_cabecalho">
            <div id="linha_superior"></div>

            <div id="logo_cliente">
                <div id="logo1"> </div>
                <div id="logo2"> <img src="<?php echo base_url(); ?>assets/img/logo.png" alt="<?php echo($this->config->item('cliente')); ?>"/> </div>
                <div id="logo3"> </div>
            </div>
        </div>

        <div id="form_login"> 
            <?php if (strlen(validation_errors()) > 0 or isset($message)) { ?>
                <div class="errorBox">
                    <?php
                    echo validation_errors();
                    echo((isset($message)) ? "<p>" . $message . "</p>" : "");
                    ?>
                </div>
            <?php } ?>

            <?php echo form_open('manager/ops/verifica'); ?>
            Para acessar o site em <b>homologação</s>, informe seus dados
            <label for="usuario">USUÁRIO:</label>
            <?php echo form_input(array('name' => 'usuariotest', 'tabindex' => "1", 'class' => 'form_login_input', "value" => set_value("usuario"))); ?>

            
            <label for="senha">SENHA:</label>
            <?php echo form_password(array('name' => 'senhatest', 'tabindex' => "2", 'class' => 'form_login_input')); ?>

            <br/><br/>
                Acessando  de: <?php echo $this->input->ip_address(); ?>
            <?php echo form_submit('enviar', '', 'id="btn_login" tabindex=3'); ?>

            <?php echo form_close(); ?>

            <div style="clear: both; padding-top: 3px;">
            </div>
        </div>


        <div id="rodape_login">
            <a href="http://www.noiatec.com.br/" target="_blank"><img src="<?php echo(base_url()); ?>assets/img/manager/nt.gif"/></a>
        </div>
    </body>
</html>
