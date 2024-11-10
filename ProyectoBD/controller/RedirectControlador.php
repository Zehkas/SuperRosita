<?php
class RedirectControlador{
    public function Redirect() {
        if (isset($_GET['redirect'])) {
            switch ($_GET['redirect']) {
                case 'login':
                    header("Location: ./view/login.php");
                    break;
        
                case 'registro':
                    header("Location: ./view/registro.php");
                    break;
        
                case 'verMas':
                    header("Location: ./view/mas-productos.html");
                    break;
        
                case 'inicio':
                    header("Location: ./view/inicio.html");
                    break;
            }
            exit();
        }        
    }
}
?>