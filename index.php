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
            $codigoProducto = $data['codigoProducto'] ?? null;
            $cantidad = $data['cantidad'] ?? null;
            $idCliente = $_SESSION['codigo_cliente'];

            if ($idCliente && $codigoProducto !== null && $cantidad !== null) {
                $productoControlador->AgregarAlCarrito($codigoProducto, $idCliente, $cantidad);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Datos faltantes o cliente no autenticado']);
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
            $codigoProducto = $data['codigoProducto'] ?? null;
            $idCliente = $_SESSION['codigo_cliente'];

            if ($idCliente && $codigoProducto !== null) {
                $carritoControlador->eliminarProducto($codigoProducto, $idCliente);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Datos faltantes o cliente no autenticado']);
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
        case 'editarReembolso':
            $codigoCarrito = $_POST['codigoCarrito'] ?? null;
            $descripcion = $_POST['descripcion'] ?? null;

            if (isset($_SESSION['codigo_cliente']) && $codigoCarrito !== null && $descripcion !== null) {
                $carritoControlador->editarReembolso($codigoCarrito, $descripcion);
                header('Location: /SuperRosita/perfil/devolucion'); // Redirigir a la página de devoluciones
                exit();
            } else {
                echo json_encode(['success' => false, 'message' => 'Datos faltantes o cliente no autenticado']);
            }
            exit();
        case 'cancelarReembolso':
            $data = json_decode(file_get_contents('php://input'), true);
            $codigoCarrito = $data['codigoCarrito'] ?? null;

            if (isset($_SESSION['codigo_cliente']) && $codigoCarrito !== null) {
                $carritoControlador->cancelarReembolso($codigoCarrito);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Datos faltantes o cliente no autenticado']);
            }
            exit();
        case 'aprobarReembolso':
            $data = json_decode(file_get_contents('php://input'), true);
            $codigoCarrito = $data['codigoCarrito'] ?? null;

            if ($codigoCarrito !== null) {
                $carritoControlador->aprobarReembolso($codigoCarrito);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Datos faltantes']);
            }
            exit();
        case 'rechazarReembolso':
            $data = json_decode(file_get_contents('php://input'), true);
            $codigoCarrito = $data['codigoCarrito'] ?? null;

            if ($codigoCarrito !== null) {
                $carritoControlador->rechazarReembolso($codigoCarrito);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Datos faltantes']);
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
