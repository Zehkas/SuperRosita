<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario - SuperRosita</title>
    <link rel="stylesheet" href="/SuperRosita/css/global.css">
    <link rel="stylesheet" href="/SuperRosita/css/perfil.css">
    <link rel="stylesheet" href="/SuperRosita/css/reportes.css">
    <link rel="stylesheet" href="/SuperRosita/css/inventario.css">
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
                    echo htmlspecialchars($_SESSION['nombre'] . " " . $_SESSION['apellido1'] . " " . $_SESSION['apellido2']);
                }
                ?>
            </h2>
            <div id="inventario" class="inventario-contenido">
                <p>Cargando inventario...</p>
            </div>
        </main>
    </div>

    <div id="modalInventario" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal()">&times;</span>
            <h2 id="modal-title">Título del Producto</h2>
            <img id="modalImagen" class="inventario-imagen-modal" alt="Imagen del Producto">
            <p id="capacidadProducto">Capacidad: 0/0</p>
            <div class="modal-buttons">
                <button id="btnReportarVentas">Reportar Ventas</button>
                <button id="btnRestockProducto">Restock De Producto</button>
            </div>
            <div class="modal-input-container">
                <input type="number" id="inputCantidad" placeholder="Ingresar cantidad" style="display: none;">
                <button id="btnConfirmar" style="display: none;">Confirmar</button>
            </div>
            <p id="avisoRestock" style="display: none;">El restock reportado supera la capacidad destinada al producto
                en inventario, ¿desea confirmar su reporte?</p>
            <p id="mensajeExito" style="display: none;"></p>
        </div>
    </div>

    <script>
        function mostrarInventario() {
            fetch(`/SuperRosita/controller/InventarioControlador.php?action=obtenerInventario`)
                .then(response => response.json())
                .then(data => {
                    const inventarioDiv = document.getElementById('inventario');
                    if (data.success) {
                        const contenido = generarTablaVerInventario(data.inventario);
                        inventarioDiv.innerHTML = contenido;
                    } else {
                        inventarioDiv.innerHTML = '<p>Error al cargar el inventario.</p>';
                    }
                }).catch(error => {
                    console.error('Error al obtener el inventario:', error);
                    document.getElementById('inventario').innerHTML = '<p>Error al cargar el inventario.</p>';
                });
        }

        function generarTablaVerInventario(inventario) {
            let contenido = '<div class="inventario-grid">';
            inventario.forEach(item => {
                const nombreProducto = (item.NOMBRE_PRODUCTO || '').toLowerCase().replace(/\s/g, '_');
                const imagenProducto = `/SuperRosita/imgs/${nombreProducto}.png`;
                const cantidadUsada = parseInt(item.CANTIDAD_USADA_INVENTARIO) || 0;
                const cantidadTotal = parseInt(item.CANTIDAD_TOTAL_INVENTARIO) || 0;
                const sobrecupo = (cantidadUsada > cantidadTotal) ? '<p class="sobrecupo">Producto Con Sobrecupo</p>' : '';

                contenido += `
            <div class="inventario-item" onclick="abrirModal(${item.CODIGO_INVENTARIO}, '${item.NOMBRE_PRODUCTO}', ${cantidadUsada}, ${cantidadTotal})">
                <img src="${imagenProducto}" class="inventario-imagen" alt="${item.NOMBRE_PRODUCTO || 'Producto'}">
                <div class="inventario-detalles">
                    <h3>${item.NOMBRE_PRODUCTO || 'Producto'}</h3>
                    <p>Capacidad: ${cantidadUsada}/${cantidadTotal}</p>
                    ${sobrecupo}
                </div>
            </div>
        `;
            });
            contenido += '</div>';
            return contenido;
        }

        document.addEventListener('DOMContentLoaded', function () {
            mostrarInventario();
        });

        function abrirModal(codigoInventario, nombreProducto, cantidadUsada, cantidadTotal) {
            const modal = document.getElementById("modalInventario");
            document.getElementById("modal-title").innerText = nombreProducto;
            document.getElementById("modalImagen").src = `/SuperRosita/imgs/${nombreProducto.toLowerCase().replace(/\s/g, '_')}.png`;
            document.getElementById("capacidadProducto").innerText = `Capacidad: ${cantidadUsada}/${cantidadTotal}`;
            modal.style.display = "block";

            const btnReportarVentas = document.getElementById("btnReportarVentas");
            const btnRestockProducto = document.getElementById("btnRestockProducto");
            const inputCantidad = document.getElementById("inputCantidad");
            const avisoRestock = document.getElementById("avisoRestock");
            const btnConfirmar = document.getElementById("btnConfirmar");
            const mensajeExito = document.getElementById("mensajeExito");

            btnReportarVentas.classList.remove("activo");
            btnRestockProducto.classList.remove("activo");
            inputCantidad.style.display = "none";
            avisoRestock.style.display = "none";
            mensajeExito.style.display = "none";
            btnConfirmar.style.display = "none";

            btnReportarVentas.style.display = "inline-block";
            btnRestockProducto.style.display = "inline-block";

            btnReportarVentas.onclick = function () {
                btnReportarVentas.classList.add("activo");
                btnRestockProducto.classList.remove("activo");
                inputCantidad.max = cantidadUsada;
                inputCantidad.value = '';
                avisoRestock.style.display = "none";
                inputCantidad.style.display = "block";
                btnConfirmar.style.display = "block";
            }

            btnRestockProducto.onclick = function () {
                btnRestockProducto.classList.add("activo");
                btnReportarVentas.classList.remove("activo");
                inputCantidad.removeAttribute("max");
                inputCantidad.value = '';
                avisoRestock.style.display = "none";
                inputCantidad.style.display = "block";
                btnConfirmar.style.display = "block";
            }

            inputCantidad.oninput = function () {
                const cantidadIngresada = parseInt(inputCantidad.value) || 0;
                if (btnRestockProducto.classList.contains("activo") && (cantidadUsada + cantidadIngresada > cantidadTotal)) {
                    avisoRestock.style.display = "block";
                } else {
                    avisoRestock.style.display = "none";
                }
            }

            btnConfirmar.onclick = function () {
                const cantidad = parseInt(inputCantidad.value) || 0;
                const accion = btnReportarVentas.classList.contains("activo") ? "reportarVentas" : "realizarRestock";

                if (cantidad > 0) {
                    fetch(`/SuperRosita/controller/InventarioControlador.php?action=${accion}`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `codigoInventario=${encodeURIComponent(codigoInventario)}&cantidad=${encodeURIComponent(cantidad)}`
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                mensajeExito.innerText = "Cambios realizados con éxito";
                                mensajeExito.style.display = "block";
                                inputCantidad.style.display = "none";
                                btnReportarVentas.style.display = "none";
                                btnRestockProducto.style.display = "none";
                                btnConfirmar.style.display = "none";
                                setTimeout(() => { window.location.reload(); }, 1000);
                            } else {
                                mensajeExito.innerText = `Error al realizar cambios: ${data.error}`;
                                mensajeExito.style.display = "block";
                                inputCantidad.style.display = "none";
                                btnReportarVentas.style.display = "none";
                                btnRestockProducto.style.display = "none";
                                btnConfirmar.style.display = "none";
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            mensajeExito.innerText = "Error al realizar cambios";
                            mensajeExito.style.display = "block";
                            inputCantidad.style.display = "none";
                            btnReportarVentas.style.display = "none";
                            btnRestockProducto.style.display = "none";
                            btnConfirmar.style.display = "none";
                        });
                }
            }
        }

        function cerrarModal() {
            const modal = document.getElementById("modalInventario");
            modal.style.display = "none";
        }

    </script>
</body>

</html>