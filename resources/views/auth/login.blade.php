<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BPS Bantul</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Fraunces:wght@400;500;600&display=swap');

        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            box-sizing: border-box;
        }

        .claude-title {
            font-family: 'Fraunces', 'Georgia', 'Times New Roman', serif;
            font-weight: 400;
            letter-spacing: -0.02em;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #1a1a1a; /* Fallback color */
            color: #e8e8e8;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow-x: hidden; /* Prevent horizontal scroll */
            position: relative;
        }

        /* --- VIDEO BACKGROUND STYLES --- */
        .video-background {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: -1; /* Place behind everything */
            overflow: hidden;
            pointer-events: none; /* Prevent clicking on video */
        }

        .video-foreground,
        .video-background iframe {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100vw;
            height: 56.25vw; /* 16:9 Aspect Ratio (100 * 9 / 16) */
            min-height: 100vh;
            min-width: 177.77vh; /* 16:9 Aspect Ratio (100 * 16 / 9) */
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        /* Overlay hitam transparan agar text tetap terbaca jelas */
        .video-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4); /* Opacity overlay */
            z-index: -1;
        }
        /* ------------------------------- */

        /* Form Card with Animation */
        .form-card {
            background-color: rgba(42, 42, 42, 0.75); /* Lebih transparan */
            backdrop-filter: blur(15px); /* Efek blur kaca */
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            position: relative;
            z-index: 10;
            animation: slideUp 0.8s ease-out;
            margin: 1rem;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Logo Animation */
        .logo-container {
            animation: fadeIn 1s ease-out 0.3s both;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        /* Input Styles */
        .claude-input {
            background-color: rgba(45, 45, 45, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #e8e8e8;
            padding: 0.875rem 1rem;
            border-radius: 12px;
            width: 100%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .claude-input:focus {
            outline: none;
            border-color: #d97757;
            background-color: rgba(45, 45, 45, 0.9);
            box-shadow: 0 0 0 3px rgba(217, 119, 87, 0.3);
            transform: translateY(-2px);
        }

        .claude-input::placeholder { color: #888; }

        .input-group { animation: slideInLeft 0.6s ease-out both; }
        .input-group:nth-child(1) { animation-delay: 0.4s; }
        .input-group:nth-child(2) { animation-delay: 0.5s; }

        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .claude-label {
            display: block;
            color: #d1d1d1;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        /* Button Styles */
        .claude-button {
            background: linear-gradient(135deg, #d97757 0%, #e88968 100%);
            color: #ffffff;
            padding: 0.875rem 1.75rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(217, 119, 87, 0.3);
        }

        .claude-button:hover {
            background: linear-gradient(135deg, #e88968 0%, #f09a7a 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(217, 119, 87, 0.4);
        }

        .button-container { animation: slideInRight 0.6s ease-out 0.6s both; }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .error-message {
            color: #f87171;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .link-forgot {
            color: #9ca3af;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 0.875rem;
        }

        .link-forgot:hover { color: #d97757; }

        .remember-container { animation: fadeIn 0.6s ease-out 0.7s both; }

        input[type="checkbox"] {
            width: 1.125rem;
            height: 1.125rem;
            border-radius: 6px;
            border: 1px solid #555;
            background-color: #2a2a2a;
            cursor: pointer;
            transition: all 0.2s;
            accent-color: #d97757;
        }

        .logo-image {
            max-width: 80px;
            height: auto;
            margin: 0 auto 1rem;
            display: block;
        }
    </style>
</head>

<body>
    <div class="video-background">
        <div class="video-foreground">
            <iframe 
                src="https://www.youtube.com/embed/bRNlrdfKNd4?controls=0&autoplay=1&mute=1&playsinline=1&loop=1&playlist=bRNlrdfKNd4&showinfo=0&rel=0&iv_load_policy=3&disablekb=1" 
                frameborder="0" 
                allow="autoplay; encrypted-media" 
                allowfullscreen>
            </iframe>
        </div>
    </div>
    <div class="video-overlay"></div>
    <div class="form-card">
        <div class="text-center mb-8 logo-container">
            <img src="/images/Magnet.png" alt="Logo BPS Bantul" class="logo-image">
            <h1 class="claude-title text-2xl text-white mb-2">MagNet</h1>
            <p class="text-sm text-[#9ca3af]">Magang Network</p>
            <p class="text-sm text-[#9ca3af]">Aplikasi Monitoring dan Pendaftaran Magang </p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-5 input-group">
                <label for="email" class="claude-label">Email</label>
                <input id="email" class="claude-input" type="email" name="email" value="{{ old('email') }}" required
                    autofocus autocomplete="username" placeholder="admin@bps.go.id" />
                @error('email')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5 input-group">
                <label for="password" class="claude-label">Password</label>
                <input id="password" class="claude-input" type="password" name="password" required
                    autocomplete="current-password" placeholder="••••••••" />
                @error('password')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="block mt-4 remember-container">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input id="remember_me" type="checkbox" name="remember">
                    <span class="ms-2 text-sm text-[#c4c4c4]">Ingat saya</span>
                </label>
            </div>

            <div class="flex items-center justify-between mt-6 button-container">
                @if (Route::has('password.request'))
                    <a class="link-forgot" href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif

                <button type="submit" class="claude-button">
                    Log in
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </form>

        <div class="relative my-6" style="animation: fadeIn 0.6s ease-out 0.8s both;">
            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                <div class="w-full border-t border-gray-600"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-transparent text-gray-300" style="background-color: #2a2a2a; border-radius: 4px;">Pendaftar Harus Login Dengan Google</span>
            </div>
        </div>

        <div style="animation: slideInRight 0.6s ease-out 0.9s both;">
            <a href="{{ route('google.redirect') }}" 
                class="claude-button w-full flex justify-center items-center" 
                style="background: #ffffff; color: #1f2937; text-decoration: none; box-shadow: 0 4px 15px rgba(255, 255, 255, 0.1);">
                <i class="fab fa-google mr-2"></i> 
                <span>Login dengan Google</span>
            </a>
        </div>
        
    </div>
</body>

</html>