<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['importar_seminovos/(:any)'] = "site/importar_seminovos/index/$1";

$route['leads'] = "site/leads";

$route['seminovos'] = "site/seminovos/index";
$route['seminovos/ajx_modelos'] = "seminovos/ajx_modelos";
$route['seminovos/([0-9]+)/(:any)'] = "site/seminovos/interna/$1";
$route['seminovos/(:any)-([0-9]+)'] = "site/seminovos/interna/$2";

$route['modelos/([0-9]+)'] = "site/ajax/modelos/$1";

$route['getCarros'] = "site/home/getCarros";
$route['getModelosSistema'] = "site/home/getModelosSistema";

$route['cidades/([A-Z]+)'] = "site/ajax/cidades/$1";

$route['vendas-diretas'] = "site/vendas_diretas/index";
$route['vendas-diretas/detalhes'] = "site/vendas_diretas/detalhes";

$route['graph/(:any)'] = "graph/index/$1";


////////// ------------------------- inicio rotas manager ----------------------- /////////
// manager/qualquer coisa aponta para o controller m
$route['manager'] = "manager/m_index"; // qualquer coisa que seja exato igual a manager
$route['manager/([a-z,0-9,_]+)'] = "manager/m_$1"; // manager/controller, controller pode conter nros no nome ex: foo35save
$route['manager/([a-z,0-9,_]+)/([a-z,0-9,_]+)'] = "manager/m_$1/$2"; // manager/controller/metodo/
$route['manager/([a-z,0-9,_]+)/([a-z,0-9,_]+)/(:any)'] = "manager/m_$1/$2/$3"; // manager/controller/metodo/ n params



////////// ------------------------- fim rotas manager ----------------------- /////////






// SE o site tiver apenas UM idioma e o /pt ou /en não deve aparecer na URL
// 
// Troque o valor de default_controller para o seguinte:
// 
// $route['default_controller'] = "site/home/index"; 
// 
// 
// Se tiver mobile, declare primeiro as rotas do mobile:
// 
// $route['^mobile'] = "mobile/home";
// $route['^mobile/(.+)$'] = "mobile/$1";
// 
// e depois as rotas do site classico (que obviamente não poderá 
// ter um controller chamado "mobile"):
// 
$route['^(.+)$'] = "site/$1";
// 
// 
// Pode comentar/excluír daí as demais rotas até antes
// do 404_override (que permanece)
// 
// Tire dos controllers a referência para setar o idioma a partir da URL
//  ou passe fixo


//Rota default para o site
$route['default_controller'] = "site/home/index"; 

//Rota default para o teaser
// $route['default_controller'] = "teaser/home/index"; 




////////// ------------------------- inicio rotas site classico ----------------------- /////////

/* Rotas para os idiomas disponiveis. Sites Clássicos
 
 basicamente ignora o primeiro segmento na hora de chamar o controller
 mas guarda essa informacao em $this->uri->segments[1], de onde pode ser resgatada 
 e usada para alguma coisa nos controllers
 
 */

// pt habilitado por padrão

//$route['([a-z,_,0-9,-]+)/([0-9]+)'] = "site/home/veiculo/$1/$2";

//$route['^pt'] = "site/home/index/pt"; // site.com.br -> site/home/pt

// en habilitado por padrão
//$route['^en'] = "site/home/index/en";
//$route['^en/(.+)$'] = "site/$1";



// es habilitado por padrão
//$route['^es'] = "site/home/index/es";
//$route['^es/(.+)$'] = "site/$1";

////////// ------------------------- fim rotas site classico ----------------------- /////////







////////// ------------------------- inicio rotas mobile ----------------------- /////////

/* Regras de revrite para o mobile, questão dos idiomas, descomente se houver mobile
   É a mesma coisa do site normal, mas quando a url começa com mobile, aí cai o idioma
*/

//$route['^mobile/pt'] = "mobile/home/pt";
//$route['^mobile/pt/(.+)$'] = "mobile/$1";

// add aqui as outras rotas do mobile... nos outros idiomas.

////////// ------------------------- fim rotas mobile ----------------------- /////////




$route['404_override'] = 'error404';

/* End of file routes.php */
/* Location: ./application/config/routes.php */