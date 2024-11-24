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
    <title>Ingresar Trabajador</title>
    <link rel="stylesheet" href="/SuperRosita/css/global.css">
    <link rel="stylesheet" href="/SuperRosita/css/perfil.css">
    <link rel="stylesheet" href="/SuperRosita/css/registro.css"> <!-- Línea añadida -->
    <style>
        .centrar {
            display: flex;
            justify-content: center;
            align-items: center;
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

                <?php if (isset($_SESSION['codigo_cliente'])): ?>
                    <li><a href="/SuperRosita/perfil/historial">Historial de compras</a></li>
                    <li><a href="/SuperRosita/perfil/devolucion">Devolucion</a></li>
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
                        echo htmlspecialchars("Trabajador " . $_SESSION['nombre'] . " " . $_SESSION['apellido1'] . " " . $_SESSION['apellido2']);
                    } else {
                        echo htmlspecialchars("Cliente " . $_SESSION['nombre'] . " " . $_SESSION['apellido1'] . " " . $_SESSION['apellido2']);
                    }
                }
                ?>
            </h2>

            <div class="centrar">
                <div class="contenedorRegistro">
                    <h2>Registro de Trabajador</h2>
                    <form action="/SuperRosita/index.php?action=registro_trabajador" method="post">
                        <div class="formInterior">
                            <label for="nombre">Nombre:</label>
                            <input type="text" id="nombre" name="nombre" required>

                            <label for="apellido1">Apellido Paterno:</label>
                            <input type="text" id="apellido1" name="apellido1" required>

                            <label for="apellido2">Apellido Materno:</label>
                            <input type="text" id="apellido2" name="apellido2" required>
                            <label for="codigo_departamento">Departamento:</label>
                            <select id="codigo_departamento" name="codigo_departamento" required>
                                <option value="">Seleccione un departamento</option>
                                <option value="11">Cajas</option>
                                <option value="13">Reponedores</option>
                            </select>

                            <label for="codigo_cargo">Cargo:</label>
                            <select id="codigo_cargo" name="codigo_cargo" required>
                                <option value="">Seleccione un cargo</option>
                                <option value="2">Cajero</option>
                                <option value="3">Reponedor</option>
                            </select>

                            <input type="submit" value="Registrar Trabajador">
                        </div>
                    </form>

                    <?php if (isset($_SESSION['error_registro_trabajador'])): ?>
                        <p style="color: #FF0000"><?php echo $_SESSION['error_registro_trabajador']; ?></p>
                        <?php unset($_SESSION['error_registro_trabajador']); ?>
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