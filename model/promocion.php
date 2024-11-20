<?php
class Promocion {
    private $db;

    // Constructor que recibe la conexión Oracle
    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function agregarPromocion($codigo_departamento, $descuento) {
        // Verificamos que la conexión no sea nula
        if ($this->db === null) {
            throw new Exception("Error de conexión a la base de datos.");
        }

        // El procedimiento almacenado de Oracle
        $sql = "BEGIN MMVK_AGREGAR_DESCUENTOS(:codigo_departamento, :descuento); END;";

        // Preparamos la consulta
        $stmt = oci_parse($this->db, $sql);

        // Asociamos los parámetros con los valores
        oci_bind_by_name($stmt, ":codigo_departamento", $codigo_departamento, -1, SQLT_INT);
        oci_bind_by_name($stmt, ":descuento", $descuento, -1, SQLT_INT);

        // Ejecutamos el procedimiento
        $executeResult = oci_execute($stmt);

        // Comprobamos si la ejecución fue exitosa
        if ($executeResult) {
            // Commit para asegurarse de que los cambios se guarden
            oci_commit($this->db);
            return ['exito' => true, 'mensaje_error' => ''];
        } else {
            $error = oci_error($stmt); // Obtener el error si ocurre
            return ['exito' => false, 'mensaje_error' => 'Error al ingresar la promoción: ' . $error['message']];
        }
    }
    public function eliminarPromocion($codigo_departamento) {
        // Verificamos que la conexión no sea nula
        if ($this->db === null) {
            throw new Exception("Error de conexión a la base de datos.");
        }

        // El procedimiento almacenado de Oracle
        $sql = "BEGIN MMVK_QUITAR_DESCUENTOS(:codigo_departamento); END;";

        // Preparamos la consulta
        $stmt = oci_parse($this->db, $sql);

        // Asociamos los parámetros con los valores
        oci_bind_by_name($stmt, ":codigo_departamento", $codigo_departamento, -1, SQLT_INT);

        // Ejecutamos el procedimiento
        $executeResult = oci_execute($stmt);

        // Comprobamos si la ejecución fue exitosa
        if ($executeResult) {
            // Commit para asegurarse de que los cambios se guarden
            oci_commit($this->db);
            return ['exito' => true, 'mensaje_error' => ''];
        } else {
            $error = oci_error($stmt); // Obtener el error si ocurre
            return ['exito' => false, 'mensaje_error' => 'Error al quitar la promoción: ' . $error['message']];
        }
    }
}
?>
