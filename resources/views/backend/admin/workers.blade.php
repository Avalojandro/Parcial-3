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
        <!-- seccion del web worker -->
        <div class="card">
            <div class="card-body">
                <h3 class="section-title">
                    <i class="fas fa-video"></i> Web Worker
                </h3>
                <button id="botonIniciar" class="btn btn-primary">Iniciar calculo</button>
                <textarea class="card form-control mr-4 mt-2" rows="10" id="resultado"></textarea>
            </div>
        </div>
    </div>


    <!-- Scripts que necesité agregar -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <!-- script para el web worker -->
    <script>

        const botonIniciar = document.getElementById("botonIniciar")
        const textoResultado = document.getElementById("resultado")
        console.log(botonIniciar.textContent);



        if (window.Worker) {

            console.log("inside if");

            const miWorker = new Worker("/js/worker/worker.js")
            botonIniciar.addEventListener('click', () => {
                botonIniciar.disabled = true
                botonIniciar.textContent = "Calculando..."
                miWorker.postMessage("Empezar")
            })

            miWorker.onmessage = (e) => {
                textoResultado.textContent = e.data;

                botonIniciar.textContent = "Iniciar calculo"

                botonIniciar.disabled = false;
            };

        } else {
            alert("Tu navegador no soporta Web Workers");

        }

    </script>
</body>

</html>