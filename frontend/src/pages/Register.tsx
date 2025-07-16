import { useForm } from 'react-hook-form';
import { yupResolver } from '@hookform/resolvers/yup';
import * as yup from 'yup';
import { useState } from 'react';
import api from '../api/axios';

const schema = yup.object({
  email: yup.string().email('Email inválido').required('Email es requerido'),
  password: yup.string().min(8, 'Mínimo 8 caracteres').required('Contraseña es requerida'),
  confirmPassword: yup.string().required('Confirma tu contraseña').oneOf([yup.ref('password')], 'Las contraseñas deben coincidir'),
  nombre: yup.string().min(2, 'Mínimo 2 caracteres').required('Nombre es requerido'),
  apellido: yup.string().min(2, 'Mínimo 2 caracteres').required('Apellido es requerido'),
  tipo_discapacidad: yup.object({
    visual: yup.boolean(),
    auditiva: yup.boolean(),
    motora: yup.boolean(),
    cognitiva: yup.boolean(),
    otras: yup.string()
  })
});

type FormData = yup.InferType<typeof schema>;

export default function Register() {
  const { register, handleSubmit, formState: { errors } } = useForm<FormData>({
    resolver: yupResolver(schema)
  });
  const [loading, setLoading] = useState(false);
  const [success, setSuccess] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const onSubmit = async (data: FormData) => {
    setLoading(true);
    setError(null);
    
    try {
      const response = await api.post('/auth/register', {
        email: data.email,
        password: data.password,
        nombre: data.nombre,
        apellido: data.apellido,
        tipo_discapacidad: data.tipo_discapacidad
      });
      
      if (response.data.success) {
        setSuccess(true);
      }
    } catch (err: any) {
      setError(err.response?.data?.message || 'Error al registrar usuario');
    } finally {
      setLoading(false);
    }
  };

  return (
    <main className="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 to-cyan-100 p-4">
      <form
        className="bg-white p-8 rounded-lg shadow-lg w-full max-w-md space-y-6"
        onSubmit={handleSubmit(onSubmit)}
        aria-label="Formulario de registro"
      >
        <h1 className="text-2xl font-bold text-center">Registrarse</h1>
        
        <div>
          <label htmlFor="email" className="block text-sm font-medium mb-1">
            Correo electrónico
          </label>
          <input
            id="email"
            type="email"
            {...register('email')}
            className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
            aria-invalid={!!errors.email}
            aria-describedby="email-error"
          />
          {errors.email && (
            <p id="email-error" className="text-red-600 text-sm mt-1" role="alert">
              {errors.email.message}
            </p>
          )}
        </div>

        <div>
          <label htmlFor="password" className="block text-sm font-medium mb-1">
            Contraseña
          </label>
          <input
            id="password"
            type="password"
            {...register('password')}
            className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
            aria-invalid={!!errors.password}
            aria-describedby="password-error"
          />
          {errors.password && (
            <p id="password-error" className="text-red-600 text-sm mt-1" role="alert">
              {errors.password.message}
            </p>
          )}
        </div>

        <div>
          <label htmlFor="confirmPassword" className="block text-sm font-medium mb-1">
            Confirmar contraseña
          </label>
          <input
            id="confirmPassword"
            type="password"
            {...register('confirmPassword')}
            className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
            aria-invalid={!!errors.confirmPassword}
            aria-describedby="confirmPassword-error"
          />
          {errors.confirmPassword && (
            <p id="confirmPassword-error" className="text-red-600 text-sm mt-1" role="alert">
              {errors.confirmPassword.message}
            </p>
          )}
        </div>

        <div>
          <label htmlFor="nombre" className="block text-sm font-medium mb-1">
            Nombre
          </label>
          <input
            id="nombre"
            type="text"
            {...register('nombre')}
            className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
            aria-invalid={!!errors.nombre}
            aria-describedby="nombre-error"
          />
          {errors.nombre && (
            <p id="nombre-error" className="text-red-600 text-sm mt-1" role="alert">
              {errors.nombre.message}
            </p>
          )}
        </div>

        <div>
          <label htmlFor="apellido" className="block text-sm font-medium mb-1">
            Apellido
          </label>
          <input
            id="apellido"
            type="text"
            {...register('apellido')}
            className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
            aria-invalid={!!errors.apellido}
            aria-describedby="apellido-error"
          />
          {errors.apellido && (
            <p id="apellido-error" className="text-red-600 text-sm mt-1" role="alert">
              {errors.apellido.message}
            </p>
          )}
        </div>

        <fieldset className="border border-gray-300 rounded p-4">
          <legend className="text-sm font-medium px-2">Tipo de discapacidad (opcional)</legend>
          <div className="space-y-2">
            <label className="flex items-center">
              <input
                type="checkbox"
                {...register('tipo_discapacidad.visual')}
                className="mr-2"
              />
              Visual
            </label>
            <label className="flex items-center">
              <input
                type="checkbox"
                {...register('tipo_discapacidad.auditiva')}
                className="mr-2"
              />
              Auditiva
            </label>
            <label className="flex items-center">
              <input
                type="checkbox"
                {...register('tipo_discapacidad.motora')}
                className="mr-2"
              />
              Motora
            </label>
            <label className="flex items-center">
              <input
                type="checkbox"
                {...register('tipo_discapacidad.cognitiva')}
                className="mr-2"
              />
              Cognitiva
            </label>
          </div>
        </fieldset>

        {error && (
          <div className="text-red-600 text-sm text-center" role="alert">
            {error}
          </div>
        )}

        <button
          type="submit"
          className="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
          disabled={loading}
        >
          {loading ? 'Registrando...' : 'Registrarse'}
        </button>

        {success && (
          <div className="text-green-600 text-sm text-center" role="status">
            ¡Registro exitoso! Ya puedes iniciar sesión.
          </div>
        )}
      </form>
    </main>
  );
} 