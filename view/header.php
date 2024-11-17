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
    }
    .carrito-producto {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .carrito-producto img {
        width: 50px;
        height: 50px;
    }
    .carrito-boton {
        background-color: #333;
        color: #fff;
        padding: 10px;
        border: none;
        cursor: pointer;
        width: 100%;
        margin-top: 10px;
        text-align: center;
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
            <div class="botonCarrito" id="ver-carrito">
                <i class="fas fa-shopping-cart"></i> Ver Mi Carrito
            </div>
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
        <div id="carrito-productos"></div>
        <button id="completar-compra" class="carrito-boton">Completar Compra</button>
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
                data.forEach(producto => {
                    const productoElem = document.createElement('div');
                    productoElem.className = 'carrito-producto';
                    productoElem.innerHTML = `
                        <img src="/SuperRosita/imgs/${producto.nombre.toLowerCase().replace(/\s/g, '_')}.png" alt="${producto.nombre}">
                        <span>${producto.nombre}</span>
                        <span>${producto.cantidad}</span>
                        <span>${producto.precio}</span>
                        <button onclick="eliminarProducto(${producto.codigo})">Eliminar</button>
                    `;
                    carritoProductos.appendChild(productoElem);
                });
            });
    }

    function eliminarProducto(codigoProducto) {
        fetch('/SuperRosita/index.php?action=eliminarProducto', {
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
