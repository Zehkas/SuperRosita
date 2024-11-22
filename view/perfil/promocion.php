<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
$dbConnection = (new Connection())->connect();

// Crear instancia del controlador con la conexión a la base de datos
$promocionControlador = new PromocionControlador($dbConnection);
$promociones = $promocionControlador->PromocionesDisponibles();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar Promoción</title>
    <link rel="stylesheet" href="/SuperRosita/css/global.css">
    <link rel="stylesheet" href="/SuperRosita/css/perfil.css">
    <link rel="stylesheet" href="/SuperRosita/css/registro.css"> <!-- Línea añadida -->
    <style>
        .centrar {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            position: relative;
            top: calc(50vh - 42.22px);
            transform: translateY(-50%);
        }
        
    </style>
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
            <div class="centrar">
                <div class="contenedorRegistro">
                    <h2>Ingresar Promoción</h2>
                    <form action="/SuperRosita/index.php?action=promocion" method="post">
                        <div class="formInterior">
                            <label for="codigo_departamento">Departamento:</label>
                            <select id="codigo_departamento" name="codigo_departamento" required>
                                <option value="">Seleccione un departamento</option>
                                <option value="1">Frutas y Verduras</option>
                                <option value="2">Carniceria y Pescaderia</option>
                                <option value="3">Panaderia y Pasteleria</option>
                                <option value="4">Lacteos</option>
                                <option value="5">Congelados</option>
                                <option value="6">Bebestibles</option>
                                <option value="7">Despensa</option>
                                <option value="8">Limpieza y Hogar</option>
                                <option value="9">Cuidado Personal</option>
                                <option value="10">Jugueteria</option>
                            </select>

                            <label for="descuento">Descuento (%):</label>
                            <input type="number" id="descuento" name="descuento" required min="1" max="100" oninput="actualizarPorcentaje()">
                            
                            <p id="porcentajeTexto" style="font-weight: bold;"></p> 

                            <input type="submit" value="Registrar Promoción">
                        </div>
                    </form>
                </div>
                <div class="contenedorRegistro">
                    <h2>Quitar Promoción</h2>
                    <form action="/SuperRosita/index.php?action=quitarpromocion" method="post">
                        <div class="formInterior">
                            <label for="nombre_departamento">Departamento:</label>
                            <select id="nombre_departamento" name="codigo_departamento" required>
                                <option value="">Seleccione un departamento</option>
                                <?php
                                // Verificar si existen promociones disponibles
                                if (!empty($promociones)) {
                                    foreach ($promociones as $promocion) {
                                        if (isset($promocion['CODIGO_DEPARTAMENTO'], $promocion['NOMBRE_DEPARTAMENTO'])) {
                                            echo '<option value="' . htmlspecialchars($promocion['CODIGO_DEPARTAMENTO']) . '">'
                                                . htmlspecialchars($promocion['NOMBRE_DEPARTAMENTO']) . 
                                                '</option>';
                                        }
                                    }
                                } else {
                                    // Mensaje si no hay promociones
                                    echo '<option value="">No hay promociones activas</option>';
                                }
                                ?>
                            </select>
                            <p id="porcentajeTexto" style="font-weight: bold;"></p> 
                            <input type="submit" value="Quitar Promoción">
                        </div>
                    </form>
                </div>



                    <script>
                    // Función para actualizar el porcentaje
                    function actualizarPorcentaje() {
                        var descuento = document.getElementById('descuento').value;
                        var porcentajeTexto = document.getElementById('porcentajeTexto');

                        if (descuento) {
                            porcentajeTexto.textContent = descuento + "%";
                        } else {
                            porcentajeTexto.textContent = ""; // Si no hay valor, limpiar el texto
                        }
                    }
                    </script>
                    <?php if (isset($_SESSION['error_registro_promocion'])): ?>
                        <p style="color: #FF0000"><?php echo $_SESSION['error_registro_promocion']; ?></p>
                        <?php unset($_SESSION['error_registro_promocion']); ?>
                    <?php endif; ?>
                    <?php if (isset($_GET['mensaje_exito'])): ?>
                        <p style="color: #00A000"><?php echo htmlspecialchars($_GET['mensaje_exito']); ?></p>
                    <?php endif; ?>

                </div>
            </div>

        </main>
    </div>
</body>
</html> 