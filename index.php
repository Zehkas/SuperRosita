<?php
    require_once './controller/UsuarioControlador.php';

    $usuarioControlador = new UsuarioControlador();

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

    } else {
        header('Location: ./view/inicio.php');
        exit();
    }
    

?>