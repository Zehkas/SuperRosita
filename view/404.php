<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página no encontrada</title>
    <link rel="stylesheet" href="/SuperRosita/css/global.css">
    <style>
        .centrar {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        .contenedor404 {
            max-width: 600px;
            padding: 20px;
            border: 5px solid #000;
            border-radius: 10px;
            background-color: #fae4f0;
            font-family: 'Times New Roman', Times, serif;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
        }
        .contenedor404 button {
            cursor: pointer;
            color: #000;
            font-family: 'Times New Roman', Times, serif;
            font-size: 16px;
            background-color: #333;
            padding: 10px 15px;
            border-radius: 10px;
            color: #fff;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>

    <div class="centrar">
        <div class="contenedor404">
            <h2>Error 404</h2>
            <p>Lo sentimos, la página que estás buscando no se pudo encontrar.</p>
            <button onclick="window.location.href='/SuperRosita/'">Volver al Inicio</button>
        </div>
    </div>
</body>
</html>
