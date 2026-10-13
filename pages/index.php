<?php
session_start();

$loggedIn = isset($_SESSION['userID']);
$role = $_SESSION['role'] ?? null;
$user = null;

// Fetch regular user info when logged in.
if ($loggedIn && $role !== 'admin') {
    require __DIR__ . '/../config/connect.php';

    try {
        $stmt = $conn->prepare(
			'SELECT first_name, last_name 
			 FROM users 
			 WHERE id = :id');
        $stmt->execute([
			':id' => $_SESSION['userID']
		]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
		error_log($e->getMessage());
        $user = null;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="CST499 Home Page">
	<title>CST499 Website Project</title>
	<link rel="stylesheet" type="text/css" href="styles/styles.css">
</head>

<body>
	<div class="nav_header">
		<div class="nav">

			<?php if (!$loggedIn): ?>
				<!-- Guest Links -->
				<a href="index.php">Home</a>
				<a href="login.php">Login</a>
				<a href="registration.php">Register</a>
				<a href="contact.php">Contact Us</a>

			<?php elseif ($role === 'admin'): ?>
				<!-- Admin Links Only -->
				<a href="../admin/admin.php">Admin Panel</a>
				<a href="../function/logout.php">Logout</a>

			<?php else: ?>
				<!-- Regular User Links -->
				<a href="index.php">Home</a>
				<a href="profile.php">Profile</a>
				<a href="contact.php">Contact Us</a>
				<a href="../function/logout.php">Logout</a>
			<?php endif; ?>
		</div>
	</div>

	<div class="main">
		<?php if (!$loggedIn): ?>
			<h1><strong>Welcome to the Keystone College Website</strong></h1>
			</br></br>
			<p><strong>This is the landing page for non-users of the CST499 project created by Ruben Cruz-Colon.</strong></p>
			</br></br>
			<h2>Fun Facts About Birds:</h2>
			<ul>
				<li>Birds Are Actually Government Spy Drones</li>
				<li>Nests Are Charging Ports</li>
				<li>Baby Birds Are Small Drones Learning How To Spy</li>
			</ul>

		<?php elseif ($role === 'admin'): ?>
			<h1>Admin Dashboard</h1>
			<p>Welcome, Admin!</p>

		<?php else: ?>
			<h1>Welcome, 
			<?= htmlspecialchars($user['first_name'] ?? ''); ?>
			<?= htmlspecialchars($user['last_name'] ?? ''); ?>!</h1>
			<h2>These conspiracy theories are found only on the user page:</h2>
			</br></br>
			<ul>
				<li>Australia Does Not Exist</li>
				<li>Elvis And Tupac Live In Cuba And Jointly Run A Surf Shop/Dive Bar.
			</ul> 
			<?php endif; ?>
	</div>

	<div class="footer">
		<p>©Ruben Cruz-Colon 2026; CST499 Website Project</p>
	</div>
</body>
</html>