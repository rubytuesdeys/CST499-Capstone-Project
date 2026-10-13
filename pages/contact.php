<?php
session_start();

$loggedIn = isset($_SESSION['userID']);
$role = $_SESSION['role'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>CST499 Website Project</title>
	<link rel="stylesheet" href="styles/styles.css">
</head>

<body>
	<div class="nav_header">
		<div class="nav">
			<a href="index.php">Home</a>

            <?php if (!$loggedIn): ?>
                <a href="login.php">Login</a>
                <a href="registration.php">Register</a>

            <?php elseif ($role === 'admin'): ?>
                <a href="../admin/admin.php">Admin Panel</a>
                <a href="../function/logout.php">Logout</a>

            <?php else: ?>
                <a href="profile.php">Profile</a>
                <a href="../function/logout.php">Logout</a>
            <?php endif; ?>
		</div>
	</div>

	<div class="main">
		<h1>Contact Information</h1>
		</br></br>
		<h2>How much wood, could a woodchuck chuck, if a woodchuck could Chuck Norris?</h2>
		</br></br>
		<h2>Why did the bike fall asleep? Cuz it was two tired!</h2>
	</div>
	<div class="footer">
		<p>©Ruben Cruz-Colon 2026; CST499 Website Project</p>
	</div>	
</body>
</html>
