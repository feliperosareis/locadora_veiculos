<?php

/**
 * Métodos de auxílio para todo o processo de upload
 *
 * @author Felipe Rosa
 */
class nt_manager_upload extends NT_Model {
    
    
    public function clearInfo($conf,$tmpdir){
        
        $sql = sprintf("delete from nt_tmp_imagens_uploads where CONF='%s' 
                        and TMPDIR='%s'",$conf,$tmpdir);
        
        if($this->db->query($sql))
            return TRUE;
        else {
            return FALSE;
        }

    }
    
    
    public function save($data){
        
        $gravar = array();
        
        foreach($data as $row){
            
            $gravar['CONF'] = $row['conf'];
            $gravar['TMPDIR'] = $row['tmpdir'];
            $gravar['FILE'] = $row['file'];
            $gravar['MD5FILE'] = $row['md5file'];
            $gravar['TYPE'] = $row['type'];
            $gravar['WCORRETO'] = $row['wCorreto'];
            $gravar['HCORRETO'] = $row['hCorreto'];
            $gravar['DISK'] = $row['disk'];
            $gravar['WEB'] = $row['web'];
            $gravar['WREAL'] = $row['w'];
            $gravar['HREAL'] = $row['h'];
            
            $this->db->insert('nt_tmp_imagens_uploads',$gravar);
            
            unset($gravar);
            $gravar= array();
        }
    }
    
    
    public function getUploads($uploaded){
        $t = explode('/',$uploaded);
        
        if(count($t) != 2)
            die("Error: Unexpected parameter");
        
        $conf = $t[0];
        $tmpdir = $t[1];
        
        $res = $this->db->get('nt_tmp_imagens_uploads')->result_array();
    }
    
}

