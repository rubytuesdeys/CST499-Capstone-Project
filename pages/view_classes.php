<?php
session_start();

require __DIR__ . '/../config/connect.php';

// Check if user is logged in and is a regular user
if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit;
}

if (($_SESSION['role'] ?? '') !== 'user') {
    echo 'Access denied. This page is for users only.';
    exit;
}

$userID = $_SESSION['userID'];

try {
    // Get user's name
    $userStmt = $conn->prepare(
        'SELECT first_name, last_name 
         FROM users 
         WHERE id = :id'
    );
    $userStmt->execute([
        ':id' => $userID
    ]);
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);

    // Get all enrolled classes
    $enrolledStmt = $conn->prepare(
        'SELECT c.id, c.class_code, c.class_name, c.instructor_name, e.status, e.enrollment_date, e.grade
         FROM classes c
         JOIN enrollments e ON c.id = e.class_id
         WHERE e.user_id = :user_id
         ORDER BY c.class_code ASC'
    );
    $enrolledStmt->execute([
        ':user_id' => $userID
    ]);
    $enrolled_classes = $enrolledStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log($e->getMessage());
    echo 'A database error occurred.';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CST499 View Enrolled Classes">
    <title>CST499 Website Project</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>

<body>
    <div class="nav_header">
        <div class="nav">
            <a href="index.php">Home</a>
            <a href="profile.php">Profile</a>
            <a href="add_drop_classes.php">Add/Drop Classes</a>
            <a href="contact.php">Contact Us</a>
            <a href="../function/logout.php">Logout</a>
        </div>
    </div>

    <div class="main">
        <h1>My Enrolled Classes</h1>

        <br><br>

        <?php if (count($enrolled_classes) > 0): ?>
            <p>You are enrolled in <?= count($enrolled_classes) ?> class(es).</p>
            
            <br>
            <?php foreach ($enrolled_classes as $class): ?>
                <div style="background: rgba(255, 255, 255, 0.15); padding: 15px; margin: 15px 0; border-radius: 8px; text-align: left;">
                    <h3><?= htmlspecialchars($class['class_code']) ?> - <?= htmlspecialchars($class['class_name']) ?></h3>
                    
                    <p>
                        <strong>Instructor:</strong> <?= htmlspecialchars($class['instructor_name']) ?><br>
                        <strong>Enrolled:</strong> <?= date('M d, Y', strtotime($class['enrollment_date'])) ?><br>
                        <strong>Status:</strong> <?= ucfirst($class['status']) ?>
                        <?php if ($class['grade']): ?>
                            <br>
                            <strong>Grade:</strong> <?= htmlspecialchars($class['grade']) ?>
                        <?php endif; ?>
                    </p>
                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <p>You are not enrolled in any classes.</p>
        <?php endif; ?>

        <br><br>
        <p>
            <a href="add_drop_classes.php">Add or Drop Classes</a>
        </p>
    </div>

    <div class="footer">
        <p>©Ruben Cruz-Colon 2026; CST499 Website Project</p>
    </div>
</body>
</html>