<?php
session_start();
require_once './model/usuario.php';

class UsuarioControlador {
    public function Registro() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Obtener los datos enviados desde el formulario
            $nombre = $_POST['nombre'];
            $apellido1 = $_POST['apellido1'];
            $apellido2 = $_POST['apellido2'];
            $correo = $_POST['correo'];
            $region = $_POST['region'];
            $contrasena = $_POST['contrasena'];
            
            // Validar que el código de región sea correcto
            $regiones_validas = ['1', '2']; // Agrega los códigos de región válidos
            if (!in_array($region, $regiones_validas)) {
                echo "Error: Seleccione una región válida.";
                exit();
            }

            // Crear una instancia del modelo Usuario
            $usuario = new Usuario();
            
            // Llamar al método agregarCliente del modelo
            $resultado = $usuario->agregarCliente($region, $correo, $nombre, $apellido1, $apellido2, $contrasena);

            if ($resultado) {
                header("Location: /SuperRosita/success"); // Redirige a una página de éxito
                exit();
            } else {
                $_SESSION['error_registro'] = "Error al registrarse";
                header("Location: /SuperRosita/registro");
                exit();
            }
        }
    }

    public function Login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $correo = $_POST['email'];
            $contrasena = $_POST['password'];
            

            $usuario = new Usuario();
            $esTrabajador = str_ends_with($correo, '@superrosita.cl');

            if ($esTrabajador){

                $resultado = $usuario->validarTrabajador($correo, $contrasena);
                $datos = $usuario->obtenerDatosTrabajador($correo);

                if ($resultado){

                    $_SESSION['codigo_trabajador'] = $datos['CODIGO_TRABAJADOR'];
                    $_SESSION['nombre'] = $datos['NOMBRE_TRABAJADOR'];
                    $_SESSION['apellido1'] = $datos['APELLIDO1_TRABAJADOR'];
                    $_SESSION['apellido2'] = $datos['APELLIDO2_TRABAJADOR'];
                    $_SESSION['fecha_contrato'] = $datosTrabajador['FECHA_CONTRATO_TRABAJADOR'];
                    $_SESSION['codigo_departamento'] = $datosTrabajador['CODIGO_DEPARTAMENTO'];
                    $_SESSION['codigo_cargo'] = $datosTrabajador['CODIGO_CARGO_TRABAJADOR'];
                    $_SESSION['usuario'] = $correo;
                    header("Location: /SuperRosita/success");
                    exit();
                }

            } else {

                $resultado = $usuario->validarCliente($correo, $contrasena);
                $datos = $usuario->obtenerDatosCliente($correo);

                if ($resultado) {
                    $_SESSION['codigo_cliente'] = $datos['CODIGO_CLIENTE'];
                    $_SESSION['nombre'] = $datos['NOMBRE_CLIENTE'];
                    $_SESSION['apellido1'] = $datos['APELLIDO1_CLIENTE'];
                    $_SESSION['apellido2'] = $datos['APELLIDO2_CLIENTE'];
                    $_SESSION['usuario'] = $correo;
                    header("Location: /SuperRosita/success");
                    exit();
                }
            }


            $_SESSION['error_login'] = "Error al iniciar sesion, Correo o contraseña incorrectos";
            header("Location: /SuperRosita/login");
            exit();
        }
    }
}
?>