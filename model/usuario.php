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
    
            // Asegúrate de que $region sea un número
            $region = (int)$region;  // Convierte a número entero
            if ($region <= 0) {
                throw new Exception("Selección de región inválida.");
            }
            $sql = "BEGIN MMVK_INGRESA_CLIENTES(:region, :correo, :nombre, :apellido1, :apellido2, :contrasena); END;";
            $stmt = oci_parse($this->db, $sql);
    
            oci_bind_by_name($stmt, ':region', $region);
            oci_bind_by_name($stmt, ':correo', $correo);
            oci_bind_by_name($stmt, ':nombre', $nombre);
            oci_bind_by_name($stmt, ':apellido1', $apellido1);
            oci_bind_by_name($stmt, ':apellido2', $apellido2);
            oci_bind_by_name($stmt, ':contrasena', $contrasena);
    
            $result = oci_execute($stmt);
    
            if (!$result) {
                $error = oci_error($stmt);
                throw new Exception("Error en agregarCliente: " . $error['message']);
            }
    
            oci_free_statement($stmt);
            return $result;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
    
    

    public function validarCliente($correo, $contrasena) {
        try {
            if ($this->db === null) {
                throw new Exception("Error de conexión a la base de datos.");
            }
    
            // Consulta para verificar si el correo y la contraseña existen
            $sql = "SELECT COUNT(*) AS TOTAL FROM MMVK_CLIENTES WHERE CORREO_CLIENTE = :email AND CONTRASENA_CORREO_CLIENTE = :password";
            $stmt = oci_parse($this->db, $sql);
    
            // Asignar los parámetros
            oci_bind_by_name($stmt, ':email', $correo);
            oci_bind_by_name($stmt, ':password', $contrasena);
    
            // Ejecutar la consulta
            oci_execute($stmt);
    
            // Obtener el resultado
            $row = oci_fetch_assoc($stmt);
            $count = $row['TOTAL'];
    
            // Liberar recursos
            oci_free_statement($stmt);
    
            // Retornar true si existe al menos un registro, false en caso contrario
            return $count > 0;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function obtenerDatosCliente($correo) {
        try {
            if ($this->db === null) {
                throw new Exception("Error de conexión a la base de datos.");
            }

            $sql = "SELECT NOMBRE_CLIENTE, APELLIDO1_CLIENTE, APELLIDO2_CLIENTE, CODIGO_CLIENTE
                    FROM MMVK_CLIENTES 
                    WHERE CORREO_CLIENTE = :email"; 
            $stmt = oci_parse($this->db, $sql);

            oci_bind_by_name($stmt, ":email", $correo);

            oci_execute($stmt);

            if ($row = oci_fetch_assoc($stmt)) {
                return $row;
            } else {
                return null;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }
    
    public function validarTrabajador($correo, $contrasena){
        try {
            if ($this->db === null) {
                throw new Exception("Error de conexión a la base de datos.");
            }
              
            
            $sql = "SELECT COUNT(*) AS TOTAL FROM MMVK_TRABAJADOR WHERE CORREO_TRABAJADOR = :email AND CONTRASENA_CORREO_TRABAJADOR = :password";
            $stmt = oci_parse($this->db, $sql);

            oci_bind_by_name($stmt, ':email', $correo);
            oci_bind_by_name($stmt, ':password', $contrasena);  
            
            oci_execute($stmt);

            $row = oci_fetch_assoc($stmt);
            $count = $row['TOTAL'];
            
            oci_free_statement($stmt);           

            return $count > 0;


        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function obtenerDatosTrabajador($correo){
        try {
            if ($this->db === null) {
                throw new Exception("Error de conexión a la base de datos.");
            }

            $sql = "SELECT CODIGO_TRABAJADOR, NOMBRE_TRABAJADOR, APELLIDO1_TRABAJADOR, APELLIDO2_TRABAJADOR, FECHA_CONTRATO_TRABAJADOR, CODIGO_DEPARTAMENTO, CODIGO_CARGO_TRABAJADOR
                    FROM MMVK_TRABAJADOR
                    WHERE CORREO_TRABAJADOR = :email"; 
            $stmt = oci_parse($this->db, $sql);

            oci_bind_by_name($stmt, ":email", $correo);

            oci_execute($stmt);

            if ($row = oci_fetch_assoc($stmt)) {
                return $row;
            } else {
                return null;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }
}
?>
