<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaCare - Management System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary: #2c6bac;
            --secondary: #4bb543;
            --accent: #17a2b8;
            --light-bg: #f8f9fa;
            --dark-text: #343a40;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background:
                linear-gradient(rgba(255, 255, 255, 0.7),
                    rgba(245, 247, 249, 0.7)),
                url('/assets/images/auth-bg-1.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            color: #212529;
            /* ensure good text contrast */
        }

        /* Floating overlay elements */
        body::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background:
                radial-gradient(circle at 20% 20%, rgba(44, 107, 172, 0.08) 0%, transparent 25%),
                radial-gradient(circle at 80% 80%, rgba(75, 181, 67, 0.08) 0%, transparent 25%),
                radial-gradient(circle at 40% 60%, rgba(23, 162, 184, 0.08) 0%, transparent 25%);
            animation: float 60s infinite linear;
            /* slower for elegance */
            pointer-events: none;
            z-index: -1;
        }

        /* Smooth floating effect */
        @keyframes float {
            0% {
                transform: rotate(0deg) scale(1);
            }

            50% {
                transform: rotate(180deg) scale(1.05);
            }

            100% {
                transform: rotate(360deg) scale(1);
            }
        }


        .login-container {
            max-width: 420px;
            width: 100%;
        }

        .login-card {
            border-radius: 12px;
            border: none;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            background: white;
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary), #1a4f7a);
            color: white;
            padding: 25px 20px;
            text-align: center;
        }

        .login-header h3 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 1.8rem;
        }

        .login-header p {
            opacity: 0.9;
            font-size: 0.95rem;
            margin-bottom: 0;
        }

        .login-body {
            padding: 25px;
        }

        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 0.95rem;
            border: 1px solid #ddd;
            transition: all 0.3s;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(44, 107, 172, 0.2);
            border-color: var(--primary);
        }

        .input-group-text {
            background: white;
            border-radius: 8px 0 0 8px;
            border-right: none;
            color: var(--primary);
        }

        .form-floating>label {
            padding-left: 45px;
        }

        .btn-login {
            background: linear-gradient(to right, var(--primary), #3a7cbd);
            border: none;
            padding: 12px;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-login:hover {
            background: linear-gradient(to right, #255a9a, var(--primary));
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(44, 107, 172, 0.3);
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #ddd;
        }

        .divider span {
            padding: 0 10px;
            color: #6c757d;
            font-size: 0.85rem;
        }

        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #1a4f7a;
            text-decoration: underline;
        }

        .alert-danger {
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h3>PharmaCare System</h3>
                <p>Management Portal Access</p>
            </div>

            <div class="login-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST"
                    action="{{ route('tenant.login.attempt', ['tenant' => request()->route('tenant')]) }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                                placeholder="Enter your email" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" class="form-control"
                                placeholder="Enter your password" required>
                        </div>
                    </div>

                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Remember Me</label>
                        </div>
                        <a href="#" class="forgot-link">Forgot Password?</a>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-login text-white" type="submit">
                            <i class="fas fa-sign-in-alt me-2"></i> Login
                        </button>
                    </div>
                </form>

                <div class="divider">
                    <span>Secure Access</span>
                </div>

                <div class="text-center">
                    <p class="small text-muted">
                        <i class="fas fa-shield-alt me-1"></i>
                        Your pharmacy data is securely protected
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center mt-3">
            <p class="small text-muted">© 2023 PharmaCare Management System. All rights reserved.</p>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
