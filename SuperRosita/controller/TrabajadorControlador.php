<?php
require_once './model/trabajador.php';

class TrabajadorControlador {
    public function IngresarTrabajador() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Obtener los datos enviados desde el formulario
            $nombre = $_POST['nombre'];
            $apellido1 = $_POST['apellido1'];
            $apellido2 = $_POST['apellido2'];
            $fecha_contrato = $_POST['fecha_contrato'];
            $codigo_departamento = $_POST['codigo_departamento'];
            $codigo_cargo = $_POST['codigo_cargo'];

            // Validar que los campos obligatorios no estén vacíos
            if (empty($nombre) || empty($apellido1) || empty($apellido2) || empty($codigo_departamento) || empty($codigo_cargo)) {
                $_SESSION['error_ingreso'] = "Todos los campos son obligatorios.";
                header("Location: /SuperRosita/ingresar-trabajador");
                exit();
            }

            // Validar que el código del departamento sea válido
            $departamentos_validos = ['11','13']; 
            if (!in_array($codigo_departamento, $departamentos_validos)) {
                $_SESSION['error_ingreso'] = "Error: Seleccione un departamento válido.";
                header("Location: /SuperRosita/ingresar-trabajador");
                exit();
            }

            // Validar que el código del cargo sea válido
            $cargos_validos = ['2', '3']; // Cargos válidos: 2 (Cajero) y 3 (Reponedor)
            if (!in_array($codigo_cargo, $cargos_validos)) {
                $_SESSION['error_ingreso'] = "Error: Seleccione un cargo válido.";
                header("Location: /SuperRosita/ingresar-trabajador");
                exit();
            }

            // Crear una instancia del modelo Trabajador
            $trabajador = new Trabajador();

            // Llamar al método agregarTrabajador del modelo
            $resultado = $trabajador->agregarTrabajador($nombre, $apellido1, $apellido2, $codigo_departamento, $codigo_cargo);

            if ($resultado) {
                $_SESSION['exito_registro_trabajador'] = "Trabajador Ingresado!!";
                // Elimina el mensaje de éxito inmediatamente después de configurarlo
                $mensaje_exito = $_SESSION['exito_registro_trabajador'];
                unset($_SESSION['exito_registro_trabajador']);
                // Redirecciona con el mensaje como parámetro GET
                header("Location: /SuperRosita/perfil/ingresar-trabajador?mensaje_exito=" . urlencode($mensaje_exito));
                exit();
            }
            
             else {
                $_SESSION['error_ingreso'] = "Error al ingresar el trabajador.";
                header("Location: /SuperRosita/ingresar-trabajador");
                exit();
            }
        }
    }
}
