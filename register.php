<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
	<link rel="stylesheet" href="./styles/style.css">
</head>
<body>
	<div class="login__box">
		<a class="login__button" href="./index.php">Go back to the main page</a>
		<form class="register__form" method="POST">
			<label for="emailAddress">Enter your email:</label>
			<input class="input" type="email" name="email" id="emailAddress" placeholder="example@example.com">
			<label for="password">Enter the password:</label>
			<input class="input" type="password" name="password" id="password">
			<input type="submit" value="Register">
			<?php
			if(isset($_SESSION['failPass'])) {
				echo $_SESSION['failPass'];
				unset($_SESSION['failPass']);
			}
			?>
		</form>
	</div>
</body>
</html>

<?php
include './include/mysql.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
	$emailAdd = isset($_POST['email']) ? trim(strtolower($_POST['email'])) : null;
	$password = isset($_POST['email']) ? trim($_POST['password']) : null;

	$password_hash = md5($password);

	if(preg_match('/^(?=.*[A-Z])(?=.*[!@#$%^&*])/', $password) && strlen($password) >= 6){
		$stmt = $connection->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param('s', $emailAdd);
    $stmt->execute();
    $result = $stmt->get_result();

		if($result->num_rows > 0){
			$_SESSION['failPass'] = '<p class="text">This user already exists<p>';
			header('Location: ' . $_SERVER['PHP_SELF']);
		exit;
		} else{
			$_SESSION['email'] = $emailAdd;
			addUser($emailAdd, $password_hash, $connection);
			$_SESSION['failPass'] = '<p class="success">You have successfully registered!<p>';
			sleep(1);
			header('Location: login.php');
			exit;
		}
	}else {
		$_SESSION['failPass'] = '<p class="text">The password is not suitable, it must contain at least 6 characters, 1 large letter and 1 special character<p>';
		header('Location: ' . $_SERVER['PHP_SELF']);
		exit;
	}
} 

function addUser($email, $password, $connection) {
	$temp = $connection->prepare("INSERT INTO `users` (`email`, `password`, `role`) VALUES(?, ?, 'user')");
	$temp->bind_param('ss', $email, $password);
	$temp->execute();
	
	return $temp;
}

mysqli_close($connection);
