<div class="min-h-screen dark:bg-slate-900 font-sans text-gray-900 dark:text-gray-100">
    <!-- Incluir CSS de Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <style>
        #map {
            height: 80vh;
            width: 100%;
        }
    </style>

    <div class="p-8">
        <!-- Encabezado -->
        <div class="flex flex-col md:flex-row gap-2 items-center justify-between mb-8">
            <h1 class="text-3xl font-bold tracking-tight text-blue-800 dark:text-blue-400">
                Ruta de trabajo de: {{ $user->name }}
            </h1>
            <span class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                Fecha: {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
            </span>
        </div>

        <!-- Contenedor del Mapa -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6">
            <div id="map"></div>
        </div>
    </div>

    <!-- Incluir JS de Leaflet -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const reviews = @json($reviews);

            if (reviews.length > 0) {
                // Centrar el mapa en la primera revisión
                const map = L.map('map').setView([reviews[0].latitude, reviews[0].longitude], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                const latLngs = [];

                reviews.forEach(review => {
                    if (review.latitude && review.longitude) {
                        const latLng = [review.latitude, review.longitude];
                        latLngs.push(latLng);

                        // Crear el contenido del popup
                        const taskName = review.subtask ? review.subtask.name : review.task.name;
                        const popupContent = `
                            <b>Tarea:</b> ${taskName}<br>
                            <b>Hora:</b> ${review.time}<br>
                            <b>Comentario:</b> ${review.comments || 'N/A'}
                        `;

                        L.marker(latLng).addTo(map).bindPopup(popupContent);
                    }
                });

                // Dibujar una línea que conecte todos los puntos
                if (latLngs.length > 1) {
                    const polyline = L.polyline(latLngs, { color: 'blue' }).addTo(map);
                    // Ajustar el zoom para que se vea toda la ruta
                    map.fitBounds(polyline.getBounds());
                }
            } else {
                // Si no hay revisiones, mostrar un mensaje
                document.getElementById('map').innerHTML = '<p class="text-center text-gray-500">No hay datos de ubicación para mostrar en esta fecha.</p>';
            }
        });
    </script>
</div>
