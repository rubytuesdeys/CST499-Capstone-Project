<?php
	session_start();

	session_unset();
	session_destroy();

	header('Location: /CST499/pages/index.php');
	exit;
?>