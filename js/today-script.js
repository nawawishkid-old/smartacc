// --- today-script.js ---
window.addEventListener("load", function() {
	console.log("--- today-script/window.onload ---");

	// ----- 1. HEADER -----
	// --- 1.1 DATE ---
	var dateElem = document.querySelector("input[type=date][name=todayDate]");
	dateElem.value = date("-");
	// Initial
	sendDatetoPHP("flow");
	sendDatetoPHP("budget");
	sendDatetoPHP("reports");

	// --- 1.2 SEND DATE VALUE TO PHP FOR FETCHING CASHFLOW OF THE DAY DATA ---
	dateElem.addEventListener("change", function() {
		sendDatetoPHP("flow");
		sendDatetoPHP("budget");
		sendDatetoPHP("reports");
	});

	// --- 1.3 PREVIOUS AND NEXT DAY TAB ---
	var prevDayTab = document.querySelector(".today-header-sec-1 .sec-1-left"),
			nextDayTab = document.querySelector(".today-header-sec-1 .sec-1-right");
	prevDayTab.addEventListener("click", function() {
		changeDay("prev");
	});
	nextDayTab.addEventListener("click", function() {
		changeDay("next");
	});

	// --- 1.4 TAB ALTERNATION ON CLICK ---
	var headerSec2Tab = document.querySelectorAll(".today-header-sec-2 > section"),
			articleSec = document.querySelectorAll(".today-article > section");
	for(var i = 0; headerSec2Tab[i]; i++) {
		headerSec2Tab[i].addEventListener("click", function() {
			console.log("--- headerSec2Tab.onclick ---");
			this.classList.add("header-active");
			for(var j = 0; headerSec2Tab[j]; j++) {
				if(headerSec2Tab[j].dataset.sectionName != this.dataset.sectionName) {
					headerSec2Tab[j].classList.remove("header-active");
					articleSec[j].style.display = "none";
				} else {
					articleSec[j].style.display = "block";
					// Show hidden detail of reports.
					if(articleSec[j].dataset.sectionName === "reports") {
						let reportsSec = document.querySelectorAll(".today-reports-hidden-detail");
						for(let i = 0; reportsSec[i]; i++) {
							reportsSec[i].style.maxHeight = reportsSec[i].scrollHeight + "px";
						}
					}
				}
			}
		});
	}

	// --- 1.5 FUNCTION ---
	function date(punctuation) {
		console.log("--- date(punctuation) ---");
		if(typeof punctuation !== "string") {
			return false;
		}
		let date = new Date(),
			  year = date.getFullYear(),
			  month = date.getMonth() + 1,
			  day = date.getDate();
		return year + punctuation + addZero(month) + punctuation + addZero(day);
	}
	var dateObj = {
		today: new Date(),
	}
	//---
	function addZero(number) {
		if(number < 10) {
			var x = "0" + number;
		} else {
			var x = number;
		}
		return x;
	}
	//---
	function sendDatetoPHP(sectionName) {
		console.log("--- sendDatetoPHP(sectionName) ---");
		let sn = sectionName;
		if(typeof sn !== 'string'
				&& (sn !== "flow"
						&& sn !== "budget"
						&& sn !== "reports"
					)
			) {return false;}
		let url;
		if(sn === "flow") {
			url = "php/today-php-transact.php";
			let showArea = document.querySelector(".today-article-sec-1"),
					preloader = document.createElement("div");
			preloader.classList.add("preloader");
			showArea.innerHTML = "";
			showArea.appendChild(preloader);
		} else if(sn === "budget") {
			url = ""; // Undefined yet.
			return false;
		} else {
			url = "php/today-php-reports.php";
			let hiddenDetail = document.querySelectorAll(".today-reports-hidden-detail");
			for(let i = 0; hiddenDetail[i]; i++) {
				let preloader = document.createElement("div");
				preloader.classList.add("preloader");
				hiddenDetail[i].innerHTML = "";
				hiddenDetail[i].appendChild(preloader);
			}
		}
		console.log("url = " + url);
		let dateElem = document.querySelector("input[type=date][name=todayDate]");
		let fd = new FormData();
		fd.append(dateElem.name, dateElem.value);
		let xhr = new XMLHttpRequest();
		xhr.open("POST", url);
		xhr.onreadystatechange = function() { 
			if(this.readyState === 2) {
				console.log("--- xhr.readyState = 2 ---");
				if(sn === "flow") {

				} else if (sn === "budget") {

				} else {
				}
			} else if(this.readyState === 4) {
				console.log("--- xhr.onload ---");
				console.log(this.responseText);
				let	sec = document.querySelector(".today-article > section[data-section-name="
					+ sn + "]");
				if(sn === "flow") {
					sec.innerHTML = this.responseText;
					setTransactItemEvent();
				} else if(sn === "budget") {

				} else {
					let json = JSON.parse(this.responseText),
							accSec = sec.querySelector(".today-reports-hidden-detail.reports-account"),
							sheets = document.querySelectorAll("#exOutput .report-sheet"),
							i = 0;
					accSec.innerHTML = json[0];
					accSec.style.maxHeight = accSec.scrollHeight + "px";
					for(i; sheets[i]; i++) {
						sheets[i].innerHTML = json[1][i];
					}
					/*let json = JSON.parse(this.responseText),
							acc = json["acc"],
							gen = json["gen"],
							deep = json["deep"],
							accSec = sec.querySelector(".today-reports-hidden-detail.reports-account"),
							genSec = sec.querySelector(".today-reports-hidden-detail.reports-general"),
							deepSec = sec.querySelector(".today-reports-hidden-detail.reports-deep");
					accSec.innerHTML = acc;
					accSec.style.maxHeight = accSec.scrollHeight + "px";
					genSec.innerHTML = gen;
					genSec.style.maxHeight = genSec.scrollHeight + "px";
					deepSec.innerHTML = deep;
					deepSec.style.maxHeight = deepSec.scrollHeight + "px";
					*/
				}
			}
		}
		xhr.send(fd);
	}
	//---
	function changeDay(param) {
		console.log("--- changeDay(param) ---");
		if(typeof param !== "string") {
			return false;
		}
		let dateElem = document.querySelector("input[type=date][name=todayDate]"),
				dMS = Date.parse(dateElem.value),
				oneDay = 84000000,
				d;
		if(param === "next") {
			d = new Date(dMS + oneDay).toLocaleDateString();
		} else if(param === "prev") {
			d = new Date(dMS - oneDay).toLocaleDateString();
		}
		let dSplit = d.split("/"),
				day = dSplit[2] + "-" + addZero(dSplit[0]) + "-" + addZero(dSplit[1]);
		dateElem.value = day;
		sendDatetoPHP("flow");
		sendDatetoPHP("budget");
		sendDatetoPHP("reports");
	}


	// ----- 2. ARTICLE -----
	// --- 2.1 FLOW SECTION (sec-1) ---
	// --- 2.1.1 CLOSE TRANSACTION ITEM MENU ---
	document.onclick =  function(ev) { // Close menu active if user didn't click active menu panel. 
		console.log("--- document.onclick ---");
		var e = ev.target;
		var menuActive = document.querySelectorAll(".today-flow-item-hidden-menu.menu-active");
		var menuTab = document.querySelectorAll(".today-flow-item-sec-right");
		if(menuActive.length === 0) {
			return null;
		}
		for(var i = 0; menuActive[i]; i++) {
			var menuTab = menuActive[i].previousElementSibling;
			if(e != menuActive[i] && e != menuTab && e.parentElement != menuActive[i]) {
				menuActive[i].style.maxWidth = null;
				menuActive[i].classList.remove("menu-active");
				menuTab.classList.remove("menu-active");
				menuActive[i].parentElement.classList.toggle("menu-active"); // .today-flow-item.
			}
		}
	}

	// --- 2.1.2 TRANSACTION ITEM MENU ---

	// --- 2.1.FUNCTION ---
	function setTransactItemEvent() {
		console.log("--- setTransactItemEvent() ---");
		var transactItems = document.querySelectorAll(".today-flow-item-label");
		var transactMenu = document.querySelectorAll(".today-flow-item-sec-right");
		var transactDeleteTab = document.querySelectorAll(".hidden-menu-sec-1");
		var transactEditTab = document.querySelectorAll(".hidden-menu-sec-2");
		for(var i = 0; transactItems[i]; i++) {
			// Open detail section on click item label
			transactItems[i].addEventListener("click", function() {
				console.log("--- transactItems[i].onclick ---");
				var item = this.parentElement.parentElement; // .today-flow-item
				var hiddenItem = item.nextElementSibling;
				item.classList.toggle("item-active");
				// if max-height is empty or null, it returns false. If returns true, make it null.
				if(hiddenItem.style.maxHeight) {
					hiddenItem.style.maxHeight = null;
				} else {
					hiddenItem.style.maxHeight = hiddenItem.scrollHeight + "px";
				}
			});
			// Open menu section on click item right section
			transactMenu[i].addEventListener("click", function() {
				console.log("--- transactMenu[i].onclick ---");
				this.classList.toggle("menu-active");
				this.parentElement.classList.toggle("menu-active"); // .today-flow-item.
				var hiddenMenu = this.nextElementSibling;
				hiddenMenu.classList.toggle("menu-active");
				if(hiddenMenu.style.maxWidth) {
					hiddenMenu.style.maxWidth = null;
				} else {
					hiddenMenu.style.maxWidth = "50%";
				}
			});
			// Delete item on click delete tab
			transactDeleteTab[i].addEventListener("click", function() {
				console.log("--- transactDeleteTab[i].onclick ---");
				var c = confirm("This deletion cannot be undone, are you sure you want to delete this record?");
				if(c) {
					// Send data of the item to php for deletion in database.
					var fd = new FormData();
					fd.append("id", this.dataset.transactId);
					var xhr = new XMLHttpRequest();
					xhr.open("POST", "php/today-php-delete.php");
					xhr.send(fd);
					var item = this.parentElement.parentElement; // .today-flow-item
					var hiddenDetail = item.nextElementSibling; // .today-flow-item-hidden-menu
					var itemParent = item.parentElement; // .today-article-sec-1
					xhr.onload = function() {
						item.classList.add("item-deleted");
						item.innerHTML = xhr.responseText;
						setTimeout(function() {
							item.style.opacity = 0;
							setTimeout(function() {
								itemParent.removeChild(item);
								itemParent.removeChild(hiddenDetail);
							}, 600);
						}, 2000);
					}
				} else {
					return false;
				}
			});
			// Edit item on click edit tab
			transactEditTab[i].addEventListener("click", function() {
				console.log("--- transactEditTab[i].onclick ---");
				var form = document.createElement("form");
				var input = document.createElement("input");
				form.method = "post";
				form.action = "today-edit.php";
				input.type = "hidden";
				input.name = "id";
				input.value = this.dataset.transactId;
				form.appendChild(input);
				document.body.appendChild(form);
				form.submit();
			});
		}
	}

	// --- 2.3 REPORT SECTION (sec-3) ---
	// --- 2.3.1 OPEN & CLOSE REPORTS PANEL ---
	var reportsSec = document.querySelectorAll(".today-reports-sec");
	for(let i = 0; reportsSec[i]; i++) {
		reportsSec[i].addEventListener("click", function() {
			console.log("--- reportsSec[i].onclick() ---");
			// Unclickable until successfully fetched data.
			if(this.nextElementSibling.children.length <= 1) {
				return false;
			}
			let hiddenDetail = this.nextElementSibling,
					mh = hiddenDetail.style.maxHeight;
			if(mh == "0px"
					&& !this.classList.contains("reports-active")) {
				hiddenDetail.style.maxHeight = hiddenDetail.scrollHeight + "px";
			} else if(mh != "0px"
					&& this.classList.contains("reports-active")) {
				hiddenDetail.style.maxHeight = 0;
			}
			this.classList.toggle("reports-active");
		});
	}
	// --- 2.3.2 INCOME/EXPENSE REPORT TAB SELECTION ---

});