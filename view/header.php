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
    /* Espacio entre el icono y el texto */
    color: #000;
    /* Color del ícono */
    font-size: 1.2em;
    /* Tamaño del ícono */
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
</style>
<link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


<header class="header">
    <div class="logo" onclick="location.href='/SuperRosita/'">
        <super class="fas fa-shopping-cart"></super>SuperRosita
    </div>
    <div class="botonesHeader">
        <?php if (isset($_SESSION['usuario'])): ?>
            <div class="botonPerfil" onclick="location.href='/SuperRosita/perfil'">
                <i class="fas fa-user"></i> Ver Mi Perfil
            </div>
            <div class="botonCarrito" onclick="location.href='/SuperRosita/carrito'">
                <i class="fas fa-shopping-cart"></i> Ver Mi Carrito
            </div>
            <div class="botonCerrarSesion" onclick="location.href='/SuperRosita/logout'">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </div>

        <?php else: ?>
            <div class="botonRegistro" onclick="location.href='/SuperRosita/registro'">
                <i class="fas fa-user-plus"></i> Registrarse
            </div>

            <div class="botonInicioSesion" onclick="location.href='/SuperRosita/login'">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </div>
        <?php endif; ?>
    </div>
</header>
