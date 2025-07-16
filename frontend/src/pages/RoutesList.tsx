import { useEffect, useState } from 'react';
import api from '../api/axios';

interface Ruta {
  id: string;
  titulo: string;
  descripcion: string;
  categoria_id: string;
  dificultad: string;
  distancia_km: number;
  duracion_estimada: number;
}

export default function RoutesList() {
  const [rutas, setRutas] = useState<Ruta[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    api.get('/routes')
      .then(res => setRutas(res.data.data))
      .catch(err => setError('No se pudieron cargar las rutas'))
      .finally(() => setLoading(false));
  }, []);

  if (loading) return <p className="text-center mt-8">Cargando rutas...</p>;
  if (error) return <p className="text-center text-red-600 mt-8">{error}</p>;

  return (
    <section className="max-w-3xl mx-auto mt-8 p-4">
      <h2 className="text-xl font-bold mb-4">Rutas accesibles</h2>
      <ul className="space-y-4" aria-label="Lista de rutas accesibles">
        {rutas.map(ruta => (
          <li key={ruta.id} className="bg-white rounded shadow p-4" tabIndex={0} aria-label={`Ruta: ${ruta.titulo}`}>
            <h3 className="text-lg font-semibold">{ruta.titulo}</h3>
            <p className="text-gray-600 mb-2">{ruta.descripcion}</p>
            <div className="flex flex-wrap gap-4 text-sm text-gray-700">
              <span>Dificultad: <b>{ruta.dificultad}</b></span>
              <span>Distancia: <b>{ruta.distancia_km} km</b></span>
              <span>Duración: <b>{ruta.duracion_estimada} min</b></span>
            </div>
          </li>
        ))}
      </ul>
    </section>
  );
} 