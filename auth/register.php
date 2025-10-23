<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $conn = $db->connect();

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);

    // Validaciones
    $errors = [];

    if (strlen($username) < 4) {
        $errors[] = "El usuario debe tener al menos 4 caracteres";
    }

    if (strlen($password) < 6) {
        $errors[] = "La contraseña debe tener al menos 6 caracteres";
    } else {
        $password = password_hash($password, PASSWORD_BCRYPT);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "El email no es válido";
    }

    if (empty($errors)) {
        $query = "INSERT INTO usuarios (username, password, email, telefono, rol_id) 
                  VALUES (:username, :password, :email, :telefono, 2)";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefono', $telefono);

        if ($stmt->execute()) {
            $_SESSION['register_success'] = true;
            header('Location: login.php');
            exit();
        } else {
            $errors[] = "Error al registrar el usuario";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Danta 11</title>
    <link rel="stylesheet" href="../css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="../Imagens/logo/logo.png" type="imagen/x-icon">
</head>
<body>
    <div class="auth-container">
        <div class="auth-logo">
            <img src="../Imagens/logo/Imagotipo_Danta11_RGB (1) (1).png" alt="Danta 11">
        </div>

        <div class="auth-form">
            <h2>Crear Cuenta</h2>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <?php foreach ($errors as $error): ?>
                        <p><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <div class="form-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" placeholder="Usuario" required>
                </div>

                <div class="form-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email" required>
                </div>

                <div class="form-group">
                    <i class="fas fa-phone"></i>
                    <input type="tel" name="telefono" placeholder="Teléfono" required>
                </div>

                <div class="form-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Contraseña" required>
                </div>

                <div class="form-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="confirm_password" placeholder="Confirmar Contraseña" required>
                </div>

                <button type="submit" class="btn-auth">Registrarse</button>

                <div class="auth-footer">
                    ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
