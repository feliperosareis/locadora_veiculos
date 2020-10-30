<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title><?php echo(isset($seo['titulo']) ? $seo['titulo'] : ''); ?></title>

    <meta charset="utf-8">

    <link rel='shortcut icon' href='<?php echo base_url(); ?>assets/img/favicon.ico'/>

    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="description" content="<?php echo(isset($seo['descricao']) ? $seo['descricao'] : ''); ?>"/>
    <meta name="author" content="Lead Force LTDA">
    <meta name="referrer" content="origin">

    <!-- Copyright semantico -->
    <meta name="copyright" content="&copy; <?php echo date("Y")?> Example Name"/>

    <!-- fb prop-->
    <meta property="og:locale" content="pt_BR">
    <meta property="og:url" content="<?php echo(isset($seo['url']) ? $seo['url'] : site_url()); ?>">
    <meta property="og:title" content="<?php echo(isset($seo['titulo']) ? $seo['titulo'] : ''); ?>">

    <meta property="og:description" content="<?php echo(isset($seo['descricao']) ? $seo['descricao'] : ''); ?>"/>

    <?php if (isset($seo['image']) && $seo['image']): ?>

        <meta property="og:image" content="<?php echo $seo['image']; ?>"/>

    <?php endif; ?>

    <meta property="og:type" content="website"/>

    <link rel="canonical" href="<?php echo(isset($seo['url']) ? $seo['url'] : site_url()); ?>"/>

    <meta name="referrer" content="origin">

    <script async type="text/javascript">
        var ROOT = "<?php echo base_url(); ?>";
        var LANG = "PT";
        var CLIENT_IP = "<?php echo get_client_ip() ?>";

        //verifucação do navegador
        navigator.sayswho= (function(){
            var ua= navigator.userAgent, tem,
                M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
            if(/trident/i.test(M[1])){
                tem=  /\brv[ :]+(\d+)/g.exec(ua) || [];
                return 'IE '+(tem[1] || '');
            }
            if(M[1]=== 'Chrome'){
                tem= ua.match(/\b(OPR|Edge)\/(\d+)/);
                if(tem!= null) return tem.slice(1).join(' ').replace('OPR', 'Opera');
            }
            M= M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
            if((tem= ua.match(/version\/(\d+)/i))!= null) M.splice(1, 1, tem[1]);
            return M.join(' ');
        })();

        if(navigator.sayswho == "MSIE 9" || navigator.sayswho == "MSIE 8" || navigator.sayswho == "MSIE 7" || navigator.sayswho == "MSIE 5"){
            window.location = ROOT + "update_browser";
        }
    </script>

    <?php echo cssGroup('default'); ?>
    <?php echo cssGroup( $section ); ?>
    <?php echo cssGroup('custom'); ?>
</head>

<body>

<main>