<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>welcome xion</title>
    <link rel="shortcut icon" type="text/css" href="favicon.ico">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            overflow: hidden;
            background: radial-gradient(circle at top, #182848, #0f0f1a);
            color: white;
        }

        /* Floating Particles */
        .particle {
            position: absolute;
            background: rgba(0, 200, 255, 0.8);
            border-radius: 50%;
            animation: floatUp linear infinite;
        }

        @keyframes floatUp {
            from { transform: translateY(0) scale(1); opacity: 1; }
            to { transform: translateY(-200vh) scale(0.5); opacity: 0; }
        }

        .content {
            position: relative;
            z-index: 2;
            text-align: center;
            top: 50%;
            transform: translateY(-50%);
            animation: fadeIn 1.3s ease-out;
        }

        h1 {
            font-size: 4rem;
            font-weight: 700;
            letter-spacing: 4px;
        }

        h1 span {
            color: #00c6ff;
            text-shadow: 0 0 15px #00c6ff;
        }

        p.subtitle {
            font-size: 1.3rem;
            margin-top: 10px;
            opacity: 0.85;
        }

        .card {
            margin: 35px auto;
            width: 450px;
            padding: 25px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.15);
            box-shadow: 0 0 25px rgba(0,0,0,0.4);
            animation: popUp 1.4s ease;
        }

        @keyframes popUp {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .btn-docs {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            font-size: 1rem;
            background: #00c6ff;
            color: #000;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.25s;
            box-shadow: 0 0 12px #00c6ff;
        }

        .btn-docs:hover {
            background: #0097cc;
            box-shadow: 0 0 20px #0097cc;
        }
    </style>
</head>
<body>

    <!-- Floating Particles JS -->
    <script>
        function createParticle() {
            const particle = document.createElement('div');
            const size = Math.random() * 6 + 3;
            const duration = Math.random() * 4 + 6;
            const left = Math.random() * window.innerWidth;

            particle.classList.add('particle');
            particle.style.width = size + 'px';
            particle.style.height = size + 'px';
            particle.style.left = left + 'px';
            particle.style.animationDuration = duration + 's';

            document.body.appendChild(particle);

            setTimeout(() => particle.remove(), duration * 1000);
        }

        setInterval(createParticle, 120);
    </script>

    <div class="content">
        <h1>Welcome to <span>Xion</span></h1>
        <p class="subtitle">Framework super ringan dengan kecepatan seperti kilat ⚡</p>

        <div class="card">
            <p>Xion berhasil dijalankan!</p>
            <p>Mulai kembangkan aplikasi Anda di folder <strong>/app</strong></p>

            <a href="#" class="btn-docs">📘 Dokumentasi</a>
        </div>
    </div>

</body>
</html>
