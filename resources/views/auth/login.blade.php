<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – Sistem Peminjaman Alat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0A0A0C;
            --text-main: #F5F5F7;
            --text-soft: #9A9AA2;
            --text-faint: #55555E;
            --field: #1A1A1E;
            --field-border: #2A2A30;
            --field-border-focus: #6D6D78;
            --accent: #E7E7E9;
        }

        * { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: "";
            position: absolute;
            top: -35%;
            left: -20%;
            width: 160%;
            height: 90%;
            background: radial-gradient(ellipse 70% 55% at 50% 0%, #ffffff 0%, #e4d4ff 12%, #b088f5 28%, #7c4de0 45%, #3a1f8f 62%, transparent 78%);
            filter: blur(55px);
            z-index: 0;
            opacity: 0.95;
            animation: horizon-shimmer 6s ease-in-out infinite alternate;
            pointer-events: none;
        }

        @keyframes horizon-shimmer {
            0%   { opacity: 0.75; }
            100% { opacity: 1; }
        }

        .split {
            position: relative;
            z-index: 1;
            display: flex;
            gap: 64px;
            align-items: center;
            max-width: 1000px;
            width: 100%;
        }

        .photo-card {
            position: relative;
            flex-shrink: 0;
            width: 320px;
            height: 460px;
            border-radius: 20px;
            background-image: url('/images/sf-bridge.jpg');
            background-size: cover;
            background-position: center;
            box-shadow: 0 30px 70px -20px rgba(0,0,0,0.6);
            z-index: 1;
        }

        .photo-card::before {
            content: "";
            position: absolute;
            top: -6%;
            left: -6%;
            width: 112%;
            height: 112%;
            background: radial-gradient(ellipse at 50% 40%, rgba(255, 170, 100, 0.35) 0%, rgba(255, 140, 80, 0.16) 35%, transparent 70%);
            filter: blur(40px);
            z-index: -1;
            pointer-events: none;
        }

        @media (max-width: 860px) {
            .photo-card { display: none; }
        }

        .photo-card::after {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 20px;
            background: linear-gradient(180deg, rgba(0,0,0,0.15) 0%, transparent 30%, rgba(0,0,0,0.55) 100%);
        }

        .photo-badge {
            position: absolute;
            top: 18px;
            left: 18px;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(0,0,0,0.35);
            border: 1px solid rgba(255,255,255,0.18);
            backdrop-filter: blur(8px);
            padding: 6px 12px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            color: #fff;
        }

        .photo-badge span {
            font-size: 10px;
            font-weight: 700;
            border: 1px solid rgba(255,255,255,0.4);
            border-radius: 4px;
            padding: 1px 5px;
        }

        .photo-caption {
            position: absolute;
            bottom: 14px;
            left: 18px;
            z-index: 2;
            font-size: 11px;
            color: rgba(255,255,255,0.65);
        }

        .form-side {
            flex: 1;
            min-width: 280px;
        }

        .form-side h1 {
            font-size: 26px;
            font-weight: 700;
            color: var(--text-main);
            margin: 0 0 26px;
            line-height: 1.3;
            letter-spacing: -0.3px;
        }

        .alert {
            background: rgba(244, 63, 94, 0.1);
            border: 1px solid rgba(244, 63, 94, 0.28);
            color: #FCA5B1;
            font-size: 13px;
            line-height: 1.5;
            padding: 11px 14px;
            border-radius: 10px;
            margin-bottom: 16px;
        }

        .alert ul { margin: 0; padding-left: 16px; }

        .field { margin-bottom: 12px; }

        .field input {
            width: 100%;
            padding: 14px 16px;
            font-family: 'Inter', sans-serif;
            font-size: 14.5px;
            color: var(--text-main);
            background: var(--field);
            border: 1px solid var(--field-border);
            border-radius: 12px;
            outline: none;
            transition: border-color 0.15s ease;
        }

        .field input::placeholder { color: var(--text-faint); }

        .field input:focus { border-color: var(--field-border-focus); }

        .submit {
            width: 100%;
            padding: 14px 18px;
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 14.5px;
            font-weight: 600;
            color: #0A0A0C;
            background: var(--accent);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: filter 0.15s ease, transform 0.1s ease;
        }

        .submit:hover { filter: brightness(0.95); }
        .submit:active { transform: translateY(1px); }

        .foot {
            margin-top: 22px;
            font-size: 12.5px;
            color: var(--text-faint);
            line-height: 1.6;
        }

        .foot a { color: var(--text-soft); text-decoration: underline; }

        .roles {
            display: flex;
            gap: 8px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .roles span {
            font-size: 11px;
            color: var(--text-soft);
            background: var(--field);
            border: 1px solid var(--field-border);
            padding: 4px 10px;
            border-radius: 999px;
        }
    </style>
</head>
<body>

    <div class="split">
        <div class="photo-card">
            <div class="photo-badge">San Francisco <span>ID</span></div>
            <div class="photo-caption">Bay Bridge, malam hari</div>
        </div>

        <div class="form-side">
            <h1>Selamat Datang Kembali,<br>Masuk ke Sistem Peminjaman Alat</h1>

            @if(session('error'))
                <div class="alert">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="field">
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
                </div>

                <div class="field">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>

                <button type="submit" class="submit">
                    Masuk ke Akun
                    <span>→</span>
                </button>
            </form>

            <div class="roles">
                <span>Admin</span>
                <span>Petugas</span>
                <span>Peminjam</span>
            </div>

            <p class="foot">Dengan masuk, Anda menyetujui <a href="#">Ketentuan Layanan</a> dan <a href="#">Kebijakan Privasi</a> sistem ini.</p>
        </div>
    </div>

</body>
</html>