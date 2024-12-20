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
    
            $region = (int)$region;
            if ($region <= 0) {
                throw new Exception("Selección de región inválida.");
            }
            $sql = "BEGIN 
                    MMVK_CRUD_CLIENTE('I', :region, :correo, :nombre, :apellido1, :apellido2, :contrasena);
                    END;";
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
    
            $sql = "SELECT COUNT(*) AS TOTAL FROM MMVK_CLIENTES WHERE CORREO_CLIENTE = :email AND CONTRASENA_CORREO_CLIENTE = :password";
            $stmt = oci_parse($this->db, $sql);
    
            oci_bind_by_name($stmt, ':email', $correo);
            oci_bind_by_name($stmt, ':password', $contrasena);
    
            oci_execute($stmt);
    
            $row = oci_fetch_assoc($stmt);
            $count = $row['TOTAL'];
    
            oci_free_statement($stmt);
    
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


    
    
    public function validarContrasenaCliente($correo, $oldPassword) {
        try {
            if ($this->db === null) {
                throw new Exception("Error de conexión a la base de datos.");
            }

            $sql = "SELECT CONTRASENA_CORREO_CLIENTE
                    FROM MMVK_CLIENTES
                    WHERE CORREO_CLIENTE = :correo";
            $stmt = oci_parse($this->db, $sql);


            oci_bind_by_name($stmt, ":correo", $correo);
            oci_execute($stmt);
            $row = oci_fetch_assoc($stmt);

            if ($oldPassword === $row['CONTRASENA_CORREO_CLIENTE']) {
                return true;
            } 

            return false;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function cambiarContrasenaCliente($correo, $contrasena){
        try {
            if ($this->db === null) {
                throw new Exception("Error de conexión a la base de datos.");
            }

            $sql = "BEGIN
                    MMVK_CRUD_CLIENTE('U', NULL, :correo, NULL, NULL, NULL, :contrasena);
                    END;"; 

            $stmt = oci_parse($this->db, $sql);

            oci_bind_by_name($stmt, ":correo", $correo);
            oci_bind_by_name($stmt, ":contrasena", $contrasena);

            if (oci_execute($stmt)){
                return "Contraseña actualizada correctamente.";
            } else {
                $error = oci_error($stmt);
                throw new Exception("Error al realizar la consulta: " . $error['message']);
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }        
    }

    public function validarContrasenaTrabajador($correo, $oldPassword) {
        try {
            if ($this->db === null) {
                throw new Exception("Error de conexión a la base de datos.");
            }

            $sql = "SELECT CONTRASENA_CORREO_TRABAJADOR
                    FROM MMVK_TRABAJADOR
                    WHERE CORREO_TRABAJADOR = :correo";

            $stmt = oci_parse($this->db, $sql);

            oci_bind_by_name($stmt, ":correo", $correo);
            oci_execute($stmt);
            $row = oci_fetch_assoc($stmt);

            if ($oldPassword === $row['CONTRASENA_CORREO_TRABAJADOR']) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function cambiarContrasenaTrabajador($correo, $contrasena){
        try {
            if ($this->db === null) {
                throw new Exception("Error de conexión a la base de datos.");
            }

            $sql = "BEGIN
                    MMVK_CRUD_TRABAJADOR('U', NULL, NULL, NULL, NULL, NULL, NULL, NULL, :correo, :contrasena);
                    END;";

            $stmt = oci_parse($this->db, $sql);

            oci_bind_by_name($stmt, ":correo", $correo);
            oci_bind_by_name($stmt, ":contrasena", $contrasena);

            if (oci_execute($stmt)){
                return "Contraseña actualizada correctamente.";
            } else {
                $error = oci_error($stmt);
                throw new Exception("Error al realizar la consulta: " . $error['message']);
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }        
    }
}
?>
