<?php
/**
 * Description of m_usuarios
 *
 * @author Felipe Rosa
 */
class m_usuarios extends NT_Manager_Controller {

    private $crud;

    public function __construct() {
        parent::__construct();

        $this->checkLogin();

        $this->load->model("nt_manager_usuarios");
        $this->load->model("nt_global_parametros");

        $this->load->library('grocery_CRUD');

        $this->crud = new grocery_CRUD();
        
        $this->crud->set_rules($this->nt_manager_usuarios->getRules())
                   ->auto_label($this->nt_manager_usuarios->getRules())
                   ->set_table($this->nt_manager_usuarios->getSft())
                   ->set_subject("Usuários")
                   ->display_as("papeis","Papéis")
                   ->unset_columns("SENHA","FOTO_PERFIL","URLPOSLOGIN")
                   ->set_field_upload("FOTO_PERFIL", "assets/uploads/nt_manager_usuarios")
                   ->change_field_type("SENHA", "password")
                   ->unset_print();

        
        $this->crud->callback_before_insert(array($this, 'encrypt_password_callback'));
        $this->crud->callback_before_update(array($this, 'verifyUpdatePassword'));

       
        if (!$this->nt_manager_permissoes->isValid(array("manager", "usuarios", "index", "export")))
            $this->crud->unset_export();
        
        if (!$this->nt_manager_permissoes->isValid(array("manager", "usuarios", "index", "add")))
            $this->crud->unset_add();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "usuarios", "index", "edit")))
            $this->crud->unset_edit();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "usuarios", "index", "delete")))
            $this->crud->unset_delete();
    }

    /**
     * Criptografa a senha de acordo com a criptografia definida em config/config.php
     * 
     * @param array $post_array recebe o array de dados de todos os campos, o índice é o nome do campo
     * @return string a senha criptografada
     */
    function encrypt_password_callback($post_array) {
        $post_array['SENHA'] = $this->nt_manager_usuarios->cryptText($post_array['SENHA']);
        return $post_array;
    }

    /**
     * Se a senha não foi mudada, tira fora o campo para que ele não seja atingido
     * no updade de dados
     * 
     * @param array $post_array array de dados, indice é o nome do campo no BD
     * @return array volta o arry com os campos todos e a senha criptografada, ou sem o field da senha
     */
    function verifyUpdatePassword($post_array) {

        if (strlen(trim($post_array['SENHA'])) == 0) {
            unset($post_array['SENHA']);
        } else {
            $post_array['SENHA'] = $this->nt_manager_usuarios->cryptText($post_array['SENHA']);
        }
        return $post_array;
    }

    
    // funcao padrao que chama ao incializar a classe
    public function index() {
        
        $data['js'] = '';

        $this->crud->set_relation_n_n("papeis", "nt_manager_permissoes", "nt_manager_papeis", "NT_MANAGER_USUARIO_ID", "NT_MANAGER_PAPEL_ID", "NOME");
        $this->crud->set_relation_n_n("setores", "nt_global_setores_usuarios", "nt_global_setores", "NT_USUARIO_ID", "NT_SETOR_ID", "SETOR");

        $this->crud->add_tooltip_description("SENHA", "Para manter a senha atual, não informe senha. Deixe o campo sem valor");
        
        $this->crud->add_tooltip_description("papeis", "Os papéis definem as permissões do usuário no site.
                                                        <br/>Um usuário pode ter vários papéis, e para cada
                                                        <br/>situação que envolve permissões, a permissão
                                                        <br/>mais permissiva entre os papéis do usuário 
                                                        <br/>será aplicada.");
        
        
        
        $regras = "";
        $min = $this->nt_global_parametros->q("nt_tam_min_password");
        $max = $this->nt_global_parametros->q("nt_tam_max_password");
        if ($min != '' and $max != '')
            $regras = "min_length[$min]|max_length[$max]";

        $this->crud->set_rules("SENHA", "Senha", "trim|$regras");

        // se esta em edicao, avisa que nao vai trocar a senha
        if ($this->crud->getState() == 'edit') {

            // no edit ele não é required pq pode que o user não queira mudar a senha
            $this->crud->set_rules("SENHA", "Senha", "trim|$regras");

            $data['js'] = "<script>
                        $('#field-SENHA').val(''); 
                         $('#field-SENHA').css('width','200px');
                        </script>";
        }
        

        $data['crud'] = $this->crud->render();
        $this->load->view("manager/m_usuarios/index", $data);
        
    }

}
