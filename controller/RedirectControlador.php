<?php
class RedirectControlador {
    public function redirigir($ruta) {
        switch (strtolower($ruta)) {
            case 'login':
                require_once __DIR__ . '/../view/login.php';
                break;
            case 'logout':
                require_once __DIR__ . '/../logout.php';
                break;
            case 'registro':
                require_once __DIR__ . '/../view/registro.php';
                break;
            case 'success':
                require_once __DIR__ . '/../view/success.php';
                break;
            case 'errorregistro':
                require_once __DIR__ . '/../view/errorregistro.php';
                break;
            case 'errorlogin':
                require_once __DIR__ . '/../view/errorlogin.php';
                break;
            case 'inicio':
                require_once __DIR__ . '/../view/inicio.php';
                break;
            case 'mas-productos':
                require_once __DIR__ . '/../view/mas-productos.php';
                break;
            default:
                require_once __DIR__ . '/../view/404.php';
                break;
        }
    }
}
