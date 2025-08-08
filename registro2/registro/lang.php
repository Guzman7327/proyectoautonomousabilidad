<?php
// Detecta idioma por URL (ej: ?lang=en)
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'es';
$_SESSION['lang'] = $lang; // Guarda en sesión

// Diccionarios por idioma
$translations = [
  'es' => [
    'titulo_portal' => 'PORTAL TURÍSTICO EC',
    'inicio' => 'Inicio',
    'rutas' => 'Rutas',
    'alojamientos' => 'Alojamientos',
    'accesibilidad' => 'Accesibilidad',
    'contacto' => 'Contacto',
    'iniciar_sesion' => 'Iniciar sesión',
    'cerrar_sesion' => 'Cerrar sesión',
    'destinos_destacados' => 'Destinos Destacados',
    'mapa_rutas' => 'Mapa de Rutas Turísticas',
  ],
  'en' => [
    'titulo_portal' => 'TOURISM PORTAL EC',
    'inicio' => 'Home',
    'rutas' => 'Routes',
    'alojamientos' => 'Accommodations',
    'accesibilidad' => 'Accessibility',
    'contacto' => 'Contact',
    'iniciar_sesion' => 'Log in',
    'cerrar_sesion' => 'Log out',
    'destinos_destacados' => 'Featured Destinations',
    'mapa_rutas' => 'Tourist Route Map',
  ],

    // Español
    'cuenca' => 'Cuenca',
    'cuenca_desc' => 'Ciudad patrimonial de la humanidad con arquitectura colonial y entorno tradicional.',
    'salinas' => 'Salinas',
    'salinas_desc' => 'Hermosa playa del Pacífico con excelente infraestructura turística y accesibilidad.',
    'manta' => 'Manta',
    'manta_desc' => 'Puerto pesquero con hermosas playas y deliciosa gastronomía marina.',
    'ver_mas' => 'Ver más',

    // Inglés
    'cuenca' => 'Cuenca',
    'cuenca_desc' => 'Heritage city with colonial architecture and traditional surroundings.',
    'salinas' => 'Salinas',
    'salinas_desc' => 'Beautiful Pacific beach with excellent tourist infrastructure and accessibility.',
    'manta' => 'Manta',
    'manta_desc' => 'Fishing port with beautiful beaches and delicious seafood.',
    'ver_mas' => 'Read more',

];

// Escoge idioma
$t = $translations[$lang] ?? $translations['es'];
