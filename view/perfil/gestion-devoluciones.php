<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/SuperRosita/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/SuperRosita/controller/CarritoControlador.php';

$dbConnection = (new Connection())->connect();
$carritoControlador = new CarritoControlador($dbConnection);
$devoluciones = $carritoControlador->obtenerTodasDevoluciones(); 

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestionar Devoluciones</title>
  <link rel="stylesheet" href="/SuperRosita/css/global.css">
  <link rel="stylesheet" href="/SuperRosita/css/perfil.css">
  <link rel="stylesheet" href="/SuperRosita/css/historial.css">
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
          <li><a href="/SuperRosita/perfil/promocion">Ingresar Promoción</a></li>
          <li><a href="/SuperRosita/perfil/gestion-devoluciones">Gestionar Devoluciones</a></li>
          <?php endif; ?>
      </ul>
    </aside>

    <main class="contenido">
        <h2>Gestionar Devoluciones</h2>
        
        <div id="devoluciones">
          <?php foreach ($devoluciones as $devolucion) : ?>
            <div class="producto">
                <?php 
                  $nombreProducto = str_replace(' ', '_', strtolower($devolucion['NOMBRE'])); 
                  $rutaImagen = "/SuperRosita/imgs/" . $nombreProducto . ".png";
                ?>
                <img src="<?php echo $rutaImagen; ?>" alt="<?php echo $devolucion['NOMBRE']; ?>">
                <div class="producto-info">
                    <strong><?php echo $devolucion['NOMBRE']; ?></strong>
                    <span>Cantidad: <?php echo $devolucion['CANTIDAD']; ?></span>
                    <span>Motivo: <?php echo $devolucion['DESCRIPCION']; ?></span>
                    <?php if ($devolucion['ESTADO'] == 4) : ?>
                        <span class="reembolsado">Reembolso Aprobado</span>
                    <?php elseif ($devolucion['ESTADO'] == 5) : ?>
                      <div class="reembolsopendiente">
                        <span>Reembolso Pendiente</span>
                        <div class="reembolso-botones">
                          <button class="aprobar-reembolso" onclick="aprobarReembolso(<?php echo $devolucion['CODIGO_CARRITO']; ?>)">Aprobar</button>
                          <button class="rechazar-reembolso" onclick="rechazarReembolso(<?php echo $devolucion['CODIGO_CARRITO']; ?>)">Rechazar</button>
                        </div>
                      </div>
                    <?php elseif ($devolucion['ESTADO'] == 6) : ?>
                      <span class="reembolsorechazado">Reembolso Rechazado</span>
                    <?php elseif ($devolucion['ESTADO'] == 7) : ?>
                      <span class="reembolsopendiente">Reembolso Cancelado Por El Cliente</span>
                    <?php endif; ?>
                </div>
            </div>
          <?php endforeach; ?>
        </div>
    </main>
  </div>

  <script>
    function aprobarReembolso(codigoCarrito) {
        if (confirm("¿Estás seguro de que deseas aprobar esta solicitud de reembolso?")) {
            fetch('/SuperRosita/index.php?action=aprobarReembolso', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    codigoCarrito: codigoCarrito
                })
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error al aprobar el reembolso');
                }
            }).catch(error => {
                console.error('Error:', error);
            });
        }
    }

    function rechazarReembolso(codigoCarrito) {
        if (confirm("¿Estás seguro de que deseas rechazar esta solicitud de reembolso?")) {
            fetch('/SuperRosita/index.php?action=rechazarReembolso', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    codigoCarrito: codigoCarrito
                })
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error al rechazar el reembolso');
                }
            }).catch(error => {
                console.error('Error:', error);
            });
        }
    }

    window.onclick = function(event) {
        const modalEditar = document.getElementById('modalEditarReembolso');
        if (event.target == modalEditar) {
            modalEditar.style.display = 'none';
        }
    }
  </script>

</body>
</html>