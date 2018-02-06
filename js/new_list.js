function addItem(itemNumber) {
	var elementName = "item" + itemNumber;
	var nextItemNumber = itemNumber + 1;
	var newItem = getNewItemHtml(itemNumber);
	var newItemName = "item" + nextItemNumber;

	var parentDiv = document.getElementById("submitButton").parentNode;

	// Create "Add Item" Button
	var newItemButton = document.createElement("div");
	newItemButton.setAttribute("class", "row");
	newItemButton.setAttribute("id", newItemName);

	// Create HR
	var hr = document.createElement("hr");

	// Dump new HTML in old element
	document.getElementById(elementName).innerHTML = newItem;

	// Add new "Add Item" button and hr to document
	var submitButton = document.getElementById("submitButton");
	parentDiv.insertBefore(newItemButton, submitButton);
	parentDiv.insertBefore(hr, submitButton);
	
	document.getElementById(newItemName).innerHTML = getNewItemButton(nextItemNumber);	
}

function getNewItemHtml(itemNumber) {
	var html = '<div class="col-md-2">\
		<label for="item' + itemNumber + '_name">Item Name</label>\
	</div>\
\
	<div class="col-md-4">\
		<input type="text" name="item[' + itemNumber + '][name]" class="form-control" id="item' + itemNumber + '_name" placeholder="Item Name">\
	</div>\
\
	<div class="col-md-2">\
		<label for="item' + itemNumber + '_name">Item Description</label>\
	</div>\
\
	<div class="col-md-4">\
		<textarea name="item[' + itemNumber + '][description]" class="form-control" id="item' + itemNumber + '_description" rows="3"></textarea>\
	</div>';

	return html;
}

function getNewItemButton(itemNumber) {
	var html = '<div class="col-md-12">\
					<button type="button" name="add_item" class="btn btn-outline-secondary" onclick="addItem(' + itemNumber + ');">+ Add Item to Wishlist</button>\
				</div>';
	return html;
}
