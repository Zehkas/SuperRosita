<?php
require_once './model/producto.php';

class ProductoControlador {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // Método para registrar un nuevo producto
    public function RegistroProducto() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre_producto'];
            $peso = $_POST['peso_producto'];
            $fecha_fabricacion = $_POST['fecha_fabricacion_producto'];
            $precio_compra = $_POST['precio_compra_producto'];
            $precio_venta = $_POST['precio_venta_producto'];
            $existencia = $_POST['existencia_producto'];
            $codigo_pais = $_POST['codigo_pais_origen_producto'];
            $codigo_departamento = $_POST['codigo_departamento'];

            $producto = new Producto($this->db);
            $resultado = $producto->agregarProducto($nombre, $peso, $fecha_fabricacion, $precio_compra, $precio_venta, $existencia, $codigo_pais, $codigo_departamento);

            if ($resultado) {
                echo "Producto registrado exitosamente.";
            } else {
                echo "Error al registrar el producto.";
            }
        }
    }

    // Método para editar un producto existente
    public function EditarProducto() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $codigo_producto = $_POST['codigo_producto'];
            $nombre = $_POST['nombre_producto'];
            $peso = $_POST['peso_producto'];
            $fecha_fabricacion = $_POST['fecha_fabricacion_producto'];
            $precio_compra = $_POST['precio_compra_producto'];
            $precio_venta = $_POST['precio_venta_producto'];
            $existencia = $_POST['existencia_producto'];
            $codigo_pais = $_POST['codigo_pais_origen_producto'];
            $codigo_departamento = $_POST['codigo_departamento'];

            $producto = new Producto($this->db);
            $resultado = $producto->editarProducto($codigo_producto, $nombre, $peso, $fecha_fabricacion, $precio_compra, $precio_venta, $existencia, $codigo_pais, $codigo_departamento);

            if ($resultado) {
                echo "Producto editado exitosamente.";
            } else {
                echo "Error al editar el producto.";
            }
        }
    }

    // Método para eliminar un producto existente
    public function EliminarProducto() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $codigo_producto = $_POST['codigo_producto'];

            $producto = new Producto($this->db);
            $resultado = $producto->eliminarProducto($codigo_producto);

            if ($resultado) {
                echo "Producto eliminado exitosamente.";
            } else {
                echo "Error al eliminar el producto.";
            }
        }
    }

    // Método para obtener todos los productos
    public function MostrarProductos() {
        $producto = new Producto($this->db);
        return $producto->obtenerProductos();
    }
}
?>