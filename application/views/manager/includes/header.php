<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?php echo $this->config->item("titulo-manager"); ?></title>

    <link href="<?php echo base_url() . 'assets/img/favicon.ico'; ?>" rel="icon" type="image/x-icon" />

    <!-- basico -->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/grocery_crud/js/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/grocery_crud/js/jquery-migrate-1.2.1.min.js"></script>

    <!-- Grocery CRUD includes -->
    <?php if(isset($crud)) { foreach ($crud->css_files as $file): ?>
      <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
  <?php endforeach; } ?>

  <?php if(isset($crud)) { foreach ($crud->js_files as $file):
            // apenas o cuidado para ele nao incluir 2x a jQuery
    if(!strstr($file,"jquery-1.8.2.min.js")){  ?>
    <script src="<?php echo $file; ?>"></script>
    <?php  }    endforeach; }  ?>


    <!-- css reset -->
    <link href="<?php echo base_url(); ?>assets/css/manager/normalize.css" media="all" rel="stylesheet" type="text/css" />

    <!-- css padrao de todo o manager -->
    <link href="<?php echo base_url(); ?>assets/css/manager/default.css" media="all" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/manager/fonts/font-awesome.min.css" media="all" rel="stylesheet" type="text/css" />

    <!-- Fancybox, janelas tipo ligthbox, só mais leves -->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/jquery_scrollbar/jquery.tinyscrollbar.min.js"></script>


    <!-- Tooltips all over manager in complicated fields, when they have some explanation -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/tooltipster-1.2/css/tooltipster.css" />
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/tooltipster/js/jquery.tooltipster.min.js"></script>


    <!-- JS generico, default de todo o manager -->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/manager/default.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/jquery/jquery_mask/jquery.mask.min.js"></script>

     <!-- Coloro Picker -->
    <link href="<?php echo base_url(); ?>assets/plugins/colorpick/css/layout.css" media="all" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/plugins/colorpick/css/colorpicker.css" media="all" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/colorpick/js/colorpicker.js"></script>

    <meta name="Author" content="NoiaTec">

    <script>
       var base_url = "<?php echo(site_url()); ?>/";
   </script>
</head>

<body>

<?php if(!isset($esconde)){?>
<div class="n-header">
    <a href="<?php echo $this->session->userdata('url_pos_login'); ?>" class="n-logo n-pq-box-logo">
      <img class="img img-responsive" src="<?php echo base_url(); ?>assets/img/manager/logo.png" alt="<?php echo($this->config->item('cliente')); ?>" border="0"/>
    </a>

    <div class="n-bloco-topo n-user">
        <div class="n-circle">
            <i class="fa fa-user"></i>
        </div>
        <p>
           <?php
                echo(Greetings::getSaudacaoTurno()." ");
                $l = ($this->session->userdata('login'));
                echo($l['usuario']);
            ?>!
            Hoje é
            <?php
                echo(Greetings::getDayOfWeek());
                echo(", ".date("d")." de ");
                echo(Greetings::getMonthText());
                echo(" de ".date("Y"));
            ?>
        </p>
    </div>

    <div class="n-bloco-topo n-ip">
        <div class="n-circle n-bg-blue">
            <i class="fa  fa-globe"></i>
        </div>
        <p>
            Você está acessando do ip: <?php echo $this->input->ip_address(); ?>
        </p>
    </div>

     <a href="<?php echo base_url(); ?>manager/login/logout" class="n-bloco-topo transition600 n-logoff">
        <div class="n-circle n-bg-red">
            <i class="fa fa-power-off"></i>
        </div>
        <p>
            Sair
        </p>
    </a>

</div>
<?php } ?>