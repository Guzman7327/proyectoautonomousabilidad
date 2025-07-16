import { useState } from 'react';
import { useAuth } from '../hooks/useAuth';

export default function Login() {
  const { login, error, loading } = useAuth();
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [success, setSuccess] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    const ok = await login(email, password);
    setSuccess(ok);
  };

  return (
    <main className="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 to-cyan-100">
      <form
        className="bg-white p-8 rounded-lg shadow-lg w-full max-w-md space-y-6"
        onSubmit={handleSubmit}
        aria-label="Formulario de inicio de sesión"
      >
        <h1 className="text-2xl font-bold text-center">Iniciar sesión</h1>
        <div>
          <label htmlFor="email" className="block text-sm font-medium mb-1">
            Correo electrónico
          </label>
          <input
            id="email"
            name="email"
            type="email"
            autoComplete="email"
            required
            className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
            value={email}
            onChange={e => setEmail(e.target.value)}
            aria-invalid={!!error}
            aria-describedby="email-error"
          />
        </div>
        <div>
          <label htmlFor="password" className="block text-sm font-medium mb-1">
            Contraseña
          </label>
          <input
            id="password"
            name="password"
            type="password"
            autoComplete="current-password"
            required
            className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
            value={password}
            onChange={e => setPassword(e.target.value)}
            aria-invalid={!!error}
            aria-describedby="password-error"
          />
        </div>
        {error && (
          <div id="email-error" className="text-red-600 text-sm" role="alert">
            {error}
          </div>
        )}
        <button
          type="submit"
          className="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
          disabled={loading}
        >
          {loading ? 'Cargando...' : 'Entrar'}
        </button>
        {success && (
          <div className="text-green-600 text-sm text-center mt-2" role="status">
            ¡Login exitoso!
          </div>
        )}
      </form>
    </main>
  );
} 