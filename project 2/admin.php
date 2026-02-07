<?php
session_start();
require_once 'config/database.php';

// Проверка авторизации и роли
if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit;
}
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$admin = $stmt->fetch();
if (!$admin || $admin['role'] !== 'admin') {
    header('Location: auth.php');
    exit;
}

// Выход из аккаунта админа
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: auth.php');
    exit;
}

// 2. CRUD-операции (пример для пользователей, остальные сущности аналогично)
$info = '';
$error = '';

// Удаление пользователя
if (isset($_GET['delete_user'])) {
    $userId = (int)$_GET['delete_user'];
    if ($userId !== $admin['id']) { // нельзя удалить себя
        $pdo->prepare('DELETE FROM users WHERE id = ?')->execute([$userId]);
        $info = 'Пользователь удалён.';
    } else {
        $error = 'Нельзя удалить администратора.';
    }
}
// Добавление пользователя
if (isset($_POST['add_user'])) {
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $role = $_POST['role'] ?? 'user';
    if ($firstName && $lastName && $email && $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $pdo->prepare('INSERT INTO users (first_name, last_name, email, phone, password, role, created_at, last_login) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())')
            ->execute([$firstName, $lastName, $email, $phone, $hashedPassword, $role]);
        $info = 'Пользователь добавлен.';
    } else {
        $error = 'Заполните все поля.';
    }
}
// Редактирование пользователя
if (isset($_POST['edit_user'])) {
    $userId = (int)$_POST['user_id'];
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $role = $_POST['role'] ?? 'user';
    $set = 'first_name=?, last_name=?, email=?, phone=?, role=?';
    $params = [$firstName, $lastName, $email, $phone, $role, $userId];
    if (!empty($_POST['password'])) {
        $set .= ', password=?';
        $params = [$firstName, $lastName, $email, $phone, $role, password_hash($_POST['password'], PASSWORD_DEFAULT), $userId];
    }
    $pdo->prepare("UPDATE users SET $set WHERE id = ?")
        ->execute($params);
    $info = 'Пользователь обновлён.';
}

// --- CRUD для услуг (services) ---
if (isset($_GET['delete_service'])) {
    $serviceId = (int)$_GET['delete_service'];
    $pdo->prepare('DELETE FROM services WHERE id = ?')->execute([$serviceId]);
    $info = 'Услуга удалена.';
}

if (isset($_POST['add_service'])) {
    $category = trim($_POST['category']);
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $features = json_encode(array_filter(explode("\n", $_POST['features'])));
    $warranty_months = (int)$_POST['warranty_months'];
    $icon_class = trim($_POST['icon_class']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($category && $name && $description && $price && $features && $warranty_months && $icon_class) {
        $pdo->prepare('INSERT INTO services (category, name, description, price, features, warranty_months, icon_class, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)')
            ->execute([$category, $name, $description, $price, $features, $warranty_months, $icon_class, $is_active]);
        $info = 'Услуга добавлена.';
    } else {
        $error = 'Заполните все поля.';
    }
}

if (isset($_POST['edit_service'])) {
    $serviceId = (int)$_POST['service_id'];
    $category = trim($_POST['category']);
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $features = json_encode(array_filter(explode("\n", $_POST['features'])));
    $warranty_months = (int)$_POST['warranty_months'];
    $icon_class = trim($_POST['icon_class']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($category && $name && $description && $price && $features && $warranty_months && $icon_class) {
        $pdo->prepare('UPDATE services SET category = ?, name = ?, description = ?, price = ?, features = ?, warranty_months = ?, icon_class = ?, is_active = ? WHERE id = ?')
            ->execute([$category, $name, $description, $price, $features, $warranty_months, $icon_class, $is_active, $serviceId]);
        $info = 'Услуга обновлена.';
    } else {
        $error = 'Заполните все поля.';
    }
}

// --- CRUD для вакансий (vacancies) ---
if (isset($_GET['delete_vacancy'])) {
    $vacancyId = (int)$_GET['delete_vacancy'];
    $pdo->prepare('DELETE FROM vacancies WHERE id = ?')->execute([$vacancyId]);
    $info = 'Вакансия удалена.';
}

if (isset($_POST['add_vacancy'])) {
    $title = trim($_POST['title']);
    $salary = trim($_POST['salary']);
    $employment_type = trim($_POST['employment_type']);
    $experience = trim($_POST['experience']);
    $description = trim($_POST['description']);
    $requirements = trim($_POST['requirements']);
    $conditions = trim($_POST['conditions']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($title && $salary && $employment_type && $experience && $description && $requirements && $conditions) {
        $pdo->prepare('INSERT INTO vacancies (title, salary, employment_type, experience, description, requirements, conditions, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)')
            ->execute([$title, $salary, $employment_type, $experience, $description, $requirements, $conditions, $is_active]);
        $info = 'Вакансия добавлена.';
    } else {
        $error = 'Заполните все поля.';
    }
}

if (isset($_POST['edit_vacancy'])) {
    $vacancyId = (int)$_POST['vacancy_id'];
    $title = trim($_POST['title']);
    $salary = trim($_POST['salary']);
    $employment_type = trim($_POST['employment_type']);
    $experience = trim($_POST['experience']);
    $description = trim($_POST['description']);
    $requirements = trim($_POST['requirements']);
    $conditions = trim($_POST['conditions']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($title && $salary && $employment_type && $experience && $description && $requirements && $conditions) {
        $pdo->prepare('UPDATE vacancies SET title = ?, salary = ?, employment_type = ?, experience = ?, description = ?, requirements = ?, conditions = ?, is_active = ? WHERE id = ?')
            ->execute([$title, $salary, $employment_type, $experience, $description, $requirements, $conditions, $is_active, $vacancyId]);
        $info = 'Вакансия обновлена.';
    } else {
        $error = 'Заполните все поля.';
    }
}

// Получаем все данные
$users = $pdo->query('SELECT * FROM users ORDER BY id DESC')->fetchAll();
$cars = $pdo->query('SELECT * FROM user_cars ORDER BY id DESC')->fetchAll();
$orders = $pdo->query('SELECT * FROM service_orders ORDER BY id DESC')->fetchAll();

// --- CRUD для автомобилей клиентов (user_cars) ---
if (isset($_GET['delete_car'])) {
    $carId = (int)$_GET['delete_car'];
    $pdo->prepare('DELETE FROM user_cars WHERE id = ?')->execute([$carId]);
    $info = 'Автомобиль удалён.';
}
if (isset($_POST['add_car'])) {
    $userId = (int)$_POST['user_id'];
    $brand = trim($_POST['brand']);
    $model = trim($_POST['model']);
    $year = (int)$_POST['year'];
    $vin = trim($_POST['vin']);
    $license = trim($_POST['license_plate']);
    if ($userId && $brand && $model && $year && $license) {
        $pdo->prepare('INSERT INTO user_cars (user_id, brand, model, year, vin, license_plate) VALUES (?, ?, ?, ?, ?, ?)')
            ->execute([$userId, $brand, $model, $year, $vin, $license]);
        $info = 'Автомобиль добавлен.';
    } else {
        $error = 'Заполните все поля.';
    }
}
if (isset($_POST['edit_car'])) {
    $carId = (int)$_POST['car_id'];
    $userId = (int)$_POST['user_id'];
    $brand = trim($_POST['brand']);
    $model = trim($_POST['model']);
    $year = (int)$_POST['year'];
    $vin = trim($_POST['vin']);
    $license = trim($_POST['license_plate']);
    $pdo->prepare('UPDATE user_cars SET user_id=?, brand=?, model=?, year=?, vin=?, license_plate=? WHERE id=?')
        ->execute([$userId, $brand, $model, $year, $vin, $license, $carId]);
    $info = 'Автомобиль обновлён.';
}
// --- CRUD для заказов (service_orders) ---
if (isset($_GET['delete_order'])) {
    $orderId = (int)$_GET['delete_order'];
    $pdo->prepare('DELETE FROM service_orders WHERE id = ?')->execute([$orderId]);
    $info = 'Заказ удалён.';
}
if (isset($_POST['add_order'])) {
    $userId = (int)$_POST['user_id'];
    $carId = (int)$_POST['car_id'];
    $serviceType = trim($_POST['service_type']);
    $description = trim($_POST['description']);
    $status = $_POST['status'] ?? 'pending';
    $appointment = $_POST['appointment_date'];
    if ($userId && $carId && $serviceType && $appointment) {
        $pdo->prepare('INSERT INTO service_orders (user_id, car_id, service_type, description, status, appointment_date) VALUES (?, ?, ?, ?, ?, ?)')
            ->execute([$userId, $carId, $serviceType, $description, $status, $appointment]);
        $info = 'Заказ добавлен.';
    } else {
        $error = 'Заполните все поля.';
    }
}
if (isset($_POST['edit_order'])) {
    $orderId = (int)$_POST['order_id'];
    $userId = (int)$_POST['user_id'];
    $carId = (int)$_POST['car_id'];
    $serviceType = trim($_POST['service_type']);
    $description = trim($_POST['description']);
    $status = $_POST['status'] ?? 'pending';
    $appointment = $_POST['appointment_date'];
    $pdo->prepare('UPDATE service_orders SET user_id=?, car_id=?, service_type=?, description=?, status=?, appointment_date=? WHERE id=?')
        ->execute([$userId, $carId, $serviceType, $description, $status, $appointment, $orderId]);
    $info = 'Заказ обновлён.';
}

// --- CRUD для stats ---
if (isset($_GET['delete_stat'])) {
    $statId = (int)$_GET['delete_stat'];
    $pdo->prepare('DELETE FROM stats WHERE id = ?')->execute([$statId]);
    $info = 'Статистика удалена.';
}
if (isset($_POST['add_stat'])) {
    $value = trim($_POST['value']);
    $label = trim($_POST['label']);
    $description = trim($_POST['description']);
    $sort_order = (int)$_POST['sort_order'];
    if ($value && $label) {
        $pdo->prepare('INSERT INTO stats (value, label, description, sort_order) VALUES (?, ?, ?, ?)')
            ->execute([$value, $label, $description, $sort_order]);
        $info = 'Статистика добавлена.';
    } else {
        $error = 'Заполните все обязательные поля.';
    }
}
if (isset($_POST['edit_stat'])) {
    $statId = (int)$_POST['stat_id'];
    $value = trim($_POST['value']);
    $label = trim($_POST['label']);
    $description = trim($_POST['description']);
    $sort_order = (int)$_POST['sort_order'];
    if ($value && $label) {
        $pdo->prepare('UPDATE stats SET value=?, label=?, description=?, sort_order=? WHERE id=?')
            ->execute([$value, $label, $description, $sort_order, $statId]);
        $info = 'Статистика обновлена.';
    } else {
        $error = 'Заполните все обязательные поля.';
    }
}
$stats = $pdo->query('SELECT * FROM stats ORDER BY sort_order, id')->fetchAll();

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' https:; script-src 'self' https: 'unsafe-inline'; style-src 'self' https: 'unsafe-inline'; img-src 'self' https: data:;">
    <title>Админ-панель — Автосервис "Профи"</title>
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
        .nav-link:hover, .nav-link.active {
            color: var(--accent-color) !important;
            transform: translateY(-2px);
        }
        .admin-header {
            background: var(--gradient-primary);
            color: var(--text-light);
            padding: 80px 0 40px;
            text-align: center;
            position: relative;
        }
        .admin-header h1 {
            font-size: 2.7rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .admin-header p {
            font-size: 1.2rem;
            color: var(--text-light);
            opacity: 0.9;
        }
        .admin-section {
            padding: 40px 0 30px;
        }
        .admin-card {
            background: white;
            border-radius: 18px;
            box-shadow: var(--shadow-md);
            padding: 2rem 1.5rem 1.5rem 1.5rem;
            margin-bottom: 2.5rem;
        }
        .admin-card h2 {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }
        .admin-table {
            background: var(--light-bg);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }
        .admin-table th {
            background: var(--gradient-primary);
            color: var(--text-light);
            font-weight: 600;
            border: none;
        }
        .admin-table td {
            vertical-align: middle;
            border: none;
        }
        .admin-table tr {
            transition: background 0.2s;
        }
        .admin-table tr:hover {
            background: #eaf6ff;
        }
        .admin-btn {
            border: none;
            border-radius: 18px;
            padding: 0.5rem 1.2rem;
            font-weight: 600;
            font-size: 1rem;
            margin-right: 0.5rem;
            transition: background 0.2s, color 0.2s;
        }
        .admin-btn.add {
            background: var(--secondary-color);
            color: var(--text-light);
        }
        .admin-btn.add:hover {
            background: var(--accent-color);
            color: var(--text-light);
        }
        .admin-btn.edit {
            background: #f1c40f;
            color: var(--text-dark);
        }
        .admin-btn.edit:hover {
            background: #f39c12;
            color: var(--text-light);
        }
        .admin-btn.delete {
            background: var(--accent-color);
            color: var(--text-light);
        }
        .admin-btn.delete:hover {
            background: #c0392b;
            color: var(--text-light);
        }
        .admin-btn.logout {
            background: var(--dark-bg);
            color: var(--text-light);
            float: right;
        }
        .admin-btn.logout:hover {
            background: var(--accent-color);
            color: var(--text-light);
        }
        .modal-content {
            border-radius: 18px;
        }
        .modal-header {
            border-bottom: none;
        }
        .modal-title {
            font-weight: 600;
            color: var(--primary-color);
        }
        .form-label {
            font-weight: 500;
            color: var(--primary-color);
        }
        .form-control {
            border-radius: 14px;
            padding: 0.8rem 1.1rem;
            font-size: 1.05rem;
        }
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: var(--shadow-sm);
        }
        @media (max-width: 768px) {
            .admin-header {
                padding: 60px 0 30px;
            }
            .admin-card {
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
                        <a class="nav-link" href="auth.php">Личный кабинет</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link logout" href="logout.php"><i class="fas fa-sign-out-alt"></i> Выйти</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Заголовок -->
    <header class="admin-header">
        <div class="container">
            <h1><i class="fas fa-cogs"></i> Админ-панель</h1>
            <p>Управление пользователями, автомобилями и заказами автосервиса</p>
        </div>
    </header>

    <main class="container admin-section">
        <div class="admin-card">
            <h2><i class="fas fa-users"></i> Пользователи</h2>
            <?php if ($info): ?><div class="alert alert-success"><?= $info ?></div><?php endif; ?>
            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <button class="admin-btn add mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="fas fa-plus"></i> Добавить пользователя</button>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th><th>Имя</th><th>Email</th><th>Телефон</th><th>Роль</th><th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?= $u['id'] ?></td>
                            <td><?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td><?= htmlspecialchars($u['phone']) ?></td>
                            <td><?= $u['role'] ?></td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editUserModal<?= $u['id'] ?>">Редактировать</button>
                                <?php if ($u['id'] != $admin['id']): ?>
                                    <a href="?delete_user=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Удалить пользователя?')">Удалить</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <!-- Модальное окно редактирования -->
                        <div class="modal fade" id="editUserModal<?= $u['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Редактировать пользователя</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                            <div class="mb-3">
                                                <label class="form-label">Имя</label>
                                                <input type="text" class="form-control" name="first_name" value="<?= htmlspecialchars($u['first_name']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Фамилия</label>
                                                <input type="text" class="form-control" name="last_name" value="<?= htmlspecialchars($u['last_name']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($u['email']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Телефон</label>
                                                <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($u['phone']) ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Роль</label>
                                                <select class="form-select" name="role">
                                                    <option value="user"<?= $u['role']==='user'?' selected':'' ?>>Пользователь</option>
                                                    <option value="admin"<?= $u['role']==='admin'?' selected':'' ?>>Админ</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Новый пароль (не менять — оставить пустым)</label>
                                                <input type="password" class="form-control" name="password">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                            <button type="submit" name="edit_user" class="btn btn-primary">Сохранить</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Модальное окно добавления пользователя -->
        <div class="modal fade" id="addUserModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title">Добавить пользователя</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Имя</label>
                                <input type="text" class="form-control" name="first_name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Фамилия</label>
                                <input type="text" class="form-control" name="last_name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Телефон</label>
                                <input type="text" class="form-control" name="phone">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Пароль</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Роль</label>
                                <select class="form-select" name="role">
                                    <option value="user">Пользователь</option>
                                    <option value="admin">Админ</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                            <button type="submit" name="add_user" class="btn btn-success">Добавить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="admin-card">
            <h2><i class="fas fa-car"></i> Автомобили клиентов</h2>
            <button class="admin-btn add mb-3" data-bs-toggle="modal" data-bs-target="#addCarModal"><i class="fas fa-plus"></i> Добавить автомобиль</button>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th><th>Пользователь</th><th>Марка</th><th>Модель</th><th>Год</th><th>VIN</th><th>Гос. номер</th><th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($cars as $car): ?>
                        <tr>
                            <td><?= $car['id'] ?></td>
                            <td><?= $car['user_id'] ?></td>
                            <td><?= htmlspecialchars($car['brand']) ?></td>
                            <td><?= htmlspecialchars($car['model']) ?></td>
                            <td><?= $car['year'] ?></td>
                            <td><?= htmlspecialchars($car['vin']) ?></td>
                            <td><?= htmlspecialchars($car['license_plate']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editCarModal<?= $car['id'] ?>">Редактировать</button>
                                <a href="?delete_car=<?= $car['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Удалить автомобиль?')">Удалить</a>
                            </td>
                        </tr>
                        <!-- Модальное окно редактирования автомобиля -->
                        <div class="modal fade" id="editCarModal<?= $car['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Редактировать автомобиль</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                                            <div class="mb-3">
                                                <label class="form-label">ID пользователя</label>
                                                <input type="number" class="form-control" name="user_id" value="<?= $car['user_id'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Марка</label>
                                                <input type="text" class="form-control" name="brand" value="<?= htmlspecialchars($car['brand']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Модель</label>
                                                <input type="text" class="form-control" name="model" value="<?= htmlspecialchars($car['model']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Год</label>
                                                <input type="number" class="form-control" name="year" value="<?= $car['year'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">VIN</label>
                                                <input type="text" class="form-control" name="vin" value="<?= htmlspecialchars($car['vin']) ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Гос. номер</label>
                                                <input type="text" class="form-control" name="license_plate" value="<?= htmlspecialchars($car['license_plate']) ?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                            <button type="submit" name="edit_car" class="btn btn-primary">Сохранить</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Модальное окно добавления автомобиля -->
        <div class="modal fade" id="addCarModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title">Добавить автомобиль</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">ID пользователя</label>
                                <input type="number" class="form-control" name="user_id" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Марка</label>
                                <input type="text" class="form-control" name="brand" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Модель</label>
                                <input type="text" class="form-control" name="model" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Год</label>
                                <input type="number" class="form-control" name="year" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">VIN</label>
                                <input type="text" class="form-control" name="vin">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Гос. номер</label>
                                <input type="text" class="form-control" name="license_plate" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                            <button type="submit" name="add_car" class="btn btn-success">Добавить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="admin-card">
            <h2><i class="fas fa-receipt"></i> Заявка на ТО</h2>
            <button class="admin-btn add mb-3" data-bs-toggle="modal" data-bs-target="#addOrderModal"><i class="fas fa-plus"></i> Добавить заказ</button>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th><th>Пользователь</th><th>Авто</th><th>Услуга</th><th>Описание</th><th>Статус</th><th>Дата</th><th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= $order['id'] ?></td>
                            <td><?= $order['user_id'] ?></td>
                            <td><?= $order['car_id'] ?></td>
                            <td><?= htmlspecialchars($order['service_type']) ?></td>
                            <td><?= htmlspecialchars($order['description']) ?></td>
                            <td><?= $order['status'] ?></td>
                            <td><?= $order['appointment_date'] ?></td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editOrderModal<?= $order['id'] ?>">Редактировать</button>
                                <a href="?delete_order=<?= $order['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Удалить заказ?')">Удалить</a>
                            </td>
                        </tr>
                        <!-- Модальное окно редактирования заказа -->
                        <div class="modal fade" id="editOrderModal<?= $order['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Редактировать заказ</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                            <div class="mb-3">
                                                <label class="form-label">ID пользователя</label>
                                                <input type="number" class="form-control" name="user_id" value="<?= $order['user_id'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">ID авто</label>
                                                <input type="number" class="form-control" name="car_id" value="<?= $order['car_id'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Тип услуги</label>
                                                <input type="text" class="form-control" name="service_type" value="<?= htmlspecialchars($order['service_type']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Описание</label>
                                                <textarea class="form-control" name="description"><?= htmlspecialchars($order['description']) ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Статус</label>
                                                <select class="form-select" name="status">
                                                    <option value="pending"<?= $order['status']==='pending'?' selected':'' ?>>В ожидании</option>
                                                    <option value="confirmed"<?= $order['status']==='confirmed'?' selected':'' ?>>Подтвержден</option>
                                                    <option value="in_progress"<?= $order['status']==='in_progress'?' selected':'' ?>>В работе</option>
                                                    <option value="completed"<?= $order['status']==='completed'?' selected':'' ?>>Завершён</option>
                                                    <option value="cancelled"<?= $order['status']==='cancelled'?' selected':'' ?>>Отменён</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Дата и время</label>
                                                <input type="datetime-local" class="form-control" name="appointment_date" value="<?= date('Y-m-d\TH:i', strtotime($order['appointment_date'])) ?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                            <button type="submit" name="edit_order" class="btn btn-primary">Сохранить</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Модальное окно добавления заказа -->
        <div class="modal fade" id="addOrderModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title">Добавить заказ</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">ID пользователя</label>
                                <input type="number" class="form-control" name="user_id" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">ID авто</label>
                                <input type="number" class="form-control" name="car_id" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Тип услуги</label>
                                <input type="text" class="form-control" name="service_type" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Описание</label>
                                <textarea class="form-control" name="description"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Статус</label>
                                <select class="form-select" name="status">
                                    <option value="pending">В ожидании</option>
                                    <option value="confirmed">Подтвержден</option>
                                    <option value="in_progress">В работе</option>
                                    <option value="completed">Завершён</option>
                                    <option value="cancelled">Отменён</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Дата и время</label>
                                <input type="datetime-local" class="form-control" name="appointment_date" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                            <button type="submit" name="add_order" class="btn btn-success">Добавить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Раздел управления услугами -->
        <div class="admin-card">
            <h2><i class="fas fa-wrench"></i> Управление услугами</h2>
            <button class="admin-btn add mb-3" data-bs-toggle="modal" data-bs-target="#addServiceModal"><i class="fas fa-plus"></i> Добавить услугу</button>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Категория</th>
                            <th>Название</th>
                            <th>Цена</th>
                            <th>Гарантия</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $services = $pdo->query("SELECT * FROM services ORDER BY category, name")->fetchAll();
                    $categories = [
                        'maintenance' => 'Техническое обслуживание',
                        'diagnostics' => 'Диагностика',
                        'repair' => 'Ремонт'
                    ];
                    foreach ($services as $service):
                    ?>
                        <tr>
                            <td><?= $service['id'] ?></td>
                            <td><?= $categories[$service['category']] ?></td>
                            <td><?= htmlspecialchars($service['name']) ?></td>
                            <td><?= number_format($service['price'], 0, '.', ' '); ?> ₽</td>
                            <td><?= $service['warranty_months'] ?> мес.</td>
                            <td>
                                <?php if ($service['is_active']): ?>
                                    <span class="badge bg-success">Активна</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Неактивна</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editServiceModal<?= $service['id'] ?>">Редактировать</button>
                                <a href="?delete_service=<?= $service['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Удалить услугу?')">Удалить</a>
                            </td>
                        </tr>
                        <!-- Модальное окно редактирования услуги -->
                        <div class="modal fade" id="editServiceModal<?= $service['id'] ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Редактировать услугу</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
                                            <div class="mb-3">
                                                <label class="form-label">Категория</label>
                                                <select name="category" class="form-select" required>
                                                    <?php foreach ($categories as $key => $value): ?>
                                                    <option value="<?= $key ?>"<?= $service['category']==$key?' selected':'' ?>><?= $value ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Название</label>
                                                <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($service['name']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Описание</label>
                                                <textarea class="form-control" name="description" rows="3" required><?= htmlspecialchars($service['description']) ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Цена (₽)</label>
                                                <input type="number" class="form-control" name="price" value="<?= $service['price'] ?>" required min="0">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Особенности (по одной на строку)</label>
                                                <textarea class="form-control" name="features" rows="4" required><?= implode("\n", json_decode($service['features'], true)) ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Срок гарантии (месяцев)</label>
                                                <input type="number" class="form-control" name="warranty_months" value="<?= $service['warranty_months'] ?>" required min="0">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Класс иконки (Font Awesome)</label>
                                                <input type="text" class="form-control" name="icon_class" value="<?= htmlspecialchars($service['icon_class']) ?>" required>
                                                <small class="text-muted">Например: fas fa-oil-can</small>
                                            </div>
                                            <div class="mb-3">
                                                <div class="form-check">
                                                    <input type="checkbox" name="is_active" class="form-check-input" id="isActive<?= $service['id'] ?>"<?= $service['is_active']?' checked':'' ?>>
                                                    <label class="form-check-label" for="isActive<?= $service['id'] ?>">Активна</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                            <button type="submit" name="edit_service" class="btn btn-primary">Сохранить</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Модальное окно добавления услуги -->
        <div class="modal fade" id="addServiceModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title">Добавить услугу</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Категория</label>
                                <select name="category" class="form-select" required>
                                    <?php foreach ($categories as $key => $value): ?>
                                    <option value="<?= $key ?>"><?= $value ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Название</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Описание</label>
                                <textarea class="form-control" name="description" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Цена (₽)</label>
                                <input type="number" class="form-control" name="price" required min="0">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Особенности (по одной на строку)</label>
                                <textarea class="form-control" name="features" rows="4" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Срок гарантии (месяцев)</label>
                                <input type="number" class="form-control" name="warranty_months" required min="0">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Класс иконки (Font Awesome)</label>
                                <input type="text" class="form-control" name="icon_class" required>
                                <small class="text-muted">Например: fas fa-oil-can</small>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" class="form-check-input" id="isActive" checked>
                                    <label class="form-check-label" for="isActive">Активна</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                            <button type="submit" name="add_service" class="btn btn-success">Добавить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Карточка управления вакансиями -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Управление вакансиями</h5>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addVacancyModal">
                        Добавить вакансию
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Должность</th>
                                    <th>Зарплата</th>
                                    <th>Статус</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $stmt = $pdo->query("SELECT * FROM vacancies ORDER BY id");
                                while ($vacancy = $stmt->fetch()) {
                                    echo '<tr>';
                                    echo '<td>' . $vacancy['id'] . '</td>';
                                    echo '<td>' . htmlspecialchars($vacancy['title']) . '</td>';
                                    echo '<td>' . htmlspecialchars($vacancy['salary']) . '</td>';
                                    echo '<td>' . ($vacancy['is_active'] ? 'Активна' : 'Неактивна') . '</td>';
                                    echo '<td>';
                                    echo '<button type="button" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editVacancyModal' . $vacancy['id'] . '">Редактировать</button>';
                                    echo '<a href="?delete_vacancy=' . $vacancy['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Удалить вакансию?\')">Удалить</a>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Модальное окно добавления вакансии -->
        <div class="modal fade" id="addVacancyModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Добавить вакансию</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="post">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Должность</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Зарплата</label>
                                <input type="text" class="form-control" name="salary" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Тип занятости</label>
                                <input type="text" class="form-control" name="employment_type" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Опыт работы</label>
                                <input type="text" class="form-control" name="experience" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Описание</label>
                                <textarea class="form-control" name="description" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Требования</label>
                                <textarea class="form-control" name="requirements" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Условия</label>
                                <textarea class="form-control" name="conditions" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="is_active" id="is_active" checked>
                                    <label class="form-check-label" for="is_active">Активна</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                            <button type="submit" name="add_vacancy" class="btn btn-primary">Добавить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Модальные окна редактирования вакансий -->
        <?php
        $stmt = $pdo->query("SELECT * FROM vacancies ORDER BY id");
        while ($vacancy = $stmt->fetch()) {
            ?>
            <div class="modal fade" id="editVacancyModal<?php echo $vacancy['id']; ?>" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Редактировать вакансию</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="post">
                            <input type="hidden" name="vacancy_id" value="<?php echo $vacancy['id']; ?>">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Должность</label>
                                    <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($vacancy['title']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Зарплата</label>
                                    <input type="text" class="form-control" name="salary" value="<?php echo htmlspecialchars($vacancy['salary']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Тип занятости</label>
                                    <input type="text" class="form-control" name="employment_type" value="<?php echo htmlspecialchars($vacancy['employment_type']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Опыт работы</label>
                                    <input type="text" class="form-control" name="experience" value="<?php echo htmlspecialchars($vacancy['experience']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Описание</label>
                                    <textarea class="form-control" name="description" rows="3" required><?php echo htmlspecialchars($vacancy['description']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Требования</label>
                                    <textarea class="form-control" name="requirements" rows="3" required><?php echo htmlspecialchars($vacancy['requirements']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Условия</label>
                                    <textarea class="form-control" name="conditions" rows="3" required><?php echo htmlspecialchars($vacancy['conditions']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="is_active" id="is_active<?php echo $vacancy['id']; ?>" <?php echo $vacancy['is_active'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="is_active<?php echo $vacancy['id']; ?>">Активна</label>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                <button type="submit" name="edit_vacancy" class="btn btn-primary">Сохранить</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php
        }
    ?>

    <!-- Раздел управления статистикой -->
    <div class="admin-card">
        <h2><i class="fas fa-chart-bar"></i> Статистика для главной страницы</h2>
        <button class="admin-btn add mb-3" data-bs-toggle="modal" data-bs-target="#addStatModal"><i class="fas fa-plus"></i> Добавить показатель</button>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Значение</th>
                        <th>Подпись</th>
                        <th>Описание</th>
                        <th>Порядок</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($stats as $stat): ?>
                    <tr>
                        <td><?= $stat['id'] ?></td>
                        <td><?= htmlspecialchars($stat['value']) ?></td>
                        <td><?= htmlspecialchars($stat['label']) ?></td>
                        <td><?= htmlspecialchars($stat['description']) ?></td>
                        <td><?= $stat['sort_order'] ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editStatModal<?= $stat['id'] ?>">Редактировать</button>
                            <a href="?delete_stat=<?= $stat['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Удалить показатель?')">Удалить</a>
                        </td>
                    </tr>
                    <!-- Модальное окно редактирования -->
                    <div class="modal fade" id="editStatModal<?= $stat['id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Редактировать показатель</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="stat_id" value="<?= $stat['id'] ?>">
                                        <div class="mb-3">
                                            <label class="form-label">Значение</label>
                                            <input type="text" class="form-control" name="value" value="<?= htmlspecialchars($stat['value']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Подпись</label>
                                            <input type="text" class="form-control" name="label" value="<?= htmlspecialchars($stat['label']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Описание</label>
                                            <input type="text" class="form-control" name="description" value="<?= htmlspecialchars($stat['description']) ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Порядок</label>
                                            <input type="number" class="form-control" name="sort_order" value="<?= $stat['sort_order'] ?>">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                        <button type="submit" name="edit_stat" class="btn btn-primary">Сохранить</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Модальное окно добавления показателя -->
    <div class="modal fade" id="addStatModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Добавить показатель</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Значение</label>
                            <input type="text" class="form-control" name="value" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Подпись</label>
                            <input type="text" class="form-control" name="label" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Описание</label>
                            <input type="text" class="form-control" name="description">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Порядок</label>
                            <input type="number" class="form-control" name="sort_order" value="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" name="add_stat" class="btn btn-success">Добавить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </main>

    <footer class="bg-dark text-light py-4 mt-4">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 