<?php
// aqui o que precisa para funcionar o image crud
$this->load->view("manager/includes/white_page_init");
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/uploadify/uploadify.css">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/uploadify/jquery.uploadify.min.js"></script>

<style>
    #file_upload{
        float: left;
    }
</style>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="Filedata" id="file_upload" />

    <div style="float: left; width: 400px; padding-left: 15px;">
        Arquivos válidos: <b>jpg, png, gif</b><br/>
        * Clique duplo na imagem para usá-la / selecioná-la..
    </div>    
</form>

<script>
    // cria o uploader multiplo
    $(function() {
        $('#file_upload').uploadify({
            'swf': '<?php echo base_url(); ?>assets/plugins/uploadify/uploadify.swf',
            'uploader': '<?php echo(base_url() . "manager/internal/editordoupload"); ?>',
            'fileSizeLimit': '10024KB',
            'formData': {'uploadfolder': '<?php echo($uploadfolder); ?>'},
            'progressData': 'speed',
            'fileTypeExts': '*.gif; *.jpg; *.png',
            'onUploadSuccess': function(file, data, response) {

            },
            'onQueueComplete': function(queueData) {
                window.location.href = '<?php echo(base_url() . "manager/internal/ckeditorupload"); ?>';
            }
        });
    });



    function setUrlToParentFiled(elem) {
        var v = $(window.opener.document).find('.cke_dialog_ui_input_text input:visible');
        v.first().val(elem.src);
        window.close();
    }

</script>



<div style="clear: both">
    <hr/>

    <?php
    
    function newest($a, $b) { 
        return filemtime($a) - filemtime($b); 
    } 

    $dir = glob($this->config->item("local_disk_url") . $uploadfolder.'/*.{jpg,png,gif,jpeg,pneg,PNG,JPG,GIF,JPEG,PNEG}', GLOB_BRACE); // put all files in an array 
//    print_r2($dir);
    uasort($dir, "newest"); // sort the array by calling newest() 
    $dir = array_reverse($dir);

    foreach($dir as $file) { 
        $figura = base_url() . $uploadfolder . "/" . basename($file);
        echo '  <div style="float: left; margin: 10px">
                    <img ondblclick="setUrlToParentFiled(this)" src="'.$figura.'" style="max-width: 150px; max-height: 150px" />
                </div>'; 
    }
    ?>

</div>

<?php
// fim de página padrao
$this->load->view("manager/includes/white_page_end");
?>