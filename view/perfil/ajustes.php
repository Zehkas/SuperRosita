<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajustes de Perfil</title>
  <link rel="stylesheet" href="/SuperRosita/css/global.css">
  <link rel="stylesheet" href="/SuperRosita/css/perfil.css">
  <link rel="stylesheet" href="/SuperRosita/css/perfil/ajustes.css">
</head>
<body>

  <?php include __DIR__ . '/../header.php'; ?>

  <div class="contenedor">
    <aside class="menuLateral">
      <ul>
        <li><a href="/SuperRosita/perfil">General</a></li>

        <!-- Solo vista de cliente -->
        <?php if (isset($_SESSION['codigo_cliente'])): ?>
          <li><a href="/SuperRosita/perfil/historial">Historial de compras</a></li>
          <li><a href="/SuperRosita/perfil/devolucion">Devolucion</a></li>
        <?php endif; ?>


        <li><a href="/SuperRosita/perfil/ajustes">Ajustes</a></li>


        <?php if (isset($_SESSION['codigo_cargo']) && $_SESSION['codigo_cargo'] === '1'): ?>
          <li><a href="/SuperRosita/perfil/ingresar-trabajador">Ingresar Trabajador</a></li>
          <li><a href="/SuperRosita/perfil/promocion">Gestionar Promociones</a></li>
          <li><a href="/SuperRosita/perfil/gestion-devoluciones">Gestionar Devoluciones</a></li>
        <?php endif; ?>
    </ul>
  </aside>

    <main class="contenido">
      <h2>
        <?php 
          if (isset($_SESSION['usuario'], $_SESSION['nombre'], $_SESSION['apellido1'], $_SESSION['apellido2'])) {
            if (str_ends_with($_SESSION['usuario'], '@superrosita.cl')) {
              echo htmlspecialchars($_SESSION['nombre'] . " " . $_SESSION['apellido1'] . " " . $_SESSION['apellido2']);
            } else {
              echo htmlspecialchars($_SESSION['nombre'] . " " . $_SESSION['apellido1'] . " " . $_SESSION['apellido2']);
            }
          }
        ?>
      </h2>

        <form action="/SuperRosita/index.php?action=changePassword" method="POST">
          <div class="contrasena">
              <label for="oldPassword">Contraseña Actual</label>
              <input type="password" id="oldPassword" name="oldPassword" placeholder="Contraseña actual" required>

              <label for="password">Nueva Contraseña</label>
              <input type="password" id="password" name="password" placeholder="Nueva contraseña" required>
          </div>
            
          <button type="submit" class="btn">Cambiar Contraseña</button>
        </form>

    </main>
    
  </div>

</body>
</html>