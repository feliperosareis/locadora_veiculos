
    <footer class="container py-3">
        <div class="row align-items-center">
            <div class="col-12 col-md-8">
                <p id="copyright" class="m-0" property="dc:rights">UNIVALI
                    <span property="dc:dateCopyrighted"><?php echo date("Y")?></span>
                    <span property="dc:publisher">Curso de Análise e Desenvolvimiento de Sistemas</span>
                </p>
            </div>

            <div class="col-12 col-md-4 logo-lf text-right">
                <a href="https://www.univali.br/" target="_blank" title="Univali - Universidade do Vale do Itajaí">
                    <img class="max-100" src="<?php echo imgp("logo-footer.png"); ?>" alt="Univali" />
                </a>
            </div>
        </div>
    </footer>


</main>

<?php echo jsGroup('default'); ?>
<?php echo jsGroup( $section ); ?>
<?php echo jsGroup('custom'); ?>

</body>
</html>
