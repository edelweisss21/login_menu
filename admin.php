<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
	<link rel="stylesheet" href="./styles/style.css">
</head>
<body>
	
</body>
</html>

<?php
session_start();
echo '<div class="login__box">';
echo '<p>Greetings, sir, you are in the admin panel!</p>' . '<br>';
echo '<a class="login__button" href="./logout.php">Log out of your account</a>';
echo '</div>';
?>