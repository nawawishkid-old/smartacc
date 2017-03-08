window.onload = function() {
	console.log("=== window on load ===");

	// ==================== //
	// ===== CASHFLOW ===== //
	// ==================== //
	// === VIEW MODE === //
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
	// === END OF VIEW MODE === //
	// === DISPLAY DATA TABLE === //
	var ovCashflowSelect = document.getElementById("ovCashflowSelect");

	ovCashflowSelect.addEventListener("input", function() {
		console.log("=== ovCashflowSelect on input ===");

		var fd = new FormData();

		fd.append(ovCashflowSelect.name, ovCashflowSelect.value);
		for(var key of fd.keys()) {
			console.log("Key = " + key);
		}
		for(var val of fd.values()) {
			console.log("Value = " + val);
		}

		var xhr = new XMLHttpRequest(),
				phplink;

		xhr.open("POST", "data-analysis-overview.php");
		xhr.send(fd);

		xhr.onload = function() {
			console.log("=== xhr on load ===");

			var tbody = document.getElementById("ovCashflowTBody"),
					thead = document.getElementById("ovCashflowTHead");

			switch(ovCashflowSelect.value) {
				case "year":
					thead.innerHTML = "<tr><th>Year</th><th>Total income</th><th>Total expense</th></tr>";
					break;
				case "month":
					thead.innerHTML = "<tr><th>Year</th><th>Month</th><th>Total Income</th><th>Total expense</th></tr>";
					break;
				case "week":
					thead.innerHTML = "<tr><th>Year</th><th>Month</th><th>Week</th><th>Total Income</th><th>Total expense</th></tr>";
					break;
				case "day":
					thead.innerHTML = "<tr><th>Year</th><th>Month</th><th>Day</th><th>Total Income</th><th>Total expense</th></tr>";
			}

			tbody.innerHTML = xhr.responseText;
		}
	});
	// === END OF DISPLAY DATA TABLE === //

	// =========================== //
	// ===== END OF CASHFLOW ===== //
	// =========================== //

}