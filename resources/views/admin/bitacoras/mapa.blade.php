<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Bitácora</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-routing-machine/3.2.12/leaflet-routing-machine.min.js"></script>
    <style>
        #map {
            height: 600px;
            width: 100%;
        }
    </style>
</head>
<body>
    <h1>Mapa de Entregas</h1>
    <h2>Guía: {{ $bitacora->guia->numero_guia }}</h2>
    <h3>Manifiesto: {{ $bitacora->guia->manifiesto->numero_manifiesto }}</h3>
    <div id="map"></div>

    <script>
        // Coordenadas optimizadas desde el backend
        const puntosOptimizados = @json($puntosOptimizados);

        // Crear el mapa centrado en el primer punto
        const map = L.map('map').setView([puntosOptimizados[0].latitud, puntosOptimizados[0].longitud], 12);

        // Agregar el mapa base de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Crear íconos personalizados
        const iconRemitente = L.icon({
            iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684933.png', // Ícono de carro
            iconSize: [30, 30],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34]
        });

        const iconDestinatario = L.icon({
            iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png', // Marcador azul
            iconSize: [30, 30],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34]
        });

        // Crear marcadores para los puntos
        puntosOptimizados.forEach((punto) => {
            const marker = L.marker(
                [punto.latitud, punto.longitud],
                { icon: punto.tipo === 'origen' ? iconRemitente : iconDestinatario }
            ).addTo(map);
            marker.bindPopup(`<b>${punto.razon}</b>`); // Mostrar razón social del cliente en el popup
        });

        // Crear las rutas optimizadas usando Leaflet Routing Machine
        const waypoints = puntosOptimizados.map(punto => L.latLng(punto.latitud, punto.longitud));

        L.Routing.control({
            waypoints: waypoints,
            routeWhileDragging: false,
            show: false,
            createMarker: function(i, n) {
                const icon = puntosOptimizados[i].tipo === 'origen' ? iconRemitente : iconDestinatario;
                return L.marker(waypoint.latLng, { icon }).bindPopup(`<b>${puntosOptimizados[i].razon}</b>`);
            }
        }).addTo(map);
    </script>
</body>
</html>
