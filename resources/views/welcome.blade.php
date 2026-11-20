<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Estudiantes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">API de Estudiantes</h1>
            <span id="healthBadge" class="badge bg-secondary fs-6">Verificando BD...</span>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        Registrar Estudiante
                    </div>
                    <div class="card-body">
                        <form id="formEstudiante">
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" id="nombre" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Correo</label>
                                <input type="email" id="correo" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Programa</label>
                                <input type="text" id="programa" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Guardar</button>
                        </form>
                        <div id="mensajeForm" class="mt-3"></div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        Buscar por ID
                    </div>
                    <div class="card-body">
                        <div class="input-group mb-3">
                            <input type="number" id="buscarId" class="form-control" placeholder="Ej: 1 o 999">
                            <button id="btnBuscar" class="btn btn-dark" type="button">Buscar</button>
                        </div>
                        <div id="resultadoBusqueda"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Lista de Estudiantes</span>
                        <button id="btnRecargar" class="btn btn-sm btn-outline-secondary">Recargar</button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Correo</th>
                                        <th>Programa</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaEstudiantes">
                                    <tr>
                                        <td colspan="4" class="text-center py-3">Cargando datos...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function verificarHealth() {
            const badge = document.getElementById('healthBadge');
            try {
                const res = await fetch('/api/health');
                const data = await res.json();
                if (res.ok && data.status === 'ok') {
                    badge.className = 'badge bg-success fs-6';
                    badge.textContent = 'BD: Conectada';
                } else {
                    badge.className = 'badge bg-danger fs-6';
                    badge.textContent = 'BD: Desconectada';
                }
            } catch (error) {
                badge.className = 'badge bg-danger fs-6';
                badge.textContent = 'Error de conexion';
            }
        }

        async function cargarEstudiantes() {
            const tbody = document.getElementById('tablaEstudiantes');
            try {
                const res = await fetch('/api/estudiantes');
                const data = await res.json();
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center py-3">No hay estudiantes registrados</td></tr>';
                    return;
                }
                tbody.innerHTML = data.map(est => `
                    <tr>
                        <td>${est.id}</td>
                        <td>${est.nombre}</td>
                        <td>${est.correo}</td>
                        <td>${est.programa}</td>
                    </tr>
                `).join('');
            } catch (error) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center text-danger py-3">Error al cargar la lista</td></tr>';
            }
        }

        document.getElementById('formEstudiante').addEventListener('submit', async function (e) {
            e.preventDefault();
            const mensaje = document.getElementById('mensajeForm');
            const payload = {
                nombre: document.getElementById('nombre').value,
                correo: document.getElementById('correo').value,
                programa: document.getElementById('programa').value
            };

            try {
                const res = await fetch('/api/estudiantes', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();

                if (res.status === 201) {
                    mensaje.innerHTML = '<div class="alert alert-success py-2">Estudiante registrado con exito</div>';
                    this.reset();
                    cargarEstudiantes();
                } else {
                    const errores = data.errors ? Object.values(data.errors).flat().join('<br>') : (data.message || 'Error al guardar');
                    mensaje.innerHTML = `<div class="alert alert-danger py-2">${errores}</div>`;
                }
            } catch (error) {
                mensaje.innerHTML = '<div class="alert alert-danger py-2">Error de red al guardar</div>';
            }
        });

        document.getElementById('btnBuscar').addEventListener('click', async function () {
            const id = document.getElementById('buscarId').value;
            const resultado = document.getElementById('resultadoBusqueda');
            if (!id) return;

            try {
                const res = await fetch(`/api/estudiantes/${id}`);
                const data = await res.json();

                if (res.ok) {
                    resultado.innerHTML = `
                        <div class="alert alert-info py-2 mb-0">
                            <strong>ID ${data.id}:</strong> ${data.nombre}<br>
                            <small>${data.correo} - ${data.programa}</small>
                        </div>
                    `;
                } else {
                    resultado.innerHTML = `<div class="alert alert-warning py-2 mb-0">${data.mensaje || 'No encontrado'} (404)</div>`;
                }
            } catch (error) {
                resultado.innerHTML = '<div class="alert alert-danger py-2 mb-0">Error al buscar</div>';
            }
        });

        document.getElementById('btnRecargar').addEventListener('click', cargarEstudiantes);

        verificarHealth();
        cargarEstudiantes();
    </script>
</body>
</html>