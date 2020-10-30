<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


/*
  | -------------------------------------------------------------------
  | AUTO-LOADER
  | -------------------------------------------------------------------
  | This file specifies which systems should be loaded by default.
  |
  | In order to keep the framework as light-weight as possible only the
  | absolute minimal resources are loaded by default. For example,
  | the database is not Mobile_detectnnected to automatically since no assumption
  | is made regarding whether you intend to use it.  This file lets
  | you globally define which systems you would like loaded with every
  | request.
  |
  | -------------------------------------------------------------------
  | Instructions
  | -------------------------------------------------------------------
  |
  | These are the things you can load automatically:
  |
  | 1. Packages
  | 2. Libraries
  | 3. Helper files
  | 4. Custom Mobile_detectnfig files
  | 5. Language files
  | 6. Models
  |
 */

/*
  | -------------------------------------------------------------------
  |  Auto-load Packges
  | -------------------------------------------------------------------
  | Prototype:
  |
  |  $autoload['packages'] = array(APPPATH.'third_party', '/usr/local/shared');
  |
 */

$autoload['packages'] = array();


/*
  | -------------------------------------------------------------------
  |  Auto-load Libraries
  | -------------------------------------------------------------------
  | These are the classes located in the system/libraries folder
  | or in your application/libraries folder.
  |
  | Prototype:
  |
  |	$autoload['libraries'] = array('database', 'session', 'xmlrpc');
 */

// database em todo o site
// session em todo o manager
$autoload['libraries'] = array('session', 'mobile_detect');


/*
  | -------------------------------------------------------------------
  |  Auto-load Helper Files
  | -------------------------------------------------------------------
  | Prototype:
  |
  |	$autoload['helper'] = array('url', 'file');
 */

// url em todo manager
// form quando precisa se fazer uma tela totalmente personalizada no manager ou fora dele
$autoload['helper'] = array('url', 'form', 'lang', 'error', 'shortcuts');


/*
  | -------------------------------------------------------------------
  |  Auto-load Config files
  | -------------------------------------------------------------------
  | Prototype:
  |
  |	$autoload['config'] = array('config1', 'config2');
  |
  | NOTE: This item is intended for use ONLY if you have created custom
  | config files.  Otherwise, leave it blank.
  |
 */

$autoload['config'] = array();


/*
  | -------------------------------------------------------------------
  |  Auto-load Language files
  | -------------------------------------------------------------------
  | Prototype:
  |
  |	$autoload['language'] = array('lang1', 'lang2');
  |
  | NOTE: Do not include the "_lang" part of your file.  For example
  | "codeigniter_lang.php" would be referenced as array('codeigniter');
  |
 */

// nao faz autoload dos idiomas! deixa que no controller específico
// quando for necessário se faça! É Fácil.
$autoload['language'] = array();


/*
  | -------------------------------------------------------------------
  |  Auto-load Models
  | -------------------------------------------------------------------
  | Prototype:
  |
  |	$autoload['model'] = array('model1', 'model2');
  |
 */

// nt_manager_permissoes => esta model provê os metodos para checagem de logn nos controllers que herdam
// de NT_Controller.
// nt_global_logs => é usado no sistema todo para reportando o que acontece
// nt_global_seo => Usado em toda parte pública do site
$autoload['model'] = array("nt_manager_permissoes",
                           "nt_global_logs",
                           "nt_global_seo",
                           "nt_global_parametros",
                           "nt_global_google_analytics",
                           "nt_manager_testmode",
                           "nt_global_favicon");


/* End of file autoload.php */
/* Location: ./application/config/autoload.php */