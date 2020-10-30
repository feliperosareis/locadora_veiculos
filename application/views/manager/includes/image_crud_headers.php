<script type="text/javascript" src="<?php echo base_url(); ?>assets/grocery_crud/js/jquery-1.8.2.min.js"></script>
     
<?php if (isset($imagecrud)) {
    foreach ($imagecrud->css_files as $file): ?>
        <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
    <?php endforeach;
} ?>

<?php
if (isset($imagecrud)) {
    foreach ($imagecrud->js_files as $file):
        // apenas o cuidado para ele nao incluir 2x a jQuery
        if (!strstr($file, "jquery-1.8.2.min.js")) {
            ?>
            <script src="<?php echo $file; ?>"></script>
        <?php } endforeach;
} ?>