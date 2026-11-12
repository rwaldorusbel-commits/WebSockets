// resources/js/display.js
import './echo';

const $ = (id) => document.getElementById(id);
let audioOn = false;

// 1. Activar audio con clic (Requisito de los navegadores)
// Asegúrate de tener un botón con id="enable" en tu HTML
if ($('enable')) {
    $('enable').addEventListener('click', (e) => {
        audioOn = true;
        e.target.remove();
        speak('Sonido activado');
    });
}

function speak(text) {
    if (!audioOn || !('speechSynthesis' in window)) return;
    const u = new SpeechSynthesisUtterance(text);
    u.lang = 'es-ES';
    speechSynthesis.speak(u);
}

function beep() {
    if (!audioOn) return;
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.frequency.value = 880;
        gain.gain.value = 0.2;
        osc.start();
        osc.stop(ctx.currentTime + 0.4);
    } catch (e) {
        console.error("Error al reproducir beep", e);
    }
}

// 2. Lógica para la Pantalla Principal (TV)
function showCall(e) {
    const prevCode = $('current').textContent.trim();
    const prevCounter = $('counter').textContent.trim();

    if (prevCode && prevCode !== '---') {
        const li = document.createElement('li');
        li.className = 'flex justify-between border-b border-slate-700 pb-2';
        li.innerHTML = `<span class="font-bold">${prevCode}</span><span>${prevCounter}</span>`;
        $('history').prepend(li);
        while ($('history').children.length > 5) $('history').lastChild.remove();
    }

    $('current').textContent = e.code;
    $('counter').textContent = e.counter;
    $('current').classList.add('animate-pulse');
    setTimeout(() => $('current').classList.remove('animate-pulse'), 4000);

    beep();
    speak(`Turno ${e.code.replace('-', ' ')}, ${e.counter}`);
}

// Escuchar el canal general de la cola
window.Echo.channel('queue').listen('.ticket.called', (e) => {
    showCall(e); // Actualiza la TV

    // Actualizar posición en la cola si estamos en la vista de un cliente
    if (typeof TICKET_ID !== 'undefined' && $('pos')) {
        fetch(`/turno/${TICKET_ID}/posicion`)
            .then(r => r.json())
            .then(d => $('pos').textContent = d.position)
            .catch(err => console.error("Error al obtener posición", err));
    }
});

// 3. Lógica para el Cliente Individual (Si TICKET_ID está definido)
if (typeof TICKET_ID !== 'undefined') {
    window.Echo.channel(`ticket.${TICKET_ID}`)
        .listen('.ticket.called', (e) => {
            alert(`¡Es su turno! Diríjase a ${e.counter}`);
            if ($('status')) {
                $('status').textContent = `Llamado en ${e.counter}`;
            }
        });
}

// 4. Indicador de conexión WebSocket
if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
    window.Echo.connector.pusher.connection.bind('state_change', ({ current }) => {
        if ($('conn')) {
            $('conn').textContent = current === 'connected' ? '● En línea' : '○ Reconectando…';
        }
    });
}
