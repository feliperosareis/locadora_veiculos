<?php
/**
 * Gerador de Models
 *
** @author Felipe Rosa 
 */
class gerador extends CI_Controller {

    private $seo;
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index($lang = null) {
        $models_existentens = scandir('application/models/',1);
        $manager_existentens = scandir('application/controllers/manager/',1);
        print_r2($manager_existentens,false);
        $bd = $this->db->database;
        $sql = "SHOW 
                    TABLES
                FROM
                    $bd
                WHERE 
                    Tables_in_$bd NOT LIKE '%global%' 
                    and  Tables_in_$bd NOT LIKE '%manager%'
                    and Tables_in_$bd NOT LIKE '%nt_gd%'
                    and Tables_in_$bd != 'nt_site_twitter'";
        $tabelas = $this->db->query($sql)->result_array();
        foreach($tabelas as $tabela){
            $sql = "SELECT 
                             COLU.COLUMN_NAME,
                            IF(COLU.IS_NULLABLE = 'NO','required','') as obrigatorio,
                            IFNULL(COLU.CHARACTER_MAXIMUM_LENGTH,'') as tamanho_maximo,
                            COLU.COLUMN_COMMENT as label,
                            REFERENCED_TABLE_NAME TABELA_FK,
                            REFERENCED_COLUMN_NAME COLUNA_FK
                    FROM 
                            information_schema.columns COLU
                            left JOIN    information_schema.KEY_COLUMN_USAGE USAG ON USAG.table_name='".$tabela['Tables_in_'.$bd]."' 
                                                                                     AND  USAG.REFERENCED_TABLE_NAME IS NOT NULL    
                                                                                     AND  USAG.REFERENCED_COLUMN_NAME   IS NOT NULL
                                                                                     AND  USAG.COLUMN_NAME = COLU.COLUMN_NAME
                                                                                     AND  USAG.TABLE_SCHEMA = COLU.TABLE_SCHEMA
                    WHERE
                            COLU.table_name = '".$tabela['Tables_in_'.$bd]."'
                            AND COLU.COLUMN_NAME <> 'ID'
                            AND COLU.TABLE_SCHEMA = '$bd'";
            $campos = $this->db->query($sql)->result_array();
            $jsexec = '';
            $trata_moeda = array();
            $campos_unidos = '';
            $campos_select = '';
            $opcoes_manager = "->add_multiselect(base_url() .'manager/".str_replace('nt_','',$tabela['Tables_in_'.$bd])."/selecao_multipla/',true)\n\t\t\t";
            foreach($campos as $campo){
                $regra='';
                if($campo['obrigatorio'] != ''){
                    if($campo['tamanho_maximo'] != ''){
                        $regra = $campo['obrigatorio'].'|max_length['.$campo['tamanho_maximo'].']';
                    }else{
                        $regra = $campo['obrigatorio'];
                    }
                }else{
                     if($campo['tamanho_maximo'] != ''){
                          $regra = 'max_length['.$campo['tamanho_maximo'].']';
                     }
                }
                if($campo['COLUMN_NAME'] == 'LINK' || $campo['COLUMN_NAME'] == 'URL' || strpos($campo['COLUMN_NAME'],'LINK') !== false){
                        $regra .= '|valid_url';
                }
                if($campo['COLUMN_NAME'] == 'EMAIL'){
                        $regra .= '|valid_email';
                }
                
                if(strpos($campo['COLUMN_NAME'],'TELEFONE') !== false){
                    if($campo['tamanho_maximo'] > 2){
                         $regra .= '|min_length[8]';
                    }else{
                        $regra .= '|min_length[2]';
                    }
                    $regra .= '|integer';
                }
                if($campo['COLUMN_NAME'] == 'ORDEM'){
                    $opcoes_manager .= "->add_list_edit_order('ORDEM',  base_url().'manager/".str_replace('nt_','',$tabela['Tables_in_'.$bd])."/alterar_ordem/')\n\t\t\t";
                }
                if($campo['COLUMN_NAME'] == 'STATUS'){
                    $opcoes_manager .= "->add_bolean_status_switcher('STATUS', base_url().'manager/".str_replace('nt_','',$tabela['Tables_in_'.$bd])."/alterar_status/')\n\t\t\t";
                }
                if($campo['COLUMN_NAME'] == 'VALOR' || $campo['COLUMN_NAME'] == 'PRECO'){
                    $opcoes_manager .= "->callback_before_insert(array(\$this,'trata_moeda'))->callback_before_update(array(\$this,'trata_moeda'))\n\t\t\t";
                    $jsexec .= '$("#field-'.$campo['COLUMN_NAME'].'").mask("000.000.000,00", { reverse: true });';
                    $trata_moeda[] = $campo['COLUMN_NAME'];
                }
                
                if (strpos($campo['COLUMN_NAME'],'IMAGEM') !== false || strpos($campo['COLUMN_NAME'],'BANNER') !== false) {
                    $opcoes_manager .= "->set_field_upload('{$campo['COLUMN_NAME']}', 'assets/uploads/' . \$this->tabela)\n\t\t\t";
                    $opcoes_manager .= "->add_tooltip_description('{$campo['COLUMN_NAME']}', 'As imagens devem estar na resolução de 000x000.')\n\t\t\t";
                }
                if(!is_null($campo['TABELA_FK'])){
                    $opcoes_manager .= "->set_relation('".$campo['COLUMN_NAME']."','".$campo['TABELA_FK']."','NOME')\n\t\t\t";
                }
                $campos_unidos .=  "array('field' => '$campo[COLUMN_NAME]', 'label' => '$campo[label]', 'rules' =>  '$regra'),\n\t\t\t";
                $campos_select .=  " {$campo['COLUMN_NAME']},";
                
            }
            $campos_unidos = substr($campos_unidos,0,-1);
            $campos_select = substr($campos_select,0,-1);
            $data_gerado = date(DATE_RFC822);
            $conteudo = <<<EOD
<?php
/**
 * Gerador Automático
 * @author Felipe Rosa
 * Gerado em: $data_gerado
 */
class {$tabela['Tables_in_'.$bd]} extends NT_Model {
        private \$validation = array(
            $campos_unidos
        );
    /**
    * Devolve o array com as validações para esta model
    * 
    * @return array com as regras de validação desta model
    */
    public function getRules(){
       return \$this->validation;
    }
        
    public function get()
    {
        
        return \$this->db->select("ID, {$campos_select}")
                        ->from(\$this->getSft())
                        ->get();

	}
    public function getRow()
    {
        return \$this->get()->row_array();
    }
    
    public function getResult()
    {
        return \$this->get()->result_array();
    }      
}
    
EOD;
            //unlink('application/models/'.$tabela['Tables_in_'.$bd].'.php');
            $nome_limpo = str_replace('nt_','',$tabela['Tables_in_'.$bd]).'.php';
            if (!(in_array('nt_'.$nome_limpo, $manager_existentens)) && !(in_array($nome_limpo, $models_existentens))) { 
                $file = @fopen('application/models/'.$tabela['Tables_in_'.$bd].'.php', "x+");
                @fwrite($file, stripslashes($conteudo));
                @fclose($file);
            }
            
            $class = str_replace('nt_','m_',$tabela['Tables_in_'.$bd]);
            $subject = ucwords(str_replace('_',' ',str_replace('m_','',$class)));
            
            
            if(count($trata_moeda) > 0){
                $function_moeda = 'public function trata_moeda($post){';
                foreach($trata_moeda as $campo){
                    $function_moeda .= 'if(isset($post["'.$campo.'"])){
                                            $post["'.$campo.'"] = moeda_bd($post["'.$campo.'"]);
                                        }';
                }
                $function_moeda .= '    return $post;
                                    }';
            }else{
                $function_moeda = '';
            }
            
            $data_gerado = date(DATE_RFC822);
            $nome_metodo = str_replace('nt_','',$tabela['Tables_in_'.$bd]);
            $conteudo = <<<EOD
<?php
/**
 * Gerador Automático
 * @author Felipe Rosa
 * Gerado em: $data_gerado
 */
class $class extends NT_Manager_Controller {

    private \$crud;
                    
    public function __construct() {
        parent::__construct();

        \$this->checkLogin();
        \$this->load->library('grocery_CRUD');
        \$this->crud = new grocery_CRUD();
        \$this->tabela = '{$tabela['Tables_in_'.$bd]}';
        \$this->load->model(\$this->tabela);
        \$this->num_rows = \$this->{\$this->tabela}->get()->num_rows();
        \$this->bUnique = false;
        if (\$this->num_rows > 0 && \$this->bUnique) {
            \$id = \$this->{\$this->tabela}->getRow();
            \$this->id = \$id['ID'];
        }

        if (!\$this->nt_manager_permissoes->isValid(\$this->uri->segment_array() + array("add")) || (\$this->num_rows > 0 && \$this->bUnique))
            \$this->crud->unset_add();

        if (!\$this->nt_manager_permissoes->isValid(\$this->uri->segment_array() + array("edit")))
            \$this->crud->unset_edit();

        if (!\$this->nt_manager_permissoes->isValid(\$this->uri->segment_array() + array("delete")) || (\$this->num_rows > 0 && \$this->bUnique))
            \$this->crud->unset_delete();

        if (!\$this->nt_manager_permissoes->isValid(\$this->uri->segment_array() + array("export")) || (\$this->num_rows > 0 && \$this->bUnique))
            \$this->crud->unset_export();

        \$this->crud->unset_print();
    }                    

    public function index() {
        
        if (in_array(\$this->crud->getState(), array('list', 'success')) && (\$this->num_rows > 0 && \$this->bUnique)) {
            redirect('/manager/{$nome_metodo}/index/edit/' . \$this->id);
        }
        
        \$this->crud ->set_rules(\$this->{\$this->tabela}->getRules())
                    ->auto_label(\$this->{\$this->tabela}->getRules())
                    ->set_table(\$this->tabela)
                    ->set_subject("$subject")
                    $opcoes_manager
                    ->columns();
                    
        if (\$this->num_rows > 0 && \$this->bUnique)
            \$this->crud->unset_back_to_list();
                    
        \$crud = \$this->crud->render();
                
        \$data['jsexec'] = '$jsexec';

        \$data['crud'] = \$crud;
        \$this->load->view("manager/m_default/index", \$data);
    }
                
    $function_moeda
}
    
EOD;

            $nome_limpo = str_replace('nt_','',$tabela['Tables_in_'.$bd]).'.php';
            if (!(in_array('m_'.$nome_limpo, $manager_existentens)) && !(in_array($nome_limpo, $manager_existentens))) { 
                $file = @fopen('application/controllers/manager/'.$class.'.php', "x+");
                @fwrite($file, stripslashes($conteudo));
                @fclose($file);   
            }
            
        }
    }

}
