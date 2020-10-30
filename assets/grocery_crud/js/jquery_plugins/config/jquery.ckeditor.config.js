$(function(){
        // esses dois primeiros sao padrao do Grocery
        
	$( 'textarea.texteditor' ).ckeditor({toolbar:'Full'});
	$( 'textarea.mini-texteditor' ).ckeditor({toolbar:'Basic',width:700});
        
        /*
         * esses aqui abaixo são personalizados
         * passa no php $this->config->set_item('grocery_crud_text_editor_type','basic1');
         * e aí "basic1" é a classe do text area que ele vai criar
         * conforme a classe do text area, tem uma paleta de componentes diferemtes
         * Basic1, Basic2 ... que esta declarado em 
         * /assets/grocery_crud/texteditor/ckeditor/config.js
        */
       
        $( 'textarea.basic1' ).ckeditor({toolbar:'Basic1',width:700});
        
        $( 'textarea.basic2' ).ckeditor({toolbar:'Basic2',width:700});
        
        $( 'textarea.basic3' ).ckeditor({toolbar:'Basic3',width:700});
        
});