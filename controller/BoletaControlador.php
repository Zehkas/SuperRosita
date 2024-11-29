<?php
require_once './model/boleta.php';
require_once './connection.php';

class BoletaControlador {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function obtenerBoleta($codigoCliente, $nomb) {
        $boleta = new Boleta($this->db);
        return $boleta->obtenerUltimaBoleta($codigoCliente);
    }

}
?>
