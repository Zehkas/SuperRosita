<?php
require_once './connection.php';

class Producto {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function agregarProducto($nombre, $peso, $fecha_fabricacion, $precio_compra, $precio_venta, $existencia, $codigo_pais, $codigo_departamento) {
        $sql = "INSERT INTO MMVK_PRODUCTO(nombre, peso, fecha_fabricacion, precio_compra, precio_venta, existencia, codigo_pais, codigo_departamento)
                VALUES (:nombre, :peso, :fecha_fabricacion, :precio_compra, :precio_venta, :existencia, :codigo_pais, :codigo_departamento)";
        
        $parametros = [
            ':nombre' => $nombre,
            ':peso' => $peso,
            ':fecha_fabricacion' => $fecha_fabricacion,
            ':precio_compra' => $precio_compra,
            ':precio_venta' => $precio_venta,
            ':existencia' => $existencia,
            ':codigo_pais' => $codigo_pais,
            ':codigo_departamento' => $codigo_departamento
        ];

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($parametros);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            die("Error al agregar producto: " . $e->getMessage());
        }
    }
}
?>