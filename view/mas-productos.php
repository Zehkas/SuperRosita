<?php
require_once __DIR__ . '/../controller/ProductoControlador.php';
require_once __DIR__ . '/../connection.php';

$dbConnection = (new Connection())->connect();

$productoControlador = new ProductoControlador($dbConnection);
$productos = $productoControlador->MostrarProductos();
$productosPorDepartamento = $productoControlador->ProductosDepartamento();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Más Productos - SuperRosita</title>
    <link rel="stylesheet" href="/SuperRosita/css/productos.css">
    <link rel="stylesheet" href="/SuperRosita/css/global.css">
    <link rel="stylesheet" href="/SuperRosita/css/modal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

    <?php include __DIR__ . '/header.php'; ?>

    <div class="contenedor">
        <h1>Todos Los Productos</h1>
    </div>

    <section class="productos-destacados">
    <?php foreach ($productosPorDepartamento as $departamento => $productos): ?>
        <div class="departamento">
            <h2><?php echo htmlspecialchars($departamento); ?></h2>
            <div class="productos">
                <?php foreach ($productos as $producto): ?>
                    <div class="producto" onclick="mostrarModal('<?php echo htmlspecialchars($producto['NOMBRE_PRODUCTO']); ?>', '<?php echo htmlspecialchars($producto['CODIGO_PRODUCTO']); ?>', '<?php echo htmlspecialchars($producto['PRECIO_VENTA_PRODUCTO']); ?>')">
                        <?php if (isset($producto['NOMBRE_PRODUCTO'], $producto['PRECIO_VENTA_PRODUCTO'], $producto['CODIGO_PRODUCTO'])): ?>
                            <?php 
                                $nombreImagen = strtolower(str_replace(' ', '_', $producto['NOMBRE_PRODUCTO'])) . '.png'; 
                            ?>
                                <img src="/SuperRosita/imgs/<?php echo htmlspecialchars($nombreImagen); ?>" alt="<?php echo htmlspecialchars($producto['NOMBRE_PRODUCTO']); ?>">
                                <h3><?php echo htmlspecialchars($producto['NOMBRE_PRODUCTO']); ?></h3>
                                <p>$<?php echo htmlspecialchars($producto['PRECIO_VENTA_PRODUCTO']); ?></p>
                            <?php else: ?>
                                <p>Datos del producto no disponibles</p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <div class="ver-mas">
        <button onclick="location.href='/SuperRosita/'">Volver a la Página Principal</button>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <img id="modal-img" src="" alt="Producto">
            <h2 id="modal-title"></h2>
            <p id="modal-price"></p>
            <div class="contador">
                <button id="decremento" class="contador-boton">-</button>
                <span id="cantidad">1</span>
                <button id="incremento" class="contador-boton">+</button>
            </div>
            <div id="modal-footer" class="footer">
                <button id="addToCart"><i class="fas fa-shopping-bag"></i> Añadir al Carrito</button>
                <button id="cancelarBtn">Cancelar</button>
            </div>
            <p id="mensajeIniciarSesion" class="mensaje-iniciar-sesion">Por favor, <a href="login">inicia sesión</a> para agregar productos al carrito.</p>
            <p id="mensajeTrabajador" class="mensaje-trabajador">No puedes agregar productos al carrito con una cuenta de trabajador.</p>
            <p id="success-message">Producto agregado exitosamente</p>
        </div>
    </div>

    <script>
        let productoSeleccionado = null;

        function mostrarModal(nombre, codigo, precio) {
            productoSeleccionado = codigo;
            document.getElementById('modal-img').src = "/SuperRosita/imgs/" + nombre.toLowerCase().replace(/\s/g, '_') + ".png";
            document.getElementById('modal-title').innerText = nombre;
            document.getElementById('modal-price').innerText = "$" + precio;
            document.getElementById('cantidad').innerText = 1;
            document.getElementById('modal').style.display = 'block';
            document.getElementById('modal-footer').style.display = 'flex';
            document.getElementById('success-message').style.display = 'none';
            document.getElementById('mensajeIniciarSesion').style.display = 'none';
            document.getElementById('mensajeTrabajador').style.display = 'none';
        }

        window.addEventListener('click', event => {
            if (event.target == document.getElementById('modal')) {
                document.getElementById('modal').style.display = 'none';
            }
        });

        document.getElementById('incremento').addEventListener('click', () => {
            const cantidadElem = document.getElementById('cantidad');
            let cantidad = parseInt(cantidadElem.innerText, 10);
            cantidadElem.innerText = ++cantidad;
        });

        document.getElementById('decremento').addEventListener('click', () => {
            const cantidadElem = document.getElementById('cantidad');
            let cantidad = parseInt(cantidadElem.innerText, 10);
            if (cantidad > 1) {
                cantidadElem.innerText = --cantidad;
            }
        });

        document.getElementById('addToCart').addEventListener('click', () => {
            const cantidad = parseInt(document.getElementById('cantidad').innerText, 10);
            <?php if (isset($_SESSION['codigo_trabajador'])): ?>
                document.getElementById('mensajeTrabajador').style.display = 'block';
                document.getElementById('modal-footer').style.display = 'none';
            <?php else: ?>
                agregarAlCarrito(productoSeleccionado, cantidad);
            <?php endif; ?>
        });

        document.getElementById('cancelarBtn').addEventListener('click', () => {
            document.getElementById('modal').style.display = 'none';
        });

        function agregarAlCarrito(codigoProducto, cantidad) {
            fetch('/SuperRosita/index.php?action=agregarAlCarrito', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    codigoProducto: codigoProducto,
                    cantidad: cantidad
                })
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('success-message').style.display = 'block';
                    document.getElementById('modal-footer').style.display = 'none';
                    document.getElementById('mensajeIniciarSesion').style.display = 'none';
                    setTimeout(() => {
                        document.getElementById('modal').style.display = 'none';
                    }, 1000);
                } else if (data.message === 'iniciar_sesion') {
                    document.getElementById('mensajeIniciarSesion').style.display = 'block';
                } else {
                    alert('Error al agregar el producto al carrito');
                }
            }).catch(error => {
                console.error('Error:', error);
            });
        }
    </script>

</body>
</html>
