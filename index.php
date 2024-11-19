<?php
require_once './controller/UsuarioControlador.php';
require_once './controller/ProductoControlador.php';
require_once './controller/RedirectControlador.php';
require_once './controller/CarritoControlador.php';
require_once './controller/TrabajadorControlador.php';
require_once './connection.php';

// Crear conexión a la base de datos
$dbConnection = (new Connection())->connect();

// Crear instancias de los controladores con la conexión a la base de datos
$usuarioControlador = new UsuarioControlador($dbConnection);
$productoControlador = new ProductoControlador($dbConnection);
$redirectControlador = new RedirectControlador();
$carritoControlador = new CarritoControlador($dbConnection);
$trabajadorControlador = new TrabajadorControlador($dbConnection);

// Acciones relacionadas con el usuario
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'registro':
            $usuarioControlador->Registro();
            break;
        case 'login':
            $usuarioControlador->Login();
            break;
        case 'registro_trabajador':
            $trabajadorControlador->IngresarTrabajador();
            break;
        case 'registroProducto':
            $productoControlador->RegistroProducto();
            break;
        case 'editarProducto':
            $productoControlador->EditarProducto();
            break;
        case 'eliminarProducto':
            $productoControlador->EliminarProducto();
            break;
        case 'agregarAlCarrito':
            $data = json_decode(file_get_contents('php://input'), true);
            $codigoProducto = $data['codigoProducto'];
            $cantidad = $data['cantidad'];
            $idCliente = $_SESSION['codigo_cliente'];

            if ($idCliente) {
                $productoControlador->AgregarAlCarrito($codigoProducto, $idCliente, $cantidad);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Cliente no autenticado']);
            }
            exit();
        case 'verCarrito':
            $idCliente = $_SESSION['codigo_cliente'];
            if ($idCliente) {
                $carrito = $carritoControlador->obtenerCarritoPendiente($idCliente);
                echo json_encode($carrito);
            } else {
                echo json_encode([]);
            }
            exit();
        case 'eliminarProductoCarrito':
            $data = json_decode(file_get_contents('php://input'), true);
            $codigoProducto = $data['codigoProducto'];
            $idCliente = $_SESSION['codigo_cliente'];

            if ($idCliente) {
                $carritoControlador->actualizarEstadoProducto($codigoProducto, $idCliente, 3);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Cliente no autenticado']);
            }
            exit();
        case 'completarCompra':
            $idCliente = $_SESSION['codigo_cliente'];
            if ($idCliente) {
                $carritoControlador->completarCompra($idCliente);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Cliente no autenticado']);
            }
            exit();
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
    $redirectControlador->redirigir('inicio');
    exit();
}
?>
