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

// Mostrar/ocultar menú de accesibilidad con animación moderna
function toggleAccMenu() {
    const menu = document.getElementById('accesibilidad-lista');
    if (menu) {
        const expanded = menu.classList.contains('oculto');
        menu.classList.toggle('oculto');
        // Animación moderna
        if (!menu.classList.contains('oculto')) {
            menu.style.opacity = '0';
            menu.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                menu.style.opacity = '1';
                menu.style.transform = 'translateY(0)';
            }, 10);
        } else {
            menu.style.opacity = '';
            menu.style.transform = '';
        }
        // Accesibilidad ARIA
        const btn = document.querySelector('.menu-toggle');
        if (btn) btn.setAttribute('aria-expanded', expanded ? 'true' : 'false');
    }
}

// Lupa puntual real y moderna
let lupaActiva = false;
let lupaDiv = null;

function activarLupaPuntual() {
    if (!lupaActiva) {
        lupaDiv = document.getElementById('magnifier');
        if (!lupaDiv) {
            lupaDiv = document.createElement('div');
            lupaDiv.id = 'magnifier';
            lupaDiv.className = 'magnifier';
            document.body.appendChild(lupaDiv);
        }
        lupaDiv.style.display = 'block';
        lupaDiv.style.border = '3px solid var(--primary-color)';
        lupaDiv.style.boxShadow = '0 0 16px 4px rgba(37,99,235,0.15)';
        lupaDiv.style.transition = 'box-shadow 0.3s, border 0.3s';
        document.addEventListener('mousemove', moverLupa);
        lupaActiva = true;
        registrarLog('Lupa puntual activada');
    } else {
        if (lupaDiv) lupaDiv.style.display = 'none';
        document.removeEventListener('mousemove', moverLupa);
        lupaActiva = false;
        registrarLog('Lupa puntual desactivada');
    }
}

function moverLupa(e) {
    if (!lupaDiv) return;
    const size = 150;
    lupaDiv.style.left = (e.pageX - size/2) + 'px';
    lupaDiv.style.top = (e.pageY - size/2) + 'px';
    lupaDiv.style.background = 'rgba(255,255,255,0.7)';
    lupaDiv.style.backdropFilter = 'blur(2px)';
    // Captura la zona bajo el cursor
    html2canvas(document.body, {
        x: e.pageX - size/2,
        y: e.pageY - size/2,
        width: size,
        height: size,
        windowWidth: window.innerWidth,
        windowHeight: window.innerHeight,
        scale: 2
    }).then(canvas => {
        lupaDiv.innerHTML = '';
        const img = document.createElement('img');
        img.src = canvas.toDataURL();
        img.style.width = size + 'px';
        img.style.height = size + 'px';
        img.style.borderRadius = '50%';
        img.style.boxShadow = '0 0 12px 2px rgba(37,99,235,0.10)';
        lupaDiv.appendChild(img);
    });
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

// Mejor feedback visual para alerta visual
function mostrarAlertaVisual(mensaje) {
    const alerta = document.createElement('div');
    alerta.className = 'alerta-visual fade-in';
    alerta.innerText = mensaje;
    alerta.style.position = 'fixed';
    alerta.style.top = '30px';
    alerta.style.right = '30px';
    alerta.style.background = 'linear-gradient(90deg, #2563eb 60%, #10b981 100%)';
    alerta.style.color = '#fff';
    alerta.style.padding = '1rem 2rem';
    alerta.style.borderRadius = '1rem';
    alerta.style.fontWeight = 'bold';
    alerta.style.fontSize = '1.1rem';
    alerta.style.boxShadow = '0 4px 24px rgba(37,99,235,0.15)';
    alerta.style.zIndex = '99999';
    alerta.style.transition = 'opacity 0.5s';
    document.body.appendChild(alerta);
    setTimeout(() => {
        alerta.style.opacity = '0';
        setTimeout(() => alerta.remove(), 500);
    }, 4000);
} 