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
  <link rel="stylesheet" href="/SuperRosita/css/perfil/general.css">
</head>

<body>

  <?php include __DIR__ . '/../header.php'; ?>

  <div class="contenedor">
    <aside class="menuLateral">
      <ul>
        <li><a href="/SuperRosita/perfil">General</a></li>

        <?php if (isset($_SESSION['codigo_cliente'])): ?>
          <li><a href="/SuperRosita/perfil/historial">Historial de compras</a></li>
          <li><a href="/SuperRosita/perfil/devolucion">Devoluciones</a></li>
          <li><a href="/SuperRosita/perfil/boleta">Boleta</a></li>
        <?php endif; ?>

        <li><a href="/SuperRosita/perfil/ajustes">Ajustes</a></li>

        <?php if (isset($_SESSION['codigo_cargo'])): ?>
          <li><a href="/SuperRosita/perfil/reportes">Solicitar Reportes</a></li>
        <?php endif; ?>

        <?php if (isset($_SESSION['codigo_cargo']) && $_SESSION['codigo_cargo'] === '1'): ?>
          <li><a href="/SuperRosita/perfil/inventario">Inventario</a></li>
          <li><a href="/SuperRosita/perfil/ingresar-trabajador">Ingresar Trabajador</a></li>
          <li><a href="/SuperRosita/perfil/promocion">Gestionar Promociones</a></li>
          <li><a href="/SuperRosita/perfil/gestion-devoluciones">Gestionar Devoluciones</a></li>
        <?php endif; ?>
      </ul>
    </aside>

    <main class="contenido">
      <div class="datos-cuenta">
        <h2>Datos de la cuenta</h2>
        <div class="contenido-datos">
        <?php
        if (isset($_SESSION['usuario'], $_SESSION['nombre'], $_SESSION['apellido1'], $_SESSION['apellido2'])) {
          if (str_ends_with($_SESSION['usuario'], '@superrosita.cl')) {
          echo htmlspecialchars("Trabajador: " . $_SESSION['nombre'] . " " . $_SESSION['apellido1'] . " " . $_SESSION['apellido2']) . "<br>";
          echo htmlspecialchars("Fecha de Contrato: " . $_SESSION['fecha_contrato']) . "<br>";
          echo htmlspecialchars("Tipo de cuenta: Trabajador") . "<br>";
        } else {
          echo htmlspecialchars("Cliente: " . $_SESSION['nombre'] . " " . $_SESSION['apellido1'] . " " . $_SESSION['apellido2']) . "<br>";
          echo htmlspecialchars("Tipo de cuenta: Cliente") . "<br>";
        }
      }
      if (isset($_SESSION['usuario'])) {
        echo htmlspecialchars("Correo Electrónico: " . $_SESSION['usuario']) . "<br>";
      }
      ?>
    </div>
  </div>
</main>


  </div>
</body>

</html>