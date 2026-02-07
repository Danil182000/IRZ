<?php
// Определяем текущую страницу для подсветки активного пункта меню
$current_page = basename($_SERVER['PHP_SELF']);
?>
<header class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-car-side me-2"></i>Автосервис
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'index.php' ? 'active' : ''; ?>" href="index.php">
                        <i class="fas fa-home me-1"></i>Главная
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'services.php' ? 'active' : ''; ?>" href="services.php">
                        <i class="fas fa-tools me-1"></i>Услуги
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'vacancies.php' ? 'active' : ''; ?>" href="vacancies.php">
                        <i class="fas fa-briefcase me-1"></i>Вакансии
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'faq.php' ? 'active' : ''; ?>" href="faq.php">
                        <i class="fas fa-question-circle me-1"></i>FAQ
                    </a>
                </li>
            </ul>
            <div class="navbar-nav">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>Личный кабинет
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="profile.php">
                                    <i class="fas fa-user me-2"></i>Профиль
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="profile.php#orders">
                                    <i class="fas fa-clipboard-list me-2"></i>Мои заказы
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="logout.php">
                                    <i class="fas fa-sign-out-alt me-2"></i>Выйти
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a class="nav-link <?php echo $current_page === 'auth.php' ? 'active' : ''; ?>" href="auth.php">
                        <i class="fas fa-sign-in-alt me-1"></i>Войти
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header> 