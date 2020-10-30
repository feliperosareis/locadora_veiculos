/*
Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license

Lista do nome dos botões pode ser obtida em
http://docs.cksource.com/CKEditor_3.x/Developers_Guide/Toolbar
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	config.language = 'pt';
	// config.uiColor = '#AADC6E';
        
       // esta conf habilita o browse at server nos campos de imagens, pode fazer upload dai
       CKEDITOR.config.filebrowserImageBrowseUrl = base_url+'manager/internal/ckeditorupload/';
        
       // toolbars 
       CKEDITOR.config.toolbar_Basic1 = [['Bold','Italic','Underline','Strike','NumberedList','BulletedList','-','RemoveFormat','-', 'Link', 'Unlink', '-', 'Source', 'Maximize']];
       
       CKEDITOR.config.toolbar_Basic2 = [['Image','-','Bold','Italic','Underline','Strike','NumberedList','BulletedList','-','RemoveFormat','-', 'Link','Unlink','-', 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-', 'Source', 'Maximize']];
       
       CKEDITOR.config.toolbar_Basic3 = [['Image','-','Bold','Italic','Underline','Strike','NumberedList','BulletedList','-','RemoveFormat','-', 'Link','Unlink','-', 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-', 'Source', 'Maximize','TextColor','BGColor','Font','FontSize',]];

       CKEDITOR.config.entities = false;
       
};
