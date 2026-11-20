document.addEventListener('DOMContentLoaded', () => {
  const root = document.documentElement;
  const toggle = document.getElementById('themeToggle');
  const updateThemeLabel = () => {
    if (!toggle) return;
    const dark = root.getAttribute('data-theme') === 'dark';
    toggle.innerHTML = dark
      ? '<i class="bi bi-sun"></i><span>Light mode</span>'
      : '<i class="bi bi-moon-stars"></i><span>Dark mode</span>';
  };
  updateThemeLabel();
  toggle?.addEventListener('click', () => {
    const next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    root.setAttribute('data-theme', next);
    localStorage.setItem('studenthub-theme', next);
    updateThemeLabel();
  });

  const menu = document.getElementById('mobileMenu');
  const sidebar = document.getElementById('sidebar');
  menu?.addEventListener('click', () => sidebar?.classList.toggle('open'));
  document.addEventListener('click', (event) => {
    if (window.innerWidth <= 760 && sidebar?.classList.contains('open')
      && !sidebar.contains(event.target) && !menu?.contains(event.target)) {
      sidebar.classList.remove('open');
    }
  });

  const canvas = document.getElementById('enrolmentChart');
  if (canvas && window.courseChartData && window.Chart) {
    const dark = root.getAttribute('data-theme') === 'dark';
    new Chart(canvas, {
      type: 'bar',
      data: {
        labels: window.courseChartData.map(item => item.course_name),
        datasets: [{
          label: 'Enrolled students',
          data: window.courseChartData.map(item => Number(item.enrolled)),
          backgroundColor: '#7189ee',
          borderRadius: 7,
          maxBarThickness: 38
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { display: false }, ticks: { color: dark ? '#a1aec1' : '#8792a5', font: { family: 'DM Sans' } } },
          y: { beginAtZero: true, ticks: { precision: 0, color: dark ? '#a1aec1' : '#8792a5' }, grid: { color: dark ? '#334155' : '#edf0f5' } }
        }
      }
    });
  }
});
