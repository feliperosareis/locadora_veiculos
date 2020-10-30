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
                background-color: white;
            }
            
            input{
                width: 90%;
            }
            
            select{
                width: 90%;
            }
        </style>
    </head>

    <body>
        
        <script>
            var iniURL = window.location.hash;
            if(iniURL == '#close') {
                parent.jQuery.fancybox.close();
                parent.location.reload();
            }
        </script> 
        
        <form method="post">
            
            Para o controller/método:
            <br/>
            
            
            <input type="text" name="controller[]" style="width: 75%"/>
            /<input type="text" name="metodo" style="width: 20%" value="index"/>
            Padrões Pré Carregados<br>
            <select name="controller[]" multiple="" style="width: 76%">
                <?php foreach (glob("application/controllers/manager/*.php", GLOB_BRACE) as $arquivo) {?>
                    <?php if(!in_array(substr(substr(basename($arquivo),2),0,-4), $lista)){?>
                            <option><?php echo substr(substr(basename($arquivo),2),0,-4);?></option>
                    <?php }?>
                <?php } ?>
            </select> /index
            
            <br/>&nbsp;<br/>
            
            Tipo do controller:
            
            <br/>
            
            <select name="upload">
                <option value="N">GroceryCRUD sem upload</option>
                <option value="S">GroceryCRUD com upload</option>
                <option value="I">Método com ImageCRUD</option>
            </select>
            
             <br/>
             <div class="xeqi"> 
                 <span> <input type="checkbox" name="opcoes[]" value='alterar_status'>Alterar Status</span> 
                 <span> <input type="checkbox" name="opcoes[]" value='alterar_ordem'>Alterar Ordem</span>
                 <span> <input type="checkbox" name="opcoes[]" value='selecao_multipla'>Seleção Multipla</span>
             </div>
                 
                 <br/>
             
            <input value="Criar os métodos" type="submit" />
        </form>


    </body>
</html>