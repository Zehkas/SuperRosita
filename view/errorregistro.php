<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error de Inicio de Sesión</title>
    <link rel="stylesheet" href="/SuperRosita/css/global.css"> <!-- Asegúrate de incluir tus estilos globales -->
    <style>
        /* Estilos para centrar el cuadro de error */
        .centrar {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f8f8;
        }

        .errorCuadro {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 300px;
            width: 100%;
        }

        .errorCuadro h2 {
            color: #d9534f;
            margin-bottom: 15px;
        }

        .errorCuadro button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .errorCuadro button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <?php include __DIR__ . '/header.php'; ?>
    
    <div class="centrar">
        <div class="errorCuadro">
            <h2>Error al registrarse</h2>
            <button onclick="window.history.back()">Volver atrás</button>
        </div>
    </div>
</body>
</html>
