<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<style>
    .botonesHeader {
        display: flex;
        gap: 10px;
    }
    .header {
        align-items: center;
        padding: 20px;
        display: flex;
        background-color: #E4A8CA;
        justify-content: space-between;
        border-bottom: 2px solid #000000;
        align-items: center;
    }

    .logo {
        cursor: pointer;
        color: #000;
        font-family: 'Times New Roman', Times, serif;
        font-size: 24px;
    }

    .logo super {
        margin-right: 8px;
        color: #000;
        font-size: 1.2em;
    }

    .botonRegistro,
    .botonInicioSesion,
    .botonCerrarSesion,
    .botonCarrito,
    .botonPerfil {
        cursor: pointer;
        color: #000;
        font-family: 'Times New Roman', Times, serif;
        font-size: 20px;
        text-align: center;
        background-color: #D16D8C;
        padding: 10px 15px;
        border-radius: 10px;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .botonRegistro:hover,
    .botonInicioSesion:hover,
    .botonCerrarSesion:hover,
    .botonCarrito:hover,
    .botonPerfil:hover {
        background-color: #B45F75;
        transform: scale(1.05);
    }


    /* Estilos para la ventana desplegable del carrito */
    .carrito-desplegable {
        display: none;
        position: fixed;
        right: 0;
        top: 0;
        width: 300px;
        height: 100%;
        background-color: #fff;
        box-shadow: -2px 0 5px rgba(0,0,0,0.5);
        overflow-y: auto;
        z-index: 1000;
    }

    .carrito-contenido {
        padding: 20px;
    }

    .carrito-contenido h2 {
        text-align: center;
        color: #fae4f0; 
        background-color: #B45F75;
        padding: 10px;
        border-radius: 5px;
    }

    .carrito-producto {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        padding: 15px;
        border: 1px solid #ccc; 
        border-radius: 8px;
        background-color: #ffff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .carrito-producto img {
        width: 55px;
        height: 55px;
    }

    .carrito-producto-info {
        display: flex;
        flex-direction: column;
        flex: 1;
        padding-left: 10px;
    }

    .carrito-producto-info span {
        display: block;
    }

    .carrito-producto-total {
        text-align: right;
    }

    .carrito-boton-eliminar {
        background-color: #dc3545;
        color: #fff;
        border: none;
        padding: px 10px;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .carrito-boton-eliminar:hover {
        background-color: #c82333;
        transform: scale(1.1);
    }

    .carrito-vacio {
        text-align: center;
        padding: 20px;
        font-size: 18px;
        color: #777;
    }
    .carrito-boton {
        background-color: #fae4f0; 
        color: #B45F75;
        font-size: 18px;
        font-weight: bold;
        padding: 15px 25px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        text-align: center;
        transition: background-color 0.3s ease, transform 0.2s ease;
        width: 100%;
        margin-top: 20px;
    }

    .carrito-boton:hover {
        transform: scale(1.05);
    }



</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<header class="header">

    <div class="logo" onclick="location.href='/SuperRosita/'">
        <super class="fas fa-shopping-cart"></super>SuperRosita
    </div>


    <div class="botonesHeader">
        <?php 
        $rutaActual = $_SERVER['REQUEST_URI'];
        if (isset($_SESSION['usuario'])): ?>

        <?php if (strpos($rutaActual, '/SuperRosita/perfil') === false):?>
            <div class="botonPerfil" onclick="location.href='/SuperRosita/perfil'">
                <i class="fas fa-user"></i> Ver Mi Perfil
            </div>
        <?php endif; ?> 

        <?php if (isset($_SESSION['codigo_cliente'])): ?>
            <div class="botonCarrito" id="ver-carrito">
                <i class="fas fa-shopping-cart"></i> Ver Mi Carrito
            </div>
        <?php endif; ?> 

            <div class="botonCerrarSesion" onclick="location.href='/SuperRosita/logout'">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </div>
        <?php else: ?>
            
            <?php if (strpos($rutaActual, '/SuperRosita/registro') === false && strpos($rutaActual, '/SuperRosita/login') === false):?>
                <div class="botonRegistro" onclick="location.href='/SuperRosita/registro'">
                    <i class="fas fa-user-plus"></i> Registrarse
                </div>
                <div class="botonInicioSesion" onclick="location.href='/SuperRosita/login'">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </div> 
            <?php endif; ?> 

        <?php endif; ?> 
    </div>

</header>

<div class="carrito-desplegable" id="carrito-desplegable">
    <div class="carrito-contenido">
        <h2>Mi Carrito</h2>
        <div id="carrito-productos" class="carrito-productos"></div>
        <button id="completar-compra" class="carrito-boton" style="display: none;">Completar Compra</button>
    </div>
</div>




<script>
    document.getElementById('ver-carrito').addEventListener('click', () => {
        const carritoDesplegable = document.getElementById('carrito-desplegable');
        carritoDesplegable.style.display = carritoDesplegable.style.display === 'block' ? 'none' : 'block';
        cargarCarrito();
    });

    function cargarCarrito() {
        fetch('/SuperRosita/index.php?action=verCarrito')
            .then(response => response.json())
            .then(data => {
                const carritoProductos = document.getElementById('carrito-productos');
                carritoProductos.innerHTML = '';
                const completarCompraBoton = document.getElementById('completar-compra');

                if (data.length === 0) {
                    carritoProductos.innerHTML = '<p class="carrito-vacio">Carrito Vacío</p>';
                    completarCompraBoton.style.display = 'none';
                } else {
                    completarCompraBoton.style.display = 'block';
                    data.forEach(producto => {
                        const productoElem = document.createElement('div');
                        productoElem.className = 'carrito-producto';
                        productoElem.innerHTML = `
                            <img src="/SuperRosita/imgs/${producto.NOMBRE.toLowerCase().replace(/\s/g, '_')}.png" alt="${producto.NOMBRE}">
                            <div class="carrito-producto-info">
                                <span>${producto.NOMBRE}</span>
                                <span>${producto.CANTIDAD} x $${producto.PRECIO/producto.CANTIDAD}</span>
                                <span class="carrito-producto-total">Total: $${producto.PRECIO}</span>
                            </div>
                            <button class="carrito-boton-eliminar" onclick="eliminarProducto(${producto.CODIGO})">Quitar</button>
                        `;
                        carritoProductos.appendChild(productoElem);
                    });
                }
            });
    }

    function eliminarProducto(codigoProducto) {
        fetch('/SuperRosita/index.php?action=eliminarProductoCarrito', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                codigoProducto: codigoProducto
            })
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                cargarCarrito();
            } else {
                alert('Error al eliminar el producto');
            }
        }).catch(error => {
            console.error('Error:', error);
        });
    }

    document.getElementById('completar-compra').addEventListener('click', () => {
        fetch('/SuperRosita/index.php?action=completarCompra', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Compra completada exitosamente');
                cargarCarrito();
            } else {
                alert('Error al completar la compra');
            }
        }).catch(error => {
            console.error('Error:', error);
        });
    });

    window.addEventListener('click', event => {
        const carritoDesplegable = document.getElementById('carrito-desplegable');
        if (!carritoDesplegable.contains(event.target) && event.target.id !== 'ver-carrito') {
            carritoDesplegable.style.display = 'none';
        }
    });
</script>