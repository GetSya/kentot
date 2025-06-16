<?php
session_start();
// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['id_user'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ACA POS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><radialGradient id="a" cx="50%" cy="0%" r="50%"><stop offset="0%" stop-color="rgba(255,255,255,0.1)"/><stop offset="100%" stop-color="rgba(255,255,255,0)"/></radialGradient></defs><rect width="11" height="11" fill="url(%23a)" rx="2" ry="2"/><rect x="20" width="11" height="11" fill="url(%23a)" rx="2" ry="2"/></svg>') repeat;
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(180deg); }
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        .login-wrapper {
            display: flex;
            max-width: 900px;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            overflow: hidden;
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-left {
            flex: 1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 3rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            position: relative;
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><defs><pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse"><path d="M 20 0 L 0 0 0 20" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="200" height="200" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .login-left > * {
            position: relative;
            z-index: 1;
        }

        .brand-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .brand-icon i {
            font-size: 2rem;
            color: white;
        }

        .login-left h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .login-left p {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        .login-right {
            flex: 1;
            padding: 3rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h3 {
            font-size: 2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: #666;
            font-size: 1rem;
        }

        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 1rem 1rem 1rem 3rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
        }

        .form-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: 1.1rem;
            z-index: 2;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }

        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .alert-danger {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
        }

        .alert-success {
            background: linear-gradient(135deg, #51cf66, #40c057);
            color: white;
        }

        .alert-warning {
            background: linear-gradient(135deg, #ffd43b, #fab005);
            color: #333;
        }

        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float-shapes 15s infinite ease-in-out;
        }

        .shape-1 {
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape-2 {
            top: 60%;
            right: 10%;
            animation-delay: 5s;
        }

        .shape-3 {
            bottom: 20%;
            left: 20%;
            animation-delay: 10s;
        }

        @keyframes float-shapes {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
                margin: 20px;
            }
            
            .login-left {
                padding: 2rem 1.5rem;
            }
            
            .login-left h2 {
                font-size: 2rem;
            }
            
            .login-right {
                padding: 2rem 1.5rem;
            }
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            z-index: 2;
        }

        .password-toggle:hover {
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div class="shape shape-1">
            <i class="fas fa-circle" style="font-size: 60px; color: white;"></i>
        </div>
        <div class="shape shape-2">
            <i class="fas fa-square" style="font-size: 40px; color: white;"></i>
        </div>
        <div class="shape shape-3">
            <i class="fas fa-triangle" style="font-size: 50px; color: white;"></i>
        </div>
    </div>

    <div class="login-container">
        <div class="login-wrapper">
            <div class="login-left">
                <div class="brand-icon">
                    <i class="fas fa-cash-register"></i>
                </div>
                <h2>ACA POS</h2>
                <p>Sistem Point of Sale terdepan untuk mengelola bisnis Anda dengan mudah dan efisien. Akses cepat, laporan real-time, dan kontrol penuh atas operasional toko.</p>
            </div>
            
            <div class="login-right">
                <div class="login-header">
                    <h3>Selamat Datang</h3>
                    <p>Silakan masuk ke akun Anda</p>
                </div>

                <?php
                if (isset($_GET['pesan']) && $_GET['pesan'] == 'gagal') {
                    echo '<div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Username atau password salah!
                          </div>';
                }
                if (isset($_GET['pesan']) && $_GET['pesan'] == 'logout') {
                    echo '<div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            Anda berhasil logout.
                          </div>';
                }
                if (isset($_GET['pesan']) && $_GET['pesan'] == 'belum_login') {
                    echo '<div class="alert alert-warning" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            Anda harus login untuk mengakses halaman.
                          </div>';
                }
                ?>

                <form action="auth/proses_login.php" method="POST">
                    <div class="form-group">
                        <i class="fas fa-user form-icon"></i>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required>
                    </div>
                    
                    <div class="form-group">
                        <i class="fas fa-lock form-icon"></i>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                        <i class="fas fa-eye password-toggle" onclick="togglePassword()"></i>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-login">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Masuk ke Dashboard
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.querySelector('.password-toggle');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            const formControls = document.querySelectorAll('.form-control');
            
            formControls.forEach(control => {
                control.addEventListener('focus', function() {
                    this.parentElement.querySelector('.form-icon').style.color = '#667eea';
                });
                
                control.addEventListener('blur', function() {
                    if (this.value === '') {
                        this.parentElement.querySelector('.form-icon').style.color = '#666';
                    }
                });
            });
        });
    </script>
</body>
</html>