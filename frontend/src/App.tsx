import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import Login from './pages/Login';
import RoutesList from './pages/RoutesList';
import './App.css'

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/login" element={<Login />} />
        <Route path="/rutas" element={<RoutesList />} />
        <Route path="*" element={<Navigate to="/rutas" />} />
      </Routes>
    </BrowserRouter>
  );
}

export default App;
