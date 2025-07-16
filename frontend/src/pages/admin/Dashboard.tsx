import { Link } from 'react-router-dom';

export default function AdminDashboard() {
  return (
    <main className="p-8 max-w-3xl mx-auto">
      <h1 className="text-2xl font-bold mb-6">Panel de Administración</h1>
      <nav className="space-y-4">
        <Link to="/admin/users" className="block p-4 bg-blue-100 rounded hover:bg-blue-200">Gestión de usuarios</Link>
        <Link to="/admin/reports" className="block p-4 bg-yellow-100 rounded hover:bg-yellow-200">Reportes y moderación</Link>
        <Link to="/admin/stats" className="block p-4 bg-green-100 rounded hover:bg-green-200">Estadísticas</Link>
        <Link to="/admin/audit-wcag" className="block p-4 bg-purple-100 rounded hover:bg-purple-200">Auditoría WCAG</Link>
      </nav>
    </main>
  );
} 