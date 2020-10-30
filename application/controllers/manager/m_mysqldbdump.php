<?php
/**
 * Observar e em algum momento avaliar se deve-se usar a classe de backup do próprio CI
 * Ver aqui: http://ellislab.com/codeigniter/user-guide/database/utilities.html
 * em $this->dbutil->backup()
 *
 * @author Felipe Rosa, <heltonritter@web.de>
 */
class m_mysqldbdump extends NT_Manager_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->checkLogin();
    }


    public function dump(){
        
        // no time limit here
        set_time_limit(0);
        
        $this->load->library("mysqldump");
        
        if($this->db->dbdriver == "mysql"){
            
            $this->mysqldump->db = $this->db->database;
            $this->mysqldump->host = $this->db->hostname;
            $this->mysqldump->user = $this->db->username;
            $this->mysqldump->pass = $this->db->password;
            
            $justName = $this->mysqldump->db."-".date("Y-m-d_h-i-s").".sql";
            $this->mysqldump->filename = $this->config->item("local_disk_url")."assets/uploads/".
                                         $justName;
            
            // create dump file
            $this->mysqldump->start();
            $this->nt_global_logs->s("Dump do BD Mysql", "O usuário fez dump do banco de dados");
            
            // force download
            header("Content-disposition: attachment; filename=$justName");
            header('Content-type: text/sql');
            readfile($this->mysqldump->filename);
            
            // remove file from disk
            unlink($this->mysqldump->filename);
            

        }else {
            exit("Desculpe, mas só há suporte de Mysql por enquanto. Seu banco não parece ser mysql");
        }
        
    }
}
