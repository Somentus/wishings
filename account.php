<?php

session_start();

require_once($_SERVER["DOCUMENT_ROOT"].'/includes/DB.php');
$pdo = DB();
require_once($_SERVER["DOCUMENT_ROOT"].'/includes/codes.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/includes/account.php');

if(!isLoggedIn()) {
	header('Location: /');
}

?>

<!DOCTYPE HTML>

<html lang="en">
<head>
	<meta charset="utf-8">

	<title>Wishings - Wishlist Website</title>

	<link rel="stylesheet" href="/css/main.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	<link href="/open-iconic/font/css/open-iconic-bootstrap.css" rel="stylesheet">
	<script src="/js/scripts.js" type="text/javascript" ></script>
</head>

<body>

	<div>
		<?php navbar($pdo); ?>
	</div>

	<br />
	<div class="container">
		<?php body($pdo); ?>
	</div>

</body>
</html>
