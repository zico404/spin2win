const canvas = document.getElementById('wheel');
const ctx = canvas.getContext('2d');
const spinBtn = document.getElementById('spinBtn');
const statusMsg = document.getElementById('statusMsg');
const prizes = window.SPIN_CONFIG.prizes || [];
let currentRotation = 0;
let spinning = false;

function drawWheel(rotation = 0) {
  const cx = canvas.width / 2;
  const cy = canvas.height / 2;
  const radius = canvas.width / 2 - 8;
  const segAngle = (Math.PI * 2) / prizes.length;

  ctx.clearRect(0, 0, canvas.width, canvas.height);
  ctx.save();
  ctx.translate(cx, cy);
  ctx.rotate(rotation);

  prizes.forEach((prize, i) => {
    const start = i * segAngle;
    const end = start + segAngle;
    ctx.beginPath();
    ctx.moveTo(0, 0);
    ctx.arc(0, 0, radius, start, end);
    ctx.closePath();
    ctx.fillStyle = prize.color || '#D4AF37';
    ctx.fill();
    ctx.strokeStyle = '#111';
    ctx.stroke();

    ctx.save();
    ctx.rotate(start + segAngle / 2);
    ctx.fillStyle = '#111';
    ctx.font = 'bold 16px Manrope';
    ctx.textAlign = 'right';
    ctx.fillText(prize.prize_name, radius - 15, 6);
    ctx.restore();
  });

  ctx.restore();
  ctx.fillStyle = '#FFD700';
  ctx.beginPath();
  ctx.moveTo(canvas.width / 2, 8);
  ctx.lineTo(canvas.width / 2 - 12, 30);
  ctx.lineTo(canvas.width / 2 + 12, 30);
  ctx.closePath();
  ctx.fill();
}

function easeOutCubic(t) {
  return 1 - Math.pow(1 - t, 3);
}

async function startSpin() {
  if (spinning || prizes.length === 0) return;
  spinning = true;
  statusMsg.textContent = 'Spinning...';

  const response = await fetch('spin.php', { method: 'POST' });
  const data = await response.json();
  if (!data.success) {
    statusMsg.textContent = data.message;
    spinning = false;
    return;
  }

  const winnerIndex = prizes.findIndex(p => Number(p.id) === Number(data.prize.id));
  const seg = (Math.PI * 2) / prizes.length;
  const pointerAngle = 3 * Math.PI / 2;
  const targetMiddle = winnerIndex * seg + seg / 2;
  const extraSpins = Math.PI * 2 * 6;
  const targetRotation = extraSpins + (pointerAngle - targetMiddle);

  const startRot = currentRotation;
  const duration = 5200;
  const startedAt = performance.now();

  function animate(now) {
    const progress = Math.min((now - startedAt) / duration, 1);
    currentRotation = startRot + targetRotation * easeOutCubic(progress);
    drawWheel(currentRotation);
    if (progress < 1) {
      requestAnimationFrame(animate);
      return;
    }

    statusMsg.textContent = `You won ${data.prize.prize_name}!`;
    document.getElementById('winModal').classList.remove('hidden');
    document.getElementById('wonPrize').textContent = data.prize.prize_name;
    document.getElementById('prizeId').value = data.prize.id;
    spinning = false;
  }

  requestAnimationFrame(animate);
}

spinBtn.addEventListener('click', startSpin);

const winnerForm = document.getElementById('winnerForm');
winnerForm.addEventListener('submit', async (e) => {
  e.preventDefault();
  const payload = new FormData(winnerForm);
  const response = await fetch('submit.php', { method: 'POST', body: payload });
  const data = await response.json();
  document.getElementById('formFeedback').textContent = data.message;
  if (data.success) winnerForm.reset();
});

drawWheel();
