<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>APIs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        .map-container {
            height: 420px;
            border-radius: 5px;
            margin-top: 20px;
            border: 1px solid #ccc;
            background-color: #f5f5f5;
        }
        .location-data {
            background-color: #e9ecef;
            padding: 10px 15px;
            border-radius: 5px;
            margin: 15px 0;
            font-family: 'Courier New', monospace;
            font-size: 14px;
        }
        .section-title {
            color: #343a40;
            margin-bottom: 20px;
            padding-bottom: 8px;
            border-bottom: 1px solid #dee2e6;
        }
        .btn-location {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="container py-4">    
    <div class="card mb-4">
        <div class="card-body">
            <h3 class="section-title">Geolocalización</h3>
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
    
    <div class="card mb-4">
        <div class="card-body">
            <h3 class="section-title">Canvas</h3>
            <p>Agregar aquí implementación de Canvas.</p>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <h3 class="section-title">Video</h3>
            <p>Agregar aquí implementación de Video.</p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<script>
    let mapa;
    let marcador = null;

    function mostrarUbicacion() {
        if (!navigator.geolocation) return alert("Geolocalización no soportada");
        
        navigator.geolocation.getCurrentPosition(
            posicion => {
                const {latitude: lat, longitude: lng} = posicion.coords;
                document.getElementById('latitudeValue').textContent = lat;
                document.getElementById('longitudeValue').textContent = lng;
                document.getElementById('positionInfo').style.display = 'block';
                
                if (!mapa) {
                    mapa = L.map('locationMap').setView([lat, lng], 15);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapa);
                } else {
                    mapa.setView([lat, lng], 15);
                }

                if(marcador !== null) {
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
            {timeout: 10000}
        );
    }
</script>

</body>
</html>