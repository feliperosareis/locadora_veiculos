<?php
$chatearUsuario = (!isset($chatearUsuario)?false:true);
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	 <title><?php echo $this->config->item("titulo-manager"); ?></title>

	<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/img/favicon.ico" type="image/x-icon" />

	<link href="<?php echo base_url(); ?>assets/css/manager/normalize.css" media="all" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url(); ?>assets/css/manager/default.css" media="all" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/jquery.fancybox.1.4.3/jquery-1.4.3.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/jquery.fancybox.1.4.3/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/jquery.fancybox.1.4.3/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/manager/login.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/jquery.fancybox.1.4.3/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
</head>
<style type="text/css" media="screen">
	body{

	background: url(../../img/manager/bg.png) center center no-repeat #fff;
	}

</style>
<body>
	<?php if (strlen(validation_errors()) > 0 or isset($message)) { ?>
        <div class="errorBox">
            <?php
            echo validation_errors();
            echo((isset($message)) ? "<p>" . $message . "</p>" : "");
            ?>
        </div>
    <?php } ?>

    <?php echo form_open(base_url() . 'manager/login'); ?>

	<div class="row">
		<div class="box">
			<div class="row tc">
				<img src="<?php echo base_url(); ?>assets/img/manager/logo.png" alt="<?php echo($this->config->item('cliente')); ?>"/>
			</div>
			<div class="titLogin">Faça login para entrar</div>
			<label class="coverInput row" title="Usuário">
				<?php echo form_input(array('name' => 'usuario', 'tabindex' => "1", 'autofocus' => 'true', 'class' => 'form_login_input', "value" => set_value("usuario"))); ?>
			</label>
			<label class="coverInput row" title="Senha">
				<?php echo form_password(array('name' => 'senha', 'tabindex' => "2", 'class' => 'form_login_input')); ?>
			</label>
				<?php if($chatearUsuario) { ?>
			<div class="captcha">
	             <script type="text/javascript">
	             var RecaptchaOptions = {
	                theme : 'white'
	             };
	             </script>
			</div>
	            <?php } ?>

	            <?php
	            if($chatearUsuario) {
	                require_once($this->config->item('local_disk_url').'application/third_party/recaptcha-php/recaptchalib.php');
	                echo recaptcha_get_html($this->config->item("recaptcha-public-key"));
	            }
	            ?>


            <br/><br/>

             <?php // echo form_button('esqueceu_senha', 'Esqueci minha senha', 'id="btn_esqueceu_senha" tabindex=4'); ?>

            <?php echo form_submit('enviar', 'Entrar', 'id="btn_login" tabindex=3'); ?>

            <?php echo form_close(); ?>

		</div>
	</div>

	<script type="text/javascript">
		/* nao colocar em um documento JS pq precisa da URL correta da tela de recovery de senha */
		$(document).ready(function() {

			/* Clicou que esqueceu a senha */
			$("#btn_esqueceu_senha").click(function() {
				win('<?php echo(base_url()); ?>manager/login/esquecisenha',320,100,'Recuperar senha');
			});
		});

	</script>

	<style>
		<?php if($chatearUsuario) { ?>
			#form_login{
				height: 450px;
			}
			<?php } ?>
		</style>
	</body>
	</html>
