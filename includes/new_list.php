<?php

require_once($_SERVER["DOCUMENT_ROOT"].'/includes/DB.php');
$pdo = DB();

if(isset($_POST['create_list'])) {
	echo "List name: ".$_POST['list_name'].". List description: ".$_POST['list_description']."<br/>";
	$items = $_POST['item'];
	foreach($items as $item) {
		echo "Item Name: ".$item['name'].". Item description: ".$item['description']."<br/>";
	}
}
