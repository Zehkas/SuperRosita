<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/SuperRosita/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/SuperRosita/controller/CarritoControlador.php';

$dbConnection = (new Connection())->connect();
$carritoControlador = new CarritoControlador($dbConnection);
$idCliente = $_SESSION['codigo_cliente'];
$historialCompras = $carritoControlador->obtenerHistorialCompras($idCliente);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['codigoCarrito'], $_POST['descripcion'])) {
  $codigoCarrito = $_POST['codigoCarrito'];
  $descripcion = $_POST['descripcion'];

  if (isset($idCliente)) {
    $carritoControlador->iniciarReembolso($codigoCarrito, $descripcion);
    header("Location: /SuperRosita/perfil/historial");
    exit();
  } else {
    echo "Error: Cliente no autenticado.";
  }
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

      <div id="historial">
        <?php foreach ($historialCompras as $compra): ?>
          <div class="producto">
            <?php
            $nombreProducto = str_replace(' ', '_', strtolower($compra['NOMBRE']));
            $rutaImagen = "/SuperRosita/imgs/" . $nombreProducto . ".png";
            ?>
            <img src="<?php echo $rutaImagen; ?>" alt="<?php echo $compra['NOMBRE']; ?>">
            <div class="producto-info">
              <strong><?php echo $compra['NOMBRE']; ?></strong>
              <span>Cantidad: <?php echo $compra['CANTIDAD']; ?></span>
              <?php if ($compra['ESTADO'] == 4): ?>
                <span class="reembolsado">Producto Reembolsado</span>
              <?php elseif ($compra['ESTADO'] == 5): ?>
                <span class="reembolsopendiente">Reembolso En Revision...</span>
              <?php elseif ($compra['ESTADO'] == 6): ?>
                <span class="reembolsorechazado">Reembolso Rechazado</span>
              <?php elseif ($compra['ESTADO'] == 7): ?>
                <span class="reembolsopendiente">Reembolso Cancelado Por Cliente</span>
              <?php else: ?>
                <button class="producto-boton" onclick="abrirModal(<?php echo $compra['CODIGO_CARRITO']; ?>)">Iniciar Reembolso</button>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </main>
  </div>

  <div id="modalReembolso" class="modal">
    <div class="modal-content">
      <span class="close" onclick="cerrarModal()">&times;</span>
      <h2>Solicitar Reembolso</h2>
      <form id="formReembolso" method="post">
        <input type="hidden" name="codigoCarrito" id="codigoCarritoModal">
        <textarea name="descripcion" id="descripcion" maxlength="100"
          placeholder="Explica por qué deseas retornar el producto (máximo 100 caracteres)"></textarea>
        <div class="footer">
          <button type="submit">Enviar Solicitud</button>
          <button type="button" id="cancelarBtn" onclick="cerrarModal()">Cancelar</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function abrirModal(codigoCarrito) {
      document.getElementById('codigoCarritoModal').value = codigoCarrito;
      document.getElementById('modalReembolso').style.display = 'block';
    }

    function cerrarModal() {
      document.getElementById('modalReembolso').style.display = 'none';
    }

    window.onclick = function (event) {
      const modal = document.getElementById('modalReembolso');
      if (event.target == modal) {
        modal.style.display = 'none';
      }
    }
  </script>

</body>

</html>