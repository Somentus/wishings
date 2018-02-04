<?php

require_once($_SERVER["DOCUMENT_ROOT"].'/includes/DB.php');
$pdo = DB();

if(isset($_GET['uuid']) && !empty($_GET['uuid']) && isset($_GET['user_id']) && !empty($_GET['user_id'])) {
	$uuid = $_GET['uuid'];
	$user_id = $_GET['user_id'];

	$activation_key = query($pdo, "SELECT * FROM activation_keys WHERE user_id = :user_id AND uuid = :uuid", ['user_id' => $user_id, 'uuid' => $uuid]);
	if(count($activation_key) == 1) {
		query($pdo, "UPDATE users SET is_active = 1 WHERE id = :id", ['id' => $user_id]);
		query($pdo, "DELETE FROM activation_keys WHERE user_id = :user_id AND uuid = :uuid limit 1", ['user_id' => $user_id, 'uuid' => $uuid]);
		header('Location: /');
	}
}

?>
