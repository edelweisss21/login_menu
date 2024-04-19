<?php
session_start();
setcookie('username', '', time() - 600, '/');
$_SESSION = array();
session_destroy();

header('Location: index.php');
exit;