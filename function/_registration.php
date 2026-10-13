<?php
session_start();

require __DIR__ . '/../config/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if (
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        !preg_match('/^[A-Za-z]+\.[A-Za-z]+@Keystone\.student\.edu$/i', $email)
    ) {
         $_SESSION['error'] = 'Please use a valid @Keystone.student.edu email address.';
        header('Location: ' . BASE_URL . '/pages/registration.php');
        exit;
    }

    if ($password === '' || $first_name === '' || $last_name === '') {
        $_SESSION['error'] = 'Please complete all required fields.';
        header('Location: ' . BASE_URL . '/pages/registration.php');
        exit;
    }

    if (strlen($password) < 8) {
        $_SESSION['error'] = 'Password must be at least 8 characters long.';
        header('Location: ' . BASE_URL . '/pages/registration.php');
        exit;
    }

    if (!preg_match('/^\d{10}$/', preg_replace('/\D/', '', $phone))) {
        $_SESSION['error'] = 'Please enter a valid 10-digit phone number.';
        header('Location: ' . BASE_URL . '/pages/registration.php');
        exit;
    }

    try {
        $checkStmt = $conn->prepare(
            'SELECT id 
             FROM users 
             WHERE email = :email'
        );
        $checkStmt->execute([
            ':email' => $email
        ]);

        if ($checkStmt->rowCount() > 0) {
            $_SESSION['error'] = 'This email is already registered.';
            header('Location: ' . BASE_URL . '/pages/registration.php');
            exit;
        }

        $initial = mb_strtoupper(substr($first_name, 0, 2));
        $lastnamepart = mb_strtoupper(substr($last_name, 0, 4));
        $randomnum = rand(1000, 9999);
        $student_id = $initial . $lastnamepart . $randomnum; 

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = 'INSERT INTO users (email, password, first_name, last_name, address, phone, student_id)
                VALUES (:email, :password, :first_name, :last_name, :address, :phone, :student_id)';
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':password' => $hashedPassword,
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':address' => $address,
            ':phone' => $phone,
            ':student_id' => $student_id,
        ]);

        header('Location: ' . BASE_URL . '/function/reg_redirect.php');
        exit;

    } catch (PDOException $e) {
        error_log($e->getMessage());
        $_SESSION['error'] = 'Registration could not be completed.';
        header('Location: ' . BASE_URL . '/pages/registration.php');
        exit;
    }
}
?>