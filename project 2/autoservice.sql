-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Фев 07 2026 г., 10:37
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `autoservice`
--

-- --------------------------------------------------------

--
-- Структура таблицы `services`
--

CREATE TABLE `services` (
  `id` int NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `features` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `warranty_months` int NOT NULL,
  `icon_class` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `services`
--

INSERT INTO `services` (`id`, `category`, `name`, `description`, `price`, `features`, `warranty_months`, `icon_class`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'diagnostics', 'Диагностика двигателя', 'Когда необходима диагностика двигателя?\r\nСпециалисты ДВС сервис рекомендуют обращаться за помощью при появлении одного из признаков:\r\nРезкое снижение уровня масла;\r\nНеравномерная работа двигателя («троит» двигатель);\r\nПоявление посторонних шумов;\r\nУвеличение расхода топлива;\r\nПадение мощности двигателя;', '5700.00', '[\"\\u0431\\u044b\\u0441\\u0442\\u0440\\u043e\\r\",\"\\u043a\\u0430\\u0447\\u0435\\u0441\\u0442\\u0432\\u0435\\u043d\\u043d\\u043e\\r\",\"\\u0434\\u0435\\u0448\\u0435\\u0432\\u043e\"]', 6, 'fas fa-oil-can', 1, '2025-06-17 20:17:15', '2025-06-17 20:17:42'),
(3, 'maintenance', 'Проведение ТО', 'Пройти ТО автомобиля – это значит выполнить профилактические мероприятия, которые направлены на раннее предупреждение будущих неполадок автомобиля. Детали, агрегаты имеют срок службы, который напрямую зависит от своевременного обслуживания и профилактики.', '2300.00', '[\"\\u0431\\u044b\\u0441\\u0442\\u0440\\u043e\\r\",\"\\u043a\\u0430\\u0447\\u0435\\u0441\\u0442\\u0432\\u0435\\u043d\\u043d\\u043e\\r\",\"\\u0434\\u0435\\u0448\\u0435\\u0432\\u043e\"]', 9, 'fas fa-oil-can', 1, '2025-06-17 20:19:06', '2025-06-24 11:29:21'),
(4, 'diagnostics', 'Диагностика ходовой', 'Почему нельзя без диагностики\r\nХодовая часть работает в очень жестких условиях:\r\nПовышенные нагрузки, вибрации, постоянное движение. Элементы подвески быстро изнашиваются. Целый список деталей здесь считается расходными материалами.\r\nУдары, механические повреждения. Ямы, неровности, гравий, лед — ходовая первой встречает дорожные неприятности.', '4100.00', '[\"\\u0431\\u044b\\u0441\\u0442\\u0440\\u043e\\r\",\"\\u043a\\u0430\\u0447\\u0435\\u0441\\u0442\\u0432\\u0435\\u043d\\u043d\\u043e\\r\",\"\\u0434\\u0435\\u0448\\u0435\\u0432\\u043e\"]', 12, 'fas fa-oil-can', 1, '2025-06-17 20:21:30', '2025-06-17 20:21:30'),
(5, 'diagnostics', 'Электронная диагностика', 'Электронная (компьютерная) диагностика – крайне важная процедура. Она позволяет узнать отдельные параметры работы электронных систем управления, электронных узлов автомобиля, датчиков, индикаторов и модулей. Даёт возможность обнаружить ошибки и удалить или исправить их.', '4100.00', '[\"\\u0431\\u044b\\u0441\\u0442\\u0440\\u043e\\r\",\"\\u043a\\u0430\\u0447\\u0435\\u0441\\u0442\\u0432\\u0435\\u043d\\u043d\\u043e\\r\",\"\\u0434\\u0435\\u0448\\u0435\\u0432\\u043e\"]', 6, 'fas fa-oil-can', 1, '2025-06-17 20:22:43', '2025-06-17 20:22:43'),
(6, 'repair', 'Ремонт двигателя', 'Ремонт двигателя – процедура сложная и требующая больших временных затрат. Ее можно сравнить с хирургической операцией на сердце, с той лишь разницей, что спектр выполняемых операции намного шире. Однако ювелирная точность, аккуратность и высочайшая квалификация при ремонте ДВС необходима.', '9700.00', '[\"\\u0431\\u044b\\u0441\\u0442\\u0440\\u043e\\r\",\"\\u043a\\u0430\\u0447\\u0435\\u0441\\u0442\\u0432\\u0435\\u043d\\u043d\\u043e\\r\",\"\\u0434\\u0435\\u0448\\u0435\\u0432\\u043e\"]', 24, 'fas fa-oil-can', 1, '2025-06-17 20:23:37', '2025-06-17 20:23:37'),
(7, 'maintenance', 'Плановое техническое обслуживание', 'Обслуживание и диагностика автомобиля — залог его исправности, долговечности и безопасности. Техническое обслуживание имеет множество форм, но по определению означает проверку и тестирование систем вашего автомобиля, обслуживание или замену деталей и жидкостей, а также профилактический уход за автомобилем, позволяющий избежать серьезных повреждений и повысить безопасность.', '2500.00', '[\"\\u0431\\u044b\\u0441\\u0442\\u0440\\u043e\\r\",\"\\u043a\\u0430\\u0447\\u0435\\u0441\\u0442\\u0432\\u0435\\u043d\\u043d\\u043e\\r\",\"\\u0434\\u0435\\u0448\\u0435\\u0432\\u043e\"]', 6, 'fas fa-oil-can', 1, '2025-06-17 20:26:01', '2025-06-17 20:26:01'),
(8, 'repair', 'Ремонт ККП', 'Ремонт коробки передач — это комплекс мероприятий, направленных на устранение неисправностей и восстановление работоспособности коробки передач автомобиля. Цель ремонта — обеспечить нормальную работу коробки передач и продлить её срок службы.', '13100.00', '[\"\\u0431\\u044b\\u0441\\u0442\\u0440\\u043e\\r\",\"\\u043a\\u0430\\u0447\\u0435\\u0441\\u0442\\u0432\\u0435\\u043d\\u043d\\u043e\\r\",\"\\u0434\\u0435\\u0448\\u0435\\u0432\\u043e\"]', 24, 'fas fa-oil-can', 1, '2025-06-17 20:58:05', '2025-06-17 20:58:05'),
(9, 'repair', 'Ремонт трансмиссии', 'Ремонт трансмиссии включает диагностику, устранение неисправностей и профилактику поломок. Трансмиссия — ключевой узел автомобиля, который передаёт крутящий момент от двигателя к колёсам', '8500.00', '[\"\\u0431\\u044b\\u0441\\u0442\\u0440\\u043e\\r\",\"\\u043a\\u0430\\u0447\\u0435\\u0441\\u0442\\u0432\\u0435\\u043d\\u043d\\u043e\\r\",\"\\u0434\\u0435\\u0448\\u0435\\u0432\\u043e\"]', 24, 'fas fa-oil-can', 1, '2025-06-17 21:06:14', '2025-06-17 21:06:45'),
(10, 'maintenance', 'Замена масла и фильтров', 'Замена моторного масла и фильтров — важные процедуры, которые необходимо проводить регулярно для поддержания работоспособности и долговечности автомобиля', '990.00', '[\"\\u0431\\u044b\\u0441\\u0442\\u0440\\u043e\\r\",\"\\u043a\\u0430\\u0447\\u0435\\u0441\\u0442\\u0432\\u0435\\u043d\\u043d\\u043e\\r\",\"\\u0434\\u0435\\u0448\\u0435\\u0432\\u043e\"]', 8, 'fas fa-oil-can', 1, '2025-06-17 21:08:58', '2025-06-17 21:09:39');

-- --------------------------------------------------------

--
-- Структура таблицы `service_orders`
--

CREATE TABLE `service_orders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `car_id` int NOT NULL,
  `service_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','confirmed','in_progress','completed','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `appointment_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `stats`
--

CREATE TABLE `stats` (
  `id` int NOT NULL,
  `value` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `stats`
--

INSERT INTO `stats` (`id`, `value`, `label`, `description`, `sort_order`) VALUES
(1, '11+', 'Лет опыта', '', 1),
(2, '5000+', 'Довольных клиентов', '', 2),
(3, '50+', 'Профессионалов', '', 3),
(4, '24/7', 'Поддержка', '', 4);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('user','admin') COLLATE utf8mb4_unicode_ci DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_login` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `last_name`, `phone`, `role`, `created_at`, `updated_at`, `last_login`, `is_active`) VALUES
(5, 'admin@admin', '$2y$10$BpF4znFvPsqQhJ.E9fRDbeAEiD0JSyIb4pAzcM4WmiI1DzjhcRdyO', 'admin', 'admin', '7000000000', 'admin', '2025-06-15 20:45:34', '2025-06-15 20:45:34', '2025-06-15 20:45:34', 1),
(52, '1@gj.fg', '$2y$10$ye73t86sGmyKYlhQsPJJKOYmVbR2hxB7O2vKQswZ43HiwxIL7.cAu', 'вачтп', 'вапмит', '89999999999', 'admin', '2025-06-25 06:33:44', '2025-06-25 06:33:44', '2025-06-25 06:33:44', 1),
(53, 'testuser_685b9a75d2f6c@example.com', '$2y$10$Lr74NkIBTWdfZI2283kH6.sOJgCHH1EGYzwAS8bunQkA3v71CjuGu', 'Тест', 'Пользователь', '+79990001122', 'user', '2025-06-25 06:43:01', '2025-06-25 06:43:01', '2025-06-25 06:43:01', 1),
(54, 'danil@gmail.com', '$2y$10$eQz8oM44jjHz.zSyKla/luBvGS96ur6FKvUD84lYa9NkK6MZILpZq', 'Ольга', 'мещерякова', '89999999999', 'user', '2025-08-04 12:06:10', '2025-08-04 12:06:28', '2025-08-04 12:06:10', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `user_cars`
--

CREATE TABLE `user_cars` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `brand` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` int NOT NULL,
  `vin` varchar(17) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license_plate` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user_cars`
--

INSERT INTO `user_cars` (`id`, `user_id`, `brand`, `model`, `year`, `vin`, `license_plate`, `created_at`) VALUES
(6, 54, 'Lada Granta', '2016', 2016, '201620162016', '20162016', '2025-08-04 12:06:53');

-- --------------------------------------------------------

--
-- Структура таблицы `vacancies`
--

CREATE TABLE `vacancies` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `salary` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employment_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `experience` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `requirements` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `conditions` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `vacancies`
--

INSERT INTO `vacancies` (`id`, `title`, `salary`, `employment_type`, `experience`, `description`, `requirements`, `conditions`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Автомеханик', 'от 72 000 руб. / месяц', 'Полная занятость', 'Опыт от 2 лет', 'Обслуживание и ремонт автомобилей различных марок. Диагностика неисправностей, проведение ТО, замена узлов и агрегатов. Работа с современным оборудованием.', 'профильное образование, опыт работы от 2 лет, ответственность, аккуратность', 'график 2/2, оформление по ТК РФ, обучение за счет компании', 1, '2025-06-17 21:27:36', '2025-06-24 11:30:02'),
(2, 'Автоэлектрик-диагност', 'от 80 000 руб. / месяц', 'Полная занятость', 'Опыт от 3 лет', 'Диагностика и ремонт электрооборудования автомобилей, поиск и устранение неисправностей, установка дополнительного оборудования.', 'опыт работы с электрикой, знание современных систем, внимательность', 'график 5/2, премии за результат, обучение', 1, '2025-06-17 21:27:36', '2025-06-17 21:27:36'),
(3, 'Маляр по кузовным работам', 'от 75 000 руб. / месяц', 'Сменный график', 'Опыт от 1 года', 'Подготовка и покраска кузовных элементов, подбор цвета, устранение дефектов поверхности, работа с современными материалами.', 'опыт работы, аккуратность, знание технологий покраски', 'сменный график, современная камера, премии', 1, '2025-06-17 21:27:36', '2025-06-17 21:27:36'),
(4, 'Администратор автосервиса', 'от 50 000 руб. / месяц', 'Гибкий график', 'Без опыта', 'Встреча клиентов, оформление заказов, ведение документации, координация работы мастеров, консультации по услугам.', 'грамотная речь, стрессоустойчивость, желание учиться', 'гибкий график, обучение, карьерный рост', 1, '2025-06-17 21:27:36', '2025-06-17 21:27:36');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `service_orders`
--
ALTER TABLE `service_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_id` (`car_id`),
  ADD KEY `idx_orders_user` (`user_id`),
  ADD KEY `idx_orders_status` (`status`),
  ADD KEY `idx_orders_date` (`appointment_date`);

--
-- Индексы таблицы `stats`
--
ALTER TABLE `stats`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_user_email` (`email`),
  ADD KEY `idx_user_phone` (`phone`);

--
-- Индексы таблицы `user_cars`
--
ALTER TABLE `user_cars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `vacancies`
--
ALTER TABLE `vacancies`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `services`
--
ALTER TABLE `services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `service_orders`
--
ALTER TABLE `service_orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `stats`
--
ALTER TABLE `stats`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT для таблицы `user_cars`
--
ALTER TABLE `user_cars`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `vacancies`
--
ALTER TABLE `vacancies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `service_orders`
--
ALTER TABLE `service_orders`
  ADD CONSTRAINT `service_orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_orders_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `user_cars` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_cars`
--
ALTER TABLE `user_cars`
  ADD CONSTRAINT `user_cars_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
