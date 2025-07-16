// Funciones de accesibilidad para el menú

// Guardar preferencias en backend
function guardarPreferencias(preferencias) {
    fetch('/api/preferencias_accesibilidad', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({preferencias})
    });
}

// Registrar log de accesibilidad
function registrarLog(accion) {
    fetch('/api/accesibilidad_log', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({accion})
    });
}

// Alto contraste
function activateHighContrast() {
    document.body.classList.toggle('alto-contraste');
    guardarPreferencias({alto_contraste: document.body.classList.contains('alto-contraste')});
    registrarLog('Alto contraste ' + (document.body.classList.contains('alto-contraste') ? 'activado' : 'desactivado'));
}

// Modo monocromático
function toggleMonochrome() {
    document.body.classList.toggle('monocromatico');
    guardarPreferencias({monocromatico: document.body.classList.contains('monocromatico')});
    registrarLog('Monocromático ' + (document.body.classList.contains('monocromatico') ? 'activado' : 'desactivado'));
}

// Aumentar texto
function increaseTextSize() {
    document.body.classList.add('texto-grande');
    document.body.classList.remove('texto-pequeno');
    guardarPreferencias({texto_grande: true});
    registrarLog('Texto grande activado');
}

// Disminuir texto
function decreaseTextSize() {
    document.body.classList.add('texto-pequeno');
    document.body.classList.remove('texto-grande');
    guardarPreferencias({texto_pequeno: true});
    registrarLog('Texto pequeño activado');
}

// Ajustar espaciado
function adjustSpacing(tipo) {
    if (tipo === 'increase') {
        document.body.style.letterSpacing = '0.15em';
        document.body.style.lineHeight = '2';
        guardarPreferencias({espaciado: 'aumentado'});
        registrarLog('Espaciado aumentado');
    } else {
        document.body.style.letterSpacing = '';
        document.body.style.lineHeight = '';
        guardarPreferencias({espaciado: 'normal'});
        registrarLog('Espaciado normal');
    }
}

// Tipografía dislexia-friendly
function useReadableFont() {
    document.body.classList.toggle('fuente-legible');
    guardarPreferencias({fuente_legible: document.body.classList.contains('fuente-legible')});
    registrarLog('Tipografía legible ' + (document.body.classList.contains('fuente-legible') ? 'activada' : 'desactivada'));
}

// Zoom de página
function toggleZoom() {
    if (document.body.style.zoom === '1.1') {
        document.body.style.zoom = '';
        guardarPreferencias({zoom: false});
        registrarLog('Zoom desactivado');
    } else {
        document.body.style.zoom = '1.1';
        guardarPreferencias({zoom: true});
        registrarLog('Zoom activado');
    }
}

// Lupa puntual (requiere implementación avanzada)
function activarLupaPuntual() {
    alert('Función de lupa puntual en desarrollo');
    registrarLog('Lupa puntual activada');
}

// Resaltado de foco/enlaces
function highlightFocus() {
    document.body.classList.toggle('keyboard-navigation');
    guardarPreferencias({resaltado_foco: document.body.classList.contains('keyboard-navigation')});
    registrarLog('Resaltado de foco ' + (document.body.classList.contains('keyboard-navigation') ? 'activado' : 'desactivado'));
}

// Pausar animaciones
function pauseAnimations() {
    document.body.classList.toggle('pausar-animaciones');
    guardarPreferencias({pausar_animaciones: document.body.classList.contains('pausar-animaciones')});
    registrarLog('Animaciones ' + (document.body.classList.contains('pausar-animaciones') ? 'pausadas' : 'activadas'));
}

// Lector de pantalla (texto a voz)
function readText() {
    let texto = document.getSelection().toString() || document.body.innerText;
    let utterance = new SpeechSynthesisUtterance(texto);
    window.speechSynthesis.speak(utterance);
    registrarLog('Lector de pantalla activado');
}

// Descripción de audio (simulada)
function showAudioDescription() {
    alert('Descripción de audio: Esta página contiene información turística accesible.');
    registrarLog('Descripción de audio mostrada');
}

// Restablecer accesibilidad
function resetAccessibility() {
    document.body.classList.remove('alto-contraste', 'monocromatico', 'texto-grande', 'texto-pequeno', 'fuente-legible', 'keyboard-navigation', 'pausar-animaciones');
    document.body.style.letterSpacing = '';
    document.body.style.lineHeight = '';
    document.body.style.zoom = '';
    guardarPreferencias({reset: true});
    registrarLog('Preferencias de accesibilidad restablecidas');
}

// ...puedes agregar más funciones según el menú de accesibilidad... 

function mostrarAlertaVisual(mensaje) {
    const alerta = document.createElement('div');
    alerta.className = 'alerta-visual';
    alerta.innerText = mensaje;
    document.body.appendChild(alerta);
    setTimeout(() => alerta.remove(), 4000);
} 