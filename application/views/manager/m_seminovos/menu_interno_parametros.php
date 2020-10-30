<?php $method = $this->uri->segment(2); ?>
 
<?php if ($veiculo_id > 0): ?>
	
	<div class="item_submenu_manager <?php echo ($method == 'seminovos' ? 'selected_sub' : ''); ?>">
		<a href="<?php echo site_url('manager/seminovos/index/edit/' . $veiculo_id); ?>">Informações</a>
	</div>

	<div class="item_submenu_manager <?php echo ($method == 'seminovos_imagens' ? 'selected_sub' : ''); ?>">
		<a href="<?php echo site_url('manager/seminovos_imagens/index/' . $veiculo_id); ?>">Imagens</a>
	</div>	

	<div class="lista-modelos">
		<a href="<?php echo site_url('manager/seminovos/index/'); ?>">Voltar para a listagem</a>
	</div>

<?php endif; ?>