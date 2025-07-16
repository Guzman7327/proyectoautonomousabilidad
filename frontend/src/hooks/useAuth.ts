import { useState } from 'react';
import api from '../api/axios';

export interface User {
  id: string;
  email: string;
  nombre: string;
  apellido: string;
  rol: string;
  tipo_discapacidad?: any;
  preferencias?: any;
}

export function useAuth() {
  const [user, setUser] = useState<User | null>(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  // Login
  const login = async (email: string, password: string) => {
    setLoading(true);
    setError(null);
    try {
      const res = await api.post('/auth/login', { email, password });
      const { user, tokens } = res.data.data;
      localStorage.setItem('accessToken', tokens.accessToken);
      localStorage.setItem('refreshToken', tokens.refreshToken);
      setUser(user);
      setLoading(false);
      return true;
    } catch (err: any) {
      setError(err.response?.data?.message || 'Error de autenticación');
      setLoading(false);
      return false;
    }
  };

  // Logout
  const logout = () => {
    localStorage.removeItem('accessToken');
    localStorage.removeItem('refreshToken');
    setUser(null);
  };

  // Obtener usuario actual
  const fetchMe = async () => {
    setLoading(true);
    try {
      const res = await api.get('/auth/me');
      setUser(res.data.data.user);
    } catch {
      setUser(null);
    } finally {
      setLoading(false);
    }
  };

  return { user, loading, error, login, logout, fetchMe };
} 