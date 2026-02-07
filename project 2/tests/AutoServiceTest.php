<?php

use PHPUnit\Framework\TestCase;

class AutoServiceTest extends TestCase
{
    private $testResults = [];
    private $startTime;
    private $analytics = [
        'pageLoads' => [],
        'responseTimes' => [],
        'errors' => [],
        'warnings' => [],
        'securityChecks' => [],
        'performanceMetrics' => [],
        'functionChecks' => [],
        'testAccounts' => [],
        'authChecks' => []
    ];
    private $basePath;
    private $categoryResults = [
        'Регистрация' => ['status' => true, 'errors' => [], 'checks' => []],
        'Вход' => ['status' => true, 'errors' => [], 'checks' => []],
        'Навигация' => ['status' => true, 'errors' => [], 'checks' => []],
        'Безопасность' => ['status' => true, 'errors' => [], 'checks' => []],
        'Адаптация' => ['status' => true, 'errors' => [], 'checks' => []],
        'Функциональность' => ['status' => true, 'errors' => [], 'checks' => []],
    ];

    protected function setUp(): void
    {
        $this->startTime = microtime(true);
        $this->testResults = [];
        $this->basePath = dirname(__DIR__);
        $this->analytics = [
            'pageLoads' => [],
            'responseTimes' => [],
            'errors' => [],
            'warnings' => [],
            'securityChecks' => [],
            'performanceMetrics' => [],
            'functionChecks' => [],
            'testAccounts' => [],
            'authChecks' => []
        ];
    }

    protected function tearDown(): void
    {
        $this->saveAnalytics();
    }

    /**
     * Получает содержимое файла с учетом подключаемых файлов
     */
    private function getFileContentWithIncludes($file)
    {
        $content = file_get_contents($file);
        $baseDir = dirname($file);
        
        // Заменяем include/require на их содержимое
        $content = preg_replace_callback(
            '/<\?php\s*include\s+[\'"]([^\'"]+)[\'"]\s*;\s*\?>/',
            function($matches) use ($baseDir) {
                $includeFile = $matches[1];
                // Если путь относительный, добавляем базовую директорию
                if (!str_starts_with($includeFile, '/')) {
                    $includeFile = $baseDir . '/' . $includeFile;
                }
                if (file_exists($includeFile)) {
                    return file_get_contents($includeFile);
                }
                return '';
            },
            $content
        );
        
        return $content;
    }

    /**
     * Получает содержимое файла
     */
    private function getFileContent($file)
    {
        return $this->getFileContentWithIncludes($file);
    }

    /**
     * Тестирование загрузки главной страницы
     */
    public function testHomePageLoad()
    {
        try {
            $startTime = microtime(true);
            $content = $this->getFileContent('index.php');
            
            $this->assertNotEmpty($content, 'Главная страница пуста');
            $this->assertStringContainsString('Автосервис "Профи"', $content, 'Заголовок не найден на главной странице');
            
            $loadTime = microtime(true) - $startTime;
            $this->analytics['pageLoads']['home'] = [
                'time' => $loadTime,
                'size' => strlen($content),
                'status' => 'success'
            ];
        } catch (Exception $e) {
            $this->analytics['errors'][] = "Ошибка при тестировании главной страницы: " . $e->getMessage();
            throw $e;
        }
    }

    /**
     * Тестирование загрузки страницы услуг
     */
    public function testServicesPageLoad()
    {
        try {
            $startTime = microtime(true);
            $content = $this->getFileContent('services.php');
            
            $this->assertNotEmpty($content, 'Страница услуг пуста');
            $this->assertStringContainsString('Наши услуги', $content, 'Заголовок услуг не найден');
            
            $loadTime = microtime(true) - $startTime;
            $this->analytics['pageLoads']['services'] = [
                'time' => $loadTime,
                'size' => strlen($content),
                'status' => 'success'
            ];
        } catch (Exception $e) {
            $this->analytics['errors'][] = "Ошибка при тестировании страницы услуг: " . $e->getMessage();
            throw $e;
        }
    }

    /**
     * Тестирование загрузки страницы FAQ
     */
    public function testFaqPageLoad()
    {
        try {
            $startTime = microtime(true);
            $content = $this->getFileContent('faq.php');
            
            $this->assertNotEmpty($content, 'Страница FAQ пуста');
            $this->assertStringContainsString('Часто задаваемые вопросы', $content, 'Заголовок FAQ не найден');
            
            $loadTime = microtime(true) - $startTime;
            $this->analytics['pageLoads']['faq'] = [
                'time' => $loadTime,
                'size' => strlen($content),
                'status' => 'success'
            ];
        } catch (Exception $e) {
            $this->analytics['errors'][] = "Ошибка при тестировании страницы FAQ: " . $e->getMessage();
            throw $e;
        }
    }

    /**
     * Тестирование загрузки страницы вакансий
     */
    public function testVacanciesPageLoad()
    {
        try {
            $startTime = microtime(true);
            $content = $this->getFileContent('vacancies.php');
            
            $this->assertNotEmpty($content, 'Страница вакансий пуста');
            $this->assertStringContainsString('Вакансии', $content, 'Заголовок вакансий не найден');
            
            $loadTime = microtime(true) - $startTime;
            $this->analytics['pageLoads']['vacancies'] = [
                'time' => $loadTime,
                'size' => strlen($content),
                'status' => 'success'
            ];
        } catch (Exception $e) {
            $this->analytics['errors'][] = "Ошибка при тестировании страницы вакансий: " . $e->getMessage();
            throw $e;
        }
    }

    /**
     * @runInSeparateProcess
     */
    public function testAuthPage()
    {
        $this->categoryResults['Регистрация']['checks'][] = 'Проверка формы регистрации';
        $this->categoryResults['Регистрация']['checks'][] = 'Проверка подтверждения пароля';
        $this->categoryResults['Вход']['checks'][] = 'Проверка формы входа';
        $this->categoryResults['Вход']['checks'][] = 'Проверка обязательных полей';
        try {
            $_SERVER['REQUEST_METHOD'] = 'POST';
            $content = $this->getFileContent('auth.php');
            $this->assertStringContainsString('name="action" value="login"', $content);
            $this->assertStringContainsString('name="action" value="register"', $content);
            $this->assertStringContainsString('name="reg_password_confirm"', $content);

            // --- Тестируем реальную регистрацию и вход ---
            require_once __DIR__ . '/../config/database.php';
            $uniqueEmail = 'testuser_' . uniqid() . '@example.com';
            $testPassword = 'TestPass123!';
            $testUser = [
                'reg_firstname' => 'Тест',
                'reg_lastname' => 'Пользователь',
                'reg_email' => $uniqueEmail,
                'reg_phone' => '+79990001122',
                'reg_password' => $testPassword,
                'reg_password_confirm' => $testPassword,
                'action' => 'register'
            ];
            $_POST = $testUser;
            $_SERVER['REQUEST_METHOD'] = 'POST';
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_unset();
                session_destroy();
            }
            ob_start();
            include __DIR__ . '/../auth.php';
            $registerOutput = ob_get_clean();
            // Проверяем, что пользователь появился в базе
            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
            $stmt->execute([$uniqueEmail]);
            $user = $stmt->fetch();
            if (!$user) {
                // Пытаемся вытащить ошибки из вывода
                if (preg_match_all('/<li>(.*?)<\/li>/', $registerOutput, $matches)) {
                    $errorMsg = implode('; ', $matches[1]);
                } else {
                    $errorMsg = 'Пользователь не был создан в базе данных';
                }
                $this->fail('Ошибка регистрации: ' . $errorMsg . "\n\nВывод страницы:\n" . $registerOutput);
            }

            // Теперь тестируем вход
            $_POST = [
                'login_email' => $uniqueEmail,
                'login_password' => $testPassword,
                'action' => 'login'
            ];
            $_SERVER['REQUEST_METHOD'] = 'POST';
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_unset();
                session_destroy();
            }
            ob_start();
            include __DIR__ . '/../auth.php';
            $loginOutput = ob_get_clean();
            $loginSuccess = isset($_SESSION['user_id']) || strpos($loginOutput, 'profile.php') !== false;
            if (!$loginSuccess) {
                $this->fail('Ошибка входа. Вывод страницы: ' . $loginOutput);
            }

            // Очищаем сессию и POST данные
            unset($_POST);
            unset($_SERVER['REQUEST_METHOD']);
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_unset();
                session_destroy();
            }
        } catch (\Throwable $e) {
            $this->categoryResults['Регистрация']['status'] = false;
            $this->categoryResults['Регистрация']['errors'][] = 'Форма регистрации не найдена или некорректна, либо регистрация не удалась: ' . $e->getMessage();
            $this->categoryResults['Вход']['status'] = false;
            $this->categoryResults['Вход']['errors'][] = 'Форма входа не найдена или некорректна, либо вход не удался: ' . $e->getMessage();
            throw $e;
        }
    }

    /**
     * Тестирование страницы профиля
     */
    public function testProfilePage()
    {
        try {
            $content = $this->getFileContent('profile.php');
            
            // Проверка наличия основных элементов профиля
            $this->assertStringContainsString('Личный кабинет', $content, 'Заголовок страницы профиля не найден');
            $this->assertStringContainsString('Мои автомобили', $content, 'Секция автомобилей не найдена');
            $this->assertStringContainsString('Мои заявки', $content, 'Секция заказов не найдена');
            
            // Проверка наличия форм
            $this->assertStringContainsString('add_car', $content, 'Форма добавления автомобиля не найдена');
            $this->assertStringContainsString('create_order', $content, 'Форма создания заказа не найдена');
            
            // Проверка безопасности
            $this->assertStringContainsString('noindex, nofollow', $content, 
                'Страница профиля не содержит мета-тег noindex');
            $this->assertStringContainsString('session_start()', $content, 
                'Страница профиля не использует сессии');
            
            // Проверка адаптивности
            $this->assertStringContainsString('col-md-4', $content, 
                'Страница профиля не использует адаптивную сетку');
            $this->assertStringContainsString('container', $content, 
                'Страница профиля не использует адаптивные контейнеры');
            
            $this->analytics['securityChecks'][] = [
                'file' => 'profile.php',
                'check' => 'profile_security',
                'passed' => true
            ];
        } catch (Exception $e) {
            $this->analytics['errors'][] = [
                'file' => 'profile.php',
                'message' => "Ошибка при тестировании страницы профиля: " . $e->getMessage(),
                'time' => microtime(true) - $this->startTime
            ];
            throw $e;
        }
    }

    /**
     * Тестирование файла выхода
     */
    public function testLogoutFile()
    {
        try {
            $content = $this->getFileContent('logout.php');
            
            // Проверка наличия необходимых функций
            $this->assertStringContainsString('session_start()', $content, 
                'Файл выхода не использует сессии');
            $this->assertStringContainsString('session_destroy()', $content, 
                'Файл выхода не уничтожает сессию');
            $this->assertStringContainsString('header(\'Location:', $content, 
                'Файл выхода не содержит перенаправление');
            
            // Проверка безопасности
            $this->assertStringContainsString('$_SESSION = array()', $content, 
                'Файл выхода не очищает данные сессии');
            $this->assertStringContainsString('setcookie(session_name()', $content, 
                'Файл выхода не удаляет сессионную cookie');
            
            $this->analytics['securityChecks'][] = [
                'file' => 'logout.php',
                'check' => 'logout_security',
                'passed' => true
            ];
        } catch (Exception $e) {
            $this->analytics['errors'][] = [
                'file' => 'logout.php',
                'message' => "Ошибка при тестировании файла выхода: " . $e->getMessage(),
                'time' => microtime(true) - $this->startTime
            ];
            throw $e;
        }
    }

    /**
     * Тестирование навигационного меню
     */
    public function testNavigationMenu()
    {
        $this->categoryResults['Навигация']['checks'][] = 'Проверка наличия всех пунктов меню на страницах';
        $pages = ['index.php', 'services.php', 'faq.php', 'vacancies.php', 'auth.php', 'profile.php'];
        $menuItems = ['Главная', 'Услуги', 'Вакансии', 'FAQ'];
        foreach ($pages as $page) {
            $content = $this->getFileContent($page);
            foreach ($menuItems as $item) {
                if (($page === 'auth.php' || $page === 'profile.php') && $item === 'Личный кабинет') continue;
                try {
                    $this->assertStringContainsString($item, $content);
                } catch (\Throwable $e) {
                    $this->categoryResults['Навигация']['status'] = false;
                    $this->categoryResults['Навигация']['errors'][] = "Пункт меню '$item' не найден на странице $page";
                    throw $e;
                }
            }
        }
    }

    /**
     * Тестирование наличия важных элементов на страницах
     */
    public function testImportantElements()
    {
        $pages = [
            'index.php' => ['Автосервис "Профи"', 'Наши услуги', 'Контакты'],
            'services.php' => ['Услуги автосервиса "Профи"', 'Техническое обслуживание', 'Диагностика'],
            'faq.php' => ['Часто задаваемые вопросы', 'Общие вопросы', 'Ремонт'],
            'vacancies.php' => ['Вакансии', 'Почему выбирают нас?', 'Остались вопросы?'],
            'auth.php' => ['Вход и регистрация', 'Войти', 'Зарегистрироваться'],
            'profile.php' => ['Личный кабинет', 'Мои автомобили', 'Мои заявки']
        ];

        foreach ($pages as $page => $elements) {
            try {
                $content = $this->getFileContent($page);
                foreach ($elements as $element) {
                    $this->assertStringContainsString($element, $content, 
                        "Элемент '{$element}' не найден на странице {$page}");
                }
            } catch (Exception $e) {
                $this->analytics['errors'][] = "Ошибка при проверке элементов на странице {$page}: " . $e->getMessage();
                throw $e;
            }
        }
    }

    /**
     * Тестирование производительности
     */
    public function testPerformance()
    {
        $pages = ['index.php', 'services.php', 'faq.php', 'vacancies.php', 'auth.php', 'profile.php'];
        $maxLoadTime = 2.0; // максимальное время загрузки в секундах

        foreach ($pages as $page) {
            try {
                $startTime = microtime(true);
                $content = $this->getFileContent($page);
                $loadTime = microtime(true) - $startTime;

                $this->assertLessThan($maxLoadTime, $loadTime, 
                    "Страница {$page} загружается слишком долго: {$loadTime} сек.");

                $this->analytics['responseTimes'][$page] = $loadTime;
            } catch (Exception $e) {
                $this->analytics['errors'][] = "Ошибка при проверке производительности страницы {$page}: " . $e->getMessage();
                throw $e;
            }
        }
    }

    /**
     * Тестирование валидности HTML
     */
    public function testHtmlValidity()
    {
        $pages = ['index.php', 'services.php', 'faq.php', 'vacancies.php', 'auth.php', 'profile.php'];
        
        foreach ($pages as $page) {
            try {
                $content = $this->getFileContent($page);
                
                // Проверка наличия основных HTML-тегов
                $this->assertStringContainsString('<!DOCTYPE html>', $content, 
                    "Страница {$page} не содержит DOCTYPE");
                $this->assertStringContainsString('<html', $content, 
                    "Страница {$page} не содержит тег html");
                $this->assertStringContainsString('<head', $content, 
                    "Страница {$page} не содержит тег head");
                $this->assertStringContainsString('<body', $content, 
                    "Страница {$page} не содержит тег body");
            } catch (Exception $e) {
                $this->analytics['errors'][] = "Ошибка при проверке HTML на странице {$page}: " . $e->getMessage();
                throw $e;
            }
        }
    }

    /**
     * Тестирование безопасности
     */
    public function testSecurity()
    {
        $this->categoryResults['Безопасность']['checks'][] = 'Проверка мета-тегов безопасности';
        $this->categoryResults['Безопасность']['checks'][] = 'Проверка HTTPS для внешних ресурсов';
        $this->categoryResults['Безопасность']['checks'][] = 'Проверка rel="noopener" для target="_blank"';
        $pages = ['index.php', 'services.php', 'faq.php', 'vacancies.php', 'auth.php', 'profile.php'];
        foreach ($pages as $page) {
            try {
                $content = $this->getFileContent($page);
                $this->assertStringContainsString('viewport', $content);
                $this->assertStringContainsString('charset="UTF-8"', $content);
                $this->assertStringContainsString('meta name="robots"', $content);
                $this->assertStringNotContainsString('http://', $content);
                if (strpos($content, 'target="_blank"') !== false) {
                    $this->assertStringContainsString('rel="noopener"', $content);
                }
            } catch (\Throwable $e) {
                $this->categoryResults['Безопасность']['status'] = false;
                $this->categoryResults['Безопасность']['errors'][] = "Ошибка безопасности на странице $page: " . $e->getMessage();
                throw $e;
            }
        }
    }

    /**
     * Тестирование адаптивности
     */
    public function testResponsiveness()
    {
        $this->categoryResults['Адаптация']['checks'][] = 'Проверка адаптивных классов Bootstrap';
        $this->categoryResults['Адаптация']['checks'][] = 'Проверка наличия мета-тега viewport';
        $pages = ['index.php', 'services.php', 'faq.php', 'vacancies.php', 'auth.php', 'profile.php'];
        foreach ($pages as $page) {
            $content = $this->getFileContent($page);
            $responsiveClasses = ['container', 'row', 'col-', 'form-floating', 'auth-container'];
            $foundClasses = [];
            foreach ($responsiveClasses as $class) {
                if (strpos($content, $class) !== false) {
                    $foundClasses[] = $class;
                }
            }
            try {
                $this->assertNotEmpty($foundClasses);
                $this->assertStringContainsString('viewport', $content);
            } catch (\Throwable $e) {
                $this->categoryResults['Адаптация']['status'] = false;
                $this->categoryResults['Адаптация']['errors'][] = "Страница $page не адаптивна или отсутствует viewport/meta";
                throw $e;
            }
        }
    }

    /**
     * Тестирование функциональности поиска в FAQ
     */
    public function testFaqSearch()
    {
        $this->categoryResults['Функциональность']['status'] = true;
        $this->categoryResults['Функциональность']['checks'][] = 'Проверка поиска в FAQ';
        
        try {
            $content = $this->getFileContent('faq.php');
            
            // Проверяем наличие формы поиска
            $this->assertStringContainsString('id="faqSearch"', $content, 'Форма поиска FAQ не найдена');
            $this->assertStringContainsString('class="form-control"', $content, 'Поле поиска не найдено');
            
            // Проверяем наличие JavaScript для поиска
            $this->assertStringContainsString('addEventListener', $content, 'Обработчик событий поиска не найден');
            
            // Проверяем структуру FAQ для поиска
            $this->assertStringContainsString('accordion-item', $content, 'Элементы FAQ не найдены');
            $this->assertStringContainsString('accordion-button', $content, 'Заголовки вопросов FAQ не найдены');
            $this->assertStringContainsString('accordion-collapse', $content, 'Ответы FAQ не найдены');
            
            $this->analytics['functionChecks']['faqSearch'] = [
                'status' => 'success',
                'message' => 'Функциональность поиска FAQ работает корректно'
            ];
        } catch (Exception $e) {
            $this->categoryResults['Функциональность']['status'] = false;
            $this->categoryResults['Функциональность']['errors'][] = $e->getMessage();
            throw $e;
        }
    }

    /**
     * Тестирование фильтрации услуг
     */
    public function testServicesFilter()
    {
        $this->categoryResults['Функциональность']['checks'][] = 'Проверка фильтрации услуг';
        
        try {
            $content = $this->getFileContent('services.php');
            
            // Проверяем наличие элементов фильтрации
            $this->assertStringContainsString('service-category', $content, 'Категории услуг не найдены');
            $this->assertStringContainsString('service-card', $content, 'Карточки услуг не найдены');
            
            // Проверяем наличие категорий услуг
            $this->assertStringContainsString('Диагностика', $content, 'Категория "Диагностика" не найдена');
            $this->assertStringContainsString('Ремонт', $content, 'Категория "Ремонт" не найдена');
            $this->assertStringContainsString('ТО', $content, 'Категория "ТО" не найдена');
            
            $this->analytics['functionChecks']['servicesFilter'] = [
                'status' => 'success',
                'message' => 'Функциональность фильтрации услуг работает корректно'
            ];
        } catch (Exception $e) {
            $this->categoryResults['Функциональность']['status'] = false;
            $this->categoryResults['Функциональность']['errors'][] = $e->getMessage();
            throw $e;
        }
    }

    /**
     * Сохранение аналитики в файл
     */
    private function saveAnalytics()
    {
        static $isFirstCall = true;
        
        if (!$isFirstCall) {
            return; // Выводим аналитику только один раз в конце
        }
        $isFirstCall = false;

        // Считаем общую статистику
        $total = 0; $passed = 0;
        foreach ($this->categoryResults as $cat) {
            $total++;
            if ($cat['status']) $passed++;
        }
        $percent = $total > 0 ? round($passed / $total * 100, 1) : 0;

        // Формируем отчет
        $report = [];
        $report[] = "\n┌ ОТЧЕТ О ТЕСТИРОВАНИИ САЙТА СТО ────────────────────────────────┐";
        $report[] = "■ ОБЩАЯ СТАТИСТИКА:";
        $report[] = "  • Всего тестов: $total";
        $report[] = "  • Успешно пройдено: $passed";
        $report[] = "  • Ошибок: " . ($total-$passed);
        $report[] = "  • Процент успешных тестов: $percent%";

        // Информация о тестовых аккаунтах
        if (!empty($this->analytics['testAccounts'])) {
            $report[] = "\n■ ТЕСТОВЫЕ АККАУНТЫ:";
            foreach ($this->analytics['testAccounts'] as $account) {
                $report[] = "  • Email: {$account['email']}";
                $report[] = "    Пароль: {$account['password']}";
                $report[] = "    Создан: {$account['created_at']}";
                $report[] = "    ID пользователя: {$account['user_id']}";
                $report[] = "    ────────────────────────";
            }
        }

        // Результаты функциональных проверок
        if (!empty($this->analytics['functionChecks'])) {
            $report[] = "\n■ ФУНКЦИОНАЛЬНЫЕ ПРОВЕРКИ:";
            foreach ($this->analytics['functionChecks'] as $check => $result) {
                $icon = $result['status'] === 'success' ? '✓' : '✗';
                $report[] = "  • $check: $icon {$result['message']}";
            }
        }

        // Детальные результаты по категориям
        $report[] = "\n■ ДЕТАЛЬНЫЕ РЕЗУЛЬТАТЫ:";
        foreach ($this->categoryResults as $catName => $cat) {
            $icon = $cat['status'] ? '✓' : '✗';
            $report[] = "\n$catName:";
            $report[] = "Статус: $icon " . ($cat['status'] ? 'УСПЕШНО' : 'ОШИБКА');
            
            if (!empty($cat['checks'])) {
                $report[] = "Проверяемые элементы:";
                foreach ($cat['checks'] as $check) {
                    $report[] = "  • $check";
                }
            }
            
            if (!empty($cat['errors'])) {
                $report[] = "Ошибки:";
                foreach ($cat['errors'] as $error) {
                    $report[] = "  • $error";
                }
            }
        }

        // Производительность
        if (!empty($this->analytics['pageLoads'])) {
            $report[] = "\n■ ПРОИЗВОДИТЕЛЬНОСТЬ:";
            foreach ($this->analytics['pageLoads'] as $page => $metrics) {
                $report[] = "  • $page:";
                $report[] = "    Время загрузки: {$metrics['time']} сек";
                $report[] = "    Размер: " . round($metrics['size']/1024, 2) . " KB";
            }
        }

        // Предупреждения
        if (!empty($this->analytics['warnings'])) {
            $report[] = "\n■ ПРЕДУПРЕЖДЕНИЯ:";
            foreach ($this->analytics['warnings'] as $warning) {
                $report[] = "  • $warning";
            }
        }

        // Ошибки
        if (!empty($this->analytics['errors'])) {
            $report[] = "\n■ ОШИБКИ:";
            foreach ($this->analytics['errors'] as $error) {
                $report[] = "  • $error";
            }
        }

        $report[] = "\n└─────────────────────── ТЕСТИРОВАНИЕ ЗАВЕРШЕНО ─────────────────┘";

        // Выводим отчет в консоль
        print("\n" . implode("\n", $report) . "\n");
    }

    private function generateSummary()
    {
        // Получаем список всех протестированных страниц
        $pages = [];
        foreach ($this->analytics['performanceMetrics'] as $metric) {
            $pageName = basename($metric['file'], '.php');
            if (!in_array($pageName, $pages)) {
                $pages[] = $pageName;
            }
        }

        $totalPages = count($pages);
        $totalLoadTime = array_sum(array_column($this->analytics['performanceMetrics'], 'loadTime'));
        $averageLoadTime = $totalPages > 0 ? $totalLoadTime / $totalPages : 0;

        // Находим самую быструю и медленную страницы
        $fastestPage = '';
        $slowestPage = '';
        $fastestTime = PHP_FLOAT_MAX;
        $slowestTime = 0;

        foreach ($this->analytics['performanceMetrics'] as $metric) {
            $pageName = basename($metric['file'], '.php');
            if ($metric['loadTime'] < $fastestTime) {
                $fastestTime = $metric['loadTime'];
                $fastestPage = $pageName;
            }
            if ($metric['loadTime'] > $slowestTime) {
                $slowestTime = $metric['loadTime'];
                $slowestPage = $pageName;
            }
        }

        // Подсчитываем ошибки и предупреждения
        $totalErrors = count($this->analytics['errors']);
        $totalWarnings = count($this->analytics['warnings']);

        // Рассчитываем оценки безопасности и производительности
        $securityScore = $totalPages > 0 ? max(0, 100 - ($totalErrors * 20)) : 0;
        $performanceScore = $totalPages > 0 ? min(100, max(0, 100 - ($averageLoadTime * 100))) : 0;

        return [
            'totalPages' => $totalPages,
            'averageLoadTime' => $averageLoadTime,
            'fastestPage' => $fastestPage,
            'slowestPage' => $slowestPage,
            'totalErrors' => $totalErrors,
            'totalWarnings' => $totalWarnings,
            'securityScore' => $securityScore,
            'performanceScore' => $performanceScore
        ];
    }
} 