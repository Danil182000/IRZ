<?php
session_start();
require_once 'config/database.php';

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit;
}

// Получение данных пользователя
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Получение автомобилей пользователя
$stmt = $pdo->prepare("SELECT * FROM user_cars WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$cars = $stmt->fetchAll();

// Получение заказов пользователя
$stmt = $pdo->prepare("
    SELECT so.*, uc.brand, uc.model, uc.license_plate 
    FROM service_orders so 
    JOIN user_cars uc ON so.car_id = uc.id 
    WHERE so.user_id = ? 
    ORDER BY so.appointment_date DESC
");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();

// Обработка добавления нового автомобиля
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_car') {
    $brand = filter_input(INPUT_POST, 'brand', FILTER_SANITIZE_STRING);
    $model = filter_input(INPUT_POST, 'model', FILTER_SANITIZE_STRING);
    $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
    $vin = filter_input(INPUT_POST, 'vin', FILTER_SANITIZE_STRING);
    $licensePlate = filter_input(INPUT_POST, 'license_plate', FILTER_SANITIZE_STRING);

    $stmt = $pdo->prepare("INSERT INTO user_cars (user_id, brand, model, year, vin, license_plate) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$_SESSION['user_id'], $brand, $model, $year, $vin, $licensePlate])) {
        header('Location: profile.php?success=car_added');
        exit;
    }
}

// Обработка создания нового заказа
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_order') {
    $carId = filter_input(INPUT_POST, 'car_id', FILTER_VALIDATE_INT);
    $serviceType = filter_input(INPUT_POST, 'service_type', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $appointmentDate = $_POST['appointment_date'];

    $stmt = $pdo->prepare("INSERT INTO service_orders (user_id, car_id, service_type, description, appointment_date) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$_SESSION['user_id'], $carId, $serviceType, $description, $appointmentDate])) {
        header('Location: profile.php?success=order_created');
        exit;
    }
}

// Обработка редактирования профиля
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_profile') {
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $params = [$firstName, $lastName, $email, $phone, $_SESSION['user_id']];
    $set = 'first_name=?, last_name=?, email=?, phone=?';
    if (!empty($_POST['password'])) {
        $set .= ', password=?';
        $params = [$firstName, $lastName, $email, $phone, password_hash($_POST['password'], PASSWORD_DEFAULT), $_SESSION['user_id']];
    }
    $pdo->prepare("UPDATE users SET $set WHERE id = ?")->execute($params);
    // Обновляем данные в сессии
    $_SESSION['user_name'] = $firstName . ' ' . $lastName;
    $_SESSION['user_email'] = $email;
    header('Location: profile.php?success=profile_updated');
    exit;
}
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
    <title>Личный кабинет — Автосервис "Профи"</title>
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
        .profile-header {
            background: var(--gradient-primary);
            color: var(--text-light);
            padding: 70px 0 40px;
            text-align: center;
        }
        .profile-header h1 {
            font-size: 2.3rem;
            font-weight: 700;
        }
        .profile-section {
            padding: 40px 0 30px;
        }
        .profile-card {
            background: white;
            border-radius: 18px;
            box-shadow: var(--shadow-md);
            padding: 2rem 1.5rem 1.5rem 1.5rem;
            margin-bottom: 2.5rem;
        }
        .profile-card h2 {
            font-size: 1.4rem;
            color: var(--primary-color);
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }
        .profile-btn {
            border: none;
            border-radius: 18px;
            padding: 0.5rem 1.2rem;
            font-weight: 600;
            font-size: 1rem;
            margin-right: 0.5rem;
            transition: background 0.2s, color 0.2s;
        }
        .profile-btn.add {
            background: var(--secondary-color);
            color: var(--text-light);
        }
        .profile-btn.add:hover {
            background: var(--accent-color);
            color: var(--text-light);
        }
        .profile-btn.edit {
            background: #f1c40f;
            color: var(--text-dark);
        }
        .profile-btn.edit:hover {
            background: #f39c12;
            color: var(--text-light);
        }
        .profile-btn.delete {
            background: var(--accent-color);
            color: var(--text-light);
        }
        .profile-btn.delete:hover {
            background: #c0392b;
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
        .profile-table {
            background: var(--light-bg);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }
        .profile-table th {
            background: var(--gradient-primary);
            color: var(--text-light);
            font-weight: 600;
            border: none;
        }
        .profile-table td {
            vertical-align: middle;
            border: none;
        }
        .profile-table tr {
            transition: background 0.2s;
        }
        .profile-table tr:hover {
            background: #eaf6ff;
        }
        .status-badge {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }
        @media (max-width: 768px) {
            .profile-header {
                padding: 40px 0 20px;
            }
            .profile-card {
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
                        <a class="nav-link active" href="profile.php">Личный кабинет</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Выйти</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Заголовок -->
    <header class="profile-header">
        <div class="container">
            <h1><i class="fas fa-user-circle"></i> Личный кабинет</h1>
        </div>
    </header>

    <main class="container profile-section">
        <div class="row">
            <div class="col-md-4">
                <div class="profile-card mb-4">
                    <h2><i class="fas fa-user"></i> Профиль
                        <button class="profile-btn edit ms-2" data-bs-toggle="modal" data-bs-target="#editProfileModal"><i class="fas fa-edit"></i> Редактировать</button>
                    </h2>
                    <div class="mb-3">
                        <strong>Имя:</strong> <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?>
                    </div>
                    <div class="mb-3">
                        <strong>Телефон:</strong> <?php echo htmlspecialchars($user['phone']); ?>
                    </div>
                    <div class="mb-3">
                        <strong>Дата регистрации:</strong> <?php echo date('d.m.Y', strtotime($user['created_at'])); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="profile-card mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2><i class="fas fa-car"></i> Мои автомобили</h2>
                        <button class="profile-btn add" data-bs-toggle="modal" data-bs-target="#addCarModal">
                            <i class="fas fa-plus"></i> Добавить авто
                        </button>
                    </div>
                    <?php if (empty($cars)): ?>
                        <p class="text-muted">У вас пока нет добавленных автомобилей</p>
                    <?php else: ?>
                        <div class="profile-table table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Марка</th>
                                        <th>Модель</th>
                                        <th>Год</th>
                                        <th>VIN</th>
                                        <th>Гос. номер</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($cars as $car): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($car['brand']); ?></td>
                                        <td><?php echo htmlspecialchars($car['model']); ?></td>
                                        <td><?php echo htmlspecialchars($car['year']); ?></td>
                                        <td><?php echo htmlspecialchars($car['vin']); ?></td>
                                        <td><?php echo htmlspecialchars($car['license_plate']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="profile-card mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2><i class="fas fa-receipt"></i> Мои заявки</h2>
                        <button class="profile-btn add" data-bs-toggle="modal" data-bs-target="#createOrderModal">
                            <i class="fas fa-plus"></i> Создать заявку
                        </button>
                    </div>
                    <?php if (empty($orders)): ?>
                        <p class="text-muted">У вас пока нет заказов</p>
                    <?php else: ?>
                        <div class="profile-table table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Автомобиль</th>
                                        <th>Услуга</th>
                                        <th>Дата</th>
                                        <th>Описание</th>
                                        <th>Статус</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($order['brand'] . ' ' . $order['model'] . ' (' . $order['license_plate'] . ')'); ?></td>
                                        <td><?php echo htmlspecialchars($order['service_type']); ?></td>
                                        <td><?php echo date('d.m.Y H:i', strtotime($order['appointment_date'])); ?></td>
                                        <td><?php echo htmlspecialchars($order['description']); ?></td>
                                        <td><span class="badge bg-<?php 
                                            echo match($order['status']) {
                                                'pending' => 'warning',
                                                'confirmed' => 'info',
                                                'in_progress' => 'primary',
                                                'completed' => 'success',
                                                'cancelled' => 'danger',
                                                default => 'secondary'
                                            };
                                        ?> status-badge">
                                            <?php echo match($order['status']) {
                                                'pending' => 'Ожидает подтверждения',
                                                'confirmed' => 'Подтвержден',
                                                'in_progress' => 'В работе',
                                                'completed' => 'Завершен',
                                                'cancelled' => 'Отменен',
                                                default => 'Неизвестно'
                                            }; ?>
                                        </span></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Модальное окно добавления автомобиля -->
    <div class="modal fade" id="addCarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="">
                    <input type="hidden" name="action" value="add_car">
                    <div class="modal-header">
                        <h5 class="modal-title">Добавить автомобиль</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Марка</label>
                            <input type="text" class="form-control" name="brand" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Модель</label>
                            <input type="text" class="form-control" name="model" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Год выпуска</label>
                            <input type="number" class="form-control" name="year" min="1900" max="<?php echo date('Y'); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">VIN-код</label>
                            <input type="text" class="form-control" name="vin" maxlength="17">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Гос. номер</label>
                            <input type="text" class="form-control" name="license_plate" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Добавить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Модальное окно создания заказа -->
    <div class="modal fade" id="createOrderModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="">
                    <input type="hidden" name="action" value="create_order">
                    <div class="modal-header">
                        <h5 class="modal-title">Создать заказ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Выберите автомобиль</label>
                            <select class="form-select" name="car_id" required>
                                <?php foreach ($cars as $car): ?>
                                    <option value="<?php echo $car['id']; ?>">
                                        <?php echo htmlspecialchars($car['brand'] . ' ' . $car['model'] . ' (' . $car['license_plate'] . ')'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Тип услуги</label>
                            <select class="form-select" name="service_type" required>
                                <option value="diagnostic">Диагностика</option>
                                <option value="maintenance">Техобслуживание</option>
                                <option value="repair">Ремонт</option>
                                <option value="tire_service">Шиномонтаж</option>
                                <option value="other">Другое</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Дата и время</label>
                            <input type="datetime-local" class="form-control" name="appointment_date" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Описание проблемы</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Создать заказ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Модальное окно редактирования профиля -->
    <div class="modal fade" id="editProfileModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <input type="hidden" name="action" value="edit_profile">
                    <div class="modal-header">
                        <h5 class="modal-title">Редактировать профиль</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Имя</label>
                            <input type="text" class="form-control" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Фамилия</label>
                            <input type="text" class="form-control" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Телефон</label>
                            <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Новый пароль (не менять — оставить пустым)</label>
                            <input type="password" class="form-control" name="password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>
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
</body>
</html> 