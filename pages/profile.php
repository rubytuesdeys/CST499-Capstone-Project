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

try {
    $stmt = $conn->prepare(
		'SELECT first_name, last_name, email, address, phone, student_id 
		 FROM users 
		 WHERE id = :id');
    $stmt->execute([
		':id' => $_SESSION['userID']
	]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "No information found for this user.";
        exit;
    }
	// Formats phone
	$phone = preg_replace('/[^0-9]/', '', $user['phone']);
	$phone = "(" . substr($phone,0,3) . ") " 
			. substr($phone,3,3) . "-" . substr($phone,6);
	
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
	<meta name="description" content="CST499 Profile Page">
	<title>CST499 Website Project</title>
	<link rel="stylesheet" type="text/css" href="styles/styles.css">
</head>

<body>
	<div class="nav_header">
		<div class="nav">
			<a href="index.php">Home</a>
			<a href="add_drop_classes.php">Classes</a>
			<a href="view_classes.php">My Classes</a>
			<a href="contact.php">Contact Us</a>
			<a href="../function/logout.php">Logout</a>
		</div>
	</div>

	<div class="profile_container">
		<h2>Now viewing the entered information for, 
			<?= htmlspecialchars($user['first_name']) ?>
			<?= htmlspecialchars($user['last_name']) ?>!
		</h2>

		<div class="profile_info">
			<div class="info_row">
				<span class="label">Email:</span>
				<span><?= htmlspecialchars($user['email']); ?></span>
			</div>

			<div class="info_row">
    			<span class="label">Student ID:</span>
    			<span><?= htmlspecialchars($user['student_id']) ?></span>
			</div>

			<div class="info_row">
				<span class="label">Address:</span>
				<span><?= htmlspecialchars($user['address']) ?></span>
			</div>
			
			<div class="info_row">
				<span class="label">Phone:</span>
				<span><?= htmlspecialchars($phone) ?></span>
			</div>
		</div>

		<form action="../function/logout.php" method="post">
			<button type="submit">Logout</button>
		</form>
	</div>

	<div class="footer">
		<p>©Ruben Cruz-Colon 2026; CST499 Website Project</p>
	</div>	
</body>
</html>