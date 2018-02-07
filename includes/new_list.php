<?php

session_start();

require_once($_SERVER["DOCUMENT_ROOT"].'/includes/DB.php');
$pdo = DB();
$errors = [];

if(isset($_POST['create_list'])) {
	if(isset($_POST['list_name']) && !empty($_POST['list_name'])) {
		$list_name = $_POST['list_name'];

		if(isset($_POST['list_description']) && !empty($_POST['list_description'])) {
			$list_description = $_POST['list_description'];

			// List name & description are set, so list can be inserted into DB
			$user_id = $_SESSION['id'];
			query($pdo, "INSERT INTO lists (owner_id, name, description) VALUES (:owner_id, :name, :description)", ['owner_id' => $user_id, 'name' => $list_name, 'description' => $list_description]);
			$list_id = $pdo->lastInsertId();

			if(isset($_POST['item']) && !empty($_POST['item'])) {
				$items = $_POST['item'];

				foreach($items as $index=>$item) {
					if(isset($item['name']) && !empty($item['name'])) {
						// Item Name is set, so item can be inserted into DB. Description is optional, can be entered later
						query($pdo, "INSERT INTO items (list_id, name, description) VALUES (:list_id, :name, :description)", ['list_id' => $list_id, 'name' => $item['name'], 'description' => $item['description']]);
					} else {
						if(isset($item['description']) && !empty($item['description'])) {
							$errors[] = "Item name ".$index." is empty, but description is set.";
						}
					}
				}
			}
		} else {
			$errors[] = "List description is empty.";
		}
	} else {
		$errors[] = "List name is empty.";
	}

	if(!empty($errors)) {
		foreach($errors as $error) {
			echo $error;
		}
	} else {
		header('Location: /account.php');
	}

}
