<?php
require_once './connection.php';

class Usuario {
    private $db;

    public function __construct() {
        $this->db = new Connection();
        $this->db = $this->db->connect();
    }

    public function agregarCliente($region, $correo, $nombre, $apellido1, $apellido2, $contrasena) {
        try {
            if ($this->db === null) {
                throw new Exception("Error de conexión a la base de datos.");
            }

            // Preparar la consulta PL/SQL
            $sql = "BEGIN MMVK_INGRESA_CLIENTES(:region, :correo, :nombre, :apellido1, :apellido2, :contrasena); END;";
            $stmt = oci_parse($this->db, $sql);

            // Asignar los parámetros
            oci_bind_by_name($stmt, ':region', $region);
            oci_bind_by_name($stmt, ':correo', $correo);
            oci_bind_by_name($stmt, ':nombre', $nombre);
            oci_bind_by_name($stmt, ':apellido1', $apellido1);
            oci_bind_by_name($stmt, ':apellido2', $apellido2);
            oci_bind_by_name($stmt, ':contrasena', $contrasena);

            // Ejecutar el procedimiento
            $result = oci_execute($stmt);

            if (!$result) {
                $error = oci_error($stmt);
                throw new Exception("Error en agregarCliente: " . $error['message']);
            }

            // Liberar recursos
            oci_free_statement($stmt);

            return $result;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
?>
