/*
 * Alerts the user only one element per submit.
 * This will be improved on later.
 */
function validate() {
	return checkName()
		&& checkPhone()
		&& checkTextArea("description");
}

//Register event handlers
function loadEventHandlers() {
	document.getElementById("form").onsubmit = validate;
	$('#name').load('get_name.php', function(r, s, xmlRequest) {
                $('#name').val(r);
        });
	$('#phone').load('get_phone.php', function(r, s, xmlRequest) {
		$('#phone').val(r);
	});
	load_date();
}
