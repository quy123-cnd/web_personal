

<?php

session_start();

require "connect.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {

        $error = "Vui lòng nhập đầy đủ thông tin.";

    } else {

        $stmt = $conn->prepare(
            "SELECT id, username, password_hash
             FROM admins
             WHERE username = ?
             LIMIT 1"
        );

        $stmt->execute([$username]);

        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin["password_hash"])) {

            $_SESSION["admin_id"] = $admin["id"];
            $_SESSION["admin_username"] = $admin["username"];

            header("Location: admin.php");
            exit;

        } else {

            $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
        }

        $stmt = null;
    }
}

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Đăng nhập</title>

    <link rel="stylesheet" href="css/login.css?v=2">
</head>

<body>

    <div class="login-container">
        <h1>Đăng nhập Admin</h1>
        <h3>Đăng nhập để quản lý website</h3>
        <?php if ($error !== ""): ?>
            <p class="login-error">
                <?= htmlspecialchars($error) ?>
            </p>

        <?php endif; ?>

        <h2>ĐĂNG NHẬP</h2>

        <form method="POST" action="">

            <label for="username">Tên người dùng:</label>
            <input type="text" id="username" name="username" required><br>

            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" required><br>

            <button type="submit">Đăng nhập</button>

        </form>

    </div>

    </body>
</html>
