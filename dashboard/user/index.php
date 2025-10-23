<?php
require_once '../../config/database.php';
require_once '../../config/auth.php';

session_start();

$auth = new Auth();
$auth->checkAuth(); // Verifica autenticación

$db = new Database();
$conn = $db->connect();

// Verificamos que el ID exista en la sesión antes de usarlo
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mi Cuenta - Danta 11</title>
    <link rel="stylesheet" href="../../css/user.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="../../Imagens/logo/logo.png" type="imagen/x-icon">
</head>

<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Danta 11</h2>
                <p>Mi Cuenta</p>
            </div>
            <nav>
                <ul>
                    <li class="active"><a href="#"><i class="fas fa-user"></i> Perfil</a></li>
                    <li><a href="#"><i class="fas fa-calendar"></i> Mis Citas</a></li>
                    <li><a href="#"><i class="fas fa-cog"></i> Configuración</a></li>
                    <li><a href="../../index.html"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header>
                <h1>Bienvenido, <?= htmlspecialchars($user['nombre_completo']) ?></h1>
            </header>

            <section class="profile">
                <h2>Mi Perfil</h2>
                <div class="profile-info">
                    <a href="https://wa.me/522228012469" target="_blank" class="whatsapp-btn">
                        <img src="../../Imagens/Social/logos--whatsapp-icon.svg" alt="WhatsApp" class="whatsapp-icon">
                        Contactar por WhatsApp
                    </a>