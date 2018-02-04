<?php 

function body($pdo) {
	echoListsOwnedBy($pdo, $_SESSION['id']);

	echoListsContributedBy($pdo, $_SESSION['id']);
}

// Display all lists owned by user
function echoListsOwnedBy($pdo, $user_id) {
	$ownedLists = query($pdo, "SELECT * FROM lists WHERE owner_id = :owner_id", ['owner_id' => $user_id]);

	if(count($ownedLists) > 0) {
		foreach($ownedLists as $list) {
			echo "<div class='col-md-12 border border-secondary'>
					<div class='row border border-secondary'>
						<p class='font-weight-bold'>".$list['name']."</p>
					</div>";
			$items = query($pdo, "SELECT * FROM items WHERE list_id = :list_id", ['list_id' => $list['id']]);
			foreach($items as $item) {
				echo "<div class='row border border-secondary'>";
					echo $item['name'];
				echo "</div>";
			}
			echo "</div>";
		}

	} else {
		// TODO: Click here to create a new wishlist!
		echo "You don't have any wishlists yet!";
	}

	echo "<br/>";
}

// Display all lists contributed to by the user
function echoListsContributedBy($pdo, $user_id) {
	$contributedItems = query($pdo, "SELECT * FROM items WHERE patron_id = :patron_id", ['patron_id' => $user_id]);
	$contributedListsRaw = [];

	if(count($contributedItems) > 0) {
		foreach($contributedItems as $contributedItem) {
			$list_id = $contributedItem['list_id'];
			$list = query($pdo, "SELECT * FROM lists WHERE id = :id", ['id' => $list_id])[0];
			$contributedListsRaw[] = $list;
		}

		$contributedLists = array_unique($contributedListsRaw);

		foreach($contributedLists as $list) {
			echo "<div class='col-md-12 border border-secondary'>
					<div class='row border border-secondary'>
						<p class='font-weight-bold'>".$list['name']."</p>
					</div>";
			$items = query($pdo, "SELECT * FROM items WHERE list_id = :list_id", ['list_id' => $list['id']]);
			foreach($items as $item) {
				echo "<div class='row border border-secondary'>";
					echo $item['name'];
				echo "</div>";
			}
			echo "</div>";
		}
	} else {
		echo "You're not contributing to any lists yet.";
	}

	echo "<br/>";
}