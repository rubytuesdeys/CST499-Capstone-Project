<?php
session_start();

require __DIR__ . '/../config/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $_SESSION['error'] = 'Please complete all required fields.';
        header('Location: ' . BASE_URL . '/pages/login.php');
        exit;
    }

    try {
        $stmt = $conn->prepare(
            'SELECT id, email, password, role 
             FROM users 
             WHERE email = :email');
        $stmt->execute([
            ':email' => $email
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);

            $_SESSION['userID'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header('Location: ' . BASE_URL . '/admin/admin.php');
            } else {
                header('Location: ' . BASE_URL . '/pages/index.php');
            }
            exit;
        }

        $_SESSION['error'] = 'Invalid email or password.';
        header('Location: ' . BASE_URL . '/pages/login.php');
        exit;

    } catch (PDOException $e) {
        error_log($e->getMessage());
        $_SESSION['error'] = 'Database error. Please try again.';
        header('Location: ' . BASE_URL . '/pages/login.php');
        exit;
    }
}
?>