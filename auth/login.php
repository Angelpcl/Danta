<?php
require_once '../config/database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new database();
    $conn = $db->connect();

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
    } catch (PDOException $e) {
        die("Error en la consulta: " . $e->getMessage());
    }

    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['rol_id'];

            // Redirección según rol
            if ($user['rol_id'] == 1) { // Admin
                header('Location: ../dashboard/admin/index.php');
            } else { // Usuario normal
                header('Location: ../dashboard/user/index.php');
            }
            exit();
        }
    }

    $error = "Usuario o contraseña incorrectos";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Danta 11</title>
    <link rel="stylesheet" href="../css/auth.css">
    <link rel="icon" href="../Imagens/logo/logo.png" type="imagen/x-icon">
</head>

<body>
    <div class="login-container">
        <div class="logo">
            <img src="../Imagens/logo/logo.png" alt="Danta 11">
        </div>

        <form class="login-form" action="login.php" method="POST">
            <h2>Iniciar Sesión</h2>

            <?php if (isset($error)): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn-login">Ingresar</button>

            <div class="footer-links">
                <a href="register.php">Crear cuenta</a>
                <a href="#">¿Olvidaste tu contraseña?</a>
            </div>
        </form>
    </div>
</body>

</html>