<?php
class Auth {
    public function checkAuth() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: ../auth/login.php');
            exit();
        }
    }

    public function checkAdmin() {
        $this->checkAuth();
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
            header('Location: ../auth/login.php?error=unauthorized');
            exit();
        }
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header('Location: ../auth/login.php');
        exit();
    }
}
