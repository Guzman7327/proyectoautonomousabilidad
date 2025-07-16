import { useEffect } from 'react';
// @ts-ignore
import axe from 'axe-core';

export default function AuditWCAG() {
  useEffect(() => {
    axe.run(document, {}, (err: any, results: any) => {
      if (err) return;
      // TODO: Mostrar resultados en UI o enviarlos al backend
      // Por ahora, solo log en consola
      // eslint-disable-next-line no-console
      console.log('Resultados WCAG:', results);
    });
  }, []);

  return (
    <main className="p-8 max-w-2xl mx-auto">
      <h1 className="text-2xl font-bold mb-6">Auditoría WCAG (ejemplo)</h1>
      <div className="bg-gray-100 p-4 rounded">Resultados de accesibilidad se mostrarán aquí (próximamente).</div>
    </main>
  );
} 