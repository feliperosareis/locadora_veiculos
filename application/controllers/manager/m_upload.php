<?php

/**
 * Description of m_upload
 *
 * @author Felipe Rosa
 */
class m_upload extends NT_Default {

    public function __construct() {
        parent::__construct();
        $this->load->library("wideimage/wideimage");
    }

    // visualizar um determindado arquivo em um tamanho (width)
    public function img($conf, $temp, $file, $w) {

        // aqui não pode GZip pq a saída dessa função é o stream de uma imagem
        $this->config->set_item("compress_output", FALSE);

        $file = $this->config->item("local_disk_url") . "assets/uploads/tmp$conf/$temp/$file";
        $this->wideimage->load($file)->resize($w)->output("jpg");
    }

    // para poder fazer o UNDO depois, caso o usuário quiser
    private function createBkp($file) {
        if (!file_exists($file . '.bkp')) {
            copy($file, $file . '.bkp');
        }
    }

    // apaga o arquivo do disco
    public function delete($conf, $temp, $file) {
        $file = $this->config->item("local_disk_url") . "assets/uploads/tmp$conf/$temp/$file";
        unlink($file); // apaga a original
        unlink($file . ".bkp"); // apaga o backup também
    }

    public function cropto($x1, $y1, $x2, $y2, $width, $height, $conf, $temp, $file) {
        $file = $this->config->item("local_disk_url") . "assets/uploads/tmp$conf/$temp/$file";
        $this->createBkp($file);
        $tamOriginal = getimagesize($file);
        $this->wideimage->load($file)->crop($x1, $y1, $width, $height)->saveToFile($file);
        echo("<script>  window.location.reload() </script>");
    }

    public function undo($conf, $temp, $file) {
        $file = $this->config->item("local_disk_url") . "assets/uploads/tmp$conf/$temp/$file";
        // se existe o backup
        if (file_exists($file . '.bkp')) {
            copy($file . '.bkp', $file); // copia o backup por cima do arquivo
            unlink($file . '.bkp'); // apaga o backup
        }
        echo("<script>  window.location.reload() </script>");
    }

    public function rotateleft($conf, $temp, $file) {
        $file = $this->config->item("local_disk_url") . "assets/uploads/tmp$conf/$temp/$file";
        $this->createBkp($file);
        $this->wideimage->load($file)->rotate(-90)->saveToFile($file);
        echo("<script>  window.location.reload() </script>");
    }

    public function rotateright($conf, $temp, $file) {
        $file = $this->config->item("local_disk_url") . "assets/uploads/tmp$conf/$temp/$file";
        $this->createBkp($file);
        $this->wideimage->load($file)->rotate(90)->saveToFile($file);
        echo("<script>  window.location.reload() </script>");
    }

    public function mirror($conf, $temp, $file) {
        $file = $this->config->item("local_disk_url") . "assets/uploads/tmp$conf/$temp/$file";
        $this->createBkp($file);
        $this->wideimage->load($file)->mirror()->saveToFile($file);
        echo("<script>  window.location.reload() </script>");
    }

    public function flip($conf, $temp, $file) {
        $file = $this->config->item("local_disk_url") . "assets/uploads/tmp$conf/$temp/$file";
        $this->createBkp($file);
        $this->wideimage->load($file)->flip()->saveToFile($file);
        echo("<script>  window.location.reload() </script>");
    }

    /**
     * 
     * @param string $conf identificador das configuracoes da imagem
     */
    public function to($conf, $tmpdir = "") {

        $conf = trim($conf);
        if (strlen($conf) < 3)
            die("Error: Something are wrong with the image identifer. It is just '$conf'. Are you sure?");


        // pass to view
        $data['conf'] = $conf;

        // get confs from DB
        $ls = $this->db->query("select * from nt_global_parametros 
                  where IDENTIFICADOR like 'image_" . $conf . "%'")->result_array();

        $values = array();
        if (count($ls) == 0)
            die("Error: Can't find configurations for this image identifer. Configure your manager first.");

        foreach ($ls as $row)
            $values[$row['IDENTIFICADOR']] = $row['VALOR_PARAM'];


        // se o diretorio para essa conf ainda nao existe em /assets/upload, cria
        $uploadTo = $this->config->item("local_disk_url") . "assets/uploads/tmp" . $conf . "/";

        if (!file_exists($uploadTo)) {
            if (!mkdir($uploadTo))
                die("Error on creating temp/swap directory in: $uploadTo");
        }


        // dir temporarario.. vamos la
        if ($tmpdir == "") {
            $tmp_up_path = time() . rand(0, 1000);
            header("Location: $conf/$tmp_up_path");
            exit();
        }


        if (!file_exists($uploadTo . $tmpdir)) {
            if (!mkdir($uploadTo . $tmpdir))
                die("Error on creating temp/swap directory in: {$uploadTo}{$tmpdir}");
        }

        $data['tmpdir'] = $tmpdir;

        // agora tudo ok, compound the new path
        $uploadTo = $uploadTo . $tmpdir . "/";

        // extensoes aceitas

        if (isset($values["image_{$conf}_aceitar_arquivos"]))
            $data['valid_extensions'] = $values["image_{$conf}_aceitar_arquivos"];
        else {

            $descricao = "Error: Trying to find a item called: " . "image_" . $conf . "_aceitar_arquivos";
            $this->nt_global_logs->s("upload", $descricao);
            die($descricao);
        }

        $extAllowed = explode("*.", $values["image_" . $conf . "_aceitar_arquivos"]);
        unset($extAllowed[0]);

        if (count($extAllowed) == 0) {
            $descricao = "Error: Any file extension is allowed to upload right now, take a look in manager,configs,params...";
            $this->nt_global_logs->s("upload", $descricao);
            die($descricao);
        }

        $extAllowedClean = array();
        foreach ($extAllowed as $row)
            $extAllowedClean[] = trim(str_replace(";", "", $row));



        // tamanho em KB da figura

        if (isset($values["image_{$conf}_kb_max_size"]))
            $data['max_filesize'] = $values["image_{$conf}_kb_max_size"];
        else {
            $descricao = "Error: Trying to find a item called: " . "image_" . $conf . "_kb_max_size";
            $this->nt_global_logs->s("upload", $descricao);
            die($descricao);
        }



        // passa o dir todo, procurando figuras maiores ou menores que os tamanhos exigidos

        if (isset($values["image_{$conf}_min_size"])) {
            $min = explode('x', $values["image_{$conf}_min_size"]);
            $min['w'] = $min[0];
            $min['h'] = $min[1];
        } else {
            $descricao = "Error: Trying to find a item called: " . "image_" . $conf . "_min_size";
            $this->nt_global_logs->s("upload", $descricao);
            die($descricao);
        }



        if (isset($values["image_{$conf}_max_size"])) {
            $max = explode('x', $values["image_{$conf}_max_size"]);
            $max['w'] = $max[0];
            $max['h'] = $max[1];
        } else {
            $descricao = "Error: Trying to find a item called: " . "image_" . $conf . "_max_size";
            $this->nt_global_logs->s("upload", $descricao);
            die($descricao);
        }



        $d = dir($uploadTo);
        while (false !== ($entry = $d->read())) {
            $info = pathinfo($uploadTo . $entry);

            // eh uma figura das permitidas no upload, verifica ela
            if (in_array($info['extension'], $extAllowedClean)) {
                $figura = $uploadTo . $entry;
                $xy = getimagesize($figura); // 0=> width, 1=>height
                // se o tamanho da imagem eh menor que o tamanho mínimo, fora!
                // se a imagem eh maior que o tamanho máximo, fora!
                if ($xy[0] < $min['w'] or $xy[1] < $min['h']
                        or $xy[0] > $max['w'] or $xy[1] > $max['h']) {

                    unlink($figura);
                    $stringSize = $xy[0] . 'x' . $xy[1];

                    $descricao = "Deleted $figura because size error. It was $stringSize and the allowed is min=" . $values["image_{$conf}_min_size"] . " max=" . $values["image_{$conf}_max_size"];
                    $this->nt_global_logs->s("upload", $descricao);

                    $descricaoPT = "Erro. A figura $figura estava em tamanho incorreto. Ela tinha $stringSize e está configurado para aceitar: Min =" . $values["image_{$conf}_min_size"] . " e max = " . $values["image_{$conf}_max_size"];

                    echo('<script> alert("' . $descricaoPT . '"); </script>');
                }
            }
        }

        $d->close();



        if (!isset($values["image_{$conf}_aspect_ratio"])) {
            $descricao = "Error: Trying to find a item called: " . "image_" . $conf . "_aspect_ratio";
            $this->nt_global_logs->s("upload", $descricao);
            die($descricao);
        }


        // passa o aspect ratio para view, caso esteja errado vai ser usado
        $data['aspect'] = str_replace("x", ":", $values["image_{$conf}_aspect_ratio"]);

        $tolerance = explode('x', $values["image_{$conf}_aspect_ratio_tolerance"]);

        $data['tolerance'] = $values["image_{$conf}_aspect_ratio_tolerance"];

        $aspect = explode(":", $data['aspect']);

        //// ok, tudo limpo, agoora traz o que deve aparecer na tela ao usuario
        $webPath = base_url() . "assets/uploads/tmp$conf/";

        $filesSendToView = array();

        $d = dir($uploadTo);
        while (false !== ($entry = $d->read())) {

            $info = pathinfo($uploadTo . $entry);

            if (in_array($info['extension'], $extAllowedClean)) {

                $xy = getimagesize($uploadTo . $entry);

                $wCorreto = floor(($xy[1] * $aspect[0]) / $aspect[1]); // de acordo com o aspect definido, este seria o w correto
                $hCorreto = floor(($aspect[1] * $xy[0]) / $aspect[0]); // este deveria ser o h correto, se de acordo com o aspect

                $color = 'green'; // padrao esta correrto
                // se nao é igual o ideal e o que está, então, green é que já não é
                if ($wCorreto != $xy[0] or $hCorreto != $xy[1]) {

                    $color = 'red';

                    $diffH = abs($hCorreto - $xy[1]);
                    $diffW = abs($wCorreto - $xy[0]);

                    // se a diferença esta dentro da tolerancia... amarelo
                    if ($diffW <= $tolerance[0] and $diffH <= $tolerance[1]) {
                        $color = 'yellow';
                    }
                }

                $filesSendToView[] = array(
                    'conf' => $conf,
                    'tmpdir' => $tmpdir,
                    'file' => $entry,
                    'md5file' => md5($entry),
                    'type' => $info['extension'],
                    'wCorreto' => $wCorreto,
                    'hCorreto' => $hCorreto,
                    'disk' => $uploadTo . $entry,
                    'web' => $webPath . $entry,
                    'rightAspect' => $color,
                    'w' => $xy[0], // width da imagem real nao dimensionada
                    'h' => $xy[1]); // height da imagem real nao dimensionada

            }
        }
        
        $data['files'] = $filesSendToView;
        
        $this->load->model("nt_manager_upload");
        
        $this->nt_manager_upload->clearInfo($conf,$tmpdir);
        
        $this->nt_manager_upload->save($filesSendToView);

        $this->load->view("manager/m_upload/to", $data);
    }

}
