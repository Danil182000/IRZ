<?php
session_start();
require_once 'config/database.php';

if (!function_exists('sanitizeString')) {
    function sanitizeString($str) {
        return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
    }
}

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'register') {
        // Регистрация (обычная или админская)
        $first_name = trim($_POST['reg_firstname'] ?? '');
        $last_name  = trim($_POST['reg_lastname'] ?? '');
        $email      = trim($_POST['reg_email'] ?? '');
        $phone      = trim($_POST['reg_phone'] ?? '');
        $password   = $_POST['reg_password'] ?? '';
        $password_confirm = $_POST['reg_password_confirm'] ?? '';
        $admin_key  = trim($_POST['admin_secret'] ?? ''); // Секретный ключ для регистрации админа

        if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($password) || empty($password_confirm)) {
            $error = 'Все поля обязательны для заполнения.';
        } elseif ($password !== $password_confirm) {
            $error = 'Пароли не совпадают.';
        } else {
            // Проверка, что email не занят (обычный или админский)
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                $error = 'Пользователь с таким email уже зарегистрирован.';
            } else {
                // Определяем роль: если передан секретный ключ (например, admin_secret), то роль = admin, иначе – user.
                $role = ($admin_key === 'admin_secret') ? 'admin' : 'user';
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, phone, password, role, created_at, last_login) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
                $stmt->execute([$first_name, $last_name, $email, $phone, $hashedPassword, $role]);
                if ($stmt->rowCount() > 0) {
                    // Автоматический вход после регистрации (если нужно)
                    $_SESSION['user_id'] = $pdo->lastInsertId();
                    $_SESSION['user_name'] = $first_name . ' ' . $last_name;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['role'] = $role;
                    $success = true;
                    // Перенаправляем на личный кабинет
                    if (php_sapi_name() === 'cli' || php_sapi_name() === 'phpdbg') {
                        echo 'REDIRECT_TO: ' . ($role === 'admin' ? 'admin.php' : 'profile.php');
                        return;
                    } else {
                        header('Location: ' . ($role === 'admin' ? 'admin.php' : 'profile.php'));
                        exit;
                    }
                } else {
                    $error = 'Ошибка при регистрации.';
                }
            }
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'login') {
        // Обработка входа
        $email = trim($_POST['login_email'] ?? '');
        $password = $_POST['login_password'] ?? '';
        if (empty($email) || empty($password)) {
            $error = 'Введите email и пароль.';
        } else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                // Перенаправляем на личный кабинет
                if (php_sapi_name() === 'cli' || php_sapi_name() === 'phpdbg') {
                    echo 'REDIRECT_TO: ' . ($user['role'] === 'admin' ? 'admin.php' : 'profile.php');
                    return;
                } else {
                    header('Location: ' . ($user['role'] === 'admin' ? 'admin.php' : 'profile.php'));
                    exit;
                }
            } else {
                $error = 'Неверный email или пароль.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' https:; script-src 'self' https: 'unsafe-inline'; style-src 'self' https: 'unsafe-inline'; img-src 'self' https: data:;">
    <title>Вход и регистрация — Автосервис "Профи"</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2C3E50;
            --secondary-color: #3498DB;
            --accent-color: #E74C3C;
            --light-bg: #F8F9FA;
            --dark-bg: #1A252F;
            --text-light: #ECF0F1;
            --text-dark: #2C3E50;
            --gradient-primary: linear-gradient(135deg, #2C3E50 0%, #3498DB 100%);
            --gradient-accent: linear-gradient(135deg, #E74C3C 0%, #F39C12 100%);
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.1);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
        }
        body {
            font-family: 'Roboto', sans-serif;
            color: var(--text-dark);
            background: var(--light-bg);
            min-height: 100vh;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
        }
        .navbar {
            background: var(--gradient-primary);
            padding: 1rem 0;
            box-shadow: var(--shadow-md);
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--text-light) !important;
        }
        .nav-link {
            color: var(--text-light) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            color: var(--accent-color) !important;
            transform: translateY(-2px);
        }
        .auth-bg {
            min-height: 100vh;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 0 40px;
        }
        .auth-card {
            background: white;
            border-radius: 22px;
            box-shadow: var(--shadow-lg);
            padding: 2.5rem 2.2rem 2rem 2.2rem;
            max-width: 420px;
            width: 100%;
            margin: 0 auto;
            position: relative;
        }
        .auth-tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }
        .auth-tab {
            flex: 1;
            text-align: center;
            padding: 0.8rem 0;
            font-size: 1.15rem;
            font-weight: 600;
            color: var(--primary-color);
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            cursor: pointer;
            transition: color 0.2s, border-color 0.2s;
        }
        .auth-tab.active {
            color: var(--secondary-color);
            border-bottom: 3px solid var(--secondary-color);
        }
        .auth-form .form-control {
            border-radius: 18px;
            padding: 0.9rem 1.2rem;
            font-size: 1.08rem;
            border: 2px solid var(--light-bg);
            margin-bottom: 1.1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .auth-form .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: var(--shadow-sm);
        }
        .auth-form .input-group-text {
            background: var(--light-bg);
            border-radius: 18px 0 0 18px;
            border: none;
            color: var(--secondary-color);
            font-size: 1.1rem;
        }
        .auth-form .btn-primary {
            background: var(--gradient-primary);
            border: none;
            border-radius: 18px;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 0.8rem 0;
            margin-top: 0.5rem;
            transition: background 0.2s;
        }
        .auth-form .btn-primary:hover {
            background: var(--gradient-accent);
        }
        .auth-form .form-text {
            color: var(--secondary-color);
            font-size: 0.97rem;
        }
        .auth-form .form-error {
            color: var(--accent-color);
            background: #fff0f0;
            border-radius: 10px;
            padding: 0.7rem 1rem;
            margin-bottom: 1rem;
            font-size: 1rem;
            text-align: center;
        }
        .auth-form .form-success {
            color: #27ae60;
            background: #eafaf1;
            border-radius: 10px;
            padding: 0.7rem 1rem;
            margin-bottom: 1rem;
            font-size: 1rem;
            text-align: center;
        }
        .auth-form .form-label {
            font-weight: 500;
            color: var(--primary-color);
        }
        .auth-form .input-group {
            margin-bottom: 1.1rem;
        }
        .auth-form .show-hide {
            cursor: pointer;
            color: var(--secondary-color);
            margin-left: -2.2rem;
            z-index: 10;
        }
        @media (max-width: 576px) {
            .auth-card {
                padding: 1.2rem 0.7rem 1.2rem 0.7rem;
            }
        }
        footer {
            background: var(--dark-bg);
            color: var(--text-light);
            padding: 3rem 0 2rem;
        }
    </style>
</head>
<body>
    <!-- Навигация -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">Автосервис "Профи"</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Главная</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="services.php">Услуги</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="faq.php">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="vacancies.php">Вакансии</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="auth.php">Личный кабинет</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="auth-bg">
        <div class="auth-card">
            <div class="auth-tabs">
                <button class="auth-tab active" id="loginTab" type="button">Вход</button>
                <button class="auth-tab" id="registerTab" type="button">Регистрация</button>
            </div>
            <!-- Сообщения об ошибках/успехе будут выводиться здесь через PHP -->
            <?php if (!empty($error)): ?>
                <div class="form-error"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="form-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <!-- Форма входа -->
            <form class="auth-form" id="loginForm" method="post" action="auth.php" autocomplete="on" style="display: block;">
                <input type="hidden" name="action" value="login">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control" name="login_email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" name="login_password" placeholder="Пароль" required id="loginPassword">
                    <span class="input-group-text show-hide" onclick="togglePassword('loginPassword', this)"><i class="fas fa-eye"></i></span>
                </div>
                <button type="submit" class="btn btn-primary w-100">Войти</button>
            </form>
            <!-- Форма регистрации -->
            <form class="auth-form" id="registerForm" method="post" action="auth.php" autocomplete="on" style="display: none;">
                <input type="hidden" name="action" value="register">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" name="reg_firstname" placeholder="Имя" required>
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" name="reg_lastname" placeholder="Фамилия" required>
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control" name="reg_email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    <input type="tel" class="form-control" name="reg_phone" placeholder="Телефон" required pattern="[0-9\-\+\s\(\)]{7,20}">
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" name="reg_password" placeholder="Пароль" required id="regPassword">
                    <span class="input-group-text show-hide" onclick="togglePassword('regPassword', this)"><i class="fas fa-eye"></i></span>
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" name="reg_password_confirm" placeholder="Подтвердите пароль" required id="passwordConfirm">
                    <span class="input-group-text show-hide" onclick="togglePassword('passwordConfirm', this)"><i class="fas fa-eye"></i></span>
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                    <input type="text" class="form-control" name="admin_secret" placeholder="Секретный ключ (для администратора)">
                </div>
                <button type="submit" class="btn btn-primary w-100">Зарегистрироваться</button>
            </form>
        </div>
    </div>

    <!-- Футер -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="mb-4">Автосервис "Профи"</h5>
                    <p>Профессиональный ремонт и обслуживание автомобилей всех марок. Качество, надежность, доступные цены.</p>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-4">Быстрые ссылки</h5>
                    <ul class="list-unstyled">
                        <li><a href="services.php" class="text-light">Услуги</a></li>
                        <li><a href="faq.php" class="text-light">FAQ</a></li>
                        <li><a href="vacancies.php" class="text-light">Вакансии</a></li>
                        <li><a href="auth.php" class="text-light">Личный кабинет</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-4">Мы в соцсетях</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-light"><i class="fab fa-facebook fa-2x"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-instagram fa-2x"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-vk fa-2x"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-telegram fa-2x"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; 2024 Автосервис "Профи". Все права защищены.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Переключение вкладок
        const loginTab = document.getElementById('loginTab');
        const registerTab = document.getElementById('registerTab');
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        loginTab.addEventListener('click', function() {
            loginTab.classList.add('active');
            registerTab.classList.remove('active');
            loginForm.style.display = 'block';
            registerForm.style.display = 'none';
        });
        registerTab.addEventListener('click', function() {
            registerTab.classList.add('active');
            loginTab.classList.remove('active');
            loginForm.style.display = 'none';
            registerForm.style.display = 'block';
        });
        // Показать/скрыть пароль
        function togglePassword(id, el) {
            const input = document.getElementById(id);
            if (input.type === 'password') {
                input.type = 'text';
                el.querySelector('i').classList.remove('fa-eye');
                el.querySelector('i').classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                el.querySelector('i').classList.remove('fa-eye-slash');
                el.querySelector('i').classList.add('fa-eye');
            }
        }
    </script>
</body>
</html> 