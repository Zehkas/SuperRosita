<?php
require_once __DIR__ . '/../model/inventario.php';
require_once __DIR__ . '/../connection.php';

class InventarioControlador {
    private $db;
    private $inventarioModel;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
        $this->inventarioModel = new Inventario($dbConnection);
    }

    public function obtenerInventario() {
        $inventario = $this->inventarioModel->obtenerVerMasInventario();

        if (!empty($inventario)) {
            echo json_encode(['success' => true, 'inventario' => $inventario]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontraron registros de inventario']);
        }
    }

    public function reportarVentas() {
        if (isset($_POST['codigoInventario'], $_POST['cantidad'])) {
            $codigoInventario = $_POST['codigoInventario'];
            $cantidadVendida = $_POST['cantidad'];

            $resultado = $this->inventarioModel->reportarVentas($codigoInventario, $cantidadVendida);

            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Ventas reportadas correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al reportar ventas', 'error' => $this->inventarioModel->getLastError()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        }
    }

    public function realizarRestock() {
        if (isset($_POST['codigoInventario'], $_POST['cantidad'])) {
            $codigoInventario = $_POST['codigoInventario'];
            $cantidadRestock = $_POST['cantidad'];

            $resultado = $this->inventarioModel->realizarRestock($codigoInventario, $cantidadRestock);

            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Restock realizado correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al realizar restock', 'error' => $this->inventarioModel->getLastError()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        }
    }
}

$dbConnection = (new Connection())->connect();
$controller = new InventarioControlador($dbConnection);

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'obtenerInventario':
            $controller->obtenerInventario();
            break;
        case 'reportarVentas':
            $controller->reportarVentas();
            break;
        case 'realizarRestock':
            $controller->realizarRestock();
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Acción no proporcionada']);
}
?>
