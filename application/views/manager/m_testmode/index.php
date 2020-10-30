<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?php echo($this->config->item('titulo-manager')); ?></title>

        <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/img/favicon.ico" type="image/x-icon" />

        <!-- css padrao de todo o manager -->
        <link href="<?php echo base_url(); ?>assets/css/manager/default.css" media="all" rel="stylesheet" type="text/css" />

        <!-- basico -->
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/grocery_crud/js/jquery-1.8.2.min.js"></script>

        <!-- Grocery CRUD includes -->
        <?php
        if (isset($crud)) {
            foreach ($crud->css_files as $file):
                ?>
                <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
            <?php
            endforeach;
        }
        ?>

        <?php
        if (isset($crud)) {
            foreach ($crud->js_files as $file):
                // apenas o cuidado para ele nao incluir 2x a jQuery
                if (!strstr($file, "jquery-1.8.2.min.js")) {
                    ?>
                    <script src="<?php echo $file; ?>"></script>
                <?php
                } endforeach;
        }
        ?>


        <!-- css reset -->
        <link href="<?php echo base_url(); ?>assets/css/manager/normalize.css" media="all" rel="stylesheet" type="text/css" />

    </head>
    <body>

        <?php
        if (isset($crud)) {
            echo $crud->output;
        }
        ?>

        <style>
            #field-USUARIO{
                width: 400px;
            }

            #field-SENHA{
                width: 400px;
            }

        </style>

    </body>
</html>
