<?php
// aqui o que precisa para funcionar o image crud
$this->load->view("manager/includes/white_page_init");
?>

<div style="padding: 30px; width: 540px">
    <h1>
        OK! Já temos o token do facebook!
    </h1>

    Se precisar copiar para algum fim, segue: 
    <br/><br/>
    <div style="font-size: 14px; background: #f5f5f5; height: 40px; width: 540px; overflow: auto">
        <?php echo($at); ?>
    </div>

    <br/> &nbsp;
    <br/> &nbsp;
    <br/>

    <div class="fechar" onclick="fechar()">Fechar esta janela</div>


    <style>
        .fechar{
            background-color: #cccccc;
            display: block;
            height: 25px;
            padding-top: 10px;
            width: 540px;
            text-align: center;
            cursor: pointer;

        }
    </style>    


    <script>

        window.onunload = function() {
            if (window.opener && !window.opener.closed) {
                window.opener.popUpClosed();
            }
        };

        function fechar() {
            window.opener.popUpClosed();
            window.close();

        }
    </script>
</div>
<?php
// fim de página padrao
$this->load->view("manager/includes/white_page_end");
?>

