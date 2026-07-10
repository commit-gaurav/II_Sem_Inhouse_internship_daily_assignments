const themeToggle = document.getElementById('themeToggle');
 
function toggleTheme() {
  document.body.classList.toggle('dark');
  const isDark = document.body.classList.contains('dark');
  themeToggle.textContent = isDark ? '☀️ Light mode' : '🌙 Dark mode';
}

let count = 0;
 
const counterValue = document.getElementById('counterValue');
const incrementBtn = document.getElementById('incrementBtn');
const resetBtn = document.getElementById('resetBtn');
 
incrementBtn.addEventListener('click', function () {
  count++;
  counterValue.textContent = count;
});
 
resetBtn.addEventListener('click', function () {
  count = 0;
  counterValue.textContent = count;
});