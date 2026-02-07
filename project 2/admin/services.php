<?php
require_once '../config/database.php';
require_once '../includes/auth_check.php';

// Проверка прав администратора
if (!isAdmin()) {
    header('Location: ../auth.php');
    exit();
}

// Обработка добавления/редактирования услуги
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $category = $_POST['category'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $features = json_encode($_POST['features']);
        $warranty_months = $_POST['warranty_months'];
        $icon_class = $_POST['icon_class'];
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        if ($_POST['action'] === 'add') {
            $stmt = $pdo->prepare("INSERT INTO services (category, name, description, price, features, warranty_months, icon_class, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$category, $name, $description, $price, $features, $warranty_months, $icon_class, $is_active]);
        } elseif ($_POST['action'] === 'edit' && isset($_POST['id'])) {
            $stmt = $pdo->prepare("UPDATE services SET category = ?, name = ?, description = ?, price = ?, features = ?, warranty_months = ?, icon_class = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$category, $name, $description, $price, $features, $warranty_months, $icon_class, $is_active, $_POST['id']]);
        }
        header('Location: services.php');
        exit();
    }
}

// Обработка удаления услуги
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header('Location: services.php');
    exit();
}

// Получение списка услуг
$stmt = $pdo->query("SELECT * FROM services ORDER BY category, name");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Категории услуг
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
    <title>Управление услугами - Админ панель</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Боковое меню -->
            <?php include 'includes/sidebar.php'; ?>

            <!-- Основной контент -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <h1 class="h2 mb-4">Управление услугами</h1>

                <!-- Кнопка добавления услуги -->
                <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#serviceModal">
                    <i class="fas fa-plus"></i> Добавить услугу
                </button>

                <!-- Таблица услуг -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
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
                            <?php foreach ($services as $service): ?>
                            <tr>
                                <td><?php echo $service['id']; ?></td>
                                <td><?php echo $categories[$service['category']]; ?></td>
                                <td><?php echo htmlspecialchars($service['name']); ?></td>
                                <td><?php echo number_format($service['price'], 0, '.', ' '); ?> ₽</td>
                                <td><?php echo $service['warranty_months']; ?> мес.</td>
                                <td>
                                    <?php if ($service['is_active']): ?>
                                        <span class="badge bg-success">Активна</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Неактивна</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary edit-service" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#serviceModal"
                                            data-service='<?php echo json_encode($service); ?>'>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="?delete=<?php echo $service['id']; ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Вы уверены, что хотите удалить эту услугу?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <!-- Модальное окно добавления/редактирования услуги -->
    <div class="modal fade" id="serviceModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Добавление услуги</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="serviceForm" method="POST">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="id" value="">
                        
                        <div class="mb-3">
                            <label class="form-label">Категория</label>
                            <select name="category" class="form-select" required>
                                <?php foreach ($categories as $key => $value): ?>
                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Название</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Описание</label>
                            <textarea name="description" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Цена (₽)</label>
                            <input type="number" name="price" class="form-control" required min="0">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Особенности (по одной на строку)</label>
                            <textarea name="features" class="form-control" rows="4" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Срок гарантии (месяцев)</label>
                            <input type="number" name="warranty_months" class="form-control" required min="0">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Класс иконки (Font Awesome)</label>
                            <input type="text" name="icon_class" class="form-control" required>
                            <small class="text-muted">Например: fas fa-oil-can</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" class="form-check-input" id="isActive" checked>
                                <label class="form-check-label" for="isActive">Активна</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" form="serviceForm" class="btn btn-primary">Сохранить</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Обработка редактирования услуги
        document.querySelectorAll('.edit-service').forEach(button => {
            button.addEventListener('click', function() {
                const service = JSON.parse(this.dataset.service);
                const form = document.getElementById('serviceForm');
                
                form.querySelector('[name="action"]').value = 'edit';
                form.querySelector('[name="id"]').value = service.id;
                form.querySelector('[name="category"]').value = service.category;
                form.querySelector('[name="name"]').value = service.name;
                form.querySelector('[name="description"]').value = service.description;
                form.querySelector('[name="price"]').value = service.price;
                form.querySelector('[name="features"]').value = JSON.parse(service.features).join('\n');
                form.querySelector('[name="warranty_months"]').value = service.warranty_months;
                form.querySelector('[name="icon_class"]').value = service.icon_class;
                form.querySelector('[name="is_active"]').checked = service.is_active == 1;
                
                document.querySelector('.modal-title').textContent = 'Редактирование услуги';
            });
        });

        // Сброс формы при закрытии модального окна
        document.getElementById('serviceModal').addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('serviceForm');
            form.reset();
            form.querySelector('[name="action"]').value = 'add';
            form.querySelector('[name="id"]').value = '';
            document.querySelector('.modal-title').textContent = 'Добавление услуги';
        });
    </script>
</body>
</html> 