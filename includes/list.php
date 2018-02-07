<?php 

function body($pdo) {
	// TODO:
	// Distinguish between owner and other users/guests
	// If no invitation set in $_GET, redirect back to index.php
	if(isset($_GET['id']) && !empty($_GET['id'])) {
		$list_id = $_GET['id'];
		$results = query($pdo, "SELECT * FROM lists WHERE id = :id", ['id' => $list_id]);
		if(count($results) == 1) {
			$list = $results[0];
			$items = query($pdo, "SELECT * FROM items WHERE list_id = :list_id", ['list_id' => $list_id]);

			// Build list
			echo "
			<div class='row border border-light p-2'>
				<div class='col-md-12'>
					<div class='row'>
						<div class='col-md-12'>
							<h5>
								<span class='oi oi-list mr-1'></span>
								".$list['name']."
							</h5>
						</div>
					</div>
					<div class='row'>
						<div class='col-md-12'>
							<h6>".$list['description']."</h6>
						</div>
					</div>
			";
			foreach($items as $item) {
				echo "
					<div class='row'>
						<div class='col-md-12'>
							<hr/>
						</div>
					</div>
					<div class='row'>
						<div class='col-md-10'>
							<h6>
								<span class='oi oi-target mr-1'></span>
								".$item['name']."
							</h6>
						</div>
						<div class='col-md-2'>";
				if($_SESSION['id'] != $list['owner_id']) {
					if($item['patron_id'] == NULL) {
						echo "Item is not taken yet.";
					} else {
						echo "Item is taken.";
					}
				}
				echo "
						</div>
					</div>
					<div class='row'>
						<div class='col-md-12'>
							".$item['description']."
						</div>
					</div>
					<br/>
				";
			}
			echo "
				</div>
				<br/>
			</div>
			";

		} else {
			echo "Error: list not found.";
		}
	}
}
