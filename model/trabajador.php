<?php
require_once './connection.php';

class Trabajador {
    private $db;

    public function __construct() {
        $this->db = new Connection();
        $this->db = $this->db->connect();
    }

    public function agregarTrabajador($nombre, $apellido1, $apellido2, $codigo_departamento, $codigo_cargo) {
        try {
            if ($this->db === null) {
                throw new Exception("Error de conexión a la base de datos.");
            }

            // Asegúrate de que $codigo_departamento y $codigo_cargo sean números
            $codigo_departamento = (int)$codigo_departamento;
            $codigo_cargo = (int)$codigo_cargo;

            if ($codigo_departamento <= 0 || $codigo_cargo <= 0) {
                throw new Exception("Selección de departamento o cargo inválida.");
            }

            $sql = "BEGIN MMVK_INGRESA_TRABAJADOR(:nombre, :apellido1, :apellido2, :codigo_departamento, :codigo_cargo); END;";
            $stmt = oci_parse($this->db, $sql);

            oci_bind_by_name($stmt, ':nombre', $nombre);
            oci_bind_by_name($stmt, ':apellido1', $apellido1);
            oci_bind_by_name($stmt, ':apellido2', $apellido2);
           // oci_bind_by_name($stmt, ':fecha_contrato', $fecha_contrato);
            oci_bind_by_name($stmt, ':codigo_departamento', $codigo_departamento);
            oci_bind_by_name($stmt, ':codigo_cargo', $codigo_cargo);

            $result = oci_execute($stmt);

            if (!$result) {
                $error = oci_error($stmt);
                throw new Exception("Error en agregarTrabajador: " . $error['message']);
            }

            oci_free_statement($stmt);
            return $result;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
?>
