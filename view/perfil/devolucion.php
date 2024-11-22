<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/SuperRosita/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/SuperRosita/controller/CarritoControlador.php';

$dbConnection = (new Connection())->connect();
$carritoControlador = new CarritoControlador($dbConnection);
$idCliente = $_SESSION['codigo_cliente'];
$devoluciones = $carritoControlador->obtenerDevoluciones($idCliente);

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Devoluciones</title>
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
            <?php if (isset($_SESSION['codigo_cliente'])): ?>
            <li><a href="/SuperRosita/perfil/historial">Historial de compras</a></li>
            <li><a href="/SuperRosita/perfil/devolucion">Devoluciones</a></li>
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
                    <?php if ($devolucion['ESTADO'] == 4) : ?>
                        <span class="reembolsado">Producto Reembolsado</span>
                    <?php elseif ($devolucion['ESTADO'] == 5) : ?>

                      <div class="reembolsopendiente">
                        <span>Reembolso En Revisión</span>
                        <button onclick="abrirModalEditar(<?php echo $devolucion['CODIGO_CARRITO']; ?>)">Editar Motivo</button>
                        <button onclick="cancelarReembolso(<?php echo $devolucion['CODIGO_CARRITO']; ?>)">Cancelar Solicitud</button>
                      </div>
                      

                    <?php elseif ($devolucion['ESTADO'] == 6) : ?>
                      <span class="reembolsorechazado">Reembolso Rechazado</span>
                    <?php elseif ($devolucion['ESTADO'] == 7) : ?>
                      <span class="reembolsopendiente">Reembolso Cancelado Por Cliente</span>
                    <?php endif; ?>
                </div>
            </div>
          <?php endforeach; ?>
        </div>
    </main>
  </div>

<div id="modalEditarReembolso" class="modal">
  <div class="modal-content">
    <span class="close" onclick="cerrarModalEditar()">&times;</span>
    <h2>Editar Motivo del Reembolso</h2>
    <form id="formEditarReembolso" method="post" action="/SuperRosita/index.php?action=editarReembolso">
      <input type="hidden" name="codigoCarrito" id="codigoCarritoModalEditar">
      <textarea name="descripcion" id="descripcionEditar" maxlength="100" placeholder="Editar motivo del reembolso (máximo 100 caracteres)"></textarea>
      <div class="footer">
        <button type="submit">Guardar Cambios</button>
        <button type="button" id="cancelarBtnEditar" onclick="cerrarModalEditar()">Cancelar</button>
      </div>
    </form>
  </div>
</div>


  <script>
    function abrirModalEditar(codigoCarrito) {
        document.getElementById('codigoCarritoModalEditar').value = codigoCarrito;
        document.getElementById('modalEditarReembolso').style.display = 'block';
    }

    function cerrarModalEditar() {
        document.getElementById('modalEditarReembolso').style.display = 'none';
    }

    function cancelarReembolso(codigoCarrito) {
        if (confirm("¿Estás seguro de que deseas cancelar la solicitud de reembolso?")) {
            fetch('/SuperRosita/index.php?action=cancelarReembolso', {
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
                    alert('Error al cancelar la solicitud de reembolso');
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