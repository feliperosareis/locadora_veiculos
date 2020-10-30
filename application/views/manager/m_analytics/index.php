<?php

$this->load->view('manager/includes/header');
$this->load->view('manager/includes/menus');

$this->load->view("manager/m_parametros/menu_interno_parametros");

if (isset($crud)) {
    echo $crud->output;
}
?>
<style>
    #field-CODIGO{
        width: 650px;
        height: 280px;
    }
</style>
<?php
$this->load->view('manager/includes/footer');
?>