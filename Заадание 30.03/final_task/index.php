<?php
require_once 'functions.php';

$user = getCurrentUser();
$theme = $user ? $user['theme'] : 'light';
$recent_messages = array_slice(getAllMessages(), 0, 5);

$bg    = $theme === 'dark' ? '#1a1a2e' : '#f0f2f5';
$color = $theme === 'dark' ? '#eee' : '#333';
$card  = $theme === 'dark' ? '#16213e' : 'white';
$nav_a = $theme === 'dark' ? '#eee' : '#555';
$border = $theme === 'dark' ? '#2a2a3e' : '#eee';
$footer_bg = $theme === 'dark' ? '#16213e' : '#f8f9fa';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: <?php echo $bg; ?>; color: <?php echo $color; ?>; transition: all 0.3s; min-height: 100vh; }
        .header { background: <?php echo $card; ?>; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 15px 0; position: sticky; top: 0; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .nav { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
        .logo { font-size: 24px; font-weight: bold; color: #667eea; }
        .nav-links { display: flex; gap: 20px; align-items: center; flex-wrap: wrap; }
        .nav-links a { color: <?php echo $nav_a; ?>; text-decoration: none; }
        .nav-links a:hover { color: #667eea; }
        .btn { padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: bold; }
        .btn-primary { background: #667eea; color: white; }
        .btn-outline { border: 1px solid #667eea; color: #667eea; }
        .main { padding: 40px 0; }
        .welcome-card, .messages-card, .info-card { background: <?php echo $card; ?>; border-radius: 15px; padding: 25px; margin-bottom: 30px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        h2 { margin-bottom: 20px; color: #667eea; }
        .message-item { border-bottom: 1px solid <?php echo $border; ?>; padding: 15px 0; }
        .message-item:last-child { border-bottom: none; }
        .message-header { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; color: #888; }
        .message-text { margin: 10px 0; line-height: 1.5; }
        .rating { color: #ffc107; font-size: 16px; letter-spacing: 2px; }
        .footer { background: <?php echo $footer_bg; ?>; text-align: center; padding: 20px; margin-top: 40px; font-size: 14px; }
        @media (max-width: 768px) { .nav { flex-direction: column; text-align: center; } .nav-links { justify-content: center; } }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="nav">
                <div class="logo">💬 Feedback System</div>
                <div class="nav-links">
                    <a href="index.php">Главная</a>
                    <?php if ($user): ?>
                        <a href="feedback.php">📝 Оставить отзыв</a>
                        <a href="history.php">📋 Мои отзывы</a>
                        <a href="logout.php" class="btn btn-outline">🚪 Выйти</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline">Войти</a>
                        <a href="register.php" class="btn btn-primary">Регистрация</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="main">
        <div class="container">
            <?php if ($user): ?>
                <div class="welcome-card">
                    <h2>👋 Добро пожаловать, <?php echo htmlspecialchars($user['name']); ?>!</h2>
                    <p>Вы вошли в систему как <?php echo htmlspecialchars($user['login']); ?></p>
                    <p>Ваш email: <?php echo htmlspecialchars($user['email']); ?></p>
                    <p>Дата регистрации: <?php echo $user['created_at']; ?></p>
                    <div style="margin-top: 15px;">
                        <a href="feedback.php" class="btn btn-primary">✍️ Оставить отзыв</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="welcome-card">
                    <h2>👋 Добро пожаловать!</h2>
                    <p>Это система обратной связи. Здесь вы можете оставить свой отзыв о нашем сайте.</p>
                    <p>Чтобы оставить отзыв или посмотреть историю сообщений, пожалуйста, <a href="login.php">войдите</a> или <a href="register.php">зарегистрируйтесь</a>.</p>
                </div>
            <?php endif; ?>

            <div class="messages-card">
                <h2>📢 Последние отзывы</h2>
                <?php if (empty($recent_messages)): ?>
                    <p>Пока нет ни одного отзыва. Будьте первым!</p>
                <?php else: ?>
                    <?php foreach ($recent_messages as $msg): ?>
                        <div class="message-item">
                            <div class="message-header">
                                <span>👤 <?php echo htmlspecialchars($msg['name']); ?></span>
                                <span>📅 <?php echo $msg['timestamp']; ?></span>
                            </div>
                            <div class="rating">
                                <?php for ($i = 1; $i <= 5; $i++) echo $i <= $msg['rating'] ? '★' : '☆'; ?>
                            </div>
                            <div class="message-text"><?php echo nl2br(htmlspecialchars($msg['message'])); ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="info-card">
                <h2>ℹ️ О системе</h2>
                <p>Эта система демонстрирует работу:</p>
                <ul style="margin-left: 20px; margin-top: 10px;">
                    <li>✅ Обработки форм (POST/GET)</li>
                    <li>✅ Валидации данных</li>
                    <li>✅ Сессий (авторизация)</li>
                    <li>✅ Cookie (запоминание пользователя)</li>
                    <li>✅ Работы с файловой системой</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="container">
            <p>Система обратной связи &copy; 2025 | Работа с формами, сессиями и куки</p>
        </div>
    </div>
</body>
</html>
