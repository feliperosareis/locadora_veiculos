<?php

/**
 * Este controller possui rorinas internas do manager.
 * 
 * Se não souber o que está fazendo, vá fazer em outro lugar, não nesta classe!
 *
 * @author Felipe Rosa
 */
class m_internal extends NT_Manager_Controller {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Usado principalmente nos dependent dropdown no manager
     * devolve a lista de cidades de um estado em json.
     * 
     * @param int $estado
     */
    public function ajxcidades($estado) {
        
        // $this->checkLogin(true); // Sem login, não é sensível pode ser reusado em vários lugares
        
        $this->load->model("nt_global_cidades");
        $lista = $this->nt_global_cidades->getCidadesFromEstado($estado);

        $array = array();
        foreach ($lista as $row):
            $array[] = array("value" => $row['id'], "property" => $row['cidade']);
        endforeach;

        echo json_encode($array);
        exit();
    }

    /**
     * Devolve a lista de estados de um pais em json.
     * Usado nos campos dependent drop down no manager (mas não excusivamente)
     * 
     * @param int $pais
     */
    public function ajxestados($pais) {
        // $this->checkLogin(true); // Sem login aqui, não é uma informação sensível. Pode ser reusado em qualquer lugar.
        
        $this->load->model("nt_global_estados");
        $lista = $this->nt_global_estados->getEstadosFromPais($pais);

        $array = array();
        foreach ($lista as $row):
            $array[] = array("value" => $row['id'], "property" => $row['estado']);
        endforeach;

        echo json_encode($array);
        exit();
    }

    public function ajxmodelos($marca = 0) {
        
        $this->load->model("nt_seminovos_modelos");
        $lista = $this->nt_seminovos_modelos->getModelosFromMarca($marca);

        $array = array();
        foreach ($lista as $row):
            $array[] = array("value" => $row['ID'], "property" => $row['TITULO']);
        endforeach;

        echo json_encode($array);
        exit();
    }

    public function ajxversoes($modelo = 0) {
        
        $this->load->model("nt_seminovos_versoes");
        $lista = $this->nt_seminovos_versoes->getVeroesFromModelo($modelo);

        $array = array();
        foreach ($lista as $row):
            $array[] = array("value" => $row['ID'], "property" => $row['TITULO']);
        endforeach;

        echo json_encode($array);
        exit();
    }



    
    /**
     * View da tela de upload das imagens dentro do editor de texto
     */
    public function ckeditorupload() {
        $this->checkLogin(true);
        
        $data['uploadfolder'] = $this->session->userdata("editor-upload-folder");

        // se nao esta definido, manda upar na pasta padrao de todas as figuras
        // não só desta
        if ($data['uploadfolder'] == "")
            $data['uploadfolder'] = "assets/uploads/files";

        $this->load->view("manager/m_internal/text-editor-image-upload", $data);
    }

    
    /**
     * Ação que efetivamente faz o upload na opção de imagem 
     * no editor de textos.
     * 
     */
    public function editordoupload() {
        
        // $this->checkLogin(true); //aqui NAO CHECA login, porque isso é via um request feito por um flash
        // e a session PERDE valor, não passa junto quando é flash.
        
        // aqui não pode GZip senão volta cabeçalhos http errados no upload
        $this->config->set_item("compress_output", FALSE);
        
        $tamMax = (10 * 1024 *1024); // 10mb em bytes

        $uploadFolder = $this->config->item("local_disk_url").$this->input->post('uploadfolder');
        
        if (!empty($_FILES)) {

            $tempFile = $_FILES['Filedata']['tmp_name'];
            $fileParts = pathinfo($_FILES['Filedata']['name']);


            if (strlen($tempFile) == 0) {
                $descricao = "BUG interno: Filedata em tmp_name veio sem valor :(";
                $this->nt_global_logs->s("text-editor-image-upload", $descricao);
                die($descricao);
            }

            $nameToWriteOnDisk = md5_file($_FILES['Filedata']['tmp_name']) . '.' . strtolower($fileParts['extension']);
            $targetFile = $uploadFolder."/". $nameToWriteOnDisk;



            // limit must be bigger than the size the file actualy have
            if ($_FILES['Filedata']['size'] <= $tamMax) {

                $descricao = "ok! Novo arquivo carregado $targetFile";
                if (move_uploaded_file($tempFile, $targetFile)) { // <<- UPLOAD
                    $this->nt_global_logs->s("upload", $descricao);
                } else {
                    $descricao = "Erro: Upload copiando: $tempFile para: $targetFile falhou ao mover!";
                    $this->nt_global_logs->s("upload", $descricao);
                    die($descricao);
                }
            } else {
                $descricao = "Erro: Tamanho do arquivo excede o limite de {$values["image_{$conf}_kb_max_size"]}KB definidos nas configurações do manager";
                $this->nt_global_logs->s("upload", $descricao);
                die($descricao);
            }
        }
    }

}