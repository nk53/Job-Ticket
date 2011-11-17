/*
 * Alerts the user only one element per submit.
 * This will be improved on later.
 */
function validate() {
	return checkHours()
		&& checkTextArea("materials")
		&& checkCost();
}

//Register event handlers
function loadEventHandlers() {
	document.getElementById("form").onsubmit = validate;
	load_date();
}
