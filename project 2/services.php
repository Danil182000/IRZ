<?php
require_once 'config/database.php';

// Получение услуг из базы данных
function getServicesByCategory($category) {
    global $pdo;
    
    // Маппинг русских названий на английские
    $categoryMap = [
        'ТО' => 'maintenance',
        'Техническое обслуживание' => 'maintenance',
        'Диагностика' => 'diagnostics',
        'Ремонт' => 'repair'
    ];
    
    // Если передан русский вариант, конвертируем в английский
    $dbCategory = isset($categoryMap[$category]) ? $categoryMap[$category] : $category;
    
    $stmt = $pdo->prepare("SELECT * FROM services WHERE category = ? AND is_active = TRUE ORDER BY id");
    $stmt->execute([$dbCategory]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Получение всех категорий услуг
$categories = [
    'maintenance' => 'Техническое обслуживание',
    'diagnostics' => 'Диагностика',
    'repair' => 'Ремонт'
];
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
    <title>Услуги автосервиса "Профи" - Профессиональный ремонт автомобилей</title>
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
            line-height: 1.6;
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

        .page-header {
            background: var(--gradient-primary);
            color: var(--text-light);
            padding: 120px 0 60px;
            position: relative;
            overflow: hidden;
        }

        .page-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(to top, var(--light-bg), transparent);
        }

        .service-category {
            padding: 80px 0;
            background: white;
        }

        .service-category:nth-child(even) {
            background: var(--light-bg);
        }

        .service-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
            border: none;
            height: 100%;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }

        .service-icon {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            color: var(--secondary-color);
            background: var(--light-bg);
            width: 80px;
            height: 80px;
            line-height: 80px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .service-card:hover .service-icon {
            background: var(--gradient-primary);
            color: var(--text-light);
        }

        .price-tag {
            background: var(--gradient-accent);
            color: var(--text-light);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
            margin-top: 1rem;
        }

        .features-list {
            list-style: none;
            padding: 0;
            margin: 1rem 0;
        }

        .features-list li {
            margin-bottom: 0.5rem;
            padding-left: 1.5rem;
            position: relative;
        }

        .features-list li::before {
            content: '✓';
            color: var(--secondary-color);
            position: absolute;
            left: 0;
            font-weight: bold;
        }

        .warranty-badge {
            background: rgba(52, 152, 219, 0.1);
            color: var(--secondary-color);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            display: inline-block;
            margin-top: 1rem;
        }

        .cta-section {
            background: var(--gradient-primary);
            color: var(--text-light);
            padding: 80px 0;
            text-align: center;
        }

        .cta-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
            margin-top: 2rem;
        }

        footer {
            background: var(--dark-bg);
            color: var(--text-light);
            padding: 3rem 0;
        }

        .process-steps {
            position: relative;
            padding: 40px 0;
        }

        .process-step {
            text-align: center;
            padding: 2rem;
            position: relative;
            z-index: 1;
        }

        .step-number {
            width: 40px;
            height: 40px;
            background: var(--gradient-primary);
            color: var(--text-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-weight: bold;
        }

        .process-line {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--light-bg);
            z-index: 0;
        }

        @media (max-width: 768px) {
            .process-line {
                display: none;
            }
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
                        <a class="nav-link active" href="services.php">Услуги</a>
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
                </ul>
            </div>
        </div>
    </nav>

    <!-- Заголовок страницы -->
    <header class="page-header text-center">
        <div class="container">
            <h1 class="display-4 mb-4">Наши услуги</h1>
            <p class="lead mb-0">Профессиональный ремонт и обслуживание автомобилей всех марок</p>
        </div>
    </header>

    <!-- Процесс работы -->
    <section class="process-steps bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Как мы работаем</h2>
            <div class="process-line"></div>
            <div class="row">
                <div class="col-md-3">
                    <div class="process-step">
                        <div class="step-number">1</div>
                        <h5>Диагностика</h5>
                        <p>Бесплатная первичная диагностика и оценка стоимости работ</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="process-step">
                        <div class="step-number">2</div>
                        <h5>Согласование</h5>
                        <p>Обсуждение необходимых работ и утверждение стоимости</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="process-step">
                        <div class="step-number">3</div>
                        <h5>Ремонт</h5>
                        <p>Профессиональное выполнение всех необходимых работ</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="process-step">
                        <div class="step-number">4</div>
                        <h5>Контроль</h5>
                        <p>Проверка качества работ и тест-драйв автомобиля</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Техническое обслуживание -->
    <section class="service-category">
        <div class="container">
            <h2 class="text-center mb-5">Техническое обслуживание</h2>
            <div class="row">
                <?php
                $services = getServicesByCategory('maintenance');
                foreach ($services as $service):
                    $features = json_decode($service['features'], true);
                ?>
                <div class="col-md-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="<?php echo htmlspecialchars($service['icon_class']); ?>"></i>
                        </div>
                        <h4><?php echo htmlspecialchars($service['name']); ?></h4>
                        <p><?php echo htmlspecialchars($service['description']); ?></p>
                        <ul class="features-list">
                            <?php foreach ($features as $feature): ?>
                            <li><?php echo htmlspecialchars($feature); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="price-tag">от <?php echo number_format($service['price'], 0, '.', ' '); ?> ₽</div>
                        <div class="warranty-badge">
                            <i class="fas fa-shield-alt me-2"></i>Гарантия <?php echo $service['warranty_months']; ?> месяцев
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Диагностика -->
    <section class="service-category">
        <div class="container">
            <h2 class="text-center mb-5">Диагностика</h2>
            <div class="row">
                <?php
                $services = getServicesByCategory('diagnostics');
                foreach ($services as $service):
                    $features = json_decode($service['features'], true);
                ?>
                <div class="col-md-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="<?php echo htmlspecialchars($service['icon_class']); ?>"></i>
                        </div>
                        <h4><?php echo htmlspecialchars($service['name']); ?></h4>
                        <p><?php echo htmlspecialchars($service['description']); ?></p>
                        <ul class="features-list">
                            <?php foreach ($features as $feature): ?>
                            <li><?php echo htmlspecialchars($feature); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="price-tag">от <?php echo number_format($service['price'], 0, '.', ' '); ?> ₽</div>
                        <div class="warranty-badge">
                            <i class="fas fa-shield-alt me-2"></i>Гарантия <?php echo $service['warranty_months']; ?> месяцев
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Ремонт -->
    <section class="service-category">
        <div class="container">
            <h2 class="text-center mb-5">Ремонт</h2>
            <div class="row">
                <?php
                $services = getServicesByCategory('repair');
                foreach ($services as $service):
                    $features = json_decode($service['features'], true);
                ?>
                <div class="col-md-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="<?php echo htmlspecialchars($service['icon_class']); ?>"></i>
                        </div>
                        <h4><?php echo htmlspecialchars($service['name']); ?></h4>
                        <p><?php echo htmlspecialchars($service['description']); ?></p>
                        <ul class="features-list">
                            <?php foreach ($features as $feature): ?>
                            <li><?php echo htmlspecialchars($feature); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="price-tag">от <?php echo number_format($service['price'], 0, '.', ' '); ?> ₽</div>
                        <div class="warranty-badge">
                            <i class="fas fa-shield-alt me-2"></i>Гарантия <?php echo $service['warranty_months']; ?> месяцев
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Призыв к действию -->
    <section class="cta-section">
        <div class="container">
            <h2 class="mb-4">Готовы записаться на обслуживание?</h2>
            <p class="lead mb-4">Оставьте заявку, и мы свяжемся с вами для уточнения деталей</p>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="cta-card">
                        <a href="auth.php" class="btn btn-light btn-lg w-100">Записаться на ремонт</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

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