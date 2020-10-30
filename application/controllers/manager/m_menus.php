<?php

/**
 * Description of m_menus
 *
 * @author Felipe Rosa
 */
class m_menus extends NT_Manager_Controller {

    private $crud;

    public function __construct() {
        parent::__construct();

        $this->checkLogin();

        $this->load->library('grocery_CRUD');
        $this->crud = new grocery_CRUD();

        $this->load->model("nt_manager_menus");
        
        $this->crud->set_rules($this->nt_manager_menus->getRules())
                   ->auto_label($this->nt_manager_menus->getRules())
                   ->set_table($this->nt_manager_menus->getSft())
                   ->columns("TEXTO",'IDENTIFICADOR','metodos')
                   ->set_subject("Menus")
                   ->add_multiselect(base_url()."manager/menus/multiselect/",false, null, true)
                   ->change_field_type("TARGET", "dropdown",array('_parent'=>'_parent','_self'=>'_self','_blank'=>'_blank'))
                   ->callback_field('ICONE',array($this,'seleciona_icone'))
                   ->set_relation_n_n("metodos", "nt_manager_metodos_menus", "nt_manager_metodos", "NT_MANAGER_MENU_ID", "NT_MANAGER_METODO_ID", "METODO","ORDEM","((ROUND((LENGTH(METODO)-LENGTH(REPLACE(METODO,'/',''))) / LENGTH('/')) = 2 && METODO like '%/index') or (ROUND((LENGTH(METODO)-LENGTH(REPLACE(METODO,'/',''))) / LENGTH('/')) = 1))")
                   ->unset_print();

        
        if (!$this->nt_manager_permissoes->isValid(array("manager", "menus", "index", "export")))
            $this->crud->unset_export();
        
        if (!$this->nt_manager_permissoes->isValid(array("manager", "menus", "index", "add")))
            $this->crud->unset_add();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "menus", "index", "edit")))
            $this->crud->unset_edit();

        if (!$this->nt_manager_permissoes->isValid(array("manager", "menus", "index", "delete")))
            $this->crud->unset_delete();
    }

    
    public function clearselection(){
        $this->session->unset_userdata('nt_manager_menus-managerIDFilter');
        $url = base_url()."manager/menus/index";
        redirect($url);
    }
    
    
    /**
     * Por questão de segurança, o grocery está jogando de volta para o controller
     * que chamou, assim aqui neste metodo se pode constrolar segurança e permissões
     * se for implementado na camada do grocery, seria um permenitr ou  não para todos
     * 
     * @param type $acao
     * @param type $ids
     */
    public function multiselect($acao = false, $ids = false){
        $this->load->model("nt_grocery");
        return $this->nt_grocery->ajxmultiselect($acao, $ids);
    }  
    
    
    
    /**
     * Seta para que só os menus filhos do ID passado sejam trazidos para a lista
     * @param int $id
     */
    public function justsonsof($id){
        $lista = $this->nt_manager_menus->getFilhosDe($id);
        if($lista)
            $this->session->set_userdata ("nt_manager_menus-managerIDFilter",$lista);
        
        $url = base_url()."manager/menus";
        redirect($url);
    }
    
    
    /**
     * Implementa set order
     * 
     * @param type $field_name
     * @param type $id
     * @param type $ordem
     * @param type $tabela
     */
    public function setorder($field_name = false, $id = false, $ordem = false, $tabela = false){
        $this->load->model("nt_grocery");
        return $this->nt_grocery->ajxordem($field_name, $id, $ordem, $tabela);
    }
    
    
    
    public function index() {
        
        $this->crud->add_tooltip_description("IDENTIFICADOR", "Se for de segundo ou terceiro nível, separado por traço. Exemplo: configs-cache");
        $this->crud->add_comment("ICONE", "Se for um menu principal, clique sobre o ícone desejado.");
        $this->crud->add_comment("LINK", "<b>Opcional</b>: Se o link deste menu <b>não deve ser buscado</b> conforme as permissões, 
                                          cadastre o link fixo aqui. Também se for um <b>link externo</b>.
                                          O valor cadastrado não é concatenado com a base_url().");
        
        $this->crud->add_list_edit_order("ORDEM",base_url()."manager/menus/setorder/");
        
        
        $link_url = base_url()."manager/menus/justsonsof/";
        $image_url = base_url()."assets/img/manager/todown.png";
        $this->crud->add_action("Apenas filhos de", $image_url, $link_url);
        
        
        // se houver um custom selection, aplica o filtro nesta tela
        $IdFilter = $this->session->userdata('nt_manager_menus-managerIDFilter');
        if($IdFilter!= ''){
            $href = base_url()."manager/menus/clearselection";
            $this->crud->where("nt_manager_menus.ID in ($IdFilter) ");
            $data['jsexec'] = "$(function() { $('.ftitle').html(\"<a style='position:relative; top:5px; left:10px' href='$href'>Exibir lista completa</a>\"); });";
        }        
        
        $data['jsexec'] = "$( 'span.fa' ).click(function() {  $('span.fa').removeAttr('style'); $(this).css('border-bottom','#F00 solid 1px'); $('#field-ICONE').val($(this).attr('class'));});";
        $data['cssexec'] = "span.fa{margin-right: 5px; cursor:pointer; padding-bottom: 5px;}";
        $crud = $this->crud->render();
        $data['crud'] = $crud;
        
        $this->load->view("manager/m_default/index", $data);
    }
    
    function seleciona_icone($valor,$name){
        return '<span class="fa fa-camera fa-lg" style="'.($valor == 'fa fa-camera fa-lg' ? 'border-bottom: #F00 solid 1px;' : '').'"></span>
                <span class="fa fa-comments-o fa-lg" style="'.($valor == 'fa fa-comments-o fa-lg' ? 'border-bottom: #F00 solid 1px;' : '').'"></span>
                <span class="fa fa-home fa-lg" style="'.($valor == 'fa fa-home fa-lg' ? 'border-bottom: #F00 solid 1px;' : '').'"></span>
                <span class="fa fa-users fa-lg" style="'.($valor == 'fa fa-users fa-lg' ? 'border-bottom: #F00 solid 1px;' : '').'"></span>
                <span class="fa fa-wrench fa-lg" style="'.($valor == 'fa fa-wrench fa-lg' ? 'border-bottom: #F00 solid 1px;' : '').'"></span>
                <span class="fa fa-video-camera fa-lg" style="'.($valor == 'fa fa-video-camera fa-lg' ? 'border-bottom: #F00 solid 1px;' : '').'"></span>
                <span class="fa fa-newspaper-o fa-lg" style="'.($valor == 'fa fa-camera fa-lg' ? 'border-bottom: #F00 solid 1px;' : '').'"></span>
                <span class="fa fa-briefcase fa-lg" style="'.($valor == 'fa fa-newspaper-o fa-lg' ? 'border-bottom: #F00 solid 1px;' : '').'"></span>
                <span class="fa fa-cogs fa-lg" style="'.($valor == 'fa fa-cogs fa-lg' ? 'border-bottom: #F00 solid 1px;' : '').'"></span>
                <span class="fa fa-cutlery fa-lg" style="'.($valor == 'fa fa-cutlery fa-lg' ? 'border-bottom: #F00 solid 1px;' : '').'"></span>
                <span class="fa fa-envelope-o fa-lg" style="'.($valor == 'fa fa-envelope-o fa-lg' ? 'border-bottom: #F00 solid 1px;' : '').'"></span>
                <span class="fa fa-globe fa-lg" style="'.($valor == 'fa fa-globe fa-lg' ? 'border-bottom: #F00 solid 1px;' : '').'"></span>
                <span class="fa fa-gift fa-lg" style="'.($valor == 'fa fa-gift fa-lg' ? 'border-bottom: #F00 solid 1px;' : '').'"></span>
                <span class="fa fa-key fa-lg" style="'.($valor == 'fa fa-key fa-lg' ? 'border-bottom: #F00 solid 1px;' : '').'"></span>
                <span class="fa fa-university fa-lg" style="'.($valor == 'fa fa-university fa-lg' ? 'border-bottom: #F00 solid 1px;' : '').'"></span>
                <span class="fa fa-flag fa-lg" style="'.($valor == 'fa fa-flag fa-lg' ? 'border-bottom: #F00 solid 1px;' : '').'"></span>
                <span class="fa fa-question fa-lg" style="'.($valor == 'fa  fa-question fa-lg' ? 'border-bottom: #F00 solid 1px;' : '').'"></span>
                <span class="fa fa-car fa-lg" style="'.($valor == 'fa  fa-car fa-lg' ? 'border-bottom: #F00 solid 1px;' : '').'"></span>
                <span class="fa fa-motorcycle fa-lg" style="'.($valor == 'fa  fa-motorcycle fa-lg' ? 'border-bottom: #F00 solid 1px;' : '').'"></span>
                <span class="fa fa-truck fa-lg" style="'.($valor == 'fa  fa-truck fa-lg' ? 'border-bottom: #F00 solid 1px;' : '').'"></span>
                <span class="fa fa-bell fa-lg" style="'.($valor == 'fa  fa-bell fa-lg' ? 'border-bottom: #F00 solid 1px;' : '').'"></span>
                <span class="fa fa-user-secret fa-lg" style="'.($valor == 'fa  fa-user-secret fa-lg' ? 'border-bottom: #F00 solid 1px;' : '').'"></span>
                <span class="fa fa-video-camera fa-lg" style="'.($valor == 'fa  fa-video-camera fa-lg' ? 'border-bottom: #F00 solid 1px;' : '').'"></span>
                <span class="fa fa-area-chart fa-lg" style="'.($valor == 'fa  fa-area-chart fa-lg' ? 'border-bottom: #F00 solid 1px;' : '').'"></span>
                <span class="fa fa-area-ring fa-lg" style="'.($valor == 'fa  fa-area-ring fa-lg' ? 'border-bottom: #F00 solid 1px;' : '').'"></span>
                <span class="fa fa-wheelchair fa-lg" style="'.($valor == 'fa  fa-wheelchair fa-lg' ? 'border-bottom: #F00 solid 1px;' : '').'"></span>
                <input id="field-ICONE" name="ICONE" type="hidden" value="'.$valor.'">';
    }

}
