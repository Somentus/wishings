<?php

session_start();

require_once($_SERVER["DOCUMENT_ROOT"].'/includes/DB.php');
$pdo = DB();
require_once($_SERVER["DOCUMENT_ROOT"].'/includes/codes.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/includes/new_list.php');

if(!isLoggedIn()) {
	header('Location: /');
}

?>

<!DOCTYPE HTML>

<html lang="en">
<head>
	<meta charset="utf-8">

	<title>Functional Forum</title>

	<link rel="stylesheet" href="/css/main.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	<script src="/js/scripts.js" type="text/javascript" ></script>
	<script src="/js/new_list.js" type="text/javascript" ></script>
</head>

<body>

	<div>
		<?php navbar($pdo); ?>
	</div>

	<br />
	<div class="container">
		<form action="/includes/new_list.php" method="POST">
			<div class="row">
				<div class="col-md-12">
					<label for="list_name">Wishlist Name</label>
					<input type="text" name="list_name" class="form-control" id="list_name" maxlength="64" placeholder="Wishlist Name">
				</div>
			</div>

			<br/>

			<div class="row">
				<div class="col-md-12">
					<label for="list_description">Wishlist Description</label>
					<textarea name="list_description" class="form-control" id="list_description" rows="3"></textarea>
				</div>
			</div>

			<hr/>

			<div class="row" id="item1">
				<div class="col-md-2">
					<label for="item1_name">Item Name</label>
				</div>

				<div class="col-md-4">
					<input type="text" name="item1_name" class="form-control" id="item1_name" placeholder="Item Name">
				</div>

				<div class="col-md-2">
					<label for="item1_name">Item Description</label>
				</div>

				<div class="col-md-4">
					<textarea name="item1_description" class="form-control" id="item1_description" rows="3"></textarea>
				</div>
			</div>

			<hr/>

			<div class="row" id="item2">
				<div class="col-md-12">
					<button type="button" name="add_item" class="btn btn-outline-secondary" onclick="addItem(2);">+ Add Item to Wishlist</button>
				</div>
			</div>

			<hr/>

			<div class="text-center" id="submitButton">
				<button type="submit" name="create_topic" class="btn btn-outline-primary">Submit</button>
			</div>
		</form>
		<br/>
	</div>

</body>
</html>
