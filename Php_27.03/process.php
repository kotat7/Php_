<?php
// Включаем отображение ошибок для отладки
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Устанавливаем кодировку
header('Content-Type: text/html; charset=utf-8');

// Стартуем сессию
session_start();

// ============================================
// ПРОВЕРКА: была ли отправлена форма?
// ============================================
if (!isset($_POST['submitted'])) {
    // Если форма не отправлена, перенаправляем на форму
    header('Location: index.php');
    exit();
}

// ============================================
// ПОЛУЧАЕМ И ОЧИЩАЕМ ДАННЫЕ
// ============================================
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');
$rating = $_POST['rating'] ?? '';

// Сохраняем введенные данные в сессию (на случай ошибки)
$_SESSION['form_data'] = [
    'name' => $name,
    'email' => $email,
    'message' => $message,
    'rating' => $rating
];

// ============================================
// МАССИВ ДЛЯ ОШИБОК
// ============================================
$errors = [];

// ============================================
// 1. ПРОВЕРКА ИМЕНИ
// ============================================
if (empty($name)) {
    $errors['name'] = 'Имя обязательно для заполнения';
} elseif (strlen($name) < 2) {
    $errors['name'] = 'Имя должно содержать минимум 2 символа';
} elseif (!preg_match('/^[a-zA-Zа-яА-Я\s-]+$/u', $name)) {
    $errors['name'] = 'Имя может содержать только буквы, пробелы и дефисы';
}

// ============================================
// 2. ПРОВЕРКА EMAIL
// ============================================
if (empty($email)) {
    $errors['email'] = 'Email обязателен для заполнения';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Введите корректный email (например, name@domain.ru)';
} elseif (strpos($email, '@') === false || strpos($email, '.') === false) {
    $errors['email'] = 'Email должен содержать символ @ и точку';
}

// ============================================
// 3. ПРОВЕРКА СООБЩЕНИЯ
// ============================================
if (empty($message)) {
    $errors['message'] = 'Сообщение обязательно для заполнения';
} elseif (strlen($message) < 10) {
    $errors['message'] = 'Сообщение должно содержать минимум 10 символов';
}

// ============================================
// 4. ПРОВЕРКА РЕЙТИНГА
// ============================================
if (empty($rating)) {
    $errors['rating'] = 'Пожалуйста, выберите оценку';
} elseif (!in_array($rating, ['1', '2', '3', '4', '5'])) {
    $errors['rating'] = 'Выберите корректную оценку (1-5)';
}

// ============================================
// 5. ЕСЛИ ЕСТЬ ОШИБКИ - ВОЗВРАЩАЕМ НА ФОРМУ
// ============================================
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location: index.php');
    exit();
}

// ============================================
// 6. ВСЕ ПРОВЕРКИ ПРОЙДЕНЫ - ПОКАЗЫВАЕМ СТРАНИЦУ УСПЕХА
// ============================================

// Очищаем данные формы из сессии
unset($_SESSION['form_data']);

// Текст благодарности в зависимости от рейтинга
$rating_text = [
    '5' => 'Отлично! Спасибо за высокую оценку! 🎉',
    '4' => 'Хорошо! Спасибо за отзыв! 😊',
    '3' => 'Нормально! Расскажите, что можно улучшить? 🤔',
    '2' => 'Жаль, что вам не понравилось. Напишите, что исправить? 🙏',
    '1' => 'Очень жаль! Пожалуйста, напишите подробнее, что не так? 😔'
];

$thanks_message = $rating_text[$rating] ?? 'Спасибо за ваш отзыв!';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сообщение отправлено</title>
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
            text-align: center;
        }
        
        .success-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        
        h1 {
            color: #4CAF50;
            margin-bottom: 20px;
        }
        
        .message-box {
            background: #e8f5e9;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: left;
        }
        
        .message-box p {
            margin: 10px 0;
            line-height: 1.6;
        }
        
        .data-item {
            background: #f5f5f5;
            padding: 8px 12px;
            margin: 8px 0;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
        }
        
        .data-label {
            font-weight: bold;
            color: #555;
        }
        
        .data-value {
            color: #333;
            word-break: break-word;
            max-width: 60%;
            text-align: right;
        }
        
        .rating-stars {
            font-size: 24px;
            letter-spacing: 5px;
        }
        
        .btn {
            display: inline-block;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: bold;
            transition: background 0.3s;
        }
        
        .btn:hover {
            background: #45a049;
        }
        
        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #eee;
        }
        
        .footer {
            font-size: 12px;
            color: #888;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">
            ✅
        </div>
        
        <h1>Сообщение отправлено!</h1>
        
        <div class="message-box">
            <p><?php echo $thanks_message; ?></p>
            <p>Мы обязательно прочитаем ваше сообщение и учтем ваше мнение.</p>
        </div>
        
        <div class="data-item">
            <span class="data-label">👤 Имя:</span>
            <span class="data-value"><?php echo htmlspecialchars($name); ?></span>
        </div>
        
        <div class="data-item">
            <span class="data-label">📧 Email:</span>
            <span class="data-value"><?php echo htmlspecialchars($email); ?></span>
        </div>
        
        <div class="data-item">
            <span class="data-label">⭐ Рейтинг:</span>
            <span class="data-value">
                <?php 
                $stars = '';
                for ($i = 1; $i <= 5; $i++) {
                    $stars .= $i <= $rating ? '★' : '☆';
                }
                echo $stars . ' (' . $rating . '/5)';
                ?>
            </span>
        </div>
        
        <div class="data-item">
            <span class="data-label">💬 Сообщение:</span>
            <span class="data-value"><?php echo nl2br(htmlspecialchars($message)); ?></span>
        </div>
        
        <hr>
        
        <a href="index.php" class="btn">📝 Написать еще сообщение</a>
        
        <div class="footer">
            <p>Ваше сообщение было успешно отправлено.<br>
            Мы свяжемся с вами по указанному email при необходимости.</p>
        </div>
    </div>
</body>
</html>