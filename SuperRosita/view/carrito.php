<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../controller/ProductoControlador.php';
require_once __DIR__ . '/../model/carrito.php';
require_once __DIR__ . '/../connection.php';

$dbConnection = (new Connection())->connect();
$carritoModel = new Carrito($dbConnection);

$codigoCliente = $_SESSION['codigo_cliente'] ?? null;
$productosCarrito = [];

if ($codigoCliente) {
    $productosCarrito = $carritoModel->obtenerCarritoPendiente($codigoCliente);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="/SuperRosita/css/global.css">
    <link rel="stylesheet" href="/SuperRosita/css/carrito.css">
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>

    <div class="contenedor">
        <h1>Carrito de Compras</h1>

        <?php if (empty($productosCarrito)): ?>
            <p>No hay productos en tu carrito.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productosCarrito as $producto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['CODIGO_PRODUCTO']); ?></td>
                            <td><?php echo htmlspecialchars($producto['CANTIDAD_CARRITO']); ?></td>
                            <td><?php echo htmlspecialchars($producto['PRECIO_CARRITO']); ?></td>
                            <td>
                                <button onclick="eliminarProducto(<?php echo htmlspecialchars($producto['CODIGO_PRODUCTO']); ?>)">Eliminar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script>
        function eliminarProducto(codigoProducto) {
            // Implementa la lógica para eliminar el producto del carrito
        }
    </script>
</body>
</html>
