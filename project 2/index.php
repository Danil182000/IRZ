<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' https:; script-src 'self' https: 'unsafe-inline'; style-src 'self' https: 'unsafe-inline'; img-src 'self' https: data:;">
    <title>Автосервис "Профи" - Профессиональный ремонт автомобилей</title>
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

        .hero-section {
            background: linear-gradient(rgba(44, 62, 80, 0.9), rgba(52, 152, 219, 0.9)), 
                        url('https://images.unsplash.com/photo-1581093458791-9d15482442f6?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: var(--text-light);
            padding: 200px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(to top, var(--light-bg), transparent);
        }

        .service-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
            border: none;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }

        .icon-box {
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

        .service-card:hover .icon-box {
            background: var(--gradient-primary);
            color: var(--text-light);
        }

        .about-section {
            background: var(--light-bg);
            padding: 100px 0;
            position: relative;
        }

        .about-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(to bottom, white, var(--light-bg));
        }

        .contact-info {
            background: var(--gradient-primary);
            color: var(--text-light);
            padding: 100px 0;
            position: relative;
        }

        .contact-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .contact-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.2);
        }

        .contact-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--accent-color);
        }

        footer {
            background: var(--dark-bg);
            color: var(--text-light);
            padding: 3rem 0;
        }

        .stats-section {
            background: var(--gradient-accent);
            color: var(--text-light);
            padding: 80px 0;
            text-align: center;
        }

        .stat-card {
            padding: 2rem;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            margin-bottom: 2rem;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .features-section {
            padding: 100px 0;
            background: white;
        }

        .feature-card {
            text-align: center;
            padding: 2rem;
            border-radius: 15px;
            background: var(--light-bg);
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
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
                        <a class="nav-link active" href="index.php">Главная</a>
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
                </ul>
            </div>
        </div>
    </nav>

    <!-- Главный баннер -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-3 mb-4 fw-bold">Быстрый ремонт автомобилей</h1>
            <p class="lead mb-4 fs-4">Мы предоставляем полный спектр услуг по обслуживанию и ремонту автомобилей всех марок. Наша команда профессионалов гарантирует качество и надежность.</p>
        </div>
    </section>

    <?php require_once __DIR__ . '/config/database.php';
    $stats = $pdo->query('SELECT * FROM stats ORDER BY sort_order, id')->fetchAll(); ?>

    <!-- Статистика -->
    <section class="stats-section">
        <div class="container">
            <h2 class="text-center mb-5">Наши достижения</h2>
            <div class="row">
                <?php foreach ($stats as $stat): ?>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-number"><?= htmlspecialchars($stat['value']) ?></div>
                        <p><?= htmlspecialchars($stat['label']) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Услуги -->
    <section class="features-section">
        <div class="container">
            <h2 class="text-center mb-5">Наши услуги</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="service-card">
                        <div class="icon-box">
                            <i class="fas fa-tools"></i>
                        </div>
                        <h4>Техническое обслуживание</h4>
                        <p>Профессиональное ТО вашего автомобиля с использованием современного оборудования и качественных материалов. Мы проводим полную диагностику и замену всех необходимых расходных материалов.</p>
                        <ul class="list-unstyled mt-3">
                            <li><i class="fas fa-check text-success me-2"></i> Замена масла и фильтров</li>
                            <li><i class="fas fa-check text-success me-2"></i> Проверка тормозной системы</li>
                            <li><i class="fas fa-check text-success me-2"></i> Диагностика подвески</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card">
                        <div class="icon-box">
                            <i class="fas fa-car-battery"></i>
                        </div>
                        <h4>Диагностика</h4>
                        <p>Комплексная компьютерная диагностика всех систем вашего автомобиля. Мы используем современное оборудование для точного определения неисправностей и их устранения.</p>
                        <ul class="list-unstyled mt-3">
                            <li><i class="fas fa-check text-success me-2"></i> Компьютерная диагностика</li>
                            <li><i class="fas fa-check text-success me-2"></i> Проверка электроники</li>
                            <li><i class="fas fa-check text-success me-2"></i> Анализ работы двигателя</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card">
                        <div class="icon-box">
                            <i class="fas fa-wrench"></i>
                        </div>
                        <h4>Ремонт двигателя</h4>
                        <p>Капитальный и текущий ремонт двигателя любой сложности. Наши специалисты имеют многолетний опыт работы с двигателями различных марок и моделей.</p>
                        <ul class="list-unstyled mt-3">
                            <li><i class="fas fa-check text-success me-2"></i> Капитальный ремонт</li>
                            <li><i class="fas fa-check text-success me-2"></i> Замена комплектующих</li>
                            <li><i class="fas fa-check text-success me-2"></i> Настройка и регулировка</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- О нас -->
    <section class="about-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 class="mb-4">О нашем автосервисе</h2>
                    <p class="lead mb-4">Мы - команда профессионалов, работающая на рынке автосервисных услуг более 10 лет. Наша миссия - обеспечить качественное обслуживание и ремонт вашего автомобиля.</p>
                    <p class="mb-4">За годы работы мы заслужили доверие тысяч клиентов благодаря:</p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="feature-card">
                                <i class="fas fa-certificate text-primary mb-3" style="font-size: 2rem;"></i>
                                <h5>Опытные мастера</h5>
                                <p>Все наши специалисты имеют сертификаты и регулярно проходят обучение</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-card">
                                <i class="fas fa-tools text-primary mb-3" style="font-size: 2rem;"></i>
                                <h5>Современное оборудование</h5>
                                <p>Используем новейшие технологии и инструменты для диагностики и ремонта</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-card">
                                <i class="fas fa-shield-alt text-primary mb-3" style="font-size: 2rem;"></i>
                                <h5>Гарантия качества</h5>
                                <p>Предоставляем гарантию на все виды работ и используемые материалы</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-card">
                                <i class="fas fa-wallet text-primary mb-3" style="font-size: 2rem;"></i>
                                <h5>Доступные цены</h5>
                                <p>Предлагаем оптимальное соотношение цены и качества услуг</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <img src="img/machine.jpg" class="img-fluid rounded shadow-lg" alt="Автосервис">
                </div>
            </div>
        </div>
    </section>

    <!-- Контакты -->
    <section class="contact-info">
        <div class="container">
            <h2 class="text-center mb-5">Контакты</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="contact-card text-center">
                        <i class="fas fa-map-marker-alt contact-icon"></i>
                        <h5>Адрес</h5>
                        <p>ул. Автосервисная, 123<br>г. Москва</p>
                        <p class="small">Мы находимся в удобном месте с хорошей транспортной доступностью</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-card text-center">
                        <i class="fas fa-phone contact-icon"></i>
                        <h5>Телефон</h5>
                        <p>+7 (999) 123-45-67<br>+7 (999) 765-43-21</p>
                        <p class="small">Звоните нам в любое время, мы всегда на связи</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-card text-center">
                        <i class="fas fa-clock contact-icon"></i>
                        <h5>Режим работы</h5>
                        <p>Пн-Пт: 9:00 - 20:00<br>Сб-Вс: 10:00 - 18:00</p>
                        <p class="small">Работаем без выходных для вашего удобства</p>
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
