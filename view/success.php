
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Exitoso</title>
    <link rel="stylesheet" href="/SuperRosita/css/global.css">
    <style>
        .centrar {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            top: calc(50vh - 42.22px);
            transform: translateY(-50%);
        }
        .contenedorSuccess{
            width: 35%;
            padding: 20px;
            border: 5px solid #000;
            border-radius: 10px;
            background-color: #fae4f0;
            text-align: center;
            font-family: 'Times New Roman', Times, serif;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
        }
        
        .contenedorSuccess button{
            cursor: pointer;
            color: #000;
            font-family: 'Times New Roman', Times, serif;
            font-size: 16px;
            text-align: center;
            color: #fff;
            background-color: #333;
            padding: 10px 15px;
            border-radius: 10px;
        }
    
    </style>
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>

    <?php if (isset($_SESSION['usuario'])): ?>
    <div class="centrar">
        <div class = "contenedorSuccess">
            <h2>Inicio Exitoso!</h2>
            <p>Iniciaste Sesión en SuperRosita.</p>
            <button onclick="window.location.href='/SuperRosita/'">Volver al Inicio</button>
        </div>
    </div>

    <?php else: ?>
    <div class="centrar">
        <div class = "contenedorSuccess">
            <h2>Registro Exitoso!</h2>
            <p>Gracias por registrarte en SuperRosita. Ahora puedes iniciar sesion</p>
            <button onclick="window.location.href='/SuperRosita/'">Volver al Inicio</button>
        </div>
    </div>
    <?php endif; ?>

</body>
</html>

