<?php
require_once '../../config/database.php';
require_once '../../config/auth.php';

$auth = new Auth();
$auth->checkAdmin(); // Verifica autenticación y rol admin

$db = new Database();
$conn = $db->connect();

// Obtener datos para el dashboard
$stmt = $conn->query("SELECT COUNT(*) as total FROM usuarios");
$total_users = $stmt->fetch()['total'];

$stmt = $conn->query("SELECT * FROM usuarios ORDER BY creado_en DESC LIMIT 5");
$recent_users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Danta 11</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="icon" href="Imagens/logo/logo.png" type="imagen/x-icon">
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Danta 11</h2>
                <p>Panel de Administración</p>
            </div>
            <nav>
                <ul>
                    <li class="active"><a href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="#"><i class="fas fa-users"></i> Usuarios</a></li>
                    <li><a href="#"><i class="fas fa-calendar-alt"></i> Citas</a></li>
                    <li><a href="#"><i class="fas fa-cog"></i> Configuración</a></li>
                    <li><a href="../../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header>
                <h1>Bienvenido, <?php echo $_SESSION['username']; ?></h1>
            </header>

            <div class="stats">
                <div class="stat-card">
                    <h3><?php echo $total_users; ?></h3>
                    <p>Usuarios Registrados</p>
                </div>
            </div>

            <section class="recent-users">
                <h2>Usuarios Recientes</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Fecha Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo $user['creado_en']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>