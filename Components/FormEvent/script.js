/* global requestAnimationFrame */
// Self-contained confetti burst — no external library.
// Fired when the success popup opens (see index.twig: 'formevent:success' event).

// Festive palette: brand green + a few accents for visual pop.
const COLORS = ['#c7f59a', '#000000', '#ffd166', '#ef476f', '#118ab2', '#ffffff']

export default function (el) {
  el.addEventListener('formevent:success', () => fireConfetti())
}

function fireConfetti () {
  if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return

  const canvas = document.createElement('canvas')
  canvas.style.cssText =
    'position:fixed;inset:0;width:100%;height:100%;pointer-events:none;z-index:9999'
  document.body.appendChild(canvas)

  const ctx = canvas.getContext('2d')
  const dpr = window.devicePixelRatio || 1
  const resize = () => {
    canvas.width = window.innerWidth * dpr
    canvas.height = window.innerHeight * dpr
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0)
  }
  resize()
  window.addEventListener('resize', resize)

  const w = () => canvas.width / dpr
  const h = () => canvas.height / dpr

  // Two burst origins (lower-left & lower-right) angled inward and up.
  const origins = [
    { x: w() * 0.15, y: h() * 0.9, dir: -1 },
    { x: w() * 0.85, y: h() * 0.9, dir: 1 }
  ]

  const particles = []
  origins.forEach((o) => {
    for (let i = 0; i < 90; i++) {
      const angle = (-Math.PI / 2) + o.dir * (Math.random() * 0.7) - 0.2 * o.dir
      const speed = 9 + Math.random() * 9
      particles.push({
        x: o.x,
        y: o.y,
        vx: Math.cos(angle) * speed,
        vy: Math.sin(angle) * speed,
        size: 6 + Math.random() * 7,
        rot: Math.random() * Math.PI * 2,
        vr: (Math.random() - 0.5) * 0.4,
        color: COLORS[(Math.random() * COLORS.length) | 0],
        life: 1
      })
    }
  })

  const gravity = 0.28
  const drag = 0.985
  let frames = 0

  function tick () {
    ctx.clearRect(0, 0, w(), h())
    frames++

    particles.forEach((p) => {
      p.vx *= drag
      p.vy = p.vy * drag + gravity
      p.x += p.vx
      p.y += p.vy
      p.rot += p.vr
      if (frames > 90) p.life -= 0.02

      ctx.save()
      ctx.translate(p.x, p.y)
      ctx.rotate(p.rot)
      ctx.globalAlpha = Math.max(0, p.life)
      ctx.fillStyle = p.color
      ctx.fillRect(-p.size / 2, -p.size / 2, p.size, p.size * 0.6)
      ctx.restore()
    })

    const alive = particles.some((p) => p.life > 0 && p.y < h() + 40)
    if (alive) {
      requestAnimationFrame(tick)
    } else {
      window.removeEventListener('resize', resize)
      canvas.remove()
    }
  }

  requestAnimationFrame(tick)
}
