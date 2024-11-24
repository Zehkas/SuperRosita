<?php
require_once __DIR__ . '/../connection.php';

class Inventario {
    private $db;
    private $lastError;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function obtenerVerMasInventario() {
        try {
            $sql = "SELECT 
                        I.CODIGO_INVENTARIO, 
                        P.NOMBRE_PRODUCTO, 
                        I.CANTIDAD_TOTAL_INVENTARIO, 
                        I.CANTIDAD_USADA_INVENTARIO, 
                        I.FECHA_ACTUALIZACION_INVENTARIO
                    FROM 
                        MMVK_INVENTARIO I
                    INNER JOIN 
                        MMVK_PRODUCTO P ON I.CODIGO_PRODUCTO = P.CODIGO_PRODUCTO
                    ORDER BY 
                        I.FECHA_ACTUALIZACION_INVENTARIO DESC";
            
            $stmt = oci_parse($this->db, $sql);
            oci_execute($stmt);
    
            $inventario = [];
            while ($row = oci_fetch_assoc($stmt)) {
                $inventario[] = $row;
            }
            oci_free_statement($stmt);
            return $inventario;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return [];
        }
    }
    public function reportarVentas($codigoInventario, $cantidadVendida) {
        try {
            $sql = "UPDATE MMVK_INVENTARIO SET 
                        CANTIDAD_USADA_INVENTARIO = CANTIDAD_USADA_INVENTARIO - :cantidadVendida
                    WHERE CODIGO_INVENTARIO = :codigoInventario";

            $stmt = oci_parse($this->db, $sql);
            oci_bind_by_name($stmt, ':cantidadVendida', $cantidadVendida);
            oci_bind_by_name($stmt, ':codigoInventario', $codigoInventario);
            if (oci_execute($stmt)) {
                oci_free_statement($stmt);
                return true;
            } else {
                $this->lastError = oci_error($stmt)['message'];
                oci_free_statement($stmt);
                return false;
            }
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }
    public function realizarRestock($codigoInventario, $cantidadRestock) {
        try {
            $sql = "UPDATE MMVK_INVENTARIO SET 
                        CANTIDAD_USADA_INVENTARIO = CANTIDAD_USADA_INVENTARIO + :cantidadRestock
                    WHERE CODIGO_INVENTARIO = :codigoInventario";

            $stmt = oci_parse($this->db, $sql);
            oci_bind_by_name($stmt, ':cantidadRestock', $cantidadRestock);
            oci_bind_by_name($stmt, ':codigoInventario', $codigoInventario);
            if (oci_execute($stmt)) {
                oci_free_statement($stmt);
                return true;
            } else {
                $this->lastError = oci_error($stmt)['message'];
                oci_free_statement($stmt);
                return false;
            }
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }
    public function getLastError() {
        return $this->lastError;
    }
}
?>
