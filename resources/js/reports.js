import Chart from 'chart.js/auto';

const d = JSON.parse(document.getElementById('report-data').textContent);
const $ = (id) => document.getElementById(id);

new Chart($('chartDays'), {
  type: 'line',
  data: { labels: d.days.labels, datasets: [{ label: 'Turnos', data: d.days.values, tension: 0.3, fill: true }] },
});

new Chart($('chartHours'), {
  type: 'bar',
  data: { labels: d.hours.labels, datasets: [{ label: 'Turnos', data: d.hours.values }] },
});

new Chart($('chartServices'), {
  type: 'bar',
  data: { labels: d.services.labels, datasets: [{ label: 'Turnos', data: d.services.values }] },
  options: { indexAxis: 'y' },
});

new Chart($('chartStatus'), {
  type: 'doughnut',
  data: { labels: d.status.labels, datasets: [{ data: d.status.values }] },
});
