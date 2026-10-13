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
	<meta name="description" content="CST499 Registration Page">
	<title>CST310 Website Project</title>
	<link rel="stylesheet" href="styles/styles.css">
</head>

<body>
	<div class="nav_header">
		<div class="nav">
			<a href="index.php">Home</a>
			<a href="contact.php">Contact Us</a>
		</div>	

	</div>

	<?php if ($error !== ''): ?>
    	<div class="error-message">
        	<?= htmlspecialchars($error) ?>
    	</div>
	<?php endif; ?>

	<div class="form_container register">
		<form action="../function/_registration.php" method="POST">
		<h1 style="text-align:center">Registration Form</h1>
		
            <div class="field">
                <label for="email">School Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Firstname.Lastname@Keystone.student.edu"
                    pattern="[A-Za-z]+\.[A-Za-z]+@Keystone\.student\.edu"
                    title="Enter your student email address as firstname.lastname@Keystone.student.edu"
                    required
				>

                <br><br>
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Password"
                    minlength="8"
                    title="Your password must be a minimum of 8 characters"
                    required
				>

                <br><br>
                <label for="first_name">First Name</label>
                <input
                    type="text"
                    id="first_name"
                    name="first_name"
                    placeholder="First Name"
                    required
				>

                <br><br>
                <label for="last_name">Last Name</label>
                <input
                    type="text"
                    id="last_name"
                    name="last_name"
                    placeholder="Last Name"
                    required
				>

                <br><br>
                <label for="address">Address</label>
                <input
                    type="text"
                    id="address"
                    name="address"
                    placeholder="Full Address"
                    required
				>

                <br><br>
                <label for="phone">Phone Number</label>
                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    placeholder="Phone Number (10 digits)"
                    maxlength="10"
                    pattern="[0-9]{10}"
                    title="Enter exactly 10 digits"
                    required
				>

			<br/><br/>
			<button type="submit" name="register" value="Register">Register</button>
			<br/>
		</div>
		
		<small>Have an account? <a href="login.php">Login</a></small>
		</form>
	</div>

	<div class="footer">
		<p>©Ruben Cruz-Colon 2026; CST499 Website Project</p>
	</div>
</body>
</html>