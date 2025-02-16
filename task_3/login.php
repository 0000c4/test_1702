<?php
session_start();

$users = [
    'admin' => '1234'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (isset($users[$username]) && $users[$username] === $password) {
        $_SESSION['user'] = $username;
        header('Location: stats.php');
        exit();
    } else {
        $error = "Неверное имя пользователя или пароль";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Авторизация</title>
</head>
<body>
    <h2>Вход в систему</h2>
    <?php if(isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
    <form method="POST" action="">
        <label>Имя пользователя:
            <input type="text" name="username" required>
        </label><br><br>
        <label>Пароль:
            <input type="password" name="password" required>
        </label><br><br>
        <button type="submit">Войти</button>
    </form>
</body>
</html>