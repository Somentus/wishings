<?php 

function body($pdo) {
	echoListsOwnedBy($pdo, $_SESSION['id']);

	echoListsContributedBy($pdo, $_SESSION['id']);
}

// Display all lists owned by user
function echoListsOwnedBy($pdo, $user_id) {
	$ownedLists = query($pdo, "SELECT * FROM lists WHERE owner_id = :owner_id", ['owner_id' => $user_id]);
	echo "
		<div class='row mb-1'>
			<div class='col-md-6'>
				<h5>Lists that you own:</h5>
			</div>
			<div class='col-md-6'>
				<a href='/new_list.php' class='btn btn-outline-secondary btn-sm float-right'>New Wishlist</a>
			</div>

		</div>
	";

	if(count($ownedLists) > 0) {
		foreach($ownedLists as $list) {
			echo "
				<div class='col-md-12 border border-secondary'>
					<div class='row border border-secondary'>
						<a href='list.php?id=".$list['id']."'>".$list['name']."</a>
					</div>";
			$items = query($pdo, "SELECT * FROM items WHERE list_id = :list_id", ['list_id' => $list['id']]);
			foreach($items as $item) {
				echo "<div class='row border border-secondary'>";
					echo $item['name'];
				echo "</div>";
			}
			echo "</div><br/>";
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
	$contributedListIdsRaw = [];

	echo "<h5>Lists that you contribute to:</h5>";

	if(count($contributedItems) > 0) {
		foreach($contributedItems as $contributedItem) {
			$list_id = $contributedItem['list_id'];
			$contributedListIdsRaw[] = $list_id;
		}

		$contributedListIds = array_unique($contributedListIdsRaw);

		foreach($contributedListIds as $key=>$list_id) {
			if($key != 0) {
				echo "<hr/>";
			}
			$list = query($pdo, "SELECT * FROM lists WHERE id = :id", ['id' => $list_id])[0];
			echo "<div class='col-md-12'>
					<div class='row'>
						<div clas='col-md-12'>
							<h6><span class='oi oi-list'><a class='ml-1' href='list.php?id=".$list['id']."'>".$list['name']."</a></h6>
						</div>
					</div>";
			$items = query($pdo, "SELECT * FROM items WHERE list_id = :list_id", ['list_id' => $list['id']]);
			foreach($items as $item) {
				echo "
					<div class='row'>
						<div clas='col-md-12'>";
				if($user_id == $item['patron_id']) {
					echo "<span class='oi oi-person mr-1'></span>";
				} else if($item['patron_id'] != NULL) {
					echo "<span class='oi oi-check mr-1'></span>";
				} else {
					echo "<span class='oi oi-x mr-1'></span>";
				}
				echo $item['name'];
				echo "</div>
					</div>";
			}
			echo "</div>";
		}
	} else {
		echo "You're not contributing to any lists yet.";
	}

	echo "<br/>";
}
