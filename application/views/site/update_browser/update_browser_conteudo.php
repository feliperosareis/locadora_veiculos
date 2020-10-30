<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title><?php echo(isset($seo['titulo']) ? $seo['titulo'] : ''); ?></title>

    <meta charset="utf-8">

    <link rel='shortcut icon' href='<?php echo imgu('nt_configuracoes/' . $this->nt_configuracoes->q('favicon')); ?>'/>

    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="description" content="<?php echo(isset($seo['descricao']) ? $seo['descricao'] : ''); ?>"/>
    <meta name="keywords" content="<?php echo(isset($seo['keys']) ? $seo['keys'] : ''); ?>"/>
    <meta name="author" content="Lead Force LTDA">
    <meta name="referrer" content="origin">

    <!-- Copyright semantico -->
    <meta name="copyright" content="&copy; <?php echo date("Y")?> <?php echo $this->nt_configuracoes->q('seo-default-title'); ?>"/>

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

    <?php echo cssGroup('update'); ?>

    <script async type="text/javascript">
        var ROOT = "<?php echo base_url(); ?>";
        var LANG = "PT";
        var CLIENT_IP = "<?php //echo get_client_ip() ?>";
    </script>
</head>

<body>


<header class="title" role="banner">
    <div class="browserVersion newBrowser">
        <img src="<?php echo imgu('nt_configuracoes/' . $this->nt_configuracoes->q('logo')); ?>" alt="<?php echo $this->nt_configuracoes->q('seo-default-title'); ?>">

        <p>Para ter uma experiência melhor, mantenha seu navegador atualizado. Confira aqui as últimas versões.</p>
    </div>
</header>

<main>

    <section class="c_browsers sorted">
        <!-- BROWSER - Chrome -->
        <div id="chrome" class="browser transition-all">
            
            <h3 class="statistic"><span>75,66%</span> Pessoas a usar<br>este browser</h3>
            
            <div class="center">
                <h2 class="transition-all"><span class="transition-all"></span> GOOGLE CHROME</h2>

                <div class="download">
                    <a href="https://www.google.com/chrome/browser/desktop/" target="_blank" class="browserlink" data-track="Google Chrome">Baixar</a>
                    <h4 class="versao space">VERSÃO <span>62</span></h4>
                </div>
            </div>
            <div class="available">
                <h4>DISPONÍVEL EM </h4>
                <ul>
                    <li class="windows"><span>Windows</span></li>
                    <li class="mac"><span>Mac OS</span></li>
                    <li class="linux"><span>Linux</span></li>
                </ul>
            </div>
        </div>

        <!-- BROWSER - Firefox -->
        <div id="firefox" class="browser">
            
            <h3 class="statistic"><span>6,83%</span> Pessoas a usar<br>este browser</h3>
            
            <div class="center">
                <h2><span></span> MOZILLA FIREFOX</h2>

                <div class="download">
                    <a href="http://www.mozilla.org/firefox/new/" target="_blank" class="browserlink" data-track="Mozilla Firefox">Baixar</a>
                    <h4 class="versao space">VERSÃO <span>57</span></h4>
                </div>
            </div>
            <div class="available">
                <h4>DISPONÍVEL EM </h4>
                <ul>
                    <li class="windows"><span>Windows</span></li>
                    <li class="mac"><span>Mac OS</span></li>
                    <li class="linux"><span>Linux</span></li>
                </ul>
            </div>
        </div>

        <!-- BROWSER - Internet Explorer -->
        <div id="internetExplorer" class="browser">
            
            <h3 class="statistic"><span>3,12%</span> Pessoas a usar<br>este browser</h3>
            
            <div class="center">
                <h2 class="edge"><span></span> MICROSOFT EDGE </h2>

                <div class="download">
                    <a class="edge-btn" href="https://www.microsoft.com/software-download/windows10" target="_blank" data-track="Internet Explorer">Baixar WIN10</a>
                    <h4 class="versao">VERSÃO <span>15</span></h4>
                    <h5>Disponível apenas com Windows 10</h5>
                </div>
            </div>
            <div class="available">
                <h4>DISPONÍVEL EM </h4>
                <ul class="single">
                    <li class="windows"><span>Windows</span></li>
                </ul>
            </div>
        </div>

        <!-- BROWSER - Safari -->
        <div id="safari" class="browser">
            
            <h3 class="statistic"><span>12,58%</span> Pessoas a usar<br>este browser</h3>
            
            <div class="center">
                <h2><span></span> APPLE SAFARI</h2>

                <div class="download">
                    <a class="macos-btn" href="http://www.apple.com/osx/" target="_blank" data-track="Apple Safari">Baixar macOS</a>
                    <h4 class="versao">VERSÃO <span>11</span></h4>
                    <h5>Disponível apenas com macOS</h5>
                </div>
            </div>
            <div class="available">
                <h4>DISPONÍVEL EM </h4>
                <ul class="single">
                    <li class="mac"><span>Mac OS</span></li>
                </ul>
            </div>
        </div>

        <!-- BROWSER - Opera -->
        <div id="opera" class="browser">
            
            <h3 class="statistic"><span>2,55%</span> Pessoas a usar<br>este browser</h3>
            
            <div class="center">
                <h2><span></span> OPERA</h2>

                <div class="download">
                    <a href="http://www.opera.com/?utm_medium=roc&amp;utm_source=burocratik&amp;utm_campaign=outdatedbrowser" target="_blank" data-track="Opera" class="browserlink">Baixar</a>
                    <h4 class="versao space">VERSÃO <span>49</span></h4>
                </div>
            </div>
            <div class="available">
                <h4>DISPONÍVEL EM </h4>
                <ul>
                    <li class="windows"><span>Windows</span></li>
                    <li class="mac"><span>Mac OS</span></li>
                    <li class="linux"><span>Linux</span></li>
                </ul>
            </div>
        </div>
    <!-- end SECTION -->
    </section>
</main>

<?php echo(jsGroup("update")); ?>

</body>
</html>
