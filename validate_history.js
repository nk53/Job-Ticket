/*
 * Alerts the user only one element per submit.
 * This will be improved on later.
 */
function validate() {
	return checkHours()
		&& checkCost();
}

//Register event handlers
function loadEventHandlers() {
	document.getElementById('form').onsubmit = validate;
	load_date();
	$('#assign_to').load('get_users.php', function(r, s, xmlRequest) {
		$('#assign_to').val(r);
	});
	if (!prev) {
		$('#prev').hide();
	}
	if (!next) {
		$('#next').hide();
	}
	$('#prev').click(function () {
		window.location = 'history.php?id='+prev;
	});
	$('#next').click(function () {
		window.location = 'history.php?id='+next;
	});
}
