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
		<form method="POST">
			<label for="emailAddress">Enter your email:</label>
			<input class="input" type="email" name="email" id="emailAddress" placeholder="example@example.com">
			<label for="password">Enter the password:</label>
			<input class="input" type="password" name="password" id="password">
			<div class="div_checkbox">
				<label for="remember" class="label__remember">Remember</label>
				<input type="checkbox" name="remember" id="remember">
			</div>
			<input type="submit" value="Login">
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
include "./include/mysql.php";

if(isset($_COOKIE['username'])){
	header('Location: home.php');
	exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
	$emailAdd = isset($_POST['email']) ? trim(strtolower($_POST['email'])) : null;
	$password = isset($_POST['email']) ? trim($_POST['password']) : null;
	$checkbox = isset($_POST['remember']) ? $_POST['remember'] : null;

	$password_hash = md5($password);

	checkUser($emailAdd, $password, $checkbox, $connection, $password_hash);
}

function addUser($email, $password, $connection) {
	$temp = $connection->prepare("INSERT INTO `users` (`email`, `password`, `role`) VALUES(?, ?, 'user')");
	$temp->bind_param('ss', $email, $password);
	$temp->execute();

	return $temp;
}

function checkUser($emailAdd, $password, $checkbox, $connection, $password_hash){
	if(preg_match('/^(?=.*[A-Z])(?=.*[!@#$%^&*])/', $password) && strlen($password) >= 6){
		$stmt = $connection->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param('s', $emailAdd);
    $stmt->execute();
    $result = $stmt->get_result();

		if($result->num_rows > 0){
			$user = $result->fetch_assoc();
			if($password_hash === $user['password']){
				$_SESSION['email'] = $emailAdd;
				if($checkbox){
					setcookie('username', json_encode($emailAdd), time() + 600, '/');
				}
				header('Location: home.php');
				exit;
			} else{
				$_SESSION['failPass'] = '<p class="text">Invalid password</p>';
				header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
			}
		} else {
			addUser($emailAdd, $password_hash, $connection);
			$_SESSION['email'] = $emailAdd;

			if($checkbox){
				setcookie('username', json_encode($emailAdd), time() + 600, '/');
			}
	
			header('Location: home.php');
			exit;
		}
	} else {
		$_SESSION['failPass'] = '<p class="text">The password is not suitable, it must contain at least 6 characters, 1 large letter and 1 special character</p>';
		header('Location: ' . $_SERVER['PHP_SELF']);
		exit;
	}
}

mysqli_close($connection);
?>