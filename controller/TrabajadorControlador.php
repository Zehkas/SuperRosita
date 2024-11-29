<?php
require_once './model/trabajador.php';

class TrabajadorControlador {

    public function IngresarTrabajador() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'];
            $apellido1 = $_POST['apellido1'];
            $apellido2 = $_POST['apellido2'];
            $fecha_contrato = $_POST['fecha_contrato'];
            $codigo_departamento = $_POST['codigo_departamento'];
            $codigo_cargo = $_POST['codigo_cargo'];

            if (empty($nombre) || empty($apellido1) || empty($apellido2) || empty($codigo_departamento) || empty($codigo_cargo)) {
                $_SESSION['error_ingreso'] = "Todos los campos son obligatorios.";
                header("Location: /SuperRosita/ingresar-trabajador");
                exit();
            }

            $departamentos_validos = ['11','13']; 
            if (!in_array($codigo_departamento, $departamentos_validos)) {
                $_SESSION['error_ingreso'] = "Error: Seleccione un departamento válido.";
                header("Location: /SuperRosita/ingresar-trabajador");
                exit();
            }

            $cargos_validos = ['2', '3'];
            if (!in_array($codigo_cargo, $cargos_validos)) {
                $_SESSION['error_ingreso'] = "Error: Seleccione un cargo válido.";
                header("Location: /SuperRosita/ingresar-trabajador");
                exit();
            }

            $trabajador = new Trabajador();

            $resultado = $trabajador->agregarTrabajador($nombre, $apellido1, $apellido2, $codigo_departamento, $codigo_cargo);

            if ($resultado) {
                $_SESSION['exito_registro_trabajador'] = "Trabajador Ingresado!!";
                $mensaje_exito = $_SESSION['exito_registro_trabajador'];
                unset($_SESSION['exito_registro_trabajador']);
                header("Location: /SuperRosita/perfil/ingresar-trabajador?mensaje_exito=" . urlencode($mensaje_exito));
                exit();
            }else {
                $_SESSION['error_ingreso'] = "Error al ingresar el trabajador.";
                header("Location: /SuperRosita/ingresar-trabajador");
                exit();
            }
        }
    }
}