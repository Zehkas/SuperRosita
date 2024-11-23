<?php
require_once __DIR__ . '/../connection.php';

class Reportes {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function obtenerProductosMasVendidos() {
        try {
            $sql = "SELECT 
                        p.CODIGO_PRODUCTO, 
                        p.NOMBRE_PRODUCTO, 
                        p.PRECIO_VENTA_PRODUCTO, 
                        d.NOMBRE_DEPARTAMENTO AS DEPARTAMENTO_PRODUCTO,
                        SUM(c.CANTIDAD_CARRITO) AS cantidad_vendida,
                        SUM(c.PRECIO_CARRITO) AS ganancia_total,
                        SUM(CASE WHEN c.ESTADO_CARRITO = 4 THEN c.CANTIDAD_CARRITO ELSE 0 END) AS devoluciones_aprobadas,
                        SUM(CASE WHEN c.ESTADO_CARRITO = 4 THEN c.PRECIO_CARRITO ELSE 0 END) AS dinero_perdido_aprobadas
                    FROM 
                        MMVK_CARRITO c
                        JOIN MMVK_PRODUCTO p ON c.CODIGO_PRODUCTO = p.CODIGO_PRODUCTO
                        JOIN MMVK_DEPARTAMENTO d ON p.CODIGO_DEPARTAMENTO = d.CODIGO_DEPARTAMENTO
                    WHERE 
                        (c.ESTADO_CARRITO = 1 OR c.ESTADO_CARRITO BETWEEN 4 AND 7)
                    GROUP BY 
                        p.CODIGO_PRODUCTO, p.NOMBRE_PRODUCTO, p.PRECIO_VENTA_PRODUCTO, d.NOMBRE_DEPARTAMENTO
                    ORDER BY 
                        cantidad_vendida DESC
                    FETCH FIRST 5 ROWS ONLY";
            $stmt = oci_parse($this->db, $sql);
            oci_execute($stmt);

            $productos = [];
            while ($row = oci_fetch_assoc($stmt)) {
                $productos[] = $row;
            }
            oci_free_statement($stmt);
            return $productos;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
    
    public function obtenerProductosConMasDevoluciones() {
        try {
            $sql = "SELECT 
                        p.CODIGO_PRODUCTO,
                        p.NOMBRE_PRODUCTO,
                        p.PRECIO_VENTA_PRODUCTO,
                        d.NOMBRE_DEPARTAMENTO AS DEPARTAMENTO_PRODUCTO,
                        SUM(c.CANTIDAD_CARRITO) AS total_devoluciones,
                        SUM(CASE WHEN c.ESTADO_CARRITO = 4 THEN c.CANTIDAD_CARRITO ELSE 0 END) AS devoluciones_aprobadas,
                        SUM(CASE WHEN c.ESTADO_CARRITO = 5 THEN c.CANTIDAD_CARRITO ELSE 0 END) AS devoluciones_pendientes,
                        SUM(CASE WHEN c.ESTADO_CARRITO = 6 THEN c.CANTIDAD_CARRITO ELSE 0 END) AS devoluciones_rechazadas,
                        SUM(CASE WHEN c.ESTADO_CARRITO = 4 THEN c.PRECIO_CARRITO ELSE 0 END) AS dinero_perdido_aprobadas,
                        SUM(CASE WHEN c.ESTADO_CARRITO = 5 THEN c.PRECIO_CARRITO ELSE 0 END) AS dinero_pendiente,
                        SUM(CASE WHEN c.ESTADO_CARRITO = 6 THEN c.PRECIO_CARRITO ELSE 0 END) AS dinero_rechazado
                    FROM 
                        MMVK_CARRITO c
                        JOIN MMVK_PRODUCTO p ON c.CODIGO_PRODUCTO = p.CODIGO_PRODUCTO
                        JOIN MMVK_DEPARTAMENTO d ON p.CODIGO_DEPARTAMENTO = d.CODIGO_DEPARTAMENTO
                    WHERE 
                        c.ESTADO_CARRITO BETWEEN 4 AND 6
                    GROUP BY 
                        p.CODIGO_PRODUCTO, p.NOMBRE_PRODUCTO, p.PRECIO_VENTA_PRODUCTO, d.NOMBRE_DEPARTAMENTO
                    ORDER BY 
                        total_devoluciones DESC
                    FETCH FIRST 5 ROWS ONLY";
            $stmt = oci_parse($this->db, $sql);
            oci_execute($stmt);
            
            $productos = [];
            while ($row = oci_fetch_assoc($stmt)) {
                $productos[] = $row;
            }
            oci_free_statement($stmt);
            return $productos;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function obtenerClientesMasFieles() {
        try {
            $sql = "SELECT 
                        c.CODIGO_CLIENTE,
                        cl.NOMBRE_CLIENTE,
                        cl.APELLIDO1_CLIENTE, 
                        cl.CORREO_CLIENTE,
                        SUM(c.CANTIDAD_CARRITO) AS cantidad_comprada,
                        SUM(c.PRECIO_CARRITO) AS total_gastado
                    FROM 
                        MMVK_CARRITO c
                        JOIN MMVK_CLIENTES cl ON c.CODIGO_CLIENTE = cl.CODIGO_CLIENTE
                    WHERE 
                        (c.ESTADO_CARRITO = 1 OR c.ESTADO_CARRITO = 7)
                    GROUP BY 
                        c.CODIGO_CLIENTE, cl.NOMBRE_CLIENTE, cl.APELLIDO1_CLIENTE, cl.CORREO_CLIENTE
                    ORDER BY 
                        total_gastado DESC
                    FETCH FIRST 5 ROWS ONLY";
            $stmt = oci_parse($this->db, $sql);
            oci_execute($stmt);
    
            $clientes = [];
            while ($row = oci_fetch_assoc($stmt)) {
                $clientes[] = $row;
            }
            oci_free_statement($stmt);
            return $clientes;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }    
}