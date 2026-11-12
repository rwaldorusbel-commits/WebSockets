// resources/js/operator.js
import './echo';

const list = document.getElementById('waiting-list');
const count = document.getElementById('waiting-count');

async function refreshWaiting() {
  try {
    const res = await fetch('/operador/cola', {
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
    });
    list.innerHTML = await res.text();
    count.textContent = list.querySelectorAll('.border-b').length;
  } catch (e) {
    console.error('No se pudo actualizar la cola', e);
  }
}
laravel({
    input: [
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/js/display.js',
        'resources/js/ticket.js',
        'resources/js/operator.js',
    ],
    refresh: true,
}),
// Llega un ciudadano nuevo, o otro operador llama un turno
window.Echo.channel('queue')
  .listen('.ticket.created', refreshWaiting)
  .listen('.ticket.called', refreshWaiting);

