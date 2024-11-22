<?php
require_once __DIR__ . '/../controller/ProductoControlador.php';
require_once __DIR__ . '/../connection.php';

// Crear conexión a la base de datos
$dbConnection = (new Connection())->connect();

// Crear instancia del controlador con la conexión a la base de datos
$productoControlador = new ProductoControlador($dbConnection);
$productos = $productoControlador->MostrarProductos();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Más Productos - SuperRosita</title>
    <link rel="stylesheet" href="/SuperRosita/css/inicio.css">
    <link rel="stylesheet" href="/SuperRosita/css/global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }
        .modal-content {
            background-color: #fae4f0;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            text-align: center;
            font-family: 'Times New Roman', Times, serif;
        }
        .contador-boton {
            margin: 5px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #D16D8C;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .contador-boton:hover {
            background-color: #B45F75;
        }
        #addToCart {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        #addToCart:hover {
            background-color: #555;
        }
        #success-message {
            color: green;
            margin-top: 10px;
            display: none;
        }
    </style>
</head>

<body>

    <?php include __DIR__ . '/header.php'; ?>

    <div class="contenedor">
        <h1>Todos Los Productos</h1>
    </div>

    <section class="productos-destacados">
        <?php foreach ($productos as $producto): ?>
            <div class="producto" onclick="mostrarModal('<?php echo htmlspecialchars($producto['NOMBRE_PRODUCTO']); ?>', '<?php echo htmlspecialchars($producto['CODIGO_PRODUCTO']); ?>', '<?php echo htmlspecialchars($producto['PRECIO_VENTA_PRODUCTO']); ?>')">
                <?php if (isset($producto['NOMBRE_PRODUCTO'], $producto['PRECIO_VENTA_PRODUCTO'], $producto['CODIGO_PRODUCTO'])): ?>
                    <?php 
                        $nombreImagen = strtolower(str_replace(' ', '_', $producto['NOMBRE_PRODUCTO'])) . '.png'; 
                    ?>
                    <img src="/SuperRosita/imgs/<?php echo htmlspecialchars($nombreImagen); ?>" alt="<?php echo htmlspecialchars($producto['NOMBRE_PRODUCTO']); ?>">
                    <h2><?php echo htmlspecialchars($producto['NOMBRE_PRODUCTO']); ?></h2>
                    <p>$<?php echo htmlspecialchars($producto['PRECIO_VENTA_PRODUCTO']); ?></p>
                <?php else: ?>
                    <p>Datos del producto no disponibles</p>
                <?php endif; ?>
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
            <button id="addToCart"><i class="fas fa-shopping-bag"></i> Añadir al Carrito</button>
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
            document.getElementById('success-message').style.display = 'none';
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
            agregarAlCarrito(productoSeleccionado, cantidad);
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
                    setTimeout(() => {
                        document.getElementById('modal').style.display = 'none';
                    }, 2000); // Cierra la ventana emergente después de 2 segundos
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