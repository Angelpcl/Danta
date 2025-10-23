<?php
session_start();
require_once '../../Config/Connection.php';

$connection = new Connection();
$pdo = $connection->connect();

$username = $_POST['username'];
$password = $_POST['password'];

// Consulta preparada para evitar SQL injection
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['username'] = $user['username'];
    $_SESSION['role_id'] = $user['role_id'];
    $_SESSION['user_id'] = $user['id'];
    
    // Redirección según el rol
    switch ($user['role_id']) {
        case 1: // Admin
            header('Location: ../../dashboard/admin/index.php');
            break;
        case 2: // Secretaria
            header('Location: ../../dashboard/secretary/index.php');
            break;
        case 3: // Usuario normal
            header('Location: ../../dashboard/user/index.php');
            break;
        default:
            header('Location: ../../login/index.php?error=1');
            break;
    }
    exit();
} else {
    header('Location: ../../login/index.php?error=1');
    exit();
}
?>