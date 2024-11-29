<?php

class Boleta {

    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    
    public function obtenerUltimaBoleta($codigoCliente) {
        try {
            $sqlCabeza = "
                SELECT CODIGO_CABEZA_BOLETA, FECHA_EMISION_CABEZA_BOLETA, CODIGO_CLIENTE
                FROM MMVK_CABEZA_BOLETA
                WHERE CODIGO_CLIENTE = :codigo_cliente
                ORDER BY CODIGO_CABEZA_BOLETA DESC
                FETCH FIRST 1 ROWS ONLY
            ";

            $stmtCabeza = oci_parse($this->db, $sqlCabeza);
            oci_bind_by_name($stmtCabeza, ':codigo_cliente', $codigoCliente);
            oci_execute($stmtCabeza);

            $cabezaBoleta = oci_fetch_assoc($stmtCabeza);
            if (!$cabezaBoleta) {
                return "No se encontró ninguna boleta para el cliente con código: $codigoCliente.";
            }

            $codigoCabezaBoleta = $cabezaBoleta['CODIGO_CABEZA_BOLETA'];
            $fechaEmision = $cabezaBoleta['FECHA_EMISION_CABEZA_BOLETA'];
            $codigoClienteBoleta = $cabezaBoleta['CODIGO_CLIENTE'];

        
            $sqlCuerpo = "
                SELECT P.NOMBRE_PRODUCTO, CB.CANTIDAD_CUERPO_BOLETA, CB.PRECIO_CUERPO_BOLETA
                FROM MMVK_CUERPO_BOLETA CB
                JOIN MMVK_PRODUCTO P ON CB.CODIGO_PRODUCTO = P.CODIGO_PRODUCTO
                WHERE CB.CODIGO_CABEZA_BOLETA = :codigo_cabeza_boleta
            ";
            $stmtCuerpo = oci_parse($this->db, $sqlCuerpo);
            oci_bind_by_name($stmtCuerpo, ':codigo_cabeza_boleta', $codigoCabezaBoleta);
            oci_execute($stmtCuerpo);

            $resultado = "---------------------------------SUPERROSITA---------------------------------\n";
            $resultado .= "Fecha: $fechaEmision\n";
            $resultado .= "Codigo cliente: $codigoClienteBoleta\n\n";

            while ($cuerpoBoleta = oci_fetch_assoc($stmtCuerpo)) {
                $nombreProducto = $cuerpoBoleta['NOMBRE_PRODUCTO'];
                $cantidad = $cuerpoBoleta['CANTIDAD_CUERPO_BOLETA'];
                $precio = $cuerpoBoleta['PRECIO_CUERPO_BOLETA'];
                $resultado .= "$nombreProducto x$cantidad\n";
                $resultado .= "Total: $$precio\n\n";
            }

            $resultado .= "---------------------------------SUPERROSITA---------------------------------\n";

            oci_free_statement($stmtCabeza);
            oci_free_statement($stmtCuerpo);

            return $resultado;
        } catch (Exception $e) {
            return "Error al obtener la boleta: " . $e->getMessage();
        }
    }
}
?>
