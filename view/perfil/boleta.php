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
  <title>Boleta</title>
  <link rel="stylesheet" href="/SuperRosita/css/global.css">
  <link rel="stylesheet" href="/SuperRosita/css/perfil.css">
  <link rel="stylesheet" href="/SuperRosita/css/perfil/boleta.css">
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


      <div class="contenedor-botones">
        <h3>Opciones para Boleta</h3>
        <button id="mostrarBoletaBtn" class="btn">Mostrar última boleta</button>
      </div>

      <div id="boletaResultado" style="display: none;"></div>

      <script>
        document.getElementById('mostrarBoletaBtn').addEventListener('click', () => {
          fetch('/SuperRosita/index.php?action=obtenerBoleta', {
                  method: 'GET',
                  headers: {
                      'Content-Type': 'application/json'
                  }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.boleta) {
                        document.getElementById('boletaResultado').innerText = data.boleta;
                        document.getElementById('boletaResultado').style.display = 'block';

                    } else {
                        document.getElementById('boletaResultado').innerText = 'No se encontró la boleta.';
                    }
                })
                .catch(error => {
                    console.error('Error al obtener la boleta:', error);
                });
            });
        </script>



    </main>

  </div>

</body>

</html>