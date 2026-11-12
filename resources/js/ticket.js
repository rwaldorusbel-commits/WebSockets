import './echo';
import QRCode from 'qrcode';

const canvas = document.getElementById('qr');
QRCode.toCanvas(canvas, canvas.dataset.url, { width: 160, margin: 1 });

const pos = document.getElementById('pos');
const status = document.getElementById('status');

// Mi turno fue llamado
window.Echo.channel(`ticket.${TICKET_ID}`).listen('.ticket.called', (e) => {
  status.textContent = `¡Es su turno! Diríjase a ${e.counter}`;
  status.className = 'mt-4 p-3 rounded bg-green-100 text-green-800';
  pos.textContent = 0;
  if (navigator.vibrate) navigator.vibrate([300, 100, 300]);
});

// Alguien más fue llamado: recalcular mi posición
window.Echo.channel('queue').listen('.ticket.called', () => {
  fetch(`/turno/${TICKET_ID}/posicion`)
    .then((r) => r.json())
    .then((d) => { pos.textContent = d.position; });
});
