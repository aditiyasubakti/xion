<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <link rel="shortcut icon" type="text/css" href="favicon.ico">
  <title>404 — Xion Framework</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
    :root{--bg:#07070a;--accent:#00c6ff;--muted:rgba(255,255,255,0.08)}
    *{box-sizing:border-box}
    body{margin:0;height:100vh;display:flex;align-items:center;justify-content:center;background:radial-gradient(circle at 10% 10%, #0f1b2b 0%, #07070a 40%);font-family:'Poppins',sans-serif;color:#fff}

    .wrap{width:920px;max-width:92%;display:grid;grid-template-columns:1fr 380px;gap:30px;align-items:center}

    /* Left panel */
    .left{padding:40px;background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));border-radius:18px;border:1px solid var(--muted);backdrop-filter:blur(8px)}
    .code{font-size:5.5rem;font-weight:800;letter-spacing:6px;color:var(--accent);text-shadow:0 6px 30px rgba(0,198,255,0.08);}
    .title{font-size:1.25rem;margin-top:6px;font-weight:600}
    .desc{margin-top:12px;color:rgba(255,255,255,0.8);line-height:1.5}
    .actions{margin-top:22px;display:flex;gap:12px;align-items:center}
    .btn{padding:12px 18px;border-radius:10px;text-decoration:none;font-weight:600}
    .btn-primary{background:var(--accent);color:#000;box-shadow:0 6px 20px rgba(0,198,255,0.18)}
    .btn-ghost{background:transparent;border:1px solid var(--muted);color:rgba(255,255,255,0.9)}

    /* Right panel (card) */
    .card{padding:18px;border-radius:14px;background:linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.005));border:1px solid var(--muted);display:flex;flex-direction:column;gap:16px;align-items:center}
    .logo{width:96px;height:96px;border-radius:14px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#02233a,#001320);box-shadow:0 8px 30px rgba(0,0,0,0.6);position:relative;overflow:hidden}
    .logo svg{width:68px;height:68px;filter:drop-shadow(0 6px 16px rgba(0,198,255,0.14))}
    .hint{font-size:0.95rem;opacity:0.9;text-align:center}
    .meta{font-family:monospace;font-size:0.85rem;color:rgba(255,255,255,0.65);background:rgba(255,255,255,0.02);padding:8px 10px;border-radius:8px}

    /* small screens */
    @media(max-width:780px){
      .wrap{grid-template-columns:1fr;}
      .left{padding:28px}
      .code{font-size:4.5rem}
    }

    /* subtle floating lines */
    .glow{position:absolute;inset:0;pointer-events:none}
    .glow:before{content:"";position:absolute;left:-10%;top:-30%;width:40%;height:140%;background:linear-gradient(120deg, rgba(0,198,255,0.06), transparent 40%);transform:rotate(-18deg)}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="left">
      <div class="code">404</div>
      <div class="title">Halaman tidak ditemukan</div>
      <div class="desc">Halaman yang Anda cari tidak tersedia di server Xion. Mungkin URL salah ketik, halaman sudah dipindah, atau belum dibuat. Tenang — Xion tetap cepat kok.</div>

      <div class="actions">
        <a href="/" class="btn btn-primary">Kembali ke Home</a>
        <a href="/docs" class="btn btn-ghost">Buka Dokumentasi</a>
      </div>

      <div style="margin-top:18px;color:rgba(255,255,255,0.6);font-size:0.92rem">Coba juga mengetik alamat yang benar atau hubungi tim jika perlu.</div>
    </div>

    <div class="card">
      <div class="logo" aria-hidden="true">
        <!-- Inline SVG neon X logo -->
        <svg viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Xion logo">
          <defs>
            <linearGradient id="g" x1="0" x2="1">
              <stop offset="0%" stop-color="#00c6ff"/>
              <stop offset="100%" stop-color="#00a0ff"/>
            </linearGradient>
            <filter id="f" x="-50%" y="-50%" width="200%" height="200%">
              <feGaussianBlur stdDeviation="3" result="b"/>
              <feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge>
            </filter>
          </defs>
          <g filter="url(#f)">
            <path d="M20 18 L60 60 L20 102 L42 120 L82 78 L122 120 L142 102 L102 60 L142 18 L122 0 L82 42 L42 0 Z" fill="url(#g)" opacity="0.95"/>
          </g>
        </svg>
      </div>

      <div class="hint">Xion Framework — ringan, modular, dan siap dipakai.</div>
      <div class="meta">Requested URL: <span id="url">/unknown</span></div>
      <div style="font-size:0.85rem;color:rgba(255,255,255,0.55);text-align:center">Jika Anda developer, letakkan file ini sebagai <strong>404.html</strong> di folder publik (root). Untuk server lain, konfigurasikan routing 404 di server / webserver Anda.</div>
    </div>
  </div>

  <div class="glow"></div>

  <script>
    // Tampilkan URL yang diminta (jika tersedia) — berguna saat men-debug
    const meta = document.getElementById('url');
    try{
      meta.textContent = window.location.pathname + (window.location.search||'');
    }catch(e){/* ignore */}

    // Animasi ringan: naik turun logo
    const logo = document.querySelector('.logo');
    let dir = 1, pos = 0;
    setInterval(()=>{
      pos += dir*0.18; if(pos>6||pos<-6) dir*=-1;
      logo.style.transform = `translateY(${pos}px)`;
    },16);
  </script>
</body>
</html>
