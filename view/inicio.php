<?php
require_once __DIR__ . '/../controller/ProductoControlador.php';
require_once __DIR__ . '/../connection.php';

// Crear conexión a la base de datos
$dbConnection = (new Connection())->connect();

// Crear instancia del controlador con la conexión a la base de datos
$productoControlador = new ProductoControlador($dbConnection);
$productosAleatorios = $productoControlador->MostrarProductosAleatorios(4);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - SuperRosita</title>
    <link rel="stylesheet" href="/SuperRosita/css/inicio.css">
    <link rel="stylesheet" href="/SuperRosita/css/global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <?php include __DIR__ . '/header.php'; ?>

    <div class="contenedor">
            <h1>Página Principal</h1>
        </div>
        <div class="banner-promociones">
            <p>Espacio para promociones, actualizaremos...</p>
        </div>

    <section class="productos-destacados">
        <?php foreach ($productosAleatorios as $producto): ?>
            <div class="producto">
                <?php if (isset($producto['NOMBRE_PRODUCTO'], $producto['PRECIO_VENTA_PRODUCTO'], $producto['CODIGO_PRODUCTO'])): ?>
                    <?php
                    $nombreImagen = strtolower(str_replace(' ', '_', $producto['NOMBRE_PRODUCTO'])) . '.png';
                    ?>
                    <img src="/SuperRosita/imgs/<?php echo htmlspecialchars($nombreImagen); ?>"
                        alt="<?php echo htmlspecialchars($producto['NOMBRE_PRODUCTO']); ?>">
                    <h2><?php echo htmlspecialchars($producto['NOMBRE_PRODUCTO']); ?></h2>
                    <p>$<?php echo htmlspecialchars($producto['PRECIO_VENTA_PRODUCTO']); ?></p>
                <?php else: ?>
                    <p>Datos del producto no disponibles</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </section>

    <div class="ver-mas"> <button onclick="location.href='/SuperRosita/mas-productos'">Ver más productos</button> </div>

</body>

</html>