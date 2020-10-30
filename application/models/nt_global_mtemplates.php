<?php
/**
 * Description of nt_global_mtemplates
 *
 * @author Felipe Rosa
 */
class nt_global_mtemplates extends NT_Model{
    private $validation = array(
        array('field' => 'ASSUNTO', 'label' => 'Assunto', 'rules' => 'required'),
        array('field' => 'CORPO', 'label' => 'Corpo do email', 'rules' => 'required'),
        array('field' => 'NT_GLOBAL_IDIOMA_ID', 'label' => 'Idioma deste modelo', 'rules' => 'required|integer'),
        array('field' => 'IDENTIFICADOR', 'label' => 'Identificador', 'rules' => 'required')      
    );
    
    /**
     * Devolve o array com as validações para esta model
     * 
     * @return array com as regras de validação desta model
     */
    public function getRules(){
        return $this->validation;
    }
    
    /**
     * Monta o corpo e o assunto de um email de acordo com o template
     * que depende do idioma
     * 
     * @param string $identificador Identidicador na tabela de templates de emails
     * @param string $idioma em minúsculo, dois caracteres, o idioma em questão
     * @param array $replaces no formaro $replaces = array('cliente'=>'Aqui valor','foo'=>'java')
     * @return array indices array('subject'=>"valor",'body'=>'valor')
     */
    public function getEmail($identificador,$idioma,$replaces){
        
        $content = array('subject'=>'','body'=>'');
        
        // qual é o idioma que veio?
        $idi = $this->db->where('ABREVIATURA',$idioma)->get('nt_global_idiomas')->result_array();
        if(!isset($idi[0]['ID']))
            $idi[0]['ID'] = 1; // default PT
        
        
        // busca o email do identificador no idioma
        $email = $this->db->where('NT_GLOBAL_IDIOMA_ID',$idi[0]['ID'])
                          ->where('IDENTIFICADOR',$identificador)
                          ->get('nt_global_mtemplates')->result_array();
        
        if(!isset($email[0]['CORPO']))
            return $content; // não tem o que fazer, não tem o identificador, volta em branco
        
        
        $replace['subject'] = $email[0]['ASSUNTO'];
        $replace['body'] = $email[0]['CORPO'];
        
        // monta os arrays para o str_replace
        $search = array();
        $values = array();
        foreach($replaces as $key=>$rep){
            $search[] = ":$key:";
            $values[] = $rep;
        }
        
        // faz os replaces
        $newSubject = str_replace($search, $values, $replace['subject']);
        $newBody = str_replace($search, $values, $replace['body']);
        
        
        $content = array('subject'=>$newSubject,'body'=>$newBody);
        return $content;      
    }

}
