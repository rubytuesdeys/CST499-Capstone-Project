<?php
session_start();

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="CST499 Login Page">
	<title>CST499 Website Project</title>
	<link rel="stylesheet" href="styles/styles.css">
</head>

<body>
    <div class = "nav_header">
		<div class="nav">
			<a href="index.php">Home</a>
			<a href="registration.php">Register</a>
			<a href="contact.php">Contact Us</a>
		</div>
	</div>

	<?php if ($error !== ''): ?>
        <div class="error-message">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

	<div class="form_container login">
		<form action="../function/_login.php" method="POST">
		<h1>Sign In</h1>

		<div class="field">
			<label for="email">School Email</label>
			<input
  				type="email"
				id="email"
				name="email"
				placeholder="Firstname.Lastname@Keystone.student.edu" 
				pattern="[A-Za-z]+\.[A-Za-z]+@Keystone\.student\.edu"
				title="Enter your school email, such as Firstname.Lastname@Keystone.student.edu"
				required
			>	

		<br>
			<label for="password">Password</label>
			<input 
				type="Password"
				id="password" 
				name="password" 
				placeholder="Password" 
				required
			>		
		</div>
		
		<button type="submit" name="login">Login</button>
		<small>Don't have an account? <a href="registration.php">Sign Up</a></small>
		</form>
	</div>	

	<div class = "footer">
		<p>©Ruben Cruz-Colon 2026; CST499 Website Project</p>
	</div>
</body>
</html>