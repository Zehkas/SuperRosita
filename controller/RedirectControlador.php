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

            case 'inicio':
                require_once __DIR__ . '/../view/inicio.php';
                break;

            case 'mas-productos':
                require_once __DIR__ . '/../view/mas-productos.php';
                break;

            case 'carrito': 
                require_once __DIR__ . '/../view/carrito.php';
                break;

            case 'perfil':
                require_once __DIR__ . '/../view/perfil/general.php';
                break;

            case 'perfil/historial':
                require_once __DIR__ . '/../view/perfil/historial.php';
                break;

            case 'perfil/devolucion':
                require_once __DIR__ . '/../view/perfil/devolucion.php';
                break;
            
            case 'perfil/gestion-devoluciones':
                require_once __DIR__ . '/../view/perfil/gestion-devoluciones.php';
                break;

            case 'perfil/boleta':
                require_once __DIR__ . '/../view/perfil/boleta.php';
                break;
            case 'perfil/ajustes':
                require_once __DIR__ . '/../view/perfil/ajustes.php';
                break;
            case 'perfil/inventario':
                require_once __DIR__ . '/../view/perfil/inventario.php';
                break;
            case 'perfil/ingresar-trabajador':
                require_once __DIR__ . '/../view/perfil/ingresar-trabajador.php';
                break;
            case 'perfil/reportes':
                require_once __DIR__ . '/../view/perfil/reportes.php';
                break;
            case 'perfil/promocion':
                require_once __DIR__ . '/../view/perfil/promocion.php';
                break;
            default:
                require_once __DIR__ . '/../view/404.php';
                break; 
        }
    }
}
?>
