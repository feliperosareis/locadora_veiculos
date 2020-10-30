<?php

class mail {

    public $confmail;

    public function __construct() {
        require_once 'application/libraries/PHPMailer/PHPMailerAutoload.php';

        $this->ci = &get_instance();
        //Create a new PHPMailer instance
        $this->confmail = new PHPMailer;

        $this->confmail->isSMTP();
        
        $this->confmail->CharSet = 'UTF-8';
        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $this->confmail->SMTPDebug = 0;

        //Ask for HTML-friendly debug output
        $this->confmail->Debugoutput = 'html';

        //Set the hostname of the mail server
        $this->confmail->Host = 'smtp.gmail.com';

        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $this->confmail->Port = 587;

        //Set the encryption system to use - ssl (deprecated) or tls
        $this->confmail->SMTPSecure = 'tls';

        //Whether to use SMTP authentication
        $this->confmail->SMTPAuth = true;

        //Username to use for SMTP authentication - use full email address for gmail
        $this->confmail->Username = "teste@syonet.com";

        //Password to use for SMTP authentication
        $this->confmail->Password = "teste01";
//        
//          

        //Set who the message is to be sent from
       $this->confmail->setFrom('teste@syonet.com', 'Syonet Marketing Force');

        //Attach an image file
        //$this->confmail->addAttachment('images/phpmailer_mini.png');
        //send the message, check for errors
    }

    function sendLead($leads) {
       /// $this->ci->load->model("leads");
       // $this->confmail->Host = 'smtp.gmail.com';
        
        //Set who the message is to be sent from
        //$this->confmail->setFrom('nissius.ribas@syonet.com', 'MKT Syonet');

        //Set the subject line
        $this->confmail->Subject = '[Lead Force] Novo Lead gerado';

        foreach ($leads as $lead){
           
            if($lead['DISPARADO_EMAIL'] != 1){
                $ids_leads_disparados[] = $lead['ID'];
                $dados_lead = $this->ci->leads->interna(0, $lead['ID']);
                $dado = $dados_lead[0];
                
                $destinos = explode(',', $dado['EMAILS']);
                foreach($destinos as $email){
                    $this->confmail->addAddress($email);
                }
                
                $msg[] = (!empty($dado['NOME']) ? '<b>Nome:</b> ' . $dado['NOME'] : '');
                $msg[] = (!empty($dado['EMAIL']) ? '<b>E-mail:</b> ' . $dado['EMAIL'] : '');
                $msg[] = (!empty($dado['TELEFONER']) ? '<b>Telefone Residêncial:</b> ' . mascara_string('(##)####-#####', $dado['TELEFONER']) : '');
                $msg[] = (!empty($dado['TELEFONEM']) ? '<b>Telefone Celular:</b> ' . mascara_string('(##)####-#####', $dado['TELEFONEM']) : '');
                $msg[] = (!empty($dado['TELEFONEC']) ? '<b>Telefone Comercial:</b> ' . mascara_string('(##)####-#####', $dado['TELEFONEC']) : '');
                $msg[] = (!empty($dado['LOCALIDADE']) ? '<b>Localidade:</b> ' . $dado['LOCALIDADE'] : '');
                $msg[] = (!empty($dado['EMPRESA']) ? '<b>Empresa:</b> ' . $dado['EMPRESA'] : '');
                $msg[] = (!empty($dado['ID_EVENTO_RETORNADO']) ? '<b>Evento CRM:</b> '.$dado['ID_EVENTO_RETORNADO']: '');
                $msg[] = '<b>Qualificação:</b> '.(!empty($dado['QUALIFICACAO']) ? ($dado['QUALIFICACAO'] == 1 ? 'Visita Loja' : 'Vendido'): 'Não Qualificado');
                if(!empty($dado['VALOR'])){
                    $msg[] = "<b>Valor:</b> R$".number_format($dado['VALOR'], 2, ',', '.');
                }
                //print_r($dado);
                if ($dado['FK_LIGACOES_ID'] > 0) {
                    $msg[] = (!empty($dado['STATUS']) ? '<b>Status da ligação:</b> ' . $dado['STATUS'] : '');
                    $msg[] = (!empty($dado['DATA_LIGACAO']) ? '<b>Data e Hora da ligação:</b> ' . $dado['DATA_LIGACAO'] : '');
                    $msg[] = (!empty($dado['TEMPO_LIGACAO']) ? '<b>Duração da chamada:</b> ' . $dado['TEMPO_LIGACAO'] : '');
                    $msg[] = (!empty($dado['TEMPO_TOQUE']) ? '<b>Tempo de toque:</b> ' . $dado['TEMPO_TOQUE'] . ' segundos' : '');
                    $msg[] = (!empty($dado['AUDIO']) ? '<b>Link para download:</b> <a targe="_blank" href="http://voip.syonet.com:1080/recordings/misc/download.php?dados=' . $dado['DATA_PASTA'] . '$$' . $dado['AUDIO'] . '">Aqui</a>' : '');
                } else {
                    //$msg[] = (!empty($dado['OBSERVACAO']) ? '<b>Obverservação:</b> '.nl2br($dado['OBSERVACAO']): '');
                }
                $msg[] = (!empty($dado['MODELO']) ? '<b>Modelo:</b> ' . $dado['MODELO'] : '');
                $pos = strpos($dado['MENSAGEM'], 'chat');
                if ($pos !== false) {
                    $msg[] = "<br>==========CHAT==========<br>".nl2br($dado['MENSAGEM']);
                } else {
                    $msg[] = (!empty($dado['MENSAGEM']) ? '<b>Mensagem:</b> ' . nl2br($dado['MENSAGEM']) : '');
                }

                //$msg[] = (!empty($dado['OBSERVACAO']) ? '<b>Obverservação:</b> '.nl2br($dado['OBSERVACAO']): '');
                $msg = array_filter($msg);
                $conteudo = "   <b>Data:</b> " . $dado['DATA'] . "<br>
                                <b>Midia:</b>" . $dado['MIDIA'] . "<br>
                                <b>Captação:</b>" . $dado['CAPTACAO'] . "<br>
                                ".implode('<br>', $msg);
                unset($msg);

                //Read an HTML message body from an external file, convert referenced images to embedded,
                //convert HTML into a basic plain-text alternative body
                $this->confmail->msgHTML($conteudo);
                $this->confmail->send();
                $this->confmail->ClearAddresses();
            }
        }
         $this->ci->db->query("UPDATE leads SET DISPARADO_EMAIL = 1 WHERE ID IN(".implode(',',$ids_leads_disparados).")");
    }
    
    function sendEmailSenha($email) {
      
        $this->confmail->Subject = '[Lead Force] Gerar nova senha';
        $this->confmail->addAddress($email);
        $msg = 'Para gerar uma nova senha, acesse o link:<br> <a href="'.base_url().'login/senha/nova/'.urlencode(base64_encode($email)).'">'.base_url().'login/senha/nova/'.urlencode(base64_encode($email).'</a>');
        $this->confmail->msgHTML($msg);
        $this->confmail->send();
        $this->confmail->ClearAddresses();
        echo 1;
    }
    
    function sendEmailCliqueLigueOff() {
      
        $this->confmail->Subject = '[Lead Force] [Report] Clique Ligue Off*';
        $this->confmail->addAddress('nissius.ribas@leadforce.com.br');
        $this->confmail->addAddress('daniel.correa@leadforce.com.br');
        $this->confmail->Priority = 1;
        $dados = print_r($_REQUEST, true);
        $msg = 'Falha na solicitação de ligação, com os dados:<br><pre>'.$dados.'</pre>';
        $this->confmail->msgHTML($msg);
        $this->confmail->send();
        $this->confmail->ClearAddresses();
        echo 1;
    }

}
