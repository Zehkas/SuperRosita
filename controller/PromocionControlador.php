<?php
require_once './model/promocion.php';

class PromocionControlador {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function IngresarPromocion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $codigo_departamento = $_POST['codigo_departamento'];
            $descuento = $_POST['descuento'];

            if (empty($codigo_departamento) || empty($descuento)) {
                $_SESSION['error_ingreso_promocion'] = 'Todos los campos son requeridos.';
                header('Location: /SuperRosita/perfil/promocion');
                exit;
            }

            if ($descuento < 1 || $descuento > 100) {
                $_SESSION['error_ingreso_promocion'] = 'El descuento debe estar entre 1 y 100.';
                header('Location: /SuperRosita/perfil/promocion');
                exit;
            }

            try {
                $promocion = new Promocion($this->db);
                $resultado = $promocion->agregarPromocion($codigo_departamento, $descuento);

                if ($resultado['exito']) {
                    $_SESSION['mensaje_exito_agregar'] = 'Promoción ingresada con éxito.';
                    header('Location: /SuperRosita/perfil/promocion');
                } else {
                    $_SESSION['error_ingreso_promocion'] = $resultado['mensaje_error'];
                    header('Location: /SuperRosita/perfil/promocion');
                }
            } catch (PDOException $e) {
                $_SESSION['error_ingreso_promocion'] = 'Error al ingresar la promoción: ' . $e->getMessage();
                header('Location: /SuperRosita/perfil/promocion');
            }

            exit;
        } else {
            header('Location: /SuperRosita/perfil');
            exit;
        }
    }
    public function QuitarPromocion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $codigo_departamento = $_POST['codigo_departamento'];

            try {
                $promocion = new Promocion($this->db);
                $resultado = $promocion->eliminarPromocion($codigo_departamento);

                if ($resultado['exito']) {
                    $_SESSION['mensaje_exito_quitar'] = 'Promoción quitada con exito.';
                    header('Location: /SuperRosita/perfil/promocion');
                } else {
                    $_SESSION['error_al_quitar_promocion'] = $resultado['mensaje_error'];
                    header('Location: /SuperRosita/perfil/promocion');
                }
            } catch (PDOException $e) {
                $_SESSION['error_al_quitar_promocion'] = 'Error al quitar la promoción: ' . $e->getMessage();
                header('Location: /SuperRosita/perfil/promocion');
            }

            exit;
        } else {
            header('Location: /SuperRosita/perfil');
            exit;
        }
    }
    public function PromocionesDisponibles() {
        $promocion = new Promocion($this->db);
        return $promocion->obtenerPromocionesDisponibles();
    }    
       
}
?>