<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>APIs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Leaflet CSS para el mapa -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <!-- Font Awesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        /* Estilos generales  */
        .map-container,
        .canvas-container {
            height: 420px;
            border-radius: 8px;
            margin-top: 20px;
            border: 1px solid #dee2e6;
            background-color: #f8f9fa;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        /* Estilo para los títulos  */
        .section-title {
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Animación que agregué para el ícono del título */
        .section-title i {
            color: #3498db;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Barra de herramientas  */
        .toolbar {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        /* Estilo para botones  */
        .btn-tool {
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-tool:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Canvas con estilo */
        #drawingCanvas {
            background-color: white;
            width: 100%;
            height: 100%;
            display: block;
            cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%233498db" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19l7-7 3 3-7 7-3-3z"></path><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path><path d="M2 2l7.586 7.586"></path><circle cx="11" cy="11" r="2"></circle></svg>') 8 8, crosshair;
            border-radius: 6px;
            box-shadow: inset 0 0 8px rgba(0, 0, 0, 0.1);
        }

        /* Selector de colores */
        .color-picker {
            display: flex;
            gap: 6px;
        }

        .color-option {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            cursor: pointer;
            transition: transform 0.2s;
            border: 2px solid transparent;
        }

        .color-option:hover {
            transform: scale(1.1);
        }

        .color-option.active {
            border-color: #2c3e50;
            transform: scale(1.1);
        }

        /* Control de tamaño del pincel*/
        .brush-control {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #fff;
            padding: 8px 12px;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .brush-control label {
            font-size: 14px;
            font-weight: 500;
            color: #2c3e50;
            margin: 0;
        }

        .brush-size-value {
            width: 30px;
            text-align: center;
            font-weight: bold;
            color: #3498db;
        }
    </style>
</head>

<body>

    <div class="container py-4">
        <!-- Sección de Geolocalización  -->
        <div class="card mb-4">
            <div class="card-body">
                <h3 class="section-title">
                    <i class="fas fa-map-marked-alt"></i> Geolocalización
                </h3>
                <button class="btn btn-primary btn-location" onclick="mostrarUbicacion()">
                    <i class="fas fa-location-arrow"></i> Mostrar ubicación
                </button>
                <div id="locationMap" class="map-container"></div>
                <div id="positionInfo" class="location-data" style="display:none;">
                    <h5>Coordenadas:</h5>
                    <p>Latitud: <span id="latitudeValue" class="fw-bold"></span></p>
                    <p>Longitud: <span id="longitudeValue" class="fw-bold"></span></p>
                </div>
            </div>
        </div>

        <!-- Sección de Canvas -->
        <div class="card mb-4">
            <div class="card-body">
                <h3 class="section-title">
                    <i class="fas fa-paint-brush fa-fw"></i> Canvas Creativo
                </h3>

                <!-- Barra de herramientas  -->
                <div class="toolbar">
                    <button id="btnDraw" class="btn btn-primary btn-tool">
                        <i class="fas fa-pencil-alt"></i> Dibujar
                    </button>
                    <button id="btnClear" class="btn btn-warning btn-tool">
                        <i class="fas fa-broom"></i> Limpiar
                    </button>
                    <button id="btnSave" class="btn btn-success btn-tool">
                        <i class="fas fa-file-export"></i> Exportar
                    </button>

                    <!-- Selector de colores  -->
                    <div class="color-picker">
                        <div class="color-option active" data-color="black" style="background: #000;"></div>
                        <div class="color-option" data-color="#e74c3c" style="background: #e74c3c;"></div>
                        <div class="color-option" data-color="#3498db" style="background: #3498db;"></div>
                        <div class="color-option" data-color="#2ecc71" style="background: #2ecc71;"></div>
                        <div class="color-option" data-color="#9b59b6" style="background: #9b59b6;"></div>
                        <div class="color-option" data-color="#f1c40f" style="background: #f1c40f;"></div>
                    </div>

                    <!-- Control de tamaño del pincel -->
                    <div class="brush-control">
                        <label><i class="fas fa-brush"></i> Tamaño:</label>
                        <input type="range" id="brushSize" min="1" max="30" value="5" class="form-range">
                        <span id="brushSizeValue" class="brush-size-value">5</span>
                    </div>
                </div>

                <!-- Área de dibujo -->
                <div class="canvas-container">
                    <canvas id="drawingCanvas"></canvas>
                </div>
            </div>
        </div>

        <!-- Sección de Video (reservada para futuras implementaciones) -->
        <div class="card">
    <div class="card-body">
        <h3 class="section-title">
            <i class="fas fa-video"></i> Video
        </h3>
        <div>
            <div class="container text-center">
                <video id="videoPlayer" width="480" class="mb-3 border rounded">
                    <source src="{{ asset('media/Sonic%20Mania%20Opening%20Animation.mp4') }}" type="video/mp4">
                    Tu navegador no soporta la etiqueta <code>video</code>.
                </video>

                <!-- Controles -->
                <div class="mb-3">
                <button class="btn btn-warning me-2" onclick="video.currentTime -= 10"><< 10s</button>
                <button class="btn btn-success me-2" onclick="video.play()">&#9658;</button>
                    <button class="btn btn-danger me-2" onclick="video.pause()">| |</button>

                    <button class="btn btn-warning me-2" onclick="video.currentTime += 10">10s >></button>
                </div>

                <!-- Tiempo actual / duración -->
                <div class="mb-3">
                    <span id="tiempoActual">0:00</span> / <span id="duracion">0:00</span>
                </div>

                <!-- Velocidad de reproducción -->
                <div class="mb-3">
                    <div>
                        <small>(Para que las funciones de adelantar y retroceder funcionen correctamente el video debe haber cargado en su totalidad)</small>
                    </div>
                     <br>
                    <label for="velocidad" class="form-label">Velocidad:</label>
                    <select id="velocidad" class="form-select w-auto d-inline" onchange="video.playbackRate = this.value">
                        <option value="0.5">0.5x</option>
                        <option value="1" selected>1x (Normal)</option>
                        <option value="1.5">1.5x</option>
                        <option value="2">2x</option>
                    </select>
                </div>

                <!-- Control de volumen -->
                <div class="mb-3">
                    <label for="volumen" class="form-label">Volumen:</label>
                    <input type="range" id="volumen" min="0" max="1" step="0.01" value="1" class="form-range w-50"
                        onchange="video.volume = this.value">
                </div>
            </div>
        </div>
    </div>
</div>
        <!-- seccion del web worker -->
        <div class="card">
            <div class="card-body">
                <h3 class="section-title">
                    <i class="fas fa-video"></i> Web Worker
                </h3>
                <button id="botonIniciar" class="btn btn-primary">Iniciar calculo</button>
                <p id="resultado"></p>
            </div>
        </div>
    </div>


    <!-- Scripts que necesité agregar -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <!-- Script de Geolocalización  -->
    <script>
        // Geolocalización implementada
        let mapa;
        let marcador = null;

        function mostrarUbicacion() {
            if (!navigator.geolocation) return alert("Geolocalización no soportada");

            navigator.geolocation.getCurrentPosition(
                posicion => {
                    const { latitude: lat, longitude: lng } = posicion.coords;
                    document.getElementById('latitudeValue').textContent = lat;
                    document.getElementById('longitudeValue').textContent = lng;
                    document.getElementById('positionInfo').style.display = 'block';

                    if (!mapa) {
                        mapa = L.map('locationMap').setView([lat, lng], 15);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapa);
                    } else {
                        mapa.setView([lat, lng], 15);
                    }

                    if (marcador !== null) {
                        mapa.removeLayer(marcador);
                    }

                    marcador = L.marker([lat, lng]).addTo(mapa).bindPopup("¡Se encuentra aquí!").openPopup();
                },
                error => {
                    const mensajes = {
                        [error.PERMISSION_DENIED]: "Permiso denegado",
                        [error.POSITION_UNAVAILABLE]: "Ubicación no disponible",
                        [error.TIMEOUT]: "Tiempo de espera agotado"
                    };
                    alert(`Error: ${mensajes[error.code] || "Desconocido"}`);
                },
                { timeout: 10000 }
            );
        }
    </script>

    <!-- Script del Canvas  -->
    <script>
        // Canvas 
        document.addEventListener('DOMContentLoaded', function () {
            // Configuración inicial del Canvas
            const canvas = document.getElementById('drawingCanvas');
            const ctx = canvas.getContext('2d');
            let isDrawing = false;
            let currentColor = 'black';
            let brushSize = 5;
            let currentTool = 'pencil';
            let startX, startY;

            //  ajusta el tamaño del canvas
            function resizeCanvas() {
                const container = canvas.parentElement;
                canvas.width = container.clientWidth;
                canvas.height = container.clientHeight;
                ctx.fillStyle = 'white';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
            }
            resizeCanvas();
            window.addEventListener('resize', resizeCanvas);

            // Herramientas de dibujo 
            const tools = {
                pencil: function (e) {
                    if (!isDrawing) return;

                    ctx.lineWidth = brushSize;
                    ctx.lineCap = 'round';
                    ctx.strokeStyle = currentColor;

                    const rect = canvas.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;

                    ctx.lineTo(x, y);
                    ctx.stroke();
                    ctx.beginPath();
                    ctx.moveTo(x, y);
                }
            };

            // Eventos de dibujo 
            canvas.addEventListener('mousedown', function (e) {
                isDrawing = true;
                const rect = canvas.getBoundingClientRect();
                startX = e.clientX - rect.left;
                startY = e.clientY - rect.top;

                if (currentTool === 'pencil') {
                    ctx.beginPath();
                    ctx.moveTo(startX, startY);
                }
            });

            canvas.addEventListener('mousemove', function (e) {
                if (tools[currentTool]) {
                    tools[currentTool](e);
                }
            });

            canvas.addEventListener('mouseup', function () {
                isDrawing = false;
            });

            canvas.addEventListener('mouseout', function () {
                isDrawing = false;
            });

            // Controladores de botones
            document.getElementById('btnDraw').addEventListener('click', function () {
                currentTool = 'pencil';
                canvas.style.cursor = 'url(\'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="' +
                    encodeURIComponent(currentColor) + '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19l7-7 3 3-7 7-3-3z"></path><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path><path d="M2 2l7.586 7.586"></path><circle cx="11" cy="11" r="2"></circle></svg>\') 8 8, crosshair';
            });

            document.getElementById('btnClear').addEventListener('click', function () {
                ctx.fillStyle = 'white';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
            });

            document.getElementById('btnSave').addEventListener('click', function () {
                const link = document.createElement('a');
                link.download = 'creación-' + new Date().toISOString().slice(0, 10) + '.jpg';
                link.href = canvas.toDataURL('image/jpeg', 0.9);
                link.click();
            });

            // Selector de color 
            document.querySelectorAll('.color-option').forEach(option => {
                option.addEventListener('click', function () {
                    currentColor = this.getAttribute('data-color');
                    document.querySelectorAll('.color-option').forEach(opt => opt.classList.remove('active'));
                    this.classList.add('active');

                    //  cursor 
                    if (currentTool === 'pencil') {
                        canvas.style.cursor = 'url(\'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="' +
                            encodeURIComponent(currentColor) + '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19l7-7 3 3-7 7-3-3z"></path><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path><path d="M2 2l7.586 7.586"></path><circle cx="11" cy="11" r="2"></circle></svg>\') 8 8, crosshair';
                    }
                });
            });

            // Control de tamaño del pincel 
            document.getElementById('brushSize').addEventListener('input', function () {
                brushSize = this.value;
                document.getElementById('brushSizeValue').textContent = brushSize;
            });
        });
    </script>

    <!-- script para el web worker -->
    <script>

        const botonIniciar = document.getElementById("botonIniciar")
        const textoResultado = document.getElementById("resultado")
        console.log(botonIniciar.textContent);



        if (window.Worker) {

            console.log("inside if");

            const miWorker = new Worker("/js/worker/worker.js")
            botonIniciar.addEventListener('click', () => {
                console.log("iniciando");

                alert("iniciando calculo")
                botonIniciar.disabled = true
                botonIniciar.textContent = "..."
                miWorker.postMessage("Empezar")
            })

            miWorker.onmessage = (e) => {
                textoResultado.textContent = e.data;

                botonIniciar.textContent = "Iniciar calculo"

                botonIniciar.disabled = false;
            };

        } else {
            console.log("not supported");

        }

    </script>

<script>
const video = document.getElementById("videoPlayer");
window.video = video; // Hacerlo accesible desde HTML inline
    const tiempoActual = document.getElementById("tiempoActual");
    const duracion = document.getElementById("duracion");

    // Formatear segundos a mm:ss
    function formatoTiempo(segundos) {
        const m = Math.floor(segundos / 60);
        const s = Math.floor(segundos % 60).toString().padStart(2, '0');
        return `${m}:${s}`;
    }

    // Mostrar duración una vez que el video esté cargado
    video.addEventListener("loadedmetadata", () => {
        duracion.textContent = formatoTiempo(video.duration);
    });

    // Actualizar el tiempo actual cada segundo
    video.addEventListener("timeupdate", () => {
        tiempoActual.textContent = formatoTiempo(video.currentTime);
    });
</script>

</body>

</html>