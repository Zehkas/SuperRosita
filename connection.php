<?php
class Connection {
    public function connect() {
        $db_user = "ROSITA";
        $db_password = "superrosita";

        $db_host = "localhost";
        $db_port = "1521";
        $db_sid = "xe";

        $conn = oci_connect($db_user, $db_password, "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$db_host)(PORT=$db_port))(CONNECT_DATA=(SID=$db_sid)))");

        if (!$conn) {
            $error = oci_error();
            die("Error de conexión: " . $error['message']);
        }

        return $conn;
    }
}
?>
