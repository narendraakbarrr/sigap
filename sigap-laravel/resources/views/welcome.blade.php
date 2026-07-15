<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'SIGAP') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            width: 100%;
            font-family: 'Poppins', 'Segoe UI', Arial, sans-serif;
        }

        .hero {
            position: relative;
            width: 100%;
            min-height: 100vh;
            overflow: hidden;
            background: linear-gradient(135deg, #3a6df0 0%, #5b5ce8 35%, #7c4fe0 60%, #b23fbf 85%, #d94fa8 100%);
        }

        /* Diagonal capsule stripes crossing the hero, matching the reference image */
        .stripe {
            position: absolute;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 999px;
            transform: rotate(-40deg);
        }

        .stripe--1 { width: 130px; height: 950px; top: -300px; left: 58%; background: rgba(255,255,255,0.12); }
        .stripe--2 { width: 130px; height: 950px; top: -200px; left: 68%; background: rgba(255,255,255,0.18); }
        .stripe--3 { width: 130px; height: 950px; top: -100px; left: 78%; background: rgba(255,255,255,0.12); }
        .stripe--4 { width: 90px;  height: 950px; top: 60px;   left: 38%; background: rgba(255,255,255,0.08); }
        .stripe--5 { width: 90px;  height: 950px; top: 160px;  left: 20%; background: rgba(255,255,255,0.06); }

        /* Big soft circles / blobs */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(0px);
        }

        .blob--orange-pink {
            width: 380px;
            height: 380px;
            top: -180px;
            left: 300px;
            background: radial-gradient(circle at 30% 30%, #ff7a3d 0%, #ef4f8f 55%, rgba(239,79,143,0) 75%);
            opacity: 0.9;
        }

        .blob--cyan {
            width: 420px;
            height: 420px;
            bottom: -220px;
            left: -140px;
            background: radial-gradient(circle at 60% 40%, #35e0e0 0%, #2e8fe0 55%, rgba(46,143,224,0) 75%);
            opacity: 0.85;
        }

        .blob--magenta {
            width: 460px;
            height: 460px;
            bottom: -260px;
            right: -160px;
            background: radial-gradient(circle at 40% 40%, #d94fb0 0%, #a63fd9 55%, rgba(166,63,217,0) 75%);
            opacity: 0.9;
        }

        .blob--small-cyan {
            width: 160px;
            height: 160px;
            bottom: 40px;
            left: 380px;
            background: radial-gradient(circle at 40% 40%, #35e0e0 0%, rgba(53,224,224,0) 70%);
            opacity: 0.7;
        }

        /* Diagonal light overlay to mimic the sheen in the reference */
        .overlay-lines {
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(
                135deg,
                rgba(255,255,255,0.03) 0px,
                rgba(255,255,255,0.03) 2px,
                transparent 2px,
                transparent 40px
            );
        }

        /* Auth buttons - top right corner */
        .auth-buttons {
            position: absolute;
            top: 30px;
            right: 40px;
            display: flex;
            gap: 12px;
            z-index: 10;
        }

        .auth-buttons a {
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 30px;
            transition: all 0.25s ease;
        }

        .btn-login {
            color: #ffffff;
            border: 1.5px solid rgba(255, 255, 255, 0.8);
            background: transparent;
        }

        .btn-login:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .btn-register {
            color: #6a3fd9;
            background: #ffffff;
        }

        .btn-register:hover {
            background: rgba(255, 255, 255, 0.85);
        }

        .hero-content {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 120px 40px;
            z-index: 5;
        }

        .hero-copy {
            max-width: 720px;
            color: #ffffff;
            text-align: left;
        }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            padding: 0.85rem 1.25rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.18);
            color: #ffffff;
            font-size: 0.8rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .hero-title {
            font-size: clamp(3rem, 5vw, 5.2rem);
            line-height: 0.95;
            letter-spacing: -0.04em;
            margin-bottom: 1.25rem;
        }

        .hero-text {
            max-width: 660px;
            font-size: 1.05rem;
            line-height: 1.9;
            opacity: 0.92;
            margin-bottom: 2rem;
        }

        .hero-features {
            display: flex;
            flex-wrap: wrap;
            gap: 0.85rem;
        }

        .hero-feature {
            padding: 0.95rem 1.15rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.13);
            border: 1px solid rgba(255, 255, 255, 0.18);
            color: #ffffff;
            font-size: 0.95rem;
            backdrop-filter: blur(10px);
        }

        @media (max-width: 600px) {
            .auth-buttons {
                top: 18px;
                right: 18px;
            }

            .auth-buttons a {
                padding: 8px 16px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <div class="hero">
        <div class="overlay-lines"></div>

        <div class="blob blob--orange-pink"></div>
        <div class="blob blob--cyan"></div>
        <div class="blob blob--magenta"></div>
        <div class="blob blob--small-cyan"></div>

        <div class="stripe stripe--1"></div>
        <div class="stripe stripe--2"></div>
        <div class="stripe stripe--3"></div>
        <div class="stripe stripe--4"></div>
        <div class="stripe stripe--5"></div>

        <div class="auth-buttons">
            <a href="{{ route('login') }}" class="btn-login">Login</a>
            <a href="{{ route('register') }}" class="btn-register">Register</a>
        </div>

        <div class="hero-content">
            <div class="hero-copy">
                <div class="hero-eyebrow">Siap Tanggap</div>
                <h1 class="hero-title">SIGAP: Sistem Pelaporan Infrastruktur dan Aspirasi Publik</h1>
                <p class="hero-text">Kelola laporan darurat, pantau kategori, dan berikan respons cepat dengan antarmuka yang jelas, ringan, dan mudah digunakan. SIGAP membantu tim kamu tetap terkoordinasi dan siap beraksi.</p>
                <div class="hero-features">
                    <span class="hero-feature">Laporan real-time</span>
                    <span class="hero-feature">Dashboard analitik</span>
                    <span class="hero-feature">Manajemen pengguna</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
