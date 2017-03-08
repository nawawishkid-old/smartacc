// --- today-script.js ---
window.addEventListener("load", function() {
	console.log("--- today-script/window.onload ---");

	// ----- 1. HEADER -----
	// --- 1.1 DATE ---
	var dateElem = document.querySelector("input[type=date][name=todayDate]");
	dateElem.value = date("-");
	sendDatetoPHP();

	// --- 1.2 SEND DATE VALUE TO PHP FOR FETCHING CASHFLOW OF THE DAY DATA ---
	dateElem.addEventListener("input", function() {
		sendDatetoPHP();
	});

	// --- 1.3 PREVIOUS AND NEXT DAY TAB ---
	var prevDayTab = document.querySelector(".today-header-sec-1 .sec-1-left"),
			nextDayTab = document.querySelector(".today-header-sec-1 .sec-1-right");
	prevDayTab.addEventListener("click", function() {
		changeDay("prev");
		sendDatetoPHP();
	});
	nextDayTab.addEventListener("click", function() {
		changeDay("next");
		sendDatetoPHP();
	});

	// --- 1.4 TAB ALTERNATION ON CLICK ---
	var headerSec2Tab = document.querySelectorAll(".today-header-sec-2 > section"),
			articleSec = document.querySelectorAll(".today-article > section");
	for(var i = 0; headerSec2Tab[i]; i++) {
		headerSec2Tab[i].addEventListener("click", function() {
			console.log("--- headerSec2Tab.onclick ---");
			sendDatetoPHP();
			this.classList.add("header-active");
			for(var j = 0; headerSec2Tab[j]; j++) {
				if(headerSec2Tab[j].classList.item(0) != this.classList.item(0)) {
					headerSec2Tab[j].classList.remove("header-active");
					articleSec[j].style.display = "none";
				} else {
					articleSec[j].style.display = "block";
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
		var date = new Date(),
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
	function sendDatetoPHP() {
		console.log("--- sendDatetoPHP() ---");
		var dateElem = document.querySelector("input[type=date][name=todayDate]");
		var fd = new FormData();
		fd.append(dateElem.name, dateElem.value);
		for(var pair of fd.entries()) {
			console.log("Key = " + pair[0] + ", value = " + pair[1]);
		}
		var xhr = new XMLHttpRequest();
		xhr.open("POST", "php/today-php-transact.php");
		xhr.send(fd);
		xhr.addEventListener("load", function() {
			console.log("--- xhr.onload ---");
			var flowSec = document.querySelectorAll(".today-article > section")[0];
			flowSec.innerHTML = xhr.responseText;
			setTransactItemEvent();
		});
	}
	//---
	function changeDay(param) {
		console.log("--- changeDay(param) ---");
		if(typeof param !== "string") {
			return false;
		}
		var dateElem = document.querySelector("input[type=date][name=todayDate]"),
				dMS = Date.parse(dateElem.value),
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
		dateElem.value = day;
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

	// --- 2.  FUNCTION ---
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

});