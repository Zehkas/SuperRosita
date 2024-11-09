<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro SuperRosita</title>
    <link rel="stylesheet" href="../css/registro.css">
    <link rel="stylesheet" href="../css/global.css">
</head>
<body>

    <div class = "centrar">
        <div class = "contenedorRegistro">
            <h2>Registro en SuperRosita</h2>
            <form action = "../index.php?action=registro" method = "post">
                <div class = formInterior>
                    <label for="email">Correo:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required>

                    <label for="apellidoPaterno">Apellido Paterno:</label>
                    <input type="text" id="apellidoPaterno" name="apellidoPaterno" required>

                    <label for="apellidoMaterno">Apellido Materno:</label>
                    <input type="text" id="apellidoMaterno" name="apellidoMaterno" required>

                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>

                    <input type="submit" value = "Registrar">
                </div>
            </form>
        </div>
    </div>

</body>
</html>
