<?php 

// Datos de conexión
$db_user = "bd_miguel";      // Reemplaza con tu usuario de Oracle
$db_password = "miguel2003";  // Reemplaza con tu contraseña de Oracle
$db_host = "localhost";
$db_port = "1521";
$db_sid = "xe";  // SID de la base de datos Oracle

// Crear conexión a Oracle usando una cadena directa
$conn = oci_connect($db_user, $db_password, "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$db_host)(PORT=$db_port))(CONNECT_DATA=(SID=$db_sid)))");

// Verificar la conexión
if (!$conn) {
    $error = oci_error();
    echo "Error de conexión: " . $error['message'];
    exit;
} else {
    //echo "¡Conectado a la base de datos Oracle!";  // Puedes descomentar esta línea para verificar la conexión
}

?>

