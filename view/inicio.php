<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SuperRosita</title>
        <link rel="stylesheet" href="../css/inicio.css">
        <link rel="stylesheet" href="../css/global.css">
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </head>

    <body>

    <?php include './header.php'; ?>

        <div class="contenedor">
            <h1>Página Principal</h1>
        </div>
        <div class="banner-promociones">
            <p>Espacio para promociones, actualizaremos...</p>
        </div>

        <section class="productos-destacados">
            <div class="producto">
                <img src="../imgs/berrycharming.webp" alt="Berry Charming">
                <h2>Nivea Berry Charming 50ml</h2>
                <p>$2590</p>
            </div>
            <div class="producto">
                <img src="../imgs/azucar.jpg"
                    alt="Azucar Iansa">
                <h2>1kg Azucar Iansa</h2>
                <p>$990</p>
            </div>
            <div class="producto">
                <img src="../imgs/lipton.webp"
                    alt="IcedTea x6">
                <h2>Lipton Ice Tea Limon Sixpack</h2>
                <p>$3990</p>
            </div>
            <div class="producto">
                <img src="../imgs/bandejahuevos.jpg"
                    alt="Bandeja Huevos">
                <h2>20 Huevos Color</h2>
                <p>$3000</p>
            </div>
        </section>

        <div class="ver-mas"> <button onclick="location.href='./mas-productos.php'">Ver más productos</button> </div>

        <div id="modal" class="modal">
            <div class="modal-content">
                <img id="modal-img" src alt="Producto">
                <h2 id="modal-title"></h2>
                <p id="modal-price"></p>
                <div class="contador">
                    <button id="decremento" class="contador-boton">-</button>
                    <span id="cantidad">1</span>
                    <button id="incremento" class="contador-boton">+</button>
                </div>
                <button id="addToCart"><i class="fas fa-shopping-bag"></i>
                    Añadir al Carrito</button>
            </div>
        </div>

    </body>

    <script>
        document.querySelectorAll('.producto').forEach(item => {
            item.addEventListener('click', event => {
                const imgSrc = item.querySelector('img').src;
                const title = item.querySelector('h2').innerText;
                const price = item.querySelector('p').innerText;
                
                // Actualizar contenido de la ventana modal
                document.getElementById('modal-img').src = imgSrc;
                document.getElementById('modal-title').innerText = title;
                document.getElementById('modal-price').innerText = price;
                document.getElementById('cantidad').innerText = 1;
                document.getElementById('modal').style.display = 'block';
            });
        });
    
        // Cerrado de ventana modal
        window.addEventListener('click', event => {
            if (event.target == document.getElementById('modal')) {
                document.getElementById('modal').style.display = 'none';
            }
        });
    
        // Manejo de contador
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
    </script>

</html>
