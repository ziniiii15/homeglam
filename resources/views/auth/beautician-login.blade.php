<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beautician Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

        :root {
            --primary: #D63384;
            --primary-light: #ff85c0;
            --accent: #FFA6C9;
            --text-light: #ffffff;
            --text-muted: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.15);
            --glass-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
        }

        /* Background */
        .bg-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('{{ asset("view-asset/bg1.png") }}') no-repeat center center;
            background-size: cover;
            z-index: -2;
            transform: scale(1.05);
            animation: slowZoom 20s infinite alternate;
        }

        .bg-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0,0,0,0.85) 0%, rgba(20, 5, 10, 0.7) 50%, rgba(50, 10, 30, 0.6) 100%);
            z-index: -1;
        }

        /* Container */
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            z-index: 1;
        }

        /* Glass Card */
        .glass-card {
            background: rgba(20, 20, 20, 0.6);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow: var(--glass-shadow);
            padding: 3rem;
            width: 100%;
            max-width: 420px;
            animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }

        /* Form Elements */
        h3 {
            color: white;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            letter-spacing: -0.5px;
        }

        .form-label {
            color: var(--text-muted);
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--primary-light);
            box-shadow: 0 0 0 4px rgba(214, 51, 132, 0.15);
            color: white;
            outline: none;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        /* Button */
        .btn-submit {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            margin-top: 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(214, 51, 132, 0.3);
        }

        .btn-submit:hover {
            background: #c0206d;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(214, 51, 132, 0.5);
            color: white;
        }

        /* Links */
        .auth-link {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .auth-link a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .auth-link a:hover {
            color: var(--primary-light);
            text-decoration: underline;
        }

        /* Animations */
        @keyframes slowZoom {
            from { transform: scale(1); }
            to { transform: scale(1.1); }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 576px) {
            .glass-card {
                padding: 2rem;
            }
            h3 {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>

    <div class="bg-image"></div>
    <div class="bg-overlay"></div>

    <div class="auth-container">
        <div class="glass-card">
            <h3>Beautician Login</h3>

            <form method="POST" action="{{ route('beautician.login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="name@example.com">
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="••••••••">
                </div>

                <button type="submit" class="btn-submit">Login</button>
            </form>

            <div class="auth-link">
                @if(session('too_many_attempts'))
                    <div class="mb-2">
                        <span>Too many failed attempts. You may reset your password.</span>
                    </div>
                    <a href="{{ route('beautician.password.forgot') }}">Forgot Password?</a>
                    <div class="mt-2">
                        Don’t have an account? <a href="{{ route('beautician.register') }}">Register</a>
                    </div>
                @else
                    Don’t have an account? <a href="{{ route('beautician.register') }}">Register</a>
                @endif
            </div>
        </div>
    </div>

</body>
</html>

