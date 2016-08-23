<?php 
session_start();
if (!extension_loaded("soap")) 
{
   die("Extension soap not loaded\n");
}

$url					= "glpi/plugins/webservices/soap.php";
$host					= "localhost";
$args['host'] 			= $host;
$args['login_name'] 	= "glpi";
$args['login_password'] = "glpi";
$args['method']			= "glpi.doLogin";
$args['url']			= $url;
//llamado al cliente
$client = new SoapClient(null, array('uri'      => 'http://' . $host . '/' . $url,
                                     'location' => 'http://' . $host . '/' . $url));


$result = $client->__soapCall('genericExecute', array(new SoapParam($args, 'params')));
//al generar el logueo debo guardar la sessión en una variables de sesión
$_SESSION['nioGLPI']	=	$result['session'];

//ahora para poder crear un ticket hay una serie de métodos que hay que consultar para poder armar un buen servicio web.
//consulto el listado de tipos de ticket
$argsTipo['session'] 		= $_SESSION['nioGLPI'];	
$argsTipo['method']			= "glpi.listDropdownValues";
$argsTipo['dropdown']		= "TicketType";

$listadoTipos			= $client->__soapCall('genericExecute', array(new SoapParam($argsTipo, 'params')));
//resultado
//Array ( [0] => Array ( [id] => 1 [name] => Incidencia ) [1] => Array ( [id] => 2 [name] => Requerimiento ) )

//ahora consulto las categorías
/*
$argsCat['session'] 		= $_SESSION['nioGLPI'];	
$argsCat['method']			= "glpi.listDropdownValues";
$argsCat['dropdown']		= "TicketType";
//$argsCat['helpdesk']		= 'is_helpdeskvisible';
$argsCat['criteria']		= 'is_true';
$categorias			= $client->__soapCall('genericExecute', array(new SoapParam($argsCat, 'params')));
var_dump($categorias);*/


//envio el ticket
$argsCre['session']			=	$_SESSION['nioGLPI'];
$argsCre['method']			=	'glpi.createTicket';
$argsCre['type']			=	1;
$argsCre['category']		=	1;
$argsCre['title']			=	"Ayuda!! esto es una prueba";
$argsCre['content']			=	"Estamos probando el nuevo sistema de tickets, esperemos que funcione!!";
$ticket						= $client->__soapCall('genericExecute', array(new SoapParam($argsCre, 'params')));
var_dump($ticket);
?>