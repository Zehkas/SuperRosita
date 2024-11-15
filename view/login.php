<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliente SuperRosita</title>
    <link rel="stylesheet" href="../css/login.css">
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

    <?php include './header.php'; ?>

    <div class = "centrar">
        <div class = "contenedorLogin">
            <h2>Inicio De Sesión</h2>
            <form action = "../index.php?action=login" method = "post">
                <div class = formInterior>
                    <label for="email">Correo:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>

                    <input type="submit" value = "Iniciar Sesión">
                </div>
            </form>
            <p style="text-align: center; margin-top: 10px;">¿No tienes una cuenta? <a href="../view/registro.php">Registrarse</a></p>
        </div>
    </div>


</body>
</html>
