Criado em: 2012-01-07

Para cada controller, criar um diretorio com o nome do controller
e dentro desse diretorio as views dele.

Views genericas do site publico podem ficar no raíz (header, footter por exemplo)
Exemplo:

fornecedores/index.php
fornecedores/inc_porestado.php
footer.php
header.php

Views do manager ficam todas em /application/views/manager/%controller%/%metodo%.php

Nomenclatura: Deve se explicar exatamente o que está sendo programado na view, acompanhado do prefixo "inc_%nome%" quando for uma adição de view no %metodo%.php
"inc" = include

Todo include de formulário deve conter o prefixo "frm_", baseando-se na seção sendo desenvolvida.
Exemplo
"frm" = form
Seção consórcio = frm_consorcio.php


Exemplo:
Banner seminovos/novos
/application/views/site/seminovos/inc_seminovos_banner.php
/application/views/site/novos/inc_novos_banner.php

Listagem reaproveitada
/application/views/site/includes/listas/inc_lista_seminovos.php
/application/views/site/includes/listas/inc_lista_novos.php

Deve ser reaproveitado o máximo de conteúdo possível, ou seja, quando possível reaproveitar views, sem duplicar a mesma com poucas alterações

Atentar-se também a colocar nomes extremamente auto-explicativos, se baseando pelo titulo da section.
Exemplo
Titulo ta section = <h1>Destaques</h1>

/application/views/site/seminovos/inc_destaques_seminovos.php