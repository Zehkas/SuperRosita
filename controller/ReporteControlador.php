<?php
require_once __DIR__ . '/../model/reportes.php';
require_once __DIR__ . '/../connection.php';

class ReporteControlador {
    private $reportesModel;

    public function __construct() {
        $dbConnection = (new Connection())->connect();
        $this->reportesModel = new Reportes($dbConnection);
    }

    public function obtenerReporte() {
        if (isset($_GET['numero'])) {
            switch ($_GET['numero']) {
                case 1:
                    $this->obtenerProductosMasVendidos();
                    break;
                case 2:
                    $this->obtenerProductosConMasDevoluciones();
                    break;
                case 3:
                    $this->obtenerClientesMasFieles();
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Reporte no válido']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Número de reporte no proporcionado']);
        }
    }

    private function obtenerProductosMasVendidos() {
        $productos = $this->reportesModel->obtenerProductosMasVendidos();
        if (!empty($productos)) {
            echo json_encode(['success' => true, 'reportes' => $productos]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontraron productos más vendidos']);
        }
    }

    private function obtenerProductosConMasDevoluciones() {
        $productos = $this->reportesModel->obtenerProductosConMasDevoluciones();
        if (!empty($productos)) {
            echo json_encode(['success' => true, 'reportes' => $productos]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontraron productos con más devoluciones']);
        }
    }

    private function obtenerClientesMasFieles() {
        $clientes = $this->reportesModel->obtenerClientesMasFieles();
        if (!empty($clientes)) {
            echo json_encode(['success' => true, 'reportes' => $clientes]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontraron clientes más fieles']);
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'obtenerReporte') {
    $reporteControlador = new ReporteControlador();
    $reporteControlador->obtenerReporte();
}
