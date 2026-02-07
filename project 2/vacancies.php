<?php
require_once 'config/database.php';

// Получение активных вакансий из базы данных
$stmt = $pdo->query("SELECT * FROM vacancies WHERE is_active = TRUE ORDER BY id");
$vacancies = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Вакансии — Автосервис "Профи"</title>
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
        .vacancies-section {
            padding: 80px 0 40px;
            background: white;
        }
        .vacancy-card {
            background: var(--light-bg);
            border-radius: 18px;
            box-shadow: var(--shadow-md);
            padding: 2.5rem 2rem 2rem 2rem;
            margin-bottom: 2.5rem;
            transition: box-shadow 0.3s, transform 0.3s;
            border: none;
            position: relative;
        }
        .vacancy-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px) scale(1.02);
        }
        .vacancy-icon {
            position: absolute;
            top: -30px;
            left: 2rem;
            background: var(--gradient-primary);
            color: var(--text-light);
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            box-shadow: var(--shadow-sm);
        }
        .vacancy-title {
            margin-left: 80px;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        .vacancy-meta {
            margin-left: 80px;
            color: var(--secondary-color);
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }
        .vacancy-desc {
            margin-left: 80px;
            margin-bottom: 1.2rem;
        }
        .vacancy-tags {
            margin-left: 80px;
            margin-bottom: 1.2rem;
        }
        .vacancy-tag {
            display: inline-block;
            background: var(--gradient-accent);
            color: var(--text-light);
            border-radius: 20px;
            padding: 0.3rem 1rem;
            font-size: 0.95rem;
            margin-right: 0.5rem;
            margin-bottom: 0.3rem;
            font-weight: 500;
        }
        .vacancy-apply-btn {
            margin-left: 80px;
            background: var(--secondary-color);
            color: var(--text-light);
            border: none;
            border-radius: 30px;
            padding: 0.7rem 2.2rem;
            font-size: 1.1rem;
            font-weight: 600;
            transition: background 0.3s;
        }
        .vacancy-apply-btn:hover {
            background: var(--accent-color);
            color: var(--text-light);
        }
        .about-section {
            background: var(--light-bg);
            padding: 60px 0 40px;
        }
        .about-section h2 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }
        .about-section p {
            font-size: 1.15rem;
        }
        .contact-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: var(--shadow-md);
            margin-top: 2rem;
            text-align: center;
        }
        .contact-icon {
            font-size: 2.5rem;
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }
        footer {
            background: var(--dark-bg);
            color: var(--text-light);
            padding: 3rem 0;
        }
        @media (max-width: 768px) {
            .vacancy-title, .vacancy-meta, .vacancy-desc, .vacancy-tags, .vacancy-apply-btn {
                margin-left: 0 !important;
            }
            .vacancy-icon {
                left: 50%;
                transform: translateX(-50%);
                top: -35px;
            }
            .vacancy-card {
                padding-top: 3.5rem;
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
                        <a class="nav-link" href="services.php">Услуги</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="faq.php">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="vacancies.php">Вакансии</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="auth.php">Личный кабинет</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Заголовок -->
    <header class="page-header text-center">
        <div class="container">
            <h1 class="display-4 mb-4">Вакансии</h1>
            <p class="lead mb-0">Присоединяйтесь к команде профессионалов автосервиса "Профи" — стабильная работа, достойная оплата, дружный коллектив!</p>
        </div>
    </header>

    <!-- О компании -->
    <section class="about-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 mb-4 mb-lg-0">
                    <h2>Почему выбирают нас?</h2>
                    <p>Автосервис "Профи" — это современный сервисный центр, где ценят профессионализм, честность и командную работу. Мы постоянно развиваемся, внедряем новые технологии и заботимся о своих сотрудниках. У нас вы найдете:</p>
                    <ul>
                        <li>Современное оборудование и комфортные условия труда</li>
                        <li>Официальное трудоустройство и социальные гарантии</li>
                        <li>Стабильную заработную плату и премии</li>
                        <li>Возможности для обучения и карьерного роста</li>
                        <li>Дружный коллектив и уважительное отношение</li>
                    </ul>
                </div>
                <div class="col-lg-5 text-center">
                    <img src="img/mac.png" alt="Работа в автосервисе" class="img-fluid rounded shadow-lg" style="max-width: 350px;">
                </div>
            </div>
        </div>
    </section>

    <!-- Вакансии -->
    <section class="vacancies-section">
        <div class="container">
            <h2 class="text-center mb-5">Открытые вакансии</h2>
            <div class="row">
                <?php foreach ($vacancies as $vacancy): ?>
                <div class="col-md-6 mb-4">
                    <div class="vacancy-card">
                        <h3><?php echo htmlspecialchars($vacancy['title']); ?></h3>
                        <div class="vacancy-meta">
                            <span class="salary"><?php echo htmlspecialchars($vacancy['salary']); ?></span>
                            <span class="employment"><?php echo htmlspecialchars($vacancy['employment_type']); ?></span>
                            <span class="experience"><?php echo htmlspecialchars($vacancy['experience']); ?></span>
                        </div>
                        <p class="description"><?php echo htmlspecialchars($vacancy['description']); ?></p>
                        <div class="requirements">
                            <h4>Требования:</h4>
                            <p><?php echo htmlspecialchars($vacancy['requirements']); ?></p>
                        </div>
                        <div class="conditions">
                            <h4>Условия:</h4>
                            <p><?php echo htmlspecialchars($vacancy['conditions']); ?></p>
                        </div>
                        <a href="auth.php" class="btn btn-primary">Откликнуться</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Контактная информация -->
    <section class="contact-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3>Остались вопросы?</h3>
                        <p class="mb-4">Наши специалисты готовы проконсультировать вас по всем вопросам трудоустройства</p>
                        <a href="auth.php" class="btn btn-primary btn-lg w-100">Откликнуться</a>
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