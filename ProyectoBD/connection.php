<?php
class Connection {
    public function connect() {
        // Datos de conexión
        $db_user = "ROSITA";
        $db_password = "superrosita";
        $db_host = "localhost";
        $db_port = "1521";
        $db_sid = "xe";

        // Crear conexión a Oracle usando oci_connect
        $conn = oci_connect($db_user, $db_password, "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$db_host)(PORT=$db_port))(CONNECT_DATA=(SID=$db_sid)))");

        // Verificar la conexión
        if (!$conn) {
            $error = oci_error();
            echo "Error de conexión: " . $error['message'];
            return null;
        }

        return $conn;
    }
}
?>

