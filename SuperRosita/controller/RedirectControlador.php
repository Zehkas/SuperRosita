<?php
class RedirectControlador{
    public function Redirect() {
        if (isset($_GET['redirect'])){

            if($_GET['redirect'] == 'login'){
                header("Location: ./view/login.php");
                exit();

            } else if ($_GET['redirect'] == 'registro'){
                header("Location: ./view/registro.php");
                exit();
            }
        }
    }
}
?>