/*
 * Returns true if the field is a cost given in $ (e.g. $0.15).
 */
function checkCost() {
	var elem = document.getElementById("cost");
	var cost = elem.value;
	
	//position of match, -1 if no match found
	var pos = cost.search(/^\$?\d+\.?\d*$/);
	if (pos != 0) {
		alert("Please use the following format (dollar-sign optional):\n" +
				"$100.00");
		return false;
	} else {
		return true;
	}
}

/*
 * Returns TRUE if the user gives "DD-MM-YY".
 */
function checkDate() {
	var elem = document.getElementById("date");
	var date = elem.value;
	
	//position of match, -1 if no match found
	var pos = date.search(/^[0-3]\d-[0-1]\d-\d{2}$/);
	if (pos != 0) {
		alert("Please use the following format:\n" +
				"DD-MM-YY");
		return false;
	} else {
		return true;
	}
}

/*
 * Returns TRUE if the textarea given by "id" is not empty.
 */
function checkTextArea(id) {
	var elem = document.getElementById(id);
	var desc = elem.value;
	
	if (desc == "") {
		alert("Your description is empty.");
		return false;
	} else {
		return true;
	}
}

/*
 * Returns true if the field is a number.
 */
function checkHours() {
	var elem = document.getElementById("hours");
	var hours = elem.value;
	
	//position of match, -1 if no match found
	var pos = hours.search(/^\d+\.?\d*$/);
	if (pos != 0) {
		alert("Your number of hours is invalid.\n" +
				"Enter an integer or a decimal.");
		return false;
	} else {
		return true;
	}
}

/*
 * Returns TRUE if the user gives first or last name,
 * or gives first and last name, in any order, with
 * or without a comma.
 */
function checkName() {
	var elem = document.getElementById("name");
	var name = elem.value;
	
	//position of match, -1 if no match found
	var pos = name.search(/^[A-Z][a-z]+,?( [A-Z][a-z]+)?$/);
	if (pos != 0) {
		alert("Please enter your name in one of the following forms:\n" +
				"First-name Last-name\n" +
				"Last-name, First-name\n" +
				"Last-name\n" +
				"First-name\n" +
				"Please remember that numbers and punctuation other than the comma are not allowed.");
		return false;
	} else {
		return true;
	}
}

/*
 * Returns TRUE if the user gives "ddd-ddd-dddd".
 */
function checkPhone() {
	var elem = document.getElementById("phone");
	var phone = elem.value;
	
	//position of match, -1 if no match found
	var pos = phone.search(/^((\(\d{3}\))|(\d{3}))-\d{3}-\d{4}$/);
	if (pos != 0) {
		alert("Please use the following format:\n" +
				"555-555-5555 or (555)-555-5555");
		return false;
	} else {
		return true;
	}
}

/*
 * Returns TRUE if either of the hidden 'id' or 'aid' inputs is not empty
 */
function checkRecord() {
	var id = document.getElementById('id').value;
	var aid = document.getElementById('aid').value;
	if (id || aid) {
		return true;
	} else {
		alert("You need to select an assignment or record " +
			"before you submit a new record");
		return false;
	}
}

/**
 * Makes an AJAX call that stores date info in the date elements.
 */
function load_date() {
        $('#year').load('get_date.php?item=year', function(r, s, xmlRequest) {
                $('#year').val(r);
        });
	var d = new Date().getMonth();
	$('#month_'+d).attr('selected', 1);
        $('#day').load('get_date.php?item=day', function(r, s, xmlRequest) {
		$('#day').val(r);
		var d = new Date().getDate();
		$('#day_'+d).attr('selected', 1);
        });
}

/**
 * Wait for page to load, then set date change event handler.
 */
$(document).ready(function() {
        $('#month').change(date_change_event);
	$('#year').change(date_change_event);
});

/**
 * Date change event handler.
 */
function date_change_event() {
	$('#day').load('get_date.php?item=day'+
	'&month='+$('#month').val()+
	'&year='+$('#year').val(), function(r, s, xmlRequest) {
		$('#day').val(r);
	});
}
