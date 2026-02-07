-- Создание базы данных
CREATE DATABASE IF NOT EXISTS x_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE x_test;

-- Таблица пользователей
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB;

-- Таблица автомобилей пользователей
CREATE TABLE IF NOT EXISTS user_cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    brand VARCHAR(100) NOT NULL,
    model VARCHAR(100) NOT NULL,
    year INT NOT NULL,
    vin VARCHAR(17),
    license_plate VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Таблица заказов на услуги
CREATE TABLE IF NOT EXISTS service_orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    car_id INT NOT NULL,
    service_type VARCHAR(100) NOT NULL,
    description TEXT,
    status ENUM('pending', 'confirmed', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    appointment_date DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (car_id) REFERENCES user_cars(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Таблица истории обслуживания
CREATE TABLE IF NOT EXISTS service_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    service_description TEXT NOT NULL,
    parts_used TEXT,
    cost DECIMAL(10,2) NOT NULL,
    technician_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES service_orders(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Таблица услуг
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(100) NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    features TEXT NOT NULL,
    warranty_months INT NOT NULL,
    icon_class VARCHAR(50) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Таблица вакансий
CREATE TABLE IF NOT EXISTS vacancies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    salary VARCHAR(100) NOT NULL,
    employment_type VARCHAR(100) NOT NULL,
    experience VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    requirements TEXT NOT NULL,
    conditions TEXT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Таблица статистики для главной страницы
CREATE TABLE IF NOT EXISTS stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    value VARCHAR(32) NOT NULL,
    label VARCHAR(128) NOT NULL,
    description VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0
) ENGINE=InnoDB;

-- Примеры начальных данных для stats
INSERT INTO stats (value, label, description, sort_order) VALUES
('10+', 'Лет опыта', '', 1),
('5000+', 'Довольных клиентов', '', 2),
('50+', 'Профессионалов', '', 3),
('24/7', 'Поддержка', '', 4);

-- Примеры начальных данных для вакансий
INSERT INTO vacancies (title, salary, employment_type, experience, description, requirements, conditions) VALUES
('Автомеханик', 'от 70 000 руб. / месяц', 'Полная занятость', 'Опыт от 2 лет', 'Обслуживание и ремонт автомобилей различных марок. Диагностика неисправностей, проведение ТО, замена узлов и агрегатов. Работа с современным оборудованием.', 'профильное образование, опыт работы от 2 лет, ответственность, аккуратность', 'график 2/2, оформление по ТК РФ, обучение за счет компании'),
('Автоэлектрик-диагност', 'от 80 000 руб. / месяц', 'Полная занятость', 'Опыт от 3 лет', 'Диагностика и ремонт электрооборудования автомобилей, поиск и устранение неисправностей, установка дополнительного оборудования.', 'опыт работы с электрикой, знание современных систем, внимательность', 'график 5/2, премии за результат, обучение'),
('Маляр по кузовным работам', 'от 75 000 руб. / месяц', 'Сменный график', 'Опыт от 1 года', 'Подготовка и покраска кузовных элементов, подбор цвета, устранение дефектов поверхности, работа с современными материалами.', 'опыт работы, аккуратность, знание технологий покраски', 'сменный график, современная камера, премии'),
('Администратор автосервиса', 'от 50 000 руб. / месяц', 'Гибкий график', 'Без опыта', 'Встреча клиентов, оформление заказов, ведение документации, координация работы мастеров, консультации по услугам.', 'грамотная речь, стрессоустойчивость, желание учиться', 'гибкий график, обучение, карьерный рост');

-- Индексы для оптимизации
CREATE INDEX idx_user_email ON users(email);
CREATE INDEX idx_user_phone ON users(phone);
CREATE INDEX idx_orders_user ON service_orders(user_id);
CREATE INDEX idx_orders_status ON service_orders(status);
CREATE INDEX idx_orders_date ON service_orders(appointment_date); 