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
$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error']);
unset($_SESSION['success']);

try {
    // Get all classes for the current semester
    $classStmt = $conn->prepare(
        'SELECT id, class_code, class_name, instructor_name, capacity, enrolled_count
         FROM classes
         WHERE semester = :semester
         ORDER BY class_code ASC'
    );
    $classStmt->execute([
        ':semester' => 'Fall 2024'
    ]);
    $all_classes = $classStmt->fetchAll(PDO::FETCH_ASSOC);

    // Get classes the student is already enrolled in
    $enrolledStmt = $conn->prepare(
        'SELECT class_id, status
         FROM enrollments
         WHERE user_id = :user_id'
    );
    $enrolledStmt->execute([
        ':user_id' => $userID
    ]);
    $enrolled_data = $enrolledStmt->fetchAll(PDO::FETCH_ASSOC);

    // Convert to array for easy lookup
    $enrolled_classes = [];
    foreach ($enrolled_data as $enrollment) {
        $enrolled_classes[$enrollment['class_id']] = $enrollment['status'];
    }

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
    <meta name="description" content="CST499 Add/Drop Classes">
    <title>CST499 Website Project</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>

<body>
    <div class="nav_header">
        <div class="nav">
            <a href="index.php">Home</a>
            <a href="profile.php">Profile</a>
            <a href="view_classes.php">My Classes</a>
            <a href="contact.php">Contact Us</a>
            <a href="../function/logout.php">Logout</a>
        </div>
    </div>

    <div class="main">
        <h1>Add/Drop Classes</h1>

        <br><br>
        <?php if ($error !== ''): ?>
            <div class="error-message">
                <?= htmlspecialchars($error) ?>
            </div>
            <br>
        <?php endif; ?>

        <?php if ($success !== ''): ?>
            <div class="success-message" style="background-color: #d4edda; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                <?= htmlspecialchars($success) ?>
            </div>
            <br>
        <?php endif; ?>

        <p><strong>Semester:</strong> Fall 2024</p>
        <br>
        <?php foreach ($all_classes as $class): ?>
            <?php
            $class_id = $class['id'];
            $is_enrolled = isset($enrolled_classes[$class_id]);
            $enrollment_status = $enrolled_classes[$class_id] ?? null;
            $seats_available = $class['capacity'] - $class['enrolled_count'];
            $is_full = $seats_available <= 0;
            ?>

            <div class="class-box">
                <h3><?= htmlspecialchars($class['class_code']) ?> - <?= htmlspecialchars($class['class_name']) ?></h3>

                <p>
                    <strong>Instructor:</strong> <?= htmlspecialchars($class['instructor_name']) ?><br>
                    <strong>Enrollment:</strong> <?= $class['enrolled_count'] ?>/<?= $class['capacity'] ?> (<?= $seats_available ?> seats available)
                </p>

                <?php if ($is_enrolled): ?>
                    <div class="status-badge status-<?= $enrollment_status ?>">
                        <?= ucfirst($enrollment_status) ?>
                    </div>
                <?php endif; ?>

                <div class="class-actions">
                    <?php if (!$is_enrolled): ?>
                        <form action="../function/_add_drop_handler.php" method="POST">
                            <input type="hidden" name="class_id" value="<?= $class_id ?>">
                            <input type="hidden" name="action" value="add">
                            <button type="submit" class="btn-add" <?php if ($is_full): ?> title="Class is full. Join waitlist?"<?php endif; ?>>
                                <?php if ($is_full): ?>
                                    Join Waitlist
                                <?php else: ?>
                                    Add Class
                                <?php endif; ?>
                            </button>
                        </form>

                    <?php else: ?>
                        <form action="../function/_add_drop_handler.php" method="POST">
                            <input type="hidden" name="class_id" value="<?= $class_id ?>">
                            <input type="hidden" name="action" value="drop">
                            <button type="submit" class="btn-drop">Drop Class</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

        <?php endforeach; ?>
    </div>

    <div class="footer">
        <p>©Ruben Cruz-Colon 2026; CST499 Website Project</p>
    </div>
</body>
</html>