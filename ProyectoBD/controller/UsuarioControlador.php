<?php
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

            // Crear una instancia del modelo Usuario
            $usuario = new Usuario();
            
            // Llamar al método agregarCliente del modelo
            $resultado = $usuario->agregarCliente($region, $correo, $nombre, $apellido1, $apellido2, $contrasena);

            if ($resultado) {
                header("Location: ./view/success.php"); // Redirige a una página de éxito
                exit();
            } else {
                echo "Error al registrar el cliente.";
            }
        }
    }

    public function Login() {
        // Lógica para el login del usuario
    }
}
?>
