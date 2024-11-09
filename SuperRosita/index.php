<?php
    require_once './controller/RedirectControlador.php';
    require_once './controller/UsuarioControlador.php';

    $redirectControlador = new RedirectControlador();
    $usuarioControlador = new UsuarioControlador();

    if (isset($_GET['redirect'])) {
        $redirectControlador->Redirect();
        
    } elseif (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'registro':
                $usuarioControlador->Registro();
                break;
            
            case 'login':
                $usuarioControlador->Login();
                break;
    
            default:
                header('Location: inicio.html');
                exit();
        }

    } else {
        header('Location: inicio.html');
        exit();
    }
    

?>