<?php

$this->load->view('manager/includes/header');
$this->load->view('manager/includes/menus');

// aqui o que precisa para funcionar o image crud
$this->load->view("manager/includes/white_page_init");
$this->load->view('manager/includes/image_crud_headers');

echo "<div style='width: 100%;display: inherit'>";
    $this->load->view("manager/m_seminovos/menu_interno_parametros");
    echo "</div>";
// aqui a saída de codigo fonte gerada do image crud em sí
if (isset($imagecrud)) {    
    echo $imagecrud->output;
}

if(isset($jsexec)){ echo("\n");
    echo '<script type="text/javascript">';
    
        echo($jsexec);
        
    echo '</script>';
}

echo "<b>OBS¹:</b> Imagens devem ser no formato JPG ou PNG<br>";
echo @$obs;
//echo "<b>OBS²:</b> Imagens devem possuir o tamanho de ".@$tamanho_img."<br>";
//echo "<b>OBS³:</b> Caso a imagem não esteja no tamanho especificado, a mesma poderá ser exibida de forma errada no site";

// fim de página padrao
$this->load->view("manager/includes/white_page_end");
$this->load->view('manager/includes/footer');?>    