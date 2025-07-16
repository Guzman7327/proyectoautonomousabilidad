import { useRef } from 'react';

export default function ImageUpload({ onChange }: { onChange: (file: File) => void }) {
  const inputRef = useRef<HTMLInputElement>(null);

  return (
    <div>
      <label className="block mb-2 font-medium">Subir imagen</label>
      <input
        ref={inputRef}
        type="file"
        accept="image/*"
        className="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
        onChange={e => {
          if (e.target.files && e.target.files[0]) {
            onChange(e.target.files[0]);
          }
        }}
        aria-label="Subir imagen"
      />
    </div>
  );
} 