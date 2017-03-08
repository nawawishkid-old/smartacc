// ----- 1. DATE -----
// --- 1.1 TODAY ---
 function today(punctuation) {
	console.log("--- date(punctuation) ---");
	if(typeof punctuation !== "string") {
		return false;
	}
	var date = new Date(),
		  year = date.getFullYear(),
		  month = date.getMonth() + 1,
		  day = date.getDate();
	return year + punctuation + addZero(month) + punctuation + addZero(day);
}
// --- 1.2 APPEND ZERO BEFORE ONE-DIGIT DAY OR MONTH ---
function addZero(number) {
	if(number < 10) {
		var x = "0" + number;
	} else {
		var x = number;
	}
	return x;
}
// --- 1.3 DAY CHANGING ---
function changeDay(param) {
	if(typeof param !== "string") {
		return false;
	}
	var dMS = Date.parse(/*date string value*/),
			oneDay = 84000000;
	if(param === "next") {
		var	nextMS = dMS + oneDay,
				d = new Date(nextMS).toLocaleDateString();
	} else if(param === "prev") {
		var	prevMS = dMS - oneDay,
		 		d = new Date(prevMS).toLocaleDateString();
	}
	var dSplit = d.split("/"),
			day = dSplit[2] + "-" + addZero(dSplit[0]) + "-" + addZero(dSplit[1]);
	return day;
}

// --- 2 TAB ALTERNATION ON CLICK ---
	var tab = document.querySelectorAll(""),
			section = document.querySelectorAll("");
	for(var i = 0; tab[i]; i++) {
		tab[i].addEventListener("click", function() {
			console.log("--- tab.onclick ---");
			this.classList.add("active");
			for(var j = 0; tab[j]; j++) {
				if(tab[j].classList.item(0) != this.classList.item(0)) {
					tab[j].classList.remove("active");
					section[j].style.display = "none";
				} else {
					section[j].style.display = "block";
				}
			}
		});
	}