<?php
/**
 * NT Model dispibiliza os método getAll e getWhereId por padrão para as <br/>
 * classes que a extendem. Nome padrão da tabela também, se a tabela for o nome<br/>
 * da classe, apenas use $this->sft para pegar a info da tabela atual
 *
 * @author Felipe Rosa
 */
class NT_Model extends CI_Model{
    
    protected $sft; // SelfTable
    
    public function __construct() {
        parent::__construct();
        $this->sft = get_class($this);
        
    }
    
    /**
     * Função pública para por exemplo poder acessar
     * mas não modificar a parir do controller a tabela
     * em questão
     * 
     * @return string
     */
    public function getSft(){
        return $this->sft;
    }
    
    /**
     * Retorna todos os registros da tabela padrão correspondente a esta model
     * 
     * @return array
     */
    public function getAll(){
        return $this->db->get($this->sft)->result();
    }
    
    /**
     * Devolve o registro desta tabela (padrão) onde o campo ID (pk) for o param informado
     * 
     * @param int $id
     * @return array
     */
    public function getWhereId($id){
       $id = intval($id);
       return $this->db->where("ID",$id)->get($this->sft)->row_array();
    }
    

    /**
     * getBy é um atalho para quando houver um where simples, com apenas algum campo.
     * Suporta um campo e ordenação
     * 
     * @param string $whereFieldName
     * @param string $equalsFieldValue
     * @param string $fieldNameToOrderBy
     * @param string $orderDirection
     * @return array Lista de registros encontrados
     */
    public function getBy($whereFieldName,$equalsFieldValue,$fieldNameToOrderBy=false,$orderDirection=false,$from=false,$limit=false){
        
        // if they are some field to order by
        if($fieldNameToOrderBy){
            
            // with some direction?
            if($orderDirection){
                $this->db->order_by($fieldNameToOrderBy,$orderDirection);
            }else{
                $this->db->order_by($fieldNameToOrderBy); // ASC, default direction
            }
        }
        //Limite
        if($limit){
            $this->db->limit($limit);
        }
        
        //where
        if(is_array($whereFieldName)){
            foreach($whereFieldName as $key => $campo){
                $this->db->where($campo, $equalsFieldValue[$key]);
            }
        }else{
            $this->db->where($whereFieldName, $equalsFieldValue);
        }
        
        if($from){
            return $this->db->get($from)->result();
        }else{
            return $this->db->get($this->sft)->result();
        }
        
    }

    public function getResult()
    {
        return $this->get()->result_array();
    }

    public function getRow()
    {
        return $this->get()->row_array();
    }

    public function insert($arr_insert = array())
    {
        $this->db->ignore()->insert($this->getSft(), $arr_insert);
        return $this->db->insert_id();
    }

    public function update($arr_update = array(), $where = array())
    {                
        $this->db->where($where);
        return $this->db->update($this->getSft(), $arr_update);
    }

    public function delete($where = array())
    {                
        $this->db->where($where);
        return $this->db->delete($this->getSft());
    }

    public function insert_valor($value = null)
    {
        
        if(!$value) return null;

        $row_result = $this->getByTitulo($value);        
        if(count($row_result)){
            return $row_result['ID'];
        }

        $arr_insert = array();
        $arr_insert['TITULO'] = $value;
        $arr_insert['STATUS'] = 1;

        return $this->insert($arr_insert);

    }

    public function getByID($id = 0)
    {

        $this->db->where('ID', $id);
        return $this->get()->row_array();

    }

    public function getByTitulo($titulo = 0)
    {

        return $this->db->select("*")
                        ->from($this->getSft())
                        ->where('TITULO', $titulo)
                        ->get()->row_array();

    }

    public function ws($row_lead = array()) {

        if(!count($row_lead))
        {
            
            $id = $this->db->insert_id();
            $row_lead = array_filter($this->getByID($id));
            
        }
        
        $this->load->library('lead_force'); 
        
        if($this->lead_force->geraLead($row_lead)){
            return true;
        }

        return false;

    }
}