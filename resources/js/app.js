import Alpine from 'alpinejs';
import { Chart, registerables } from 'chart.js';

// Chart.js diimpor lewat npm & di-bundle Vite (bukan CDN) agar konsisten
// dengan setup Alpine yang sudah ada, lalu diekspos ke window supaya
// script inisialisasi per-halaman (ditaruh lewat @push('scripts') di
// masing-masing blade view dashboard/monitoring) bisa langsung memakainya
// tanpa import terpisah (spesifikasi bagian 1 & 37: "Chart.js untuk
// grafik dashboard").
Chart.register(...registerables);
window.Chart = Chart;

window.Alpine = Alpine;
Alpine.start();
