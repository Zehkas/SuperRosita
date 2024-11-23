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
    <title>Reportes - SuperRosita</title>
    <link rel="stylesheet" href="/SuperRosita/css/global.css">
    <link rel="stylesheet" href="/SuperRosita/css/perfil.css">
    <link rel="stylesheet" href="/SuperRosita/css/reportes.css">
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
                
                <?php if (isset($_SESSION['codigo_cargo'])): ?>
                    <li><a href="/SuperRosita/perfil/reportes">Solicitar Reportes</a></li>
                <?php endif; ?>

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
                    echo htmlspecialchars($_SESSION['nombre'] . " " . $_SESSION['apellido1'] . " " . $_SESSION['apellido2']);
                }
                ?>
            </h2>
            <div class="submenu">
                <button onclick="mostrarReporte(1)">Más Vendidos</button>
                <button onclick="mostrarReporte(2)">Más Devoluciones</button>
                <button onclick="mostrarReporte(3)">Clientes Más Fieles</button>
            </div>
            <div id="reportes" class="reporte-contenido">
                <p>Selecciona un reporte para visualizar.</p>
            </div>
        </main>
    </div>
    <script>

        function mostrarReporte(numeroReporte) {
            fetch(`/SuperRosita/controller/ReporteControlador.php?action=obtenerReporte&numero=${numeroReporte}`)
                .then(response => response.json())
                .then(data => {
                    const reportesDiv = document.getElementById('reportes');
                    if (data.success) {
                        let contenido = '';
                        switch (numeroReporte) {
                            case 1:
                                contenido = generarTablaMasVendidos(data.reportes);
                                break;
                            case 2:
                                contenido = generarTablaMasDevoluciones(data.reportes);
                                break;
                            case 3:
                                contenido = generarTablaClientesFieles(data.reportes);
                                break;
                        }
                        reportesDiv.innerHTML = contenido;
                    } else {
                        reportesDiv.innerHTML = '<p>Error al cargar el reporte.</p>';
                    }
                }).catch(error => {
                    console.error('Error al obtener el reporte:', error);
                    document.getElementById('reportes').innerHTML = '<p>Error al cargar el reporte.</p>';
                });
        }

function generarTablaMasVendidos(reportes) {
    let contenido = `
        <table class="tabla-reporte">
            <tr>
                <th>Imagen</th>
                <th>Información del Producto</th>
                <th>Cantidad Vendida</th>
                <th>Ganancia Total</th>
            </tr>
    `;
    reportes.forEach(reporte => {
        contenido += `
            <tr>
                <td><img src="/SuperRosita/imgs/${(reporte.NOMBRE_PRODUCTO || '').toLowerCase().replace(/\s/g, '_')}.png" class="reporte-imagen" alt="${reporte.NOMBRE_PRODUCTO || 'N/A'}"></td>
                <td>
                    <div>${reporte.NOMBRE_PRODUCTO || 'N/A'}</div>
                    <div>$${reporte.PRECIO_VENTA_PRODUCTO || 'N/A'}</div>
                    <div>${reporte.DEPARTAMENTO_PRODUCTO || 'N/A'}</div>
                </td>
                <td>
                    ${reporte.CANTIDAD_VENDIDA || 'N/A'}
                    ${reporte.DEVOLUCIONES_APROBADAS > 0 ? `<div class="estado-aprobado">-${reporte.DEVOLUCIONES_APROBADAS} (Devoluciones)</div>` : ''}
                </td>
                <td>
                    $${reporte.GANANCIA_TOTAL || 'N/A'}
                    ${reporte.DINERO_PERDIDO_APROBADAS > 0 ? `<div class="estado-aprobado">-$${reporte.DINERO_PERDIDO_APROBADAS} (Devoluciones)</div>` : ''}
                </td>
            </tr>
        `;
    });
    contenido += `</table>`;
    return contenido;
}

function generarTablaMasDevoluciones(reportes) {
    let contenido = `
        <table class="tabla-reporte">
            <tr>
                <th>Imagen</th>
                <th>Información del Producto</th>
                <th>Solicitudes Totales</th>
                <th>Devoluciones Aprobadas</th>
                <th>Devoluciones Pendientes</th>
                <th>Devoluciones Rechazadas</th>
            </tr>
    `;
    reportes.forEach(reporte => {
        contenido += `
            <tr>
                <td><img src="/SuperRosita/imgs/${(reporte.NOMBRE_PRODUCTO || '').toLowerCase().replace(/\s/g, '_')}.png" class="reporte-imagen" alt="${reporte.NOMBRE_PRODUCTO || 'N/A'}"></td>
                <td>
                    <div>${reporte.NOMBRE_PRODUCTO || 'N/A'}</div>
                    <div>$${reporte.PRECIO_VENTA_PRODUCTO || 'N/A'}</div>
                    <div>${reporte.DEPARTAMENTO_PRODUCTO || 'N/A'}</div>
                </td>
                <td>${reporte.TOTAL_DEVOLUCIONES || 'N/A'}</td>
                <td class="estado-aprobado">${reporte.DEVOLUCIONES_APROBADAS || 'N/A'} ($${reporte.DINERO_PERDIDO_APROBADAS || 'N/A'})</td>
                <td class="estado-pendiente">${reporte.DEVOLUCIONES_PENDIENTES || 'N/A'} ($${reporte.DINERO_PENDIENTE || 'N/A'})</td>
                <td class="estado-rechazado">${reporte.DEVOLUCIONES_RECHAZADAS || 'N/A'} ($${reporte.DINERO_RECHAZADO || 'N/A'})</td>
            </tr>
        `;
    });
    contenido += `</table>`;
    return contenido;
}
function generarTablaClientesFieles(reportes) {
    let contenido = `
        <table class="tabla-reporte">
            <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Total Items Comprados</th>
                <th>Total Gastado En SuperRosita</th>
            </tr>
    `;
    reportes.forEach(reporte => {
        contenido += `
            <tr>
                <td>${reporte.NOMBRE_CLIENTE} ${reporte.APELLIDO1_CLIENTE || ''}</td>
                <td>${reporte.CORREO_CLIENTE || 'N/A'}</td>
                <td>${reporte.CANTIDAD_COMPRADA || 'N/A'}</td>
                <td>$${reporte.TOTAL_GASTADO || 'N/A'}</td>
            </tr>
        `;
    });
    contenido += `</table>`;
    return contenido;
}
</script>
</body>
</html>
