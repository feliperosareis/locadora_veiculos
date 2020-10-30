<?php
$this->session->set_userdata(array('uploaded' => "$conf/$tmpdir"));
?>
<!DOCTYPE html>
<html>
    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <title><?php echo($this->config->item('titulo-manager')); ?></title>

        <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/img/favicon.ico" type="image/x-icon" />

        <script type="text/javascript" src="<?php echo base_url(); ?>assets/grocery_crud/js/jquery-1.8.2.min.js"></script>

        <link href="<?php echo base_url(); ?>assets/css/manager/normalize.css" media="all" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/css/manager/default.css" media="all" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/uploadify-v3.1/uploadify.css">
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/uploadify-v3.1/jquery.uploadify-3.1.min.js"></script>


        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/jquery.imgareaselect/css/imgareaselect-default.css" />
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/jquery.imgareaselect/scripts/jquery.imgareaselect.pack.js"></script>

        <style>
            body{
                background-color: white;
            }

            a{
                color: #000000;
                text-decoration: none;
            }

            .red{
                border-top: 3px solid #ff6666;
                border-left: 3px solid #ff6666;
                border-right: 3px solid #ff6666;                
            }

            .yellow{
                border-top: 3px solid #ffff33;
                border-left: 3px solid #ffff33;
                border-right: 3px solid #ffff33;
            }

            .green{
                border-top: 3px solid #33cc00;
                border-left: 3px solid #33cc00;
                border-right: 3px solid #33cc00;                  
            }

            .toolbox{
                background-color:#f5f5f5;
                height: 22px;

            }

            .button{
                float: left;
                cursor: pointer;
                width: 45px;
                border: 1px solid white;
                text-align: center;
                padding-top: 2px;

            }

            .button:hover{
                background-color: #cccccc;
            }

            .oneFigura{
                margin-bottom: 30px;
                clear: both;
                width: 380px;
            }

            #file_upload{
                float: left;
            }
        </style>
    </head>

    <body>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="Filedata" id="file_upload" />

            <div style="float: left; width: 250px; padding-left: 15px;">
                Arquivos válidos:<?php echo($valid_extensions) ?> <br/>
                Proporção ideal: <?php echo($aspect); ?>
                Tam. máximo: <?php echo($max_filesize); ?>Kb
            </div>    

        </form>

        <?php
        if (isset($files)) {
            foreach ($files as $file) {
                ?>

                <div class="oneFigura <?php echo($file['rightAspect']); ?>" id="fig<?php echo($file['md5file']); ?>">

                    <img id="foto<?php echo ($file['md5file']); ?>" src="<?php echo(base_url()); ?>manager/upload/img/<?php echo($file['conf'] . "/" . $file['tmpdir'] . "/" . $file['file']); ?>/380" width="380px" />

                    <div class="toolbox">

                        <div class="button" onclick="imgDelete('<?php echo($file['conf'] . "/" . $file['tmpdir'] . "/" . $file['file']); ?>','<?php echo($file['md5file']); ?>')">
                            <img src="<?php echo(base_url()); ?>assets/img/manager/mail_delete.png" height="16px" width="16px" alt="Delete" />
                        </div>

                        <?php if ($file['rightAspect'] != 'green') { ?>
                            <div id="savecropdiv<?php echo $file['md5file'] ?>" class="button" onclick="btnSaveCrop('<?php echo($file['md5file']); ?>','<?php echo($file['conf'] . "' , '" . $file['tmpdir'] . "', '" . $file['file']); ?>')" style="display: none">
                                <div style="display: none">
                                    X1:<input id="frmx1<?php echo ($file['md5file']); ?>" readonly="true" type="text" style="width: 55px"/>
                                    Y1:<input id="frmy1<?php echo ($file['md5file']); ?>" readonly="true" type="text" style="width: 55px"/>
                                    X2:<input id="frmx2<?php echo ($file['md5file']); ?>" readonly="true" type="text" style="width: 55px"/>
                                    Y2:<input id="frmy2<?php echo ($file['md5file']); ?>" readonly="true" type="text" style="width: 55px"/>
                                    W:<input id="frmw<?php echo ($file['md5file']); ?>" readonly="true" type="text" style="width: 55px"/>
                                    H:<input id="frmh<?php
                echo ($file['md5file']);
                ;
                ?>" readonly="true" type="text" style="width: 55px"/>
                                </div>
                                <img src="<?php echo(base_url()); ?>assets/img/manager/save-icon.gif" height="16px" width="16px" alt="Save this crop" />
                            </div>
        <?php } ?>

                        <div class="button" onclick="imgUndo('<?php echo($file['conf'] . "/" . $file['tmpdir'] . "/" . $file['file']); ?>')">
                            <img src="<?php echo(base_url()); ?>assets/img/manager/undo.png" height="16px" width="16px" alt="Undo" />
                        </div>

                        <div class="button" onclick="imgRotateLeft('<?php echo($file['conf'] . "/" . $file['tmpdir'] . "/" . $file['file']); ?>')">
                            <img src="<?php echo(base_url()); ?>assets/img/manager/rotateLeft.png" height="16px" width="16px" alt="Rotate Left" />
                        </div>

                        <div class="button" onclick="imgRotateRight('<?php echo($file['conf'] . "/" . $file['tmpdir'] . "/" . $file['file']); ?>')">
                            <img src="<?php echo(base_url()); ?>assets/img/manager/rotateRight.png" height="16px" width="16px" alt="Rotate Right" />
                        </div>

                        <div class="button" onclick="imgMirror('<?php echo($file['conf'] . "/" . $file['tmpdir'] . "/" . $file['file']); ?>')">
                            <img src="<?php echo(base_url()); ?>assets/img/manager/mirror.png" height="16px" width="16px" alt="Mirror image"/>
                        </div>

                        <div class="button" onclick="imgFlip('<?php echo($file['conf'] . "/" . $file['tmpdir'] . "/" . $file['file']); ?>')">
                            <img src="<?php echo(base_url()); ?>assets/img/manager/flip.png" height="16px" width="16px" alt="Flip image" />
                        </div>
                    </div>
                </div>
                <?php
                // green sinaliza que está correto, yellow e red não
                if ($file['rightAspect'] != 'green') {
                    ?>
                    <!-- 
                        Tolerance: <?php echo($tolerance); ?>                    
                        wCorreto para a altura real mantendo aspect:<?php echo($file['wCorreto']); ?> 
                        hCorreto para altura real mantendo aspect:<?php echo($file['hCorreto']); ?> 
                    -->
                    <script type="text/javascript">
                                                                                                                                                                            
                        // http://odyniec.net/projects/imgareaselect/
                        function drawme<?php echo ($file['md5file']); ?>(){
                                                                                                                                                                                
                            $(document).ready(function () {
                                                                                                                                                                                    
                                $('#foto<?php echo $file['md5file'] ?>').imgAreaSelect({
                                    handles: true,
                                    imageWidth:<?php echo($file['w']); ?>, // if image are scaled on preview, original size
                                    imageHeight:<?php echo($file['h']); ?>, // if image are scaled on preview, original size                                       
                                    aspectRatio: '<?php echo($aspect); ?>',
                                    onSelectEnd: function (img, selection) {
                                                                                                                                                                    
                                        $('#frmx1<?php echo ($file['md5file']); ?>').val(selection.x1);
                                        $('#frmy1<?php echo ($file['md5file']); ?>').val(selection.y1);
                                        $('#frmx2<?php echo ($file['md5file']); ?>').val(selection.x2);
                                        $('#frmy2<?php echo ($file['md5file']); ?>').val(selection.y2);
                                        $('#frmw<?php echo ($file['md5file']); ?>').val(selection.width);
                                        $('#frmh<?php echo ($file['md5file']); ?>').val(selection.height);
                                                                                                                                                                    
                                        piscaBtn('<?php echo($file['md5file']); ?>'); // faz piscar o btn que indica que pode salvar
                                                                                                                                                                    
                                    }
                                });
                                                                                                                                                                                    
                            }); 
                                                                                                                                                                                                                            
                        }
                                                                                                                                                                                                                
                        // ao abrir, roda
                        drawme<?php echo ($file['md5file']); ?>();
                                                                                                                                                  
                                                                                                                            
                                                                                                                            
                    </script>
                    <?php
                } // fim do não está no aspect ratio correto
            }
        }
        ?>


        <script>
            
            function piscaBtn(qual){
                $('#savecropdiv'+qual).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
            }
                        
                        
            function btnSaveCrop(identFiledsPosition,conf,tmp,file){
                            
                x1 = $('#frmx1'+identFiledsPosition).val();
                y1 = $('#frmy1'+identFiledsPosition).val();
                x2 = $('#frmx2'+identFiledsPosition).val();
                y2 = $('#frmy2'+identFiledsPosition).val();
                width = $('#frmw'+identFiledsPosition).val();
                height = $('#frmh'+identFiledsPosition).val();
                            
                saveNewCrop(x1,y1,x2,y2,width,height,conf,tmp,file);
            }
                        
                        
            function saveNewCrop(x1,y1,x2,y2,width,height,conf,tmp,file){
                var urlGet = "<?php echo(base_url()); ?>manager/upload/cropto/"+x1+"/"+y1+"/"+x2+"/"+y2+"/"+width+"/"+height+"/"+conf+"/"+tmp+"/"+file;
                            
                $.get(urlGet, function(data) {
                    $('body').html(data);
                }); 
            }
                        
                        
            // cria o uploader multiplo
            $(function() {
                $('#file_upload').uploadify({
                    'swf'      : '<?php echo base_url(); ?>assets/plugins/uploadify-v3.1/uploadify.swf',
                    'uploader' : '<?php echo base_url(); ?>manager/uploadup/up/<?php echo($conf) ?>/<?php echo($tmpdir); ?>',
                    'fileSizeLimit' : '<?php echo($max_filesize); ?>KB',
                    'progressData' : 'speed',
                    'fileTypeExts' : '<?php echo($valid_extensions) ?>',
                    'buttonText': 'Fazer upload',
                    'onUploadSuccess' : function(file, data, response) {
                        if(data.length > 0)
                            alert(data);
                    },
                    'onQueueComplete' : function(queueData) {
                        window.location.href = '<?php echo base_url(); ?>manager/upload/to/<?php echo($conf) ?>/<?php echo($tmpdir); ?>';
                    }
                });
            });
                        
                        
                        
            // funcoes da toolbox
            function imgDelete(idf,md5id){
                        
                var urlGet = "<?php echo(base_url()); ?>manager/upload/delete/"+idf;
                if(confirm("Quer mesmo apagar?")){
                                
                    $.get(urlGet, function(data) {
                        $('#fig'+md5id).css('display','none');
                    });           
                }
            }
                        
                        
            function imgUndo(idf){
                var urlGet = "<?php echo(base_url()); ?>manager/upload/undo/"+idf;
                $.get(urlGet, function(data) {
                    $('body').html(data);
                });            
            }
                        
            function imgRotateLeft(idf){
                var urlGet = "<?php echo(base_url()); ?>manager/upload/rotateleft/"+idf;
                                
                $.get(urlGet, function(data) {
                    $('body').html(data);
                });   
            }
                        
            function imgRotateRight(idf){
                var urlGet = "<?php echo(base_url()); ?>manager/upload/rotateright/"+idf;
                                
                $.get(urlGet, function(data) {
                    $('body').html(data);
                });                   
            }
                        
            function imgMirror(idf){
                var urlGet = "<?php echo(base_url()); ?>manager/upload/mirror/"+idf;
                                
                $.get(urlGet, function(data) {
                    $('body').html(data);
                });                 
            }
                        
                        
            function imgFlip(idf){
                var urlGet = "<?php echo(base_url()); ?>manager/upload/flip/"+idf;
                                
                $.get(urlGet, function(data) {
                    $('body').html(data);
                });                 
            }    
        </script>

    </body>
</html>