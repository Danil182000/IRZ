<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' https:; script-src 'self' https: 'unsafe-inline'; style-src 'self' https: 'unsafe-inline'; img-src 'self' https: data:;">
    <title>Часто задаваемые вопросы - Автосервис "Профи"</title>
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

        .faq-section {
            padding: 80px 0;
            background: white;
        }

        .faq-section:nth-child(even) {
            background: var(--light-bg);
        }

        .faq-category {
            margin-bottom: 3rem;
        }

        .faq-category h2 {
            color: var(--primary-color);
            margin-bottom: 2rem;
            position: relative;
            padding-bottom: 1rem;
        }

        .faq-category h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--gradient-accent);
        }

        .accordion {
            --bs-accordion-border-color: transparent;
            --bs-accordion-btn-bg: white;
            --bs-accordion-active-bg: var(--light-bg);
            --bs-accordion-btn-focus-border-color: transparent;
            --bs-accordion-btn-focus-box-shadow: none;
        }

        .accordion-item {
            border: none;
            margin-bottom: 1rem;
            border-radius: 10px !important;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .accordion-button {
            font-weight: 500;
            padding: 1.25rem;
            transition: all 0.3s ease;
        }

        .accordion-button:not(.collapsed) {
            color: var(--secondary-color);
            background: var(--light-bg);
        }

        .accordion-button::after {
            background-size: 1rem;
            transition: all 0.3s ease;
        }

        .accordion-button:not(.collapsed)::after {
            transform: rotate(-180deg);
        }

        .accordion-body {
            padding: 1.25rem;
            background: white;
        }

        .faq-icon {
            width: 40px;
            height: 40px;
            background: var(--light-bg);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: var(--secondary-color);
            transition: all 0.3s ease;
        }

        .accordion-button:not(.collapsed) .faq-icon {
            background: var(--gradient-primary);
            color: var(--text-light);
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

        .search-box {
            position: relative;
            margin-bottom: 3rem;
        }

        .search-box input {
            padding: 1rem 1.5rem;
            padding-left: 3rem;
            border-radius: 30px;
            border: 2px solid var(--light-bg);
            width: 100%;
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            border-color: var(--secondary-color);
            box-shadow: var(--shadow-sm);
            outline: none;
        }

        .search-box i {
            position: absolute;
            left: 1.2rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary-color);
        }

        .faq-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 2rem;
        }

        .faq-tag {
            padding: 0.5rem 1rem;
            background: var(--light-bg);
            border-radius: 20px;
            color: var(--text-dark);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .faq-tag:hover, .faq-tag.active {
            background: var(--gradient-primary);
            color: var(--text-light);
        }

        footer {
            background: var(--dark-bg);
            color: var(--text-light);
            padding: 3rem 0;
        }

        @media (max-width: 768px) {
            .faq-tags {
                justify-content: center;
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
                        <a class="nav-link active" href="faq.php">FAQ</a>
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
            <h1 class="display-4 mb-4">Часто задаваемые вопросы</h1>
            <p class="lead mb-0">Ответы на популярные вопросы об услугах автосервиса</p>
        </div>
    </header>

    <!-- Поиск и теги -->
    <section class="faq-section">
        <div class="container">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="faqSearch" placeholder="Поиск по вопросам..." class="form-control">
            </div>
            <div class="faq-tags">
                <a href="#" class="faq-tag active" data-category="all">Все вопросы</a>
                <a href="#" class="faq-tag" data-category="service">Обслуживание</a>
                <a href="#" class="faq-tag" data-category="repair">Ремонт</a>
                <a href="#" class="faq-tag" data-category="price">Цены</a>
                <a href="#" class="faq-tag" data-category="warranty">Гарантия</a>
                <a href="#" class="faq-tag" data-category="schedule">График работы</a>
            </div>

            <!-- Общие вопросы -->
            <div class="faq-category" data-category="service">
                <h2>Общие вопросы</h2>
                <div class="accordion" id="generalFaq">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#general1">
                                <div class="faq-icon">
                                    <i class="fas fa-question"></i>
                                </div>
                                Какие марки автомобилей вы обслуживаете?
                            </button>
                        </h2>
                        <div id="general1" class="accordion-collapse collapse show" data-bs-parent="#generalFaq">
                            <div class="accordion-body">
                                Мы обслуживаем автомобили всех марок и моделей. Наш сервис оснащен современным оборудованием и специализированными инструментами для работы с различными марками автомобилей. Наши специалисты регулярно проходят обучение и имеют опыт работы с автомобилями как отечественного, так и иностранного производства.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#general2">
                                <div class="faq-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                Какой график работы автосервиса?
                            </button>
                        </h2>
                        <div id="general2" class="accordion-collapse collapse" data-bs-parent="#generalFaq">
                            <div class="accordion-body">
                                Мы работаем ежедневно с 8:00 до 20:00, включая выходные и праздничные дни. Для удобства наших клиентов мы предлагаем предварительную запись на обслуживание, что позволяет выбрать удобное время и избежать очередей.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Обслуживание -->
            <div class="faq-category" data-category="service">
                <h2>Обслуживание</h2>
                <div class="accordion" id="serviceFaq">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#service1">
                                <div class="faq-icon">
                                    <i class="fas fa-oil-can"></i>
                                </div>
                                Как часто нужно менять масло в двигателе?
                            </button>
                        </h2>
                        <div id="service1" class="accordion-collapse collapse" data-bs-parent="#serviceFaq">
                            <div class="accordion-body">
                                Частота замены масла зависит от типа двигателя, условий эксплуатации и используемого масла. В среднем рекомендуется менять масло каждые 10-15 тысяч километров для бензиновых двигателей и каждые 7-10 тысяч километров для дизельных. Однако точный интервал лучше уточнить в руководстве по эксплуатации вашего автомобиля или проконсультироваться с нашими специалистами.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#service2">
                                <div class="faq-icon">
                                    <i class="fas fa-tools"></i>
                                </div>
                                Что входит в плановое ТО?
                            </button>
                        </h2>
                        <div id="service2" class="accordion-collapse collapse" data-bs-parent="#serviceFaq">
                            <div class="accordion-body">
                                Плановое техническое обслуживание включает в себя:
                                <ul>
                                    <li>Проверку и замену масла и фильтров</li>
                                    <li>Диагностику тормозной системы</li>
                                    <li>Проверку состояния подвески</li>
                                    <li>Проверку уровня технических жидкостей</li>
                                    <li>Диагностику электронных систем</li>
                                    <li>Проверку состояния ремней и шлангов</li>
                                </ul>
                                Точный перечень работ зависит от модели автомобиля и пробега.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ремонт -->
            <div class="faq-category" data-category="repair">
                <h2>Ремонт</h2>
                <div class="accordion" id="repairFaq">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#repair1">
                                <div class="faq-icon">
                                    <i class="fas fa-engine"></i>
                                </div>
                                Сколько времени занимает капитальный ремонт двигателя?
                            </button>
                        </h2>
                        <div id="repair1" class="accordion-collapse collapse" data-bs-parent="#repairFaq">
                            <div class="accordion-body">
                                Сроки капитального ремонта двигателя зависят от сложности работ и модели автомобиля. В среднем ремонт занимает от 5 до 14 дней. Мы всегда информируем клиента о примерных сроках выполнения работ и держим его в курсе процесса ремонта. При необходимости мы можем предоставить подменный автомобиль.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#repair2">
                                <div class="faq-icon">
                                    <i class="fas fa-car-crash"></i>
                                </div>
                                Выполняете ли вы кузовной ремонт?
                            </button>
                        </h2>
                        <div id="repair2" class="accordion-collapse collapse" data-bs-parent="#repairFaq">
                            <div class="accordion-body">
                                Да, мы выполняем все виды кузовного ремонта, включая:
                                <ul>
                                    <li>Устранение вмятин и царапин</li>
                                    <li>Покраску кузовных элементов</li>
                                    <li>Замену поврежденных деталей</li>
                                    <li>Ремонт после ДТП</li>
                                    <li>Полировку кузова</li>
                                </ul>
                                Все работы выполняются с использованием современных материалов и технологий.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Цены и оплата -->
            <div class="faq-category" data-category="price">
                <h2>Цены и оплата</h2>
                <div class="accordion" id="priceFaq">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#price1">
                                <div class="faq-icon">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                Какие способы оплаты вы принимаете?
                            </button>
                        </h2>
                        <div id="price1" class="accordion-collapse collapse" data-bs-parent="#priceFaq">
                            <div class="accordion-body">
                                Мы принимаем оплату наличными, банковскими картами (Visa, MasterCard, МИР), а также безналичным расчетом для юридических лиц. При необходимости можем выставить счет для оплаты через интернет-банк. Все платежи сопровождаются необходимыми документами.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#price2">
                                <div class="faq-icon">
                                    <i class="fas fa-percentage"></i>
                                </div>
                                Есть ли у вас скидки для постоянных клиентов?
                            </button>
                        </h2>
                        <div id="price2" class="accordion-collapse collapse" data-bs-parent="#priceFaq">
                            <div class="accordion-body">
                                Да, у нас действует программа лояльности для постоянных клиентов. При регистрации в личном кабинете вы получаете скидку 5% на все услуги. При регулярном обслуживании размер скидки может увеличиваться до 15%. Также мы регулярно проводим акции и специальные предложения для наших клиентов.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Гарантия -->
            <div class="faq-category" data-category="warranty">
                <h2>Гарантия</h2>
                <div class="accordion" id="warrantyFaq">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#warranty1">
                                <div class="faq-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                На какие работы предоставляется гарантия?
                            </button>
                        </h2>
                        <div id="warranty1" class="accordion-collapse collapse" data-bs-parent="#warrantyFaq">
                            <div class="accordion-body">
                                Мы предоставляем гарантию на все виды ремонтных работ и установленные запчасти. Срок гарантии зависит от типа работ:
                                <ul>
                                    <li>Капитальный ремонт двигателя - 12 месяцев</li>
                                    <li>Ремонт КПП - 12 месяцев</li>
                                    <li>Ремонт подвески - 6 месяцев</li>
                                    <li>Замена масла и фильтров - 3 месяца</li>
                                </ul>
                                Гарантийные обязательства оформляются документально.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Контактная информация -->
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3>Остались вопросы?</h3>
                        <p class="mb-4">Наши специалисты готовы ответить на все ваши вопросы</p>
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
    <script>
        // Поиск по FAQ
        document.getElementById('faqSearch').addEventListener('input', function(e) {
            const searchText = e.target.value.toLowerCase();
            const questions = document.querySelectorAll('.accordion-button');
            
            questions.forEach(question => {
                const text = question.textContent.toLowerCase();
                const accordionItem = question.closest('.accordion-item');
                
                if (text.includes(searchText)) {
                    accordionItem.style.display = '';
                } else {
                    accordionItem.style.display = 'none';
                }
            });
        });

        // Фильтрация по тегам
        document.querySelectorAll('.faq-tag').forEach(tag => {
            tag.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Обновляем активный тег
                document.querySelectorAll('.faq-tag').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                const category = this.dataset.category;
                const categories = document.querySelectorAll('.faq-category');
                
                categories.forEach(cat => {
                    if (category === 'all' || cat.dataset.category === category) {
                        cat.style.display = '';
                    } else {
                        cat.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html> 