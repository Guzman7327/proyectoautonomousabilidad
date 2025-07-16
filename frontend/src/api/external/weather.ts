import axios from 'axios';

export async function getWeather(lat: number, lon: number) {
  // Ejemplo con OpenWeatherMap
  const apiKey = import.meta.env.VITE_OPENWEATHER_KEY;
  const url = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${apiKey}&lang=es&units=metric`;
  const res = await axios.get(url);
  return res.data;
} 