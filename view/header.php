<?php
session_start();
?>
<style>
    .botonesHeader {
    display: flex;
    gap: 10px;
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
    <div class="logo">
        <super class="fas fa-shopping-cart"></super> SuperRosita
    </div>
    <div class="botonesHeader">
        <?php if (isset($_SESSION['usuario'])): ?>
            <div class="botonPerfil" onclick="location.href='./index.php?redirect=perfil'">
                <i class="fas fa-user"></i> Ver Mi Perfil
            </div>
            <div class="botonCarrito" onclick="location.href='../index.php?redirect=carrito'">
                <i class="fas fa-shopping-cart"></i> Ver Mi Carrito
            </div>
            <div class="botonCerrarSesion" onclick="location.href='../logout.php'">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </div>

        <?php else: ?>
            <div class="botonRegistro" onclick="location.href='../index.php?redirect=registro'">
                <i class="fas fa-user-plus"></i> Registrarse
            </div>

            <div class="botonInicioSesion" onclick="location.href='../index.php?redirect=login'">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </div>
        <?php endif; ?>
    </div>
</header>
