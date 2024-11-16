<?php
require_once './controller/UsuarioControlador.php';
require_once './controller/RedirectControlador.php';

$usuarioControlador = new UsuarioControlador();
$redirectControlador = new RedirectControlador();

// Acciones relacionadas con el usuario
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'registro':
            $usuarioControlador->Registro();
            break;
        case 'login':
            $usuarioControlador->Login();
            break;
        default:
            header('Location: ./view/inicio.php');
            exit();
    }
}

// Rutas amigables
elseif (isset($_GET['ruta'])) {
    $ruta = $_GET['ruta'];
    $redirectControlador->redirigir($ruta);
} else {
    // Redirige a la página de inicio por defecto si no hay parámetros
    //header('Location: ./view/inicio.php');
    $redirectControlador->redirigir('inicio');


    exit();
}
?>