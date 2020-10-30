<?php
/**
 * Esse arquivo é parte do processo de upload, veja o arquivo m_upload.php
 *
 * @author Felipe Rosa
 */
class m_uploadup extends NT_Default {

    public function index() {
        
        $uploaded = $this->session->userdata('uploaded');
        
        $this->load->model("nt_manager_upload");
        
        echo("<pre>Last uploads ware to this directory:\n\n");
        
        echo("tmp".$this->session->userdata('uploaded'));
        
        echo("\n\nThe file list ist:\n");
        
        
        print_r($this->nt_manager_upload->getUploads($uploaded));
        echo('</pre>');
    }

    /**
     * Faz o upload em sí do arquivo figura
     * 
     * @param string $conf identificador de qual figura esta-se upando
     */
    public function up($conf, $tmpdir) {

        // aqui não pode GZip senão volta cabeçalhos http errados no upload
        $this->config->set_item("compress_output", FALSE);
        $conf = trim($conf);

        // get confs from DB
        $ls = $this->db->query("select * from nt_global_parametros 
                  where IDENTIFICADOR like 'image_" . $conf . "%'")->result_array();

        if (count($ls) == 0) {
            $descricao = "Error: I did't found any image config relative to $conf";
            $this->nt_global_logs->s("upload", $descricao);
        }

        foreach ($ls as $row)
            $values[$row['IDENTIFICADOR']] = $row['VALOR_PARAM'];

        $uploadTo = $this->config->item("local_disk_url") . "assets/uploads/tmp" . $conf . "/" . $tmpdir . "/";
        $descricao = "Info: Starting upload to $uploadTo";
        $this->nt_global_logs->s("upload", $descricao);


        $extAllowed = explode("*.", $values["image_" . $conf . "_aceitar_arquivos"]);
        unset($extAllowed[0]);

        if (count($extAllowed) == 0) {
            $descricao = "Erro: Não há nenhuma extensão de arquivo permitida. Verifique as configurações do manager";
            $this->nt_global_logs->s("upload", $descricao);
            die($descricao);
        }

        $extAllowedClean = array();
        foreach ($extAllowed as $row)
            $extAllowedClean[] = trim(str_replace(";", "", $row));

        $tamMax = ($values["image_{$conf}_kb_max_size"] * 1024); // estava em KB, preciso em bytes
        if (intval($tamMax) == 0) {
            $descricao = "Erro: O tamanho máximo do arquivo em KB não está definido. Verifique nas configurações do manager";
            $this->nt_global_logs->s("upload", $descricao);
            die($descricao);
        }

        if (!empty($_FILES)) {

            $tempFile = $_FILES['Filedata']['tmp_name'];
            $fileParts = pathinfo($_FILES['Filedata']['name']);


            if (strlen($tempFile) == 0) {
                $descricao = "BUG interno: Filedata em tmp_name veio sem valor :(";
                $this->nt_global_logs->s("upload", $descricao);
                die($descricao);
            }

            $nameToWriteOnDisk = md5_file($_FILES['Filedata']['tmp_name']) . '.' . strtolower($fileParts['extension']);
            $targetFile = $uploadTo . $nameToWriteOnDisk;


            if (in_array(strtolower($fileParts['extension']), $extAllowedClean)) {

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
            } else {
                $descricao = "Erro: Arquivo de tipo/extensão inválida (é permitido: " . implode(",", $extAllowedClean) . "), you are trying " . $fileParts['extension'];
                $this->nt_global_logs->s("upload", $descricao);
                die($descricao);
            }
        } else {
            $descricao = "Erro: Não foram recebidos arquivos para upload";
            $this->nt_global_logs->s("upload", $descricao);
            die($descricao);
        }
    }

}
