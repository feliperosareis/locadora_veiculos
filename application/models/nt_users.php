<?php

class nt_users extends NT_Model {

    private $validation = array(

        array('field' => 'name', 'label' => 'Nome', 'rules' =>  '', 'errors' => []),
        array('field' => 'email', 'label' => 'E-mail', 'rules' =>  'required', 'errors' => []),
        array('field' => 'username', 'label' => 'Usuário', 'rules' =>  'required', 'errors' => []),
        array('field' => 'password', 'label' => 'Password', 'rules' =>  '', 'errors' => []),
		array('field' => 'active', 'label' => 'Status', 'rules' =>  'required', 'errors' => []),
        array('field' => 'date_created', 'label' => 'Data Criada', 'rules' =>  '', 'errors' => []),
        array('field' => 'active', 'label' => 'Status', 'rules' =>  '', 'errors' => []),
        array('field' => 'role_id', 'label' => 'Papel', 'rules' =>  'required', 'errors' => []),

    );

    public function getRules(){
       return $this->validation;
    }

    public function login()
    {
        return $this->db->select('u.id, u.name, u.date_created, r.name as role_name, r.id as role_id')
                        ->from($this->getSft() . ' u')
                        ->join('nt_roles r', 'r.ID = u.role_id')
                        ->join('nt_role_permissions rp', 'rp.role_id = r.id')
                        ->where('u.username', $this->input->post('username'))
                        ->where('u.password', md5($this->input->post('password')))
                        ->where('u.active', 1)
                        ->get()->row_array();
    }

}