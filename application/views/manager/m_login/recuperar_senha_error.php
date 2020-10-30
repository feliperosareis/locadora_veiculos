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
                background-color: #ffffff;
            }
            a{
                text-decoration: none;
                color: #000;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <br/>
        Ops! Algum erro ocorreu. Hipóteses:<br/><br/>
        - Informou um usuário que não existe;        <br/>
        - O usuário informado está inativo.        <br/>
        <br/>
        <a href="esquecisenha">Voltar</a>
        <br/>
    </body>
</html>
