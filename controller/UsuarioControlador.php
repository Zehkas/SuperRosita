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
                    $_SESSION['fecha_contrato'] = $datos['FECHA_CONTRATO_TRABAJADOR'];
                    $_SESSION['codigo_departamento'] = $datos['CODIGO_DEPARTAMENTO'];
                    $_SESSION['codigo_cargo'] = $datos['CODIGO_CARGO_TRABAJADOR'];
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

    public function cambioContrasena(){
        $contrasena = $_POST['password'];
        $oldPassword = $_POST['oldPassword'];
        $correo = $_SESSION['usuario'];
        $usuario = new Usuario();


        $esTrabajador = str_ends_with($correo, '@superrosita.cl');
        $esValida = false;

        if ($esTrabajador) {
            $esValida = $usuario->validarContrasenaTrabajador($correo, $oldPassword);

        } else {
            $esValida = $usuario->validarContrasenaCliente($correo, $oldPassword);
        }

        if (!$esValida) {
            $_SESSION['error_cambio'] = "La contraseña actual no es correcta.";
            header("Location: /SuperRosita/perfil/ajustes");
            exit();
        }

        $resultado = false;

        if ($esTrabajador){
            $resultado = $usuario->cambiarContrasenaTrabajador($correo, $contrasena);

        } else {
            $resultado = $usuario->cambiarContrasenaCliente($correo, $contrasena);
        }

        if ($resultado) {
        $_SESSION['mensaje_exito'] = "La contraseña ha sido cambiada!";
            header("Location: /SuperRosita/perfil/ajustes");
            exit();
        }


        $_SESSION['error_cambio'] = "Error al cambiar de Contraseña";
        header("Location: /SuperRosita/perfil/ajustes");
        exit();
    }
}
?>