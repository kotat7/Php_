<?php
// Включаем отображение ошибок для отладки
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Устанавливаем кодировку
header('Content-Type: text/html; charset=utf-8');

// Стартуем сессию для хранения данных формы
session_start();

// Получаем сохраненные данные (если форма отправлялась с ошибками)
$form_data = $_SESSION['form_data'] ?? [];
$errors = $_SESSION['errors'] ?? [];

// Очищаем сессию после получения данных
unset($_SESSION['form_data']);
unset($_SESSION['errors']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Обратная связь</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            max-width: 550px;
            width: 100%;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 24px;
            text-align: center;
        }
        
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 25px;
            font-size: 14px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        
        .required {
            color: #e74c3c;
        }
        
        input[type="text"],
        input[type="email"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            font-family: inherit;
            transition: border-color 0.3s;
        }
        
        input:focus,
        select:focus,
        textarea:focus {
            border-color: #4CAF50;
            outline: none;
        }
        
        .error {
            border-color: #e74c3c !important;
        }
        
        .error-message {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }
        
        .hint {
            color: #888;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }
        
        button {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
            transition: background 0.3s;
        }
        
        button:hover {
            background: #45a049;
        }
        
        .rating-group {
            display: flex;
            gap: 10px;
            margin-top: 5px;
        }
        
        .rating-option {
            flex: 1;
            text-align: center;
        }
        
        .rating-option input {
            width: auto;
            margin-right: 5px;
        }
        
        select {
            background: white;
            cursor: pointer;
        }
        
        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #eee;
        }
        
        .info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 13px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📝 Обратная связь</h1>
        <div class="subtitle">Мы ценим ваше мнение!</div>
        
        <form action="process.php" method="POST">
            <!-- Поле: Имя -->
            <div class="form-group">
                <label>
                    Имя <span class="required">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       value="<?php echo htmlspecialchars($form_data['name'] ?? ''); ?>"
                       class="<?php echo isset($errors['name']) ? 'error' : ''; ?>"
                       placeholder="Введите ваше имя">
                <?php if (isset($errors['name'])): ?>
                    <span class="error-message">❌ <?php echo $errors['name']; ?></span>
                <?php endif; ?>
                <span class="hint">Минимум 2 символа (только буквы)</span>
            </div>
            
            <!-- Поле: Email -->
            <div class="form-group">
                <label>
                    Email <span class="required">*</span>
                </label>
                <input type="email" 
                       name="email" 
                       value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>"
                       class="<?php echo isset($errors['email']) ? 'error' : ''; ?>"
                       placeholder="example@mail.ru">
                <?php if (isset($errors['email'])): ?>
                    <span class="error-message">❌ <?php echo $errors['email']; ?></span>
                <?php endif; ?>
                <span class="hint">Пример: ivan@mail.ru</span>
            </div>
            
            <!-- Поле: Сообщение -->
            <div class="form-group">
                <label>
                    Сообщение <span class="required">*</span>
                </label>
                <textarea name="message" 
                          rows="5" 
                          class="<?php echo isset($errors['message']) ? 'error' : ''; ?>"
                          placeholder="Напишите ваше сообщение..."><?php echo htmlspecialchars($form_data['message'] ?? ''); ?></textarea>
                <?php if (isset($errors['message'])): ?>
                    <span class="error-message">❌ <?php echo $errors['message']; ?></span>
                <?php endif; ?>
                <span class="hint">Минимум 10 символов</span>
            </div>
            
            <!-- Поле: Рейтинг -->
            <div class="form-group">
                <label>
                    Оцените сайт <span class="required">*</span>
                </label>
                <select name="rating" class="<?php echo isset($errors['rating']) ? 'error' : ''; ?>">
                    <option value="">Выберите оценку</option>
                    <option value="5" <?php echo (($form_data['rating'] ?? '') == '5') ? 'selected' : ''; ?>>⭐⭐⭐⭐⭐ 5 - Отлично</option>
                    <option value="4" <?php echo (($form_data['rating'] ?? '') == '4') ? 'selected' : ''; ?>>⭐⭐⭐⭐ 4 - Хорошо</option>
                    <option value="3" <?php echo (($form_data['rating'] ?? '') == '3') ? 'selected' : ''; ?>>⭐⭐⭐ 3 - Нормально</option>
                    <option value="2" <?php echo (($form_data['rating'] ?? '') == '2') ? 'selected' : ''; ?>>⭐⭐ 2 - Плохо</option>
                    <option value="1" <?php echo (($form_data['rating'] ?? '') == '1') ? 'selected' : ''; ?>>⭐ 1 - Ужасно</option>
                </select>
                <?php if (isset($errors['rating'])): ?>
                    <span class="error-message">❌ <?php echo $errors['rating']; ?></span>
                <?php endif; ?>
                <span class="hint">Оцените работу сайта от 1 до 5</span>
            </div>
            
            <!-- Скрытое поле для отслеживания отправки -->
            <input type="hidden" name="submitted" value="1">
            
            <button type="submit">📨 Отправить сообщение</button>
        </form>
        
        <hr>
        
        <div class="info">
            💡 <strong>Подсказка:</strong><br>
            Все поля обязательны для заполнения.<br>
            Имя должно содержать минимум 2 буквы.<br>
            Email должен быть в формате name@domain.ru.<br>
            Сообщение — не менее 10 символов.
        </div>
    </div>
</body>
</html>