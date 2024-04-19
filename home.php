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
include './include/mysql.php';

if(isset($_SESSION['email'])){
	$email = $_SESSION['email'];
	$userEmail = getUser($email, $connection);
	$userData = $userEmail->get_result()->fetch_assoc();

	$counter_file = './counter/counter.txt';

	if(file_exists($counter_file)){
		$counter = file_get_contents($counter_file);
		$counter++;
	} else{
		$counter = 1;
	}

	file_put_contents($counter_file, $counter);

	echo '<div class="login__box">';
	echo "<p>You have visited this page {$counter} once</p>";

	if($userData['role'] === 'admin'){
		echo '<p>Welcome to the website!</p>' . '<br>';
		echo '<a class="login__button" href="./admin.php">Go to the admin panel</a>';
	} else{
		echo '<p>Thank you for taking part in testing this site!</p>' . '<br>';
		echo '<a class="login__button" href="./logout.php">Log out of your account</a>';
		echo '</div>';
	}
}

function getUser($email, $connection){
	$row = $connection->prepare("SELECT role FROM users WHERE email = ?");
	$row->bind_param('s', $email);
	$row->execute();

	return $row;
}

mysqli_close($connection);