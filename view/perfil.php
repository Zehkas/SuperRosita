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
  <title>Mi Perfil</title>
  <link rel="stylesheet" href="/SuperRosita/css/global.css">
  <link rel="stylesheet" href="/SuperRosita/css/perfil.css">
</head>
<body>

  <?php include __DIR__ . '/header.php'; ?>

  <div class="contenedor">
    <aside class="menuLateral">
        <ul>
            <li><a href="?seccion=general">General</a></li>
            <li><a href="?seccion=historial">Historial de compras</a></li>
            <li><a href="?seccion=devolucion">Devolucion</a></li>
            <li><a href="?seccion=ajustes">Ajustes</a></li>
        </ul>
    </aside>

    <main class="contenido">
        <h2>
          <?php 
            if (isset($_SESSION['nombre'], $_SESSION['apellido1'], $_SESSION['apellido2'])) {
              echo htmlspecialchars($_SESSION['nombre'] . " " . $_SESSION['apellido1'] . " " . $_SESSION['apellido2']);
            } 
          ?>
        </h2>

          <?php 
          
          $seccion = isset($_GET['seccion']) ? $_GET['seccion'] : 'general';

          switch($seccion){
            case 'general':
              echo  "<section>
                      <h2>General</h2>
                    </section>";
              break;

            case 'historial':
              echo  "<section>
                      <h2>Historial Compras</h2>
                    </section>";
            break;

            case 'devolucion':
              echo  "<section>
                      <h2>Devolucion</h2>
                    </section>";
            break;

            case 'ajustes':
              echo  "<section>
                      <h2>Ajustes</h2>
                    </section>";
            break;
          }

          ?>
    </main>
  </div>
</body>
</html>