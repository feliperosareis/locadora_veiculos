<?php

class webservice {
    private $link_wsdl = "http://demonstracasdo.syonet.com/CollaborativeWS-CollaborativeWS/CollaborativeEventoService?wsdl";
    private $opcoes_wsdl = array('trace' => 1, 'exceptions'  => true);
    private $metodo = 'gerarEventoV2';
    
    public function __construct() {
        $this->ci = &get_instance();
        $this->ci->load->model("leads");
    }   
    
    public function processa_fila(){
       
        $this->ci->load->library('mail');
        $campos_preenchidos = $this->ci->leads->seleciona_leads();
         
        if(!empty($campos_preenchidos)){   
            
             foreach($campos_preenchidos as $key => $valores_por_lead){
                $ids_leads[] = $valores_por_lead['ID_LEAD'];
                $leads_mail[$key]['ID'] = $valores_por_lead['ID_LEAD'];
                $leads_mail[$key]['DISPARADO_EMAIL'] = $valores_por_lead['DISPARADO_EMAIL'];
            }
            
            $this->ci->leads->atualiza_status_de_transacao($ids_leads,1);
            $this->ci->mail->sendLead($leads_mail);          
            
        
            foreach($campos_preenchidos as $valores_por_lead){
                unset($valores_por_lead['DISPARADO_EMAIL']);
                $integrar[0] = $valores_por_lead;

                try{
                    if($this->link_wsdl != $valores_por_lead['link_wsdl']){
                        $this->link_wsdl = $valores_por_lead['link_wsdl'];
                        $client = new SoapClient($this->link_wsdl, $this->opcoes_wsdl);
                    }

                    $retorno = $client->__soapCall($this->metodo, $integrar);

                    $retorno = json_decode($retorno->return);

                    if($retorno->codigo == 0){
                        $sql_update[] = " (".$integrar[0]['ID_LEAD'].", now() , null,'".$retorno->mensagem."' ) ";
                    }else{
                        $sql_insert[] = " ('Lead',".$integrar[0]['ID_LEAD'].", '".$retorno->mensagem."') ";
                    }
                }catch (SoapFault $e){
                    $sql_insert[] = " ('Lead',".$integrar[0]['ID_LEAD'].", 'Servidor Indisponível') ";
                }          
               unset($integrar[0]);unset($retorno);
            }
            //Atualiza Banco de Dados
            if(!empty(@$sql_update)){
                $this->ci->leads->atualiza_lead($sql_update,'update');
            }
            if(!empty(@$sql_insert)){
                $this->ci->leads->atualiza_lead($sql_insert,'insert');
            }
            $this->ci->leads->atualiza_status_de_transacao($ids_leads,'NULL');
         }
    }
    
    public function insert_test($quantidade = 100){
        
        for($cont = 0; $cont <= $quantidade; $cont++){

            $sql = "INSERT INTO `leads` (`FK_CAPTACOES_ID`, `NOME`, `EMAIL`, `NASCIMENTO`, `DDD_TELEFONE_RESIDENCIAL`, `TELEFONE_RESIDENCIAL`, `DDD_TELEFONE_COMERCIAL`, `TELEFONE_COMERCIAL`, `DDD_TELEFONE_CELULAR`, `TELEFONE_CELULAR`, `TIPO_LOGRADOURO`, `LOGRADOURO`, `NUMERO`, `BAIRRO`, `CEP`, `CIDADE`, `ESTADO`, `ASSUNTO`, `OBSERVACAO`, `NOVO_USADO`, `CPF_CNPJ`, `ID_EVENTO_RETORNADO`, `DATA_CRIACAO`, `DATA_INTEGRACAO`) VALUES ('".rand(2,3)."', 'Nissiuss', 'nissiusnh@yahoo.com.br', '0000-00-00', '51', '30655898', '51', '36490300', '51', '99419397', 'Rua', 'Pedro Alvares Cabral', '726', 'Vila Rosa', '93310330', 'Novo Hamburgo', 'RS', 'Elogio', 'Mensagem a ser entregue....', 'usado', '', NULL, now(), NULL)";   
            $this->ci->db->query($sql);
        }
    }
    
    public function registra_evento($post){
        
//       $post['TOKEN'] = '841412259285';
        $post = array_change_key_case($post, CASE_UPPER);
        $this->ci->load->model("captacoes");
        $post = array_filter($post);
        if($post['FK_CAPTACOES_ID'] = $this->ci->captacoes->existe($post['TOKEN'])){
            if(empty($post['FK_EMPRESAS_ID'])){
                $this->ci->load->model("empresas");
                $post['FK_EMPRESAS_ID'] = $this->ci->empresas->getEmpresaByToken($post['TOKEN']);
            }
            unset($post['TOKEN']);
            $this->ci->leads->salva_lead($post);
        }else{
            $this->ci->leads->atualiza_lead(" ('Importação Lead', NULL, 'Erro ao importar lead do cliente com TOKEN:\"".$post['TOKEN']."\"') ",'insert');
            echo json_encode(array('codigo' => '1', 'mensagem' => 'Dados não foram importados, pois o token utilizado não foi encontrado.'));
        }
    }
    
    public function registra_evento_chat($post,$token){
        
        $dados_chat['TOKEN'] = $token;

        $dados = json_decode($post['mandrill_events']);

        
        $html = $dados[0]->msg->html;
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        // Data de envio
        $dados_chat['DATA_CRIACAO'] =  date('Y-m-d H:i:s', $dados[0]->ts);

        
        
        // Filtra e Seleciona Chat
        
        $tags_para_tirar = array('</tr>','</td>','</table>','</div>','<br>');
        $traducao_procura = array('Visitor','joined the chat','left the chat','&mdash;');
        $traducao_troca = array('Visitante','entrou no chat','saiu do chat','');
        
        //verifica se é chat ou msg offline
        $pos = strpos($dados[0]->msg->headers->Subject, 'Offline');
        if($pos !== false){
            $tags_para_tirar = array('</tr>','</td>','</table>','</div>','<br>');            
            $dados_chat['MENSAGEM'] = nl2br(trim(str_replace($traducao_procura,$traducao_troca, str_replace($tags_para_tirar,'',(strstr($this->getNodeInnerHTML($dom->getElementsByTagName('table')->item(3)), '<br>'))))));
        }else{
            $tags_para_tirar = array('<td>','<br>','<br />','/n','<td style="border-collapse: collapse; color: #525252">','<td valign="top" style="border-collapse: collapse; color: #525252; padding-right: 30px">','<tr>','</tr>','</td>','</table>');
            $dados_chat['MENSAGEM'] = trim('<div id="chat">'.trim(str_replace($traducao_procura,$traducao_troca, str_replace($tags_para_tirar,'',(strstr($this->getNodeInnerHTML($dom->getElementsByTagName('table')->item(3)), '<b>'))))).'</div>');
            $dados_chat['MENSAGEM'] = str_replace('<b>(','<br><b>(',$dados_chat['MENSAGEM']);
        }
        
        // Filtra e seleciona dados do cliente/chat
        $dados_do_usuario = preg_replace('/<b\b[^>]*>(.*?)<\/b>/i', '', $this->getNodeInnerHTML($dom->getElementById('visitor_info')));
        $dados_do_usuario = explode("\n",trim(strip_tags($dados_do_usuario)));
        $dados_chat['NOME'] = trim(str_replace($traducao_procura,$traducao_troca,$dados_do_usuario[0]));
        if(!empty($dados_do_usuario[1])){
            $dados_chat['EMAIL'] = trim(str_replace($traducao_procura,$traducao_troca,$dados_do_usuario[1])) ;
            $telefone = trim(str_replace($traducao_procura,$traducao_troca,$dados_do_usuario[2]));
        }else{
            $dados_chat['EMAIL'] = trim(str_replace($traducao_procura,$traducao_troca,$dados_do_usuario[2])) ;
            $telefone = trim(str_replace($traducao_procura,$traducao_troca,$dados_do_usuario[4]));
        }
        if(!empty($telefone)){
                $dados_chat['DDD_TELEFONE_RESIDENCIAL'] = substr(@$telefone,0,2);
                $dados_chat['TELEFONE_RESIDENCIAL'] = str_replace('-','',substr(@$telefone,2));
        } 
        
//        print_r($dados_chat);die('a');
        $this->registra_evento($dados_chat);
    }
    
    
     public function registra_evento_ligame($post){
        
        $post['EM_ANDAMENTO'] = 1; 
        $this->registra_evento($post);
        $post['ID_LEAD'] = $this->ci->db->insert_id();
        $this->ci->load->model('captacoes');
            $post += $this->ci->captacoes->getDadosLigame($post['TOKEN']);
         
            
        if($this->post_to_url('http://voip.syonet.com:1080/clique_ligue/gera_ligacao.php',$post) == 1){
            echo '1';
        }
        
        
    }
    
    public function post_to_url($url, $data) {
        $fields = '';
        foreach($data as $key => $value) { 
           $fields .= $key . '=' . $value . '&'; 
        }
        rtrim($fields, '&');

        $post = curl_init();

        curl_setopt($post, CURLOPT_URL, $url);
        curl_setopt($post, CURLOPT_POST, count($data));
        curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($post);
        curl_close($post);
        return $result;
    }
    
    public function cria_dados($quantidade){
        $nomes = array('Felipe Rosa','Gabriela Lubini','Paulo Ribas','Syonet');
        $email = array('teste@syonet.com','email@yahoo.com.br','outroteste@gmail.com','spam@gmail.com');
        $tipoLogradoro = array('Rua','Avenida','Balneario','Rua','Avenida','Rua','Beco');
        $bairro = array('Vila Rosa', 'Centro', 'Centro', 'Cascata','Justiça', 'Don Juan');
        $Logradoro = array('','ABC','A','B','C','D','ABC');
        $DDDs  = array(11,51,54,55,21,49);
        $cidades = array('São Paulo','Novo Hamburgo','Montenegro', 'Campo Bom', 'Novo Hamburgo');
        $estados = array('SP','RS','SC', 'RS', 'RJ');
        $assuntos = array('Elogio','Reclamação','Sugestão','Questionamento','Formulário do Site', 'Pesquisa de Satisfação', 'Promoção');
        $nv_us = array("novo","usado","");
        $modelo = array("Grande","Pequeno","Fit","Large");
        $loja_proxima = array("Autocom","Carburgo","Automatic Car","CarHouse");
        $tk = array('841412259285','841412259233','841412259343');
        for($cont = 0; $cont <= $quantidade; $cont++){
            
            $data['token']      = $tk[rand(0,2)];
            $data['nome']       = $nomes[rand(0,3)];
            $data['email']      = $email[rand(0,3)];
            $data['nascimENTO'] = date("Y-m-d",rand(523152000,617846400));
            $data['DDD_TELEFONE_RESIDENCIAL'] = $DDDs[rand(0,5)];
            $data['DDD_TELEFONE_COMERCIAL']   = $DDDs[rand(0,5)];
            $data['DDD_TELEFONE_CELULAR']     = $DDDs[rand(0,5)];
            $data['MODELO']     = $modelo[rand(0,3)];
            $data['FK_EMPRESAS_ID']     = rand(1,2);
            $data['telefone_residencial']     = rand(359000000,356099999);
            $data['TELEFONE_COMERCIAL']     = rand(359000000,356099999);
            $data['TELEFONE_CELULAR']     = rand(359000000,356099999);
            $data['tipo_Logradouro']     = $tipoLogradoro[rand(0,6)];
            $data['LOGRADOURO']     = $email[rand(0,3)].' '.rand(0,5);
            $data['NUMERO']  = rand(50,100);
            $data['BAIRRO']  = $bairro[rand(0,5)];
            $data['cidade']  = $cidades[rand(0,4)];
            $data['estadO']  = $estados[rand(0,4)];
            $data['Assunto']  = $assuntos[rand(0,6)];
            $data['CEP']  = rand(90000000,94000000);
            $data['OBSERVACAO']  = rand(0,9999999);
            $data['novo_usado']  = $nv_us[rand(0,1)];
            $data['cpf_cnpj']  = rand(100000000000,444444444444);
            $data['data_criaCAO'] = date("Y-m-d H:i:s",rand(1407628800,1412899200));
            
            $this->post_to_url('http://localhost/sistema/ws/adicionar',$data);
        }
    }
    
    public function getNodeInnerHTML($domn) {
            $domd = new DOMDocument();
            if ($domn->hasChildNodes()) {
                foreach ($domn->childNodes as $domnc) {
                    $domd->appendChild($domd->importNode($domnc, true));
                }
            }
            return $domd->saveHTML();
        }
        
    public function qualifica(){
        $_REQUEST = array_change_key_case($_REQUEST, CASE_UPPER);
        if($_REQUEST['QUALIFICACAO'] == 1 || $_REQUEST['QUALIFICACAO'] == 2){
            if(($_REQUEST['VALOR'] == 0 || empty($_REQUEST['VALOR']))    && $_REQUEST['QUALIFICACAO'] == 2){
                echo "A qualificaçãofoi marcada como Venda, então deve ser atribuido um valor maior de 0.";
            }else{
                $id_lead = $this->ci->leads->getIDleadQualificao($_REQUEST['ID_EVENTO_RETORNADO'],$_REQUEST['ACESSO']);
                if($id_lead > 0){
                    unset($_REQUEST['ACESSO']);
                    $dados = $_REQUEST;

                    $dados['ID'] = $id_lead;
                    $this->ci->leads->AtualizaQualificacao($dados);
                    echo 'Lead qualificada com sucesso';
                }else{
                    echo 'Não foi encontrada nenhuma Lead com este ID e Token';
                }
            }
        }else{
            echo 'O campo "QUALIFICACAO" está com um valor incorreto';
        }
       
    }
}