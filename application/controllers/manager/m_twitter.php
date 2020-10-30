<?php
/**
 * @author Allan Dudar de Oliveira <allan@noiatec.com.br>
 */
class m_twitter extends NT_Manager_Controller {

    private $crud;

    public function __construct() {
        parent::__construct();

        $this->checkLogin();

        $this->load->library('grocery_CRUD');
        $this->crud = new grocery_CRUD();

        $this->load->model("nt_site_twitter");
        $this->crud->set_rules($this->nt_site_twitter->getRules())
                    ->auto_label($this->nt_site_twitter->getRules());        
        
        $this->crud->set_table("nt_site_twitter")
                ->set_subject("Tweet")
                ->columns("ID_TWEET","TWEET","DATA","STATUS")
                ->change_field_type("STATUS", 'true_false')
                ->unset_print()->unset_add();
        
                       
        if (!$this->nt_manager_permissoes->isValid(array("manager", "banners", "index", "edit")))
            $this->crud->unset_edit();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "banners", "index", "delete")))
            $this->crud->unset_delete();
    }
    
    public function atualizar(){
        
        $this->load->model('nt_site_twitter');
        
        $this->load->library('twitteroauth');
        
        $twitter_connection = $this->twitteroauth->create($this->nt_global_parametros->q('twitter_consumer_token'), $this->nt_global_parametros->q('twitter_consumer_secret'), $this->nt_global_parametros->q('twitter_access_token'),$this->nt_global_parametros->q('twitter_access_secret'));
        $content = $twitter_connection->get('account/verify_credentials');
        if (!isset($content->errors)){
            $arrLastTweet = end($this->nt_site_twitter->getLastTweet());
            $parametros = array('user_id'=>$content->id,'screen_name'=>$content->screen_name);
            if (isset($arrLastTweet['ID_TWEET']) && !empty($arrLastTweet['ID_TWEET']))
                $parametros['since_id'] = $arrLastTweet['ID_TWEET'];
            
            $arrResult = array();
            $user_timeline = $twitter_connection->get('statuses/user_timeline',$parametros);
            if (isset($user_timeline) && !empty($user_timeline)){
                foreach ($user_timeline as $tweet){
                    $in = null;
                    $in = array();
                    $in['ID_TWEET'] = $tweet->id_str;
                    $in['TWEET'] = $tweet->text;
                    $in['DATA'] = date("Y-m-d H:i:s",strtotime($tweet->created_at));
                    $in['STATUS'] = 0;
                    $arrResult[] = $in;
                    $this->nt_twitter->gravar($in);
                }
            }

            $data['res'] = $arrResult;
            
            $this->load->view("manager/m_twitter/index", $data);
        }else
            return false;
    }

    public function index() {

        $crud = $this->crud->render();
        $data['crud'] = $crud;

        $valor = "<div style='float:left'><a class='add-anchor' href='".  base_url()."manager/twitter/atualizar'> <div class='fbutton'> <div> <span class'add'><img src='" . base_url() . "assets/img/manager/atualizar.png' border=0/> Atualizar tweets</span></div></div></a><div class='btnseparator'></div></div>";
        $js = "$('.tDiv3').append(\"$valor\");";
        $data['jsexec'] = $js;
        
        $this->load->view("manager/m_default/index", $data);
    }

}
