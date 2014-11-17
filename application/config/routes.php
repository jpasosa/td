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

$route['default_controller'] = "front_home/homepage";
$route['homepage'] = "front_home/homepage";

$route['404_override'] = '';

# MODELOS
$route['models'] = 'all_models';
$route['models/(:any)'] = 'all_models/$1';
$route['models/(:any)/(:any)'] = 'all_models/$1/$2';



# FRONTEND -- TRABAJOS DESTACADOS
$route['destacados'] = 'front_destacados';
$route['destacados/(:any)'] = 'front_destacados/$1';
$route['destacados/(:any)/(:any)'] = 'front_destacados/$1/$2';

# FRONTEND -- TU CUENTA
$route['tucuenta'] = 'front_tucuenta';
$route['tucuenta/(:any)'] = 'front_tucuenta/$1';
$route['tucuenta/(:any)/(:any)'] = 'front_tucuenta/$1/$2';
$route['tucuenta/(:any)/(:any)/(:any)'] = 'front_tucuenta/$1/$2/$3';
$route['tucuenta/(:any)/(:any)/(:any)/(:any)'] = 'front_tucuenta/$1/$2/$3/$4';



# FRONTEND -- TRABAJOS DESTACADOS
$route['temas'] = 'front_topics';
$route['temas/(:any)'] = 'front_topics/$1';
$route['temas/(:any)/(:any)'] = 'front_topics/$1/$2';

# FRONTEND -- BENEFICIOS
$route['beneficios'] = 'front_templates/view';


# TRABAJOS -----------------------------------------------------------------------------------

# FRONTEND -- TRABAJOS  |  ALTA DE TRABAJOS
$route['trabajos/alta'] = 'front_works/add';
$route['trabajos/alta/(:any)'] = 'front_works/add/$1';
$route['trabajos/alta/(:any)/(:any)'] = 'front_works/add/$1/$2';
# FRONTEND -- TRABAJOS  |  EDITAR UN TRABAJO
$route['trabajos/editar'] = 'front_works/edit';
$route['trabajos/editar/(:any)'] = 'front_works/edit/$1';
$route['trabajos/editar/(:any)/(:any)'] = 'front_works/edit/$1/$2';
# FRONTEND -- TRABAJOS  |  DETALLE DEL TRABAJO
$route['trabajos/ver'] = 'front_works/show';
$route['trabajos/ver/(:any)'] = 'front_works/show/$1';
# FRONTEND -- TRABAJOS  |  DETALLE DEL TRABAJO
$route['trabajos/buscar'] = 'front_works/buscar';
$route['trabajos/buscar/(:any)'] = 'front_works/buscar/$1';
# FRONTEND -- TRABAJOS
$route['trabajos'] = 'front_works';
$route['trabajos/(:any)'] = 'front_works/$1';
$route['trabajos/(:any)/(:any)'] = 'front_works/$1/$2';




# FRONTEND -- LOGIN
$route['login'] = 'front_login';
$route['login/(:any)'] = 'front_login/$1';
$route['login/(:any)/(:any)'] = 'front_login/$1/$2';
$route['logout'] = 'front_login/logout';




# BACKEND -- LOGUEO
$route['admin'] = 'admin';
$route['admin/login'] = 'admin_login';
$route['admin/login/(:any)'] = 'admin_login/$1';
$route['admin/login/(:any)/(:any)'] = 'admin_login/$1/$2';

# BACKEND -- USUARIOS
$route['admin/usuarios'] = 'admin_usuarios';
$route['admin/usuarios/(:any)'] = 'admin_usuarios/$1';
$route['admin/usuarios/(:any)/(:any)'] = 'admin_usuarios/$1/$2';
$route['admin/usuarios/(:any)/(:any)/(:any)'] = 'admin_usuarios/$1/$2/$3';

# BACKEND -- TRABAJOS
$route['admin/trabajos'] = 'admin_trabajos';
$route['admin/trabajos/(:any)'] = 'admin_trabajos/$1';
$route['admin/trabajos/(:any)/(:any)'] = 'admin_trabajos/$1/$2';
$route['admin/trabajos/(:any)/(:any)/(:any)'] = 'admin_trabajos/$1/$2/$3';

# BACKEND -- CATEGORIAS
$route['admin/categorias'] = 'admin_categorias';
$route['admin/categorias/(:any)'] = 'admin_categorias/$1';
$route['admin/categorias/(:any)/(:any)'] = 'admin_categorias/$1/$2';
$route['admin/categorias/(:any)/(:any)/(:any)'] = 'admin_categorias/$1/$2/$3';

# BACKEND -- COMPRAS
$route['admin/compras'] = 'admin_compras';
$route['admin/compras/(:any)'] = 'admin_compras/$1';
$route['admin/compras/(:any)/(:any)'] = 'admin_compras/$1/$2';
$route['admin/compras/(:any)/(:any)/(:any)'] = 'admin_compras/$1/$2/$3';

# BACKEND -- PAGOS
$route['admin/pagos'] = 'admin_pagos';
$route['admin/pagos/(:any)'] = 'admin_pagos/$1';
$route['admin/pagos/(:any)/(:any)'] = 'admin_pagos/$1/$2';
$route['admin/pagos/(:any)/(:any)/(:any)'] = 'admin_pagos/$1/$2/$3';

# BACKEND -- ESTADISTICAS
$route['admin/estadisticas'] = 'admin_estadisticas';
$route['admin/estadisticas/(:any)'] = 'admin_estadisticas/$1';
$route['admin/estadisticas/(:any)/(:any)'] = 'admin_estadisticas/$1/$2';
$route['admin/estadisticas/(:any)/(:any)/(:any)'] = 'admin_estadisticas/$1/$2/$3';


# BACKEND -- PRECIOS  |  LISTA
$route['admin/precios/listar'] = 'admin_precios/listing';
$route['admin/precios/listar/(:any)'] = 'admin_precios/listing/$1';
$route['admin/precios/listar/(:any)/(:any)'] = 'admin_precios/listing/$1/$2';
$route['admin/precios/listar/(:any)/(:any)/(:any)'] = 'admin_precios/listing/$1/$2';
# BACKEND -- PRECIOS  |  NUEVO
$route['admin/precios/nuevo'] = 'admin_precios/add';
$route['admin/precios/nuevo/(:any)'] = 'admin_precios/add/$1';
# BACKEND -- PRECIOS  |  EDITAR
$route['admin/precios/editar'] = 'admin_precios/edit';
$route['admin/precios/editar/(:any)'] = 'admin_precios/edit/$1';
$route['admin/precios/editar/(:any)/(:any)'] = 'admin_precios/edit/$1/$2';
# BACKEND -- PRECIOS  |  ELIMINAR
$route['admin/precios/eliminar'] = 'admin_precios/erase';
$route['admin/precios/eliminar/(:any)'] = 'admin_precios/erase/$1';
$route['admin/precios/eliminar/(:any)/(:any)'] = 'admin_precios/erase/$1/$2';
# BACKEND -- PRECIOS GENERAL
$route['admin/precios'] = 'admin_precios';
$route['admin/precios/(:any)'] = 'admin_precios/$1';
$route['admin/precios/(:any)/(:any)'] = 'admin_precios/$1/$2';
$route['admin/precios/(:any)/(:any)/(:any)'] = 'admin_precios/$1/$2/$3';


# modulo TESTING (pruebas mias)
$route['testing'] = 'testing';
$route['testing/(:any)'] = 'testing/$1';



$route['admin/pepe'] = 'adminpepe';








/* End of file routes.php */
/* Location: ./application/config/routes.php */