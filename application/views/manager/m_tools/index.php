<?php
$this->load->view('manager/includes/header');
$this->load->view('manager/includes/menus');
?>

<style>
    .toolItem{
        float: left;
        padding: 10px;
        height: 90px;
        width: 150px;
        border: 1px #cccccc solid;
        text-align: center;
        background-color: #ffffff;
        margin: 5px;

    }

    .toolItem a img{
        border: 0px;
    }

    .toolItem a{
        text-decoration: none;
        color: #000000;
    }

    .content_manager{
        height: 125px;
    }

</style>

<?php if ($this->nt_manager_permissoes->isValid(array('manager', 'mysqldbdump', 'dump'))) { ?>
<div class="toolItem">
    <a href="<?php echo(base_url() . "manager/mysqldbdump/dump"); ?>">
        <img src="<?php echo(base_url() . "assets/img/manager/mysql_export.jpg"); ?>" />
        <br/>
        Exporta um arquivo .sql do BD Mysql deste site.
    </a>
</div>
<?php } ?>




<?php if ($this->nt_manager_permissoes->isValid(array('manager', 'testmode','index','edit'))) { ?>
    <div class="toolItem">
        <a href="javascript:win('<?php echo(base_url() . "manager/testmode/index/edit/1"); ?>',450,380,'Ativar/desativar Test Mode')"> 
            <img src="<?php echo(base_url() . "assets/img/manager/hammer.png"); ?>" />
            <br/>
            Inativa/Ativa o modo de _test do site. Impede acessos indevidos.
        </a>
    </div>
<?php } ?>





<?php $this->load->view('manager/includes/footer'); ?>

