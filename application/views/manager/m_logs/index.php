<?php

$this->load->view('manager/includes/header');
$this->load->view('manager/includes/menus');


if (isset($crud->dropdown_setup)) {
    $this->load->view('dependent_dropdown', $crud->dropdown_setup);
}

if (isset($crud)) {
    echo $crud->output;
}
?>

<?php $this->load->view('manager/includes/footer'); ?>

