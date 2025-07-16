import { useEffect, useRef, useState } from 'react';
import mapboxgl from 'mapbox-gl';
import 'mapbox-gl/dist/mapbox-gl.css';

// Configurar token de Mapbox
mapboxgl.accessToken = import.meta.env.VITE_MAPBOX_TOKEN || '';

interface MapProps {
  rutas?: Array<{
    id: string;
    titulo: string;
    coordenadas: any;
    dificultad: string;
  }>;
  onRouteClick?: (rutaId: string) => void;
}

export default function Map({ rutas = [], onRouteClick }: MapProps) {
  const mapContainer = useRef<HTMLDivElement>(null);
  const map = useRef<mapboxgl.Map | null>(null);
  const [lng] = useState(-78.4678); // Quito, Ecuador
  const [lat] = useState(-0.1807);
  const [zoom] = useState(12);

  useEffect(() => {
    if (map.current) return; // Inicializar mapa solo una vez

    if (!mapContainer.current) return;

    map.current = new mapboxgl.Map({
      container: mapContainer.current,
      style: 'mapbox://styles/mapbox/streets-v12',
      center: [lng, lat],
      zoom: zoom,
      attributionControl: false
    });

    // Agregar controles de navegación
    map.current.addControl(new mapboxgl.NavigationControl(), 'top-right');
    
    // Agregar control de geolocalización
    map.current.addControl(
      new mapboxgl.GeolocateControl({
        positionOptions: {
          enableHighAccuracy: true
        },
        trackUserLocation: true,
        showUserHeading: true
      }),
      'top-right'
    );

    // Agregar control de accesibilidad
    map.current.addControl(
      new mapboxgl.FullscreenControl(),
      'top-right'
    );

  }, [lng, lat, zoom]);

  // Agregar rutas al mapa
  useEffect(() => {
    if (!map.current || !rutas.length) return;

    // Limpiar fuentes y capas existentes
    if (map.current.getSource('rutas')) {
      map.current.removeLayer('rutas-layer');
      map.current.removeSource('rutas');
    }

    // Agregar fuente de datos
    map.current.addSource('rutas', {
      type: 'geojson',
      data: {
        type: 'FeatureCollection',
        features: rutas.map(ruta => ({
          type: 'Feature',
          properties: {
            id: ruta.id,
            titulo: ruta.titulo,
            dificultad: ruta.dificultad
          },
          geometry: {
            type: 'LineString',
            coordinates: ruta.coordenadas
          }
        }))
      }
    });

    // Agregar capa de líneas
    map.current.addLayer({
      id: 'rutas-layer',
      type: 'line',
      source: 'rutas',
      layout: {
        'line-join': 'round',
        'line-cap': 'round'
      },
      paint: {
        'line-color': [
          'match',
          ['get', 'dificultad'],
          'facil', '#10B981',
          'moderada', '#F59E0B',
          'dificil', '#EF4444',
          '#6B7280'
        ],
        'line-width': 4,
        'line-opacity': 0.8
      }
    });

    // Agregar capa de puntos para inicio/fin
    map.current.addLayer({
      id: 'rutas-points',
      type: 'circle',
      source: 'rutas',
      paint: {
        'circle-radius': 6,
        'circle-color': [
          'match',
          ['get', 'dificultad'],
          'facil', '#10B981',
          'moderada', '#F59E0B',
          'dificil', '#EF4444',
          '#6B7280'
        ],
        'circle-stroke-width': 2,
        'circle-stroke-color': '#ffffff'
      }
    });

    // Agregar interactividad
    map.current.on('click', 'rutas-layer', (e) => {
      if (e.features && e.features[0]) {
        const feature = e.features[0];
        const rutaId = feature.properties?.id;
        if (rutaId && onRouteClick) {
          onRouteClick(rutaId);
        }
      }
    });

    // Cambiar cursor al pasar sobre rutas
    map.current.on('mouseenter', 'rutas-layer', () => {
      if (map.current) {
        map.current.getCanvas().style.cursor = 'pointer';
      }
    });

    map.current.on('mouseleave', 'rutas-layer', () => {
      if (map.current) {
        map.current.getCanvas().style.cursor = '';
      }
    });

  }, [rutas, onRouteClick]);

  return (
    <div className="relative w-full h-96 rounded-lg overflow-hidden">
      <div 
        ref={mapContainer} 
        className="w-full h-full"
        role="application"
        aria-label="Mapa de rutas turísticas accesibles"
        tabIndex={0}
      />
      <div className="absolute top-4 left-4 bg-white p-2 rounded shadow">
        <h3 className="text-sm font-semibold mb-2">Leyenda</h3>
        <div className="space-y-1 text-xs">
          <div className="flex items-center">
            <div className="w-4 h-2 bg-green-500 mr-2"></div>
            <span>Fácil</span>
          </div>
          <div className="flex items-center">
            <div className="w-4 h-2 bg-yellow-500 mr-2"></div>
            <span>Moderada</span>
          </div>
          <div className="flex items-center">
            <div className="w-4 h-2 bg-red-500 mr-2"></div>
            <span>Difícil</span>
          </div>
        </div>
      </div>
    </div>
  );
} 