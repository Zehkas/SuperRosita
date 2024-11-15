<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro SuperRosita</title>
    <link rel="stylesheet" href="../css/registro.css">
    <link rel="stylesheet" href="../css/global.css">
    <style>
        .centrar {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
    </style>
</head>

<body>

    <div class="centrar">
        <div class="contenedorRegistro">
            <h2>Registro en SuperRosita</h2>
            <form action="../index.php?action=registro" method="post">
                <div class="formInterior">
                    <label for="correo">Correo:</label>
                    <input type="email" id="correo" name="correo" required>

                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required>

                    <label for="apellido1">Apellido Paterno:</label>
                    <input type="text" id="apellido1" name="apellido1" required>

                    <label for="apellido2">Apellido Materno:</label>
                    <input type="text" id="apellido2" name="apellido2" required>

                    <label for="contrasena">Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" required>

                    <label for="region">Región:</label>
                    <select id="region" name="region" required>
                        <option value="">Seleccione una región</option>
                        <option value="1">Maule</option>
                        <option value="2">Ñuble</option>
                        <!-- Agrega más opciones si tienes otras regiones en tu base de datos -->
                    </select>

                    <input type="submit" value="Registrar">
                </div>
            </form>

            <div style="text-align: center; margin-top: 20px;">
                <p>¿Ya tienes una cuenta? <a href="../view/login.php">Iniciar sesión</a></p>
            </div>
            
        </div>
    </div>

</body>
</html>