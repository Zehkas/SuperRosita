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
  <title>Historial de Compras</title>
  <link rel="stylesheet" href="/SuperRosita/css/global.css">
  <link rel="stylesheet" href="/SuperRosita/css/perfil.css">
</head>
<body>

  <?php include __DIR__ . '/../header.php'; ?>

  <div class="contenedor">
  <aside class="menuLateral">
  <ul>
            <li><a href="/SuperRosita/perfil">General</a></li>
            <li><a href="/SuperRosita/perfil/historial">Historial de compras</a></li>
            <li><a href="/SuperRosita/perfil/devolucion">Devolucion</a></li>
            <?php endif; ?>
            <li><a href="/SuperRosita/perfil/ajustes">Ajustes</a></li>
                
            <?php if (isset($_SESSION['codigo_cargo']) && $_SESSION['codigo_cargo'] === '1'): ?>
            <li><a href="/SuperRosita/perfil/gestion-devoluciones">Gestionar Devoluciones</a></li>
            <li><a href="/SuperRosita/perfil/ingresar-trabajador">Ingresar Trabajador</a></li>
            <li><a href="/SuperRosita/perfil/promocion">Ingresar Promoción</a></li>
            <?php endif; ?>
        </ul>
    </aside>


    <main class="contenido">
        <h2>
        <?php 
          if (isset($_SESSION['usuario'], $_SESSION['nombre'], $_SESSION['apellido1'], $_SESSION['apellido2'])) {
              if (str_ends_with($_SESSION['usuario'], '@superrosita.cl')) {
                  echo htmlspecialchars("Trabajador: " . $_SESSION['nombre'] . " " . $_SESSION['apellido1'] . " " . $_SESSION['apellido2']);
              } else {
                  echo htmlspecialchars("Cliente: " . $_SESSION['nombre'] . " " . $_SESSION['apellido1'] . " " . $_SESSION['apellido2']);
              }
          }
          ?>
        </h2>
    </main>

    
  </div>
</body>
</html>