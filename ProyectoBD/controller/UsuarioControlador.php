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
                header("Location: ./view/success.php"); // Redirige a una página de éxito
                exit();
            } else {
                header("Location: ./view/inicio.php"); // Cambiar la página para el error
                exit();
            }
        }
    }

    public function Login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $correo = $_POST['email'];
            $contrasena = $_POST['password'];

            $usuario = new Usuario();
            $resultado = $usuario->validarCliente($correo, $contrasena);

            if ($resultado) {
                $_SESSION['usuario'] = $correo;
                header("Location: ./view/success.php");
                exit();
            } else {
                header("Location: ./view/errorlogin.php");
                exit();
            }
        }
    }
}
?>
