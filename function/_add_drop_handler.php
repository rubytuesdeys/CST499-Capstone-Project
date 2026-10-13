<?php
session_start();

require __DIR__ . '/../config/connect.php';

// Check if user is logged in and is a regular user
if (!isset($_SESSION['userID'])) {
    $_SESSION['error'] = 'You must be logged in to add or drop classes.';
    header('Location: ' . BASE_URL . '/pages/add_drop_classes.php');
    exit;
}

// Only regular users can add/drop
if (($_SESSION['role'] ?? '') !== 'user') {
    $_SESSION['error'] = 'Access denied.';
    header('Location: ' . BASE_URL . '/pages/add_drop_classes.php');
    exit;
}

$userID = $_SESSION['userID'];
$class_id = $_POST['class_id'] ?? null;
$action = $_POST['action'] ?? null;

// Validate input
if (!$class_id || !$action || !in_array($action, ['add', 'drop'])) {
    $_SESSION['error'] = 'Invalid request.';
    header('Location: ' . BASE_URL . '/pages/add_drop_classes.php');
    exit;
}

try {
    if ($action === 'add') {
        // Check if class exists
        $classStmt = $conn->prepare(
            'SELECT id, class_code, capacity, enrolled_count
             FROM classes
             WHERE id = :id'
        );
        $classStmt->execute([
            ':id' => $class_id
        ]);
        $class = $classStmt->fetch(PDO::FETCH_ASSOC);

        if (!$class) {
            $_SESSION['error'] = 'Class not found.';
            header('Location: ' . BASE_URL . '/pages/add_drop_classes.php');
            exit;
        }

        // Check if already enrolled
        $checkStmt = $conn->prepare(
            'SELECT id 
             FROM enrollments 
             WHERE user_id = :user_id 
             AND class_id = :class_id'
        );
        $checkStmt->execute([
            ':user_id' => $userID, 
            ':class_id' => $class_id]);

        if ($checkStmt->fetch(PDO::FETCH_ASSOC)) {
            $_SESSION['error'] = 'You are already enrolled in this class.';
            header('Location: ' . BASE_URL . '/pages/add_drop_classes.php');
            exit;
        }

        // Determine status: active if space available, waitlisted if full
        $status = ($class['enrolled_count'] < $class['capacity']) ? 'active' : 'waitlisted';

        // Add enrollment
        $enrollStmt = $conn->prepare(
            'INSERT INTO enrollments (user_id, class_id, status)
             VALUES (:user_id, :class_id, :status)'
        );
        $enrollStmt->execute([
            ':user_id' => $userID,
            ':class_id' => $class_id,
            ':status' => $status
        ]);

        // Update enrolled_count if added as active
        if ($status === 'active') {
            $updateStmt = $conn->prepare(
                'UPDATE classes 
                 SET enrolled_count = enrolled_count + 1 
                 WHERE id = :id'
            );
            $updateStmt->execute([
                ':id' => $class_id
            ]);

            $_SESSION['success'] = 'Successfully added to ' . $class['class_code'] . '!';
        } else {
            $_SESSION['success'] = 'You have been added to the waitlist for ' . $class['class_code'] . '.';
        }

    } elseif ($action === 'drop') {
        // Check if enrolled
        $enrollStmt = $conn->prepare(
            'SELECT status 
             FROM enrollments 
             WHERE user_id = :user_id 
             AND class_id = :class_id'
        );
        $enrollStmt->execute([
            ':user_id' => $userID,
            ':class_id' => $class_id
        ]);
        $enrollment = $enrollStmt->fetch(PDO::FETCH_ASSOC);

        if (!$enrollment) {
            $_SESSION['error'] = 'You are not enrolled in this class.';
            header('Location: ' . BASE_URL . '/pages/add_drop_classes.php');
            exit;
        }

        // Get class info
        $classStmt = $conn->prepare(
            'SELECT class_code 
             FROM classes 
             WHERE id = :id'
        );
        $classStmt->execute([
            ':id' => $class_id
        ]);
        $class = $classStmt->fetch(PDO::FETCH_ASSOC);

        // Delete enrollment
        $deleteStmt = $conn->prepare(
            'DELETE FROM enrollments 
             WHERE user_id = :user_id 
             AND class_id = :class_id'
        );
        $deleteStmt->execute([
            ':user_id' => $userID,
            ':class_id' => $class_id
        ]);

        // Update enrolled_count if was active
        if ($enrollment['status'] === 'active') {
            $updateStmt = $conn->prepare(
                'UPDATE classes 
                 SET enrolled_count = enrolled_count - 1 
                 WHERE id = :id'
            );
            $updateStmt->execute([
                ':id' => $class_id
            ]);
        }

        $_SESSION['success'] = 'Successfully dropped ' . $class['class_code'] . '.';
    }

} catch (PDOException $e) {
    error_log($e->getMessage());
    $_SESSION['error'] = 'A database error occurred.';
}

header('Location: ' . BASE_URL . '/pages/add_drop_classes.php');
exit;
?>
