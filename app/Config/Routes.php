<?php



namespace Config;



// Create a new instance of our RouteCollection class.

$routes = Services::routes();





// Load the system's routing file first, so that the app and ENVIRONMENT

// can override as needed.

if (is_file(SYSTEMPATH . 'Config/Routes.php')) {

    require SYSTEMPATH . 'Config/Routes.php';

}





/*

 * --------------------------------------------------------------------

 * Router Setup

 * --------------------------------------------------------------------

 */

$routes->setDefaultNamespace('App\Controllers');

$routes->setDefaultController('Home');

$routes->setDefaultMethod('index');

$routes->setTranslateURIDashes(false);

//$routes->set404Override(); //Pagina 404 por defecto

$routes->set404Override(function() {

    $dataHeader =['titulo' => 'Oops',
                'mensaje'=>"",];

        echo view("influencer/templates/header",$dataHeader);
        echo view('errors/html/404'); 
        echo view("influencer/templates/footerindex");
});

// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps

// where controller filters or CSRF protection are bypassed.

// If you don't want to define all routes, please use the Auto Routing (Improved).

// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.

// $routes->setAutoRoute(false);



/*

 * --------------------------------------------------------------------

 * Route Definitions

 * --------------------------------------------------------------------

 */





 



// We get a performance increase by specifying the default

// route since we don't have to scan directories.

//$routes->get('/', 'Home::index');

//$routes->get('/', 'IndexController::index');

//$routes->get('/registro', 'Influencer::registro');

//$routes->post('/crear', 'Influencer::crear');

//$routes->get('/eliminar/(:any)', 'Influencer::delete/$1');

//$routes->get('/actualizar/(:any)', 'Influencer::edit/$1');



$routes->get('/', 'IndexController::index');





$routes->get('contacto', 'IndexController::contacto');

$routes->get('nosotros', 'IndexController::nosotros');

$routes->get('/statement', 'IndexController::statement');

$routes->get('/aviso-de-privacidad', 'IndexController::privacidad');

$routes->get('/politica-de-tratamiento-de-datos', 'IndexController::politica');

$routes->get('/terminos-y-condiciones', 'IndexController::terminos');

$routes->get('/admin', 'dashboard\DashboardController::index');



$routes->group('influencer', static function ($routes) {



    $routes->get('new', 'InfluencerController::new');

    $routes->post('create', 'InfluencerController::create');


    $routes->get('edit/(:any)', 'InfluencerController::edit/$1');

    
    $routes->match(['get', 'post'], 'cambiarFoto', 'InfluencerController::cambiarFoto');

    //$routes->post('cambiarFoto', 'InfluencerController::cambiarFoto');

    $routes->post('agregarRedSocial', 'InfluencerController::agregarRedSocial');

    $routes->post('eliminarRedes', 'InfluencerController::elminarRedes');

    $routes->post('actualizarPerfil', 'InfluencerController::actualizarPerfil');

    $routes->post('elminarCategoria', 'InfluencerController::elminarCategoria');

    $routes->post('adicionarCategoria', 'InfluencerController::adicionarCategoria');

    $routes->post('eliminarLenguaje', 'InfluencerController::eliminarLenguaje');

    $routes->post('adicionarIdioma', 'InfluencerController::adicionarIdioma');

    $routes->post('eliminarVideo', 'InfluencerController::eliminarVideo');

    $routes->post('cambiarVideo', 'InfluencerController::cambiarVideo');

    $routes->post('eliminarFotoGaleria', 'InfluencerController::eliminarFotoGaleria');

    $routes->post('agregarFotoGaleria', 'InfluencerController::agregarFotoGaleria');

    $routes->post('editarResenia', 'InfluencerController::editarResenia');

    $routes->post('eliminarMarcas', 'InfluencerController::eliminarMarcas');

    $routes->post('eliminarPagos', 'InfluencerController::eliminarPagos');

    $routes->post('adicionarEmpresa', 'InfluencerController::adicionarEmpresa');

    $routes->post('adicionarPago', 'InfluencerController::adicionarPago');

    $routes->post('editarOferta', 'InfluencerController::editarOferta');

    $routes->post('eliminarMiCuenta', 'InfluencerController::eliminarMiCuenta');

    //$routes->get('new2/(:any)', 'InfluencerController::registro/$1');

    $routes->post('guardarRedesSociales', 'InfluencerController::guardarRedesSociales');

    //$routes->get('new3/(:any)', 'InfluencerController::registrofinal/$1');

    $routes->post('continuarregistro', 'InfluencerController::continuarregistro');

    $routes->get('mensajes/(:any)', 'InfluencerController::mensajesInfluencer/$1');

    $routes->get('eliminarmensaje/(:any)/(:any)', 'InfluencerController::eliminarMensajes/$1/$2');

    $routes->post('contactanos', 'InfluencerController::enviarMensajeContactanos');



});







$routes->get('privacidad', 'InfluencerController::privacidad');

$routes->get('registro-exitoso', 'InfluencerController::registroExitoso');

$routes->get('cuenta-eliminada', 'InfluencerController::cuenta_eliminada');

$routes->get('olvido', 'InfluencerController::olvidoClave');

$routes->post('enviartokens', 'InfluencerController::enviarTokens');

$routes->get('/perfil/(:any)', 'PerfilController::index/$1');

$routes->get('/validarCorreo/(:any)/(:any)', 'InfluencerController::validarCorreo/$1/$2');

$routes->get('/respassword/(:any)/(:any)', 'InfluencerController::restablecerClave/$1/$2');

$routes->post('/restaurarpassword', 'InfluencerController::actualizarClave');

$routes->post('/perfil/create', 'PerfilController::enviarMensajeAInfluencer');

$routes->post('/perfil/correo', 'PerfilController::enviarCorreoLocal');

$routes->get('/logout', 'IndexController::logout');



$routes->get('busqueda', 'UsuarioController::index');

$routes->post('busqueda/resultado', 'UsuarioController::buscarInfluencers');

$routes->post('busqueda/nuevoresultado', 'UsuarioController::nuevaBusquedaInfluencers');



$routes->get('noticia/(:any)', 'IndexController::buscarNoticia/$1');

$routes->get('noticia/(:any)', 'IndexController::buscarNoticia/$1');

$routes->post('/login', 'IndexController::login');

$routes->post('/representante', 'IndexController::solicitarRepresentante');

$routes->get('busqueda/resultado/(:any)', 'UsuarioController::busquedaPorCategoria/$1');







//RUTAS DE EJEMPLOS

$routes->get('/ejemplo', 'EnsayoController::index');

$routes->post('/crear', 'EnsayoController::crear');













$routes->group('dashboard', static function ($routes) {

    $routes->get('/', 'dashboard\DashboardController::indexAdmin');
    $routes->post('login', 'dashboard\DashboardController::loguinAdmin',['as'=>'logindash']);
    $routes->get('logout', 'dashboard\DashboardController::logout');
    $routes->post('eliminarComentario', 'dashboard\DashboardController::EliminarRepresentantes',['as'=>'eliminarComentariodash']);
    $routes->get('mensajeleido/(:any)', 'dashboard\DashboardController::mensajeLeido/$1');
    $routes->get('editarNoticia/(:any)', 'dashboard\DashboardController::editarNoticia/$1');
    $routes->post('actualizarNoticia', 'dashboard\DashboardController::actualizarNoticia',['as'=>'actualizardash']);
    $routes->post('eliminarNoticia', 'dashboard\DashboardController::eliminarNoticia',['as'=>'eliminarNotidash']);
    $routes->post('eliminarInfluencer', 'dashboard\DashboardController::eliminarInfluencer',['as'=>'eliminarInfludash']);
    $routes->post('enviarActivacionInfluencer', 'dashboard\DashboardController::enviarActivacionInfluencer',['as'=>'enviarActiInfludash']);
    $routes->post('enviarEstado_0', 'dashboard\DashboardController::enviarEstado_0',['as'=>'enviarEstado_0_dash']);
    $routes->post('enviarEstado_50', 'dashboard\DashboardController::enviarEstado_50',['as'=>'enviarEstado_50_dash']);
    $routes->post('enviarEstado_100', 'dashboard\DashboardController::enviarEstado_100',['as'=>'enviarEstado_100_dash']);
    $routes->get('enviarTodos/(:any)', 'dashboard\DashboardController::enviarTodos/$1');
    $routes->post('eliminarMensaje', 'dashboard\DashboardController::eliminarMensaje',['as'=>'eliminarMensajedash']);
    $routes->post('editarFoto', 'dashboard\DashboardController::cambiarFotoAdmin',['as'=>'cambiarFotoAdmindash']);
    $routes->post('editarNombre', 'dashboard\DashboardController::cambiarNombreCorreo',['as'=>'cambiarNombreAdmindash']);
    $routes->post('editarClave', 'dashboard\DashboardController::cambiarClave',['as'=>'cambiarClaveAdmindash']);
    $routes->get('idiomas', 'dashboard\DashboardController::idiomas',['as'=>'idiomasdash']);
    $routes->get('nuevoidioma', 'dashboard\DashboardController::nuevoIdioma',['as'=>'nuevoidiomadash']);
    $routes->post('crearnuevoidioma', 'dashboard\DashboardController::crearNuevoIdioma',['as'=>'crearnuevoidiomadash']);
    $routes->post('eliminarIdioma', 'dashboard\DashboardController::eliminarIdioma',['as'=>'eliminarIdiomdash']);
   
    
    $routes->get('influencers', 'dashboard\DashboardController::influencers',['as'=>'influencerdash']);

    $routes->get('estadisticas', 'dashboard\DashboardController::estadisticas',['as'=>'estadisticasdash']);
    $routes->get('mensajes', 'dashboard\DashboardController::mensajes',['as'=>'mensajesdash']);

    $routes->get('noticias', 'dashboard\DashboardController::noticias',['as'=>'noticiasdash']);
    $routes->get('comentarios', 'dashboard\DashboardController::comentarios',['as'=>'comentariosdash']);
    $routes->post('editarcomentarios', 'dashboard\DashboardController::editarComentarios',['as'=>'editarComentariosdash']);
    $routes->get('aprobarcomentarios/(:any)', 'dashboard\DashboardController::aprobarComentarios/$1');
    $routes->post('eliminarcomentarios', 'dashboard\DashboardController::eliminarComentarios',['as'=>'eliminarComentariosdash']);
    

    $routes->get('nuevanoticia', 'dashboard\DashboardController::nuevaNoticia',['as'=>'nuevanoticiadash']);

    $routes->post('crearnoticia', 'dashboard\DashboardController::crearnoticia',['as'=>'crearnoticiadash']);
    $routes->post('crearnuevanoticia', 'dashboard\DashboardController::crearNuevaNoticia',['as'=>'crearnuevanoticiadash']);
    $routes->get('categorias', 'dashboard\DashboardController::categorias',['as'=>'categoriasdash']);
    $routes->get('nuevacategoria', 'dashboard\DashboardController::nuevacategorias',['as'=>'nuevacategoriasdash']);
    $routes->post('crearnuevacategoria', 'dashboard\DashboardController::crearNuevaCategoria',['as'=>'crearnuevacategoriadash']);
    $routes->get('editarCategoria/(:any)', 'dashboard\DashboardController::editarCategoria/$1');
    $routes->post('editarnuevacategoria', 'dashboard\DashboardController::editarNuevaCategoria',['as'=>'editarnuevacategoriadash']);
    $routes->post('eliminarCategoria', 'dashboard\DashboardController::eliminarCategoria',['as'=>'eliminarCatdash']);
    $routes->get('representantes', 'dashboard\DashboardController::representantes',['as'=>'representantesdash']);

    $routes->get('cuenta', 'dashboard\DashboardController::cuenta',['as'=>'cuentasdash']);

    $routes->get('show', 'dashboard\DashboardController::show');

    

});







/*

 * --------------------------------------------------------------------

 * Additional Routing

 * --------------------------------------------------------------------

 *

 * There will often be times that you need additional routing and you

 * need it to be able to override any defaults in this file. Environment

 * based routes is one such time. require() additional route files here

 * to make that happen.

 *

 * You will have access to the $routes object within that file without

 * needing to reload it.

 */

if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {

    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';

}

