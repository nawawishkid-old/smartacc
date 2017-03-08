window.addEventListener("load", function() {
	console.log("=== window.onload ===");

	// ======================================= //
	// =====##### 1. CASHFLOW DIVISION #####===== //
	// ======================================= //
	// ================================ //
	// ===== 1.1 PARENT OF DATA TABLE ===== //
	// ================================ //
	// === VIEW MODE DIVISION === //
	var viewModeSelect = document.getElementById("ovCashflowViewModeSelect");

	viewModeSelect.addEventListener("input", function() {
		console.log("=== viewModeSelect on input ===");

		var svg = document.getElementById("ovCashflowSvg"),
				table = document.getElementById("ovCashflowTable");

		if(viewModeSelect.value === "table") {
			svg.classList.add("hide");
			table.classList.remove("hide");
		} else {
			table.classList.add("hide");
			svg.classList.remove("hide");
		}
	})
	// === END OF VIEW MODE DIVISION === //

	// === DISPLAY DATA TABLE === //
	var ovCashflowFilterSelect = document.getElementById("ovCashflowFilterSelect");

	ovCashflowFilterSelect.addEventListener("input", function() {
		console.log("=== ovCashflowFilterSelect on input ===");

		var fd = new FormData();

		fd.append(ovCashflowFilterSelect.name, ovCashflowFilterSelect.value);
		for(var pair of fd.entries()) {
			console.log("Key = " + pair[0] + ", value = " + pair[1]);
		}

		var xhr = new XMLHttpRequest();

		xhr.open("POST", "php/data-analysis-overview.php");
		xhr.send(fd);

		xhr.onload = function() {
			console.log("=== xhr on load ===");

			var tbody = document.getElementById("ovCashflowTBody"),
					thead = document.getElementById("ovCashflowTHead"),
					th = document.querySelectorAll(".ov-cashflow-th");

			switch(ovCashflowFilterSelect.value) {
				case "year":
					console.log("cashflow filter = " + ovCashflowFilterSelect.value);

					for(var i = 1; i < 4; i++) {
						var allInput = th[i].querySelectorAll("input[type=checkbox]");

						//multipleInputAvailability(allInput, "disable");
						hideShow(th[i], true);
					}
					thWidth();
					break;
				case "month":
					console.log("cashflow filter = " + ovCashflowFilterSelect.value);
					
					hideShow(th[1], false);

					/*var allInput = document.querySelectorAll("input[type=checkbox][data-set=" + th[2].dataset.set
							+ "], input[type=checkbox][data-set=" + th[3].dataset.set + "]");*/

					//multipleInputAvailability(allInput, "disable");
					hideShow(th[2], true);
					hideShow(th[3], true);
					thWidth();
					break;
				case "week":
					console.log("cashflow filter = " + ovCashflowFilterSelect.value);

					var allInput = th[3].querySelectorAll("input[type=checkbox]");

					//multipleInputAvailability(allInput, "disable");
					hideShow(th[1], false);
					hideShow(th[2], false);
					hideShow(th[3], true);
					thWidth();
					break;
				case "day":
					console.log("cashflow filter = " + ovCashflowFilterSelect.value);
					
					var allInput = th[2].querySelectorAll("input[type=checkbox]");

					//multipleInputAvailability(allInput, "disable");
					hideShow(th[3], false);
					hideShow(th[1], false);
					hideShow(th[2], true);
					thWidth();
			}

			tbody.innerHTML = xhr.responseText;

			// --- Check all input[type=checkbox] when ovCashflowFilterSelect has changed. ---
			for(var i = 1; i < 5; i++) {
				checkAll(i, true);
			}
		}
	});
	// === END OF DISPLAY DATA TABLE === //

	// === FUNCTIONS === //
	// --- SHOW OR HIDE ELEMENTS ---
	function hideShow(elem, hide) {
		if(hide) {
			elem.classList.add("hide");
		} else {
			elem.classList.remove("hide");
		}
	}

	// --- DISABLE INPUT ELEMENT ---
	function multipleInputAvailability(elemList, command) {
		var disable;

		if(command.toLowerCase() === "enable") {
			disable = false;
		} else if (command.toLowerCase() === "disable") {
			disable = true;
		} else {
			return false;
		}

		for(var i = 0; elemList[i]; i++) {
			var e = elemList[i];

			if(e.tagName === "INPUT" ||
				 e.tagName === "SELECT" ||
				 e.tagName === "TEXTAREA") {
				e.disabled = disable;
			} else {
				return false;
			}
		}
	}

	// --- DEFINES <TH> WIDTH IN ACCORDANCE WITH NUMBER OF <TH> ELEMENTS ---
	function thWidth() {
		// Count hidden elements.
		var th = document.querySelectorAll(".ov-cashflow-th"),
				count = 0;

		for(var i = 0; th[i]; i++) {
			if(th[i].classList.contains("hide")) {
				count++;
			}
		}

		// Define shown element width.
		var divisor = th.length - count;

		for(var i = 0; th[i]; i++) {
			th[i].style.width = 100 / divisor + "%";
		}
	}
	// === END OF FUNCTIONS === //
	// =========================================== //
	// ===== END OF 1.1 PARENT OF DATA TABLE ===== //
	// =========================================== //


	// ======================================== //
	// ===== 1.2 DATA TABLE (<TH> SELECT) ===== //
	// ======================================== //
	// === CHECKBOX TOGGLE === //
	var selectBox = document.querySelectorAll(".ov-cashflow-th-select-box"),
	    checkbox = document.querySelectorAll(".ov-cashflow-th-checkbox-div");

	for(var i = 0; selectBox[i]; i++) {

		selectBox[i].addEventListener("click", function() {
			console.log("=== " + this.tagName + "." + this.className + " is clicked ===");
			
			var checkboxDiv = document.querySelector("div[class='ov-cashflow-th-checkbox-div'][data-set='" + this.dataset.set + "']");

			checkboxDiv.style.display = (checkboxDiv.style.display == "block" ? "none" : "block");
		});
	}
	// === CLOSE CHECKBOX WHEN CLICK ON OTHER ELEMENTS === //
	document.addEventListener("click", function(ev) {
		console.log("=== DOCUMENT is clicked ===");

		var selectPrevent = document.querySelectorAll(".ov-cashflow-th-select-prevent");

		for(var i = 0; selectPrevent[i]; i++) {
			var e = ev.target;
					subclass = e.className.substring(0, 23); // 'ov-cashflow-th-checkbox...'

			if(e != selectPrevent[i] && subclass != "ov-cashflow-th-checkbox") {
				selectBox[i].nextElementSibling.style.display = "none";
				if(howManyCheck(selectBox[i].dataset.set) === 0) {
					checkAll(selectBox[i].dataset.set, true);
				}
			}
		}
	});
	// === END OF CHECKBOX TOGGLE === //

	// === CHECKBOX INPUT CHECKING MANAGEMENT === //
	// --- CHECK ALL CHECKBOX WHEN USER CHECK 'ALL' --- //
	var allchecked = document.querySelectorAll(".ov-cashflow-th-checkbox-input-all");

	for(var i = 0; allchecked[i]; i++) {
		allchecked[i].addEventListener("click", function() {
			console.log("=== " + this.tagName + "." + this.classList[1] + " is clicked ===");

			var submitBtn = document.querySelector("button[data-set='" + this.dataset.set + "']");

			if(this.checked) {
				checkAll(this.dataset.set, true);
			} else {
				checkAll(this.dataset.set, false);
			}
		});
	}
	// --- CHECK 'ALL' WHEN USER CHECK ALL CHECKBOX --- //
	// Loop through 'all' checkbox to access to other checkboxes in each division.
	for(var i = 0; allchecked[i]; i++) {
		var otherOpt = document.querySelectorAll("." + allchecked[i].classList[0]);

		// Loop through all checkboxes to add event listener
		for(var j = 1; otherOpt[j]; j++) { // index 0 is 'all' checkbox, don't count.
			otherOpt[j].addEventListener("click", function() {
				console.log("=== " + this.tagName + "." + this.className + " is clicked ===");

				var checkbox = document.querySelectorAll("input[type=checkbox][data-set='" + this.dataset.set + "']");

				checkbox[0].checked = (howManyCheck(this.dataset.set) === (checkbox.length - 1) ? true : false);
				enableSubmit(this.dataset.set);
			});
		}
	}
	// === END OF CHECKBOX INPUT CHECKING MANAGEMENT === //

	// === CREATE FORMDATA AND SEND CHECKBOX VALUE VIA AJAX === //
	// Submit button on click.
	var btn = document.querySelectorAll("th button[type=button]");

	for(var i = 0; btn[i]; i++) {
		btn[i].addEventListener("click", function() {
			console.log("=== Submit button is clicked ===");

			// Create FormData.
			var allInput = document.querySelectorAll("th input[type=checkbox]"),
					ovCashflowFilterSelect = document.getElementById("ovCashflowFilterSelect"),
					fd = new FormData();

			for(var i = 0; allInput[i]; i++) {
				if(!allInput[i].disabled) {
					if(allInput[i].checked) {
						fd.append(allInput[i].name, allInput[i].value);
					}
				}
			}
			fd.append(ovCashflowFilterSelect.name, ovCashflowFilterSelect.value);
			for(var pair of fd.entries()) {
				console.log("key = " + pair[0] + ", value = " + pair[1]);
			}

			var xhr = new XMLHttpRequest();

			xhr.open("POST", "php/selectcheckbox-php.php");
			xhr.send(fd);

			xhr.onload = function() {
				console.log("=== xhr is loaded ===");
				console.log(xhr.responseText);

				var tbody = document.querySelector("#ovCashflowTBody");
				
				tbody.innerHTML = xhr.responseText;
			}
		});
	}
	// === END OF CREATE FORMDATA AND SEND CHECKBOX VALUE VIA AJAX === //

	// === FUNCTIONS === //
	// --- CHECK HOW MANY CHECKED CHECKBOX IN SPECIFIED INPUT SET --- //
	function howManyCheck(dataset) {
		console.log("=== howManyCheck(" + dataset + ")===");

		var checkbox = document.querySelectorAll("input[type=checkbox][data-set='" + dataset + "']"),
				checkedcount = 0;

		// Loopt through all checkboxes in each division to see if it's checked or not.
		for(var i = 1; checkbox[i]; i++) { // index 0 is 'all' checkbox, don't count.
			if(checkbox[i].checked) {
				checkedcount++;
			}
		}

		console.log(checkedcount);
		return checkedcount;
	}
	// --- CHECK/UNCHECK ALL CHECKBOXES IN SPECIFIED INPUT SET --- //
	function checkAll(dataset, checked) {
		console.log("=== checkAll(" + dataset + ", " + checked + ") ===");

		var otherOpt = document.querySelectorAll("input[type=checkbox][data-set='" + dataset + "']");

		for(var j = 0; otherOpt[j]; j++) {
			otherOpt[j].checked = checked;
		}

		// Enable/disaboe submit button based on 'checked' boolean parameter.
		enableSubmit(dataset);
	}
	// --- ENABLE/DISABLE SPECIFIED SUBMIT BUTTON --- //
	function enableSubmit(dataset) {
		console.log("=== enableSubmit(" + dataset + ")===");

		var submitBtn = document.querySelector("button[data-set='" + dataset + "']"),
				checkboxes = document.querySelectorAll("input[type=checkbox][data-set='" + dataset + "']");

		submitBtn.disabled = (howManyCheck(dataset) === 0 ? true : false);
	}
	// === END OF FUNCTIONS === //
	// =============================================== //
	// ===== END OF 1.2 DATA TABLE (<TH> SELECT) ===== //
	// =============================================== //
	// ================================================= //
	// =====##### END OF 1. CASHFLOW DIVISION #####===== //
	// ================================================= //

});