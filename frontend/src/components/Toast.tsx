import { useState } from 'react';

export default function Toast({ message, type = 'info' }: { message: string; type?: 'info' | 'success' | 'error' }) {
  const [visible, setVisible] = useState(true);
  if (!visible) return null;
  return (
    <div
      className={`fixed bottom-4 right-4 px-4 py-2 rounded shadow-lg z-50 text-white ${type === 'success' ? 'bg-green-600' : type === 'error' ? 'bg-red-600' : 'bg-blue-600'}`}
      role="alert"
      tabIndex={0}
      onClick={() => setVisible(false)}
      aria-live="polite"
    >
      {message}
      <button className="ml-4 underline" onClick={() => setVisible(false)}>Cerrar</button>
    </div>
  );
} 