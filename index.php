<!DOCTYPE html>
<html>
<head>
	<title>Home | SMARTACC</title>
	<?php include("frame1-head.html");?>
	<style type="text/css">
		/* --- TODAY-HEADER ---*/
		.today-header {
			width: 100%;
			height: 15%;
			min-height: 4em;
		}
		.today-header-sec {
			width: 100%;
			height: 50%;
			border-bottom: 1px solid white;
		}
		.today-header-sec > section {
			display: inline-flex;
			justify-content: center;
			align-items: center;
			height: 100%;
			background-color: #1a1a1a; /*#737373;*/
			color: white;
			cursor: pointer;
			transition: background-color 0.4s;
		}
		[class*="sec-1-"] {
		  background-repeat: no-repeat;
		  background-position: center;
		  background-size: .5em;
		}
		.today-header-sec-1 > section:hover {
			background-color: #333333; /*#808080;*/
		}
		.today-header-sec > section:not(:last-of-type) {
			/*border-right: 1px solid white;*/
		}
		.today-header-sec-1 .sec-1-left {
			width: 10%;
			float: left;    
			background-image: url(/smartacc/media/icon-prev@48px-fff.png);
		}
		.today-header-sec-1 .sec-1-right {
			width: 10%;
			float: right;
			background-image: url(/smartacc/media/icon-next@48px-fff.png);
		}
		.today-header-sec-1 .sec-1-center {
			width: 80%;
		}
		input#todayDate {
			background-color: #1a1a1a; /*#737373;*/
			color: white;
			text-align: center;
			border: none;
			transition: background-color 0.4s ease-out;
		}
		input#todayDate:hover {
			background-color: #333333; /*#808080;*/
		}
		.today-header-sec-2 > section {
			width: 33.33%;
			background-color: #595959; /*#999999;*/
			color: #e6e6e6;
		}
		.today-header-sec-2 > section._header-active,
		.today-header-sec-2 > section:hover {
			background-color: #1a1a1a; /*#737373;*/
			color: white;
		}
		.today-header-sec-2 .sec-2-left {
			float: left;
		}
		.today-header-sec-2 .sec-2-right {
			float: right;
		}

		/* --- TODAY-ARTICLE ---*/
		.today-article {
			width: 100%;
			height: 85%;
			overflow: auto;
		  background-color: #cccccc;
		}
		.today-article > section {
			display: none;
		}
		.today-article > section._article-active {
			display: block;
		}
		/* --- TODAY-FLOW ---*/
		[class*="today-article-sec-"] {
			width: 100%;
			height: 100%;
			background-color: #cccccc;
		}
		.today-flow-item {
			width: 100%;
			height: 15%;
			min-height: 5em;
			position: relative;
			/*border-bottom: 1px solid black;*/
			background-color: white;
			overflow: hidden;
			opacity: 1;
			transition: opacity 0.6s;
		}
		.today-flow-item.item-active, .today-flow-item.menu-active {
			background-color: whitesmoke;
		}
		.today-flow-item:hover {
			background-color: whitesmoke;
		}
		.today-flow-item.item-deleted {
			display: flex;
			justify-content: center;
			align-items: center;
			color: #808080;
			font-weight: 900;
		}
		.today-flow-item-sec-left {
			width: 95%;
			height: 100%;
			float: left;
			position: relative;
			padding: 5px;
		}
		.today-flow-item-sec-left header {
			width: 100%;
			height: 25%;
			display: flex;
		}
		.today-flow-item-sec-left header strong {
			text-transform: uppercase;
		}
		strong[data-transact=in] {color: #99ff99;}
		strong[data-transact=ex] {color: #ff9999;}
		strong[data-transact=tr] {color: #66b3ff;}
		.today-flow-item-sec-left article {
			width: 100%;
			height: 75%;
			display: flex;
			font-size: 1.5em;;
		}
		.today-flow-item-sec-left-article-sec-left {
			width: 75%;
			height: 100%;
			display: flex;
		}
		.today-flow-item-sec-left-article-sec-left img {
			width: 15%;
			height: 100%;
		}
		.today-flow-item-sec-left-article-sec-left section {
			width: 85%;
			height: 100%;
			display: flex;
			align-items: center;
			padding-left: 10px;
		}
		.today-flow-item-sec-left-article-sec-left section span {
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}
		.today-flow-item-sec-left-article-sec-right {
			width: 25%;
			height: 100%;
			display: flex;
			justify-content: center;
			align-items: center;
			font-weight: 900;
		}
		.today-flow-item-sec-left-article-sec-right[data-transact=in] {color: #99ff99;}
		.today-flow-item-sec-left-article-sec-right[data-transact=ex] {color: #ff9999;}
		.today-flow-item-sec-left-article-sec-right[data-transact=tr] {color: #66b3ff;}
		.today-flow-item-sec-left label {
			display: block;
			width: 100%;
		  height: 15%;
		  min-height: 5em;
			position: absolute;
			left: 0;
			top: 0;
			cursor: pointer;
		}
		.today-flow-item-sec-right {
			width: 5%;
			height: 100%;
			float: right;
			cursor: pointer;
			border-left: 0.2em solid ghostwhite;
			background-image: url(/smartacc/media/icon-3dots@48px-20gray.png);
			background-repeat: no-repeat;
			background-position: center;
			background-size: .3em;
		}
		.today-flow-item-sec-right.menu-active {
			background-color: #e6e6e6;
		}
		.today-flow-item-sec-right:hover {
			background-color: #e6e6e6;
		}
		.today-flow-item-hidden-menu {
			position: absolute;
		  max-width: 0;
		  width: 50%;
		  right: 5%;
		  height: 100%;
		  display: flex;
		  background-color: #e6e6e6;
		  overflow: hidden;
		  transition: max-width 0.2s ease-out;
		}
		.today-flow-item-hidden-menu.menu-active {
			border-left: 4px solid #b3b3b3;
		}
		.today-flow-item-hidden-menu > section {
			width: 50%;
			height: 100%;
			display: inline-flex;
			justify-content: center;
			align-items: center;
			background-repeat: no-repeat;
			background-position: center;
			background-size: 2em;
			cursor: pointer;
		}
		.today-flow-item-hidden-menu > section:hover {
			background-color: #d9d9d9;
		}
		.today-flow-item-hidden-menu .hidden-menu-sec-1 {
			background-image: url(/smartacc/media/icon-bin@48px-000.png);
		}
		.today-flow-item-hidden-menu .hidden-menu-sec-2 {
			background-image: url(/smartacc/media/icon-edit@48px-000.png);
		}
		.today-flow-item-hidden-detail {
			overflow: hidden;
			max-height: 0;
			width: 100%;
			background-color: floralwhite;
			padding: 0 18px;
			transition: max-height 0.2s ease-out;
		}
		.today-flow-item-hidden-detail span {
		  font-weight: 600;
		  color: red;
		}
		.today-no-transaction {
			width: 100%;
			height: 100%;
			display: flex;
			justify-content: center;
			align-items: center;
			background-color: #cccccc;
		  color: #f2f2f2;
		  text-shadow: 0px 0px 5px #b3b3b3;
			font-weight: 900;
		}

		/* --- TODAY-REPORTS --- */
		.today-reports-sec {
		  width: 100%;
		  height: 2em;
		  color: #f2f2f2;
		  background-color: #808080;
		  border-bottom: 1px solid black;
		}
		.today-reports-sec > span {
		  width: 90%;
		  height: 100%;
		  display: flex;
		  align-items: center;
		  padding-left: 10px;
		  font-weight: bold;
		  float: left;
		  cursor: pointer;
		}
		.open-icon {
		  width: 10%;
		  height: 100%;
		  float: right;
		  background-image: url(/smartacc/media/icon-arrow-down@100px.png);
			background-repeat: no-repeat;
			background-position: center;
			background-size: 1em;
		  cursor: pointer;
		}
		.today-reports-hidden-detail {
		  width: 100%;
		  max-height: 2em;
		  background-color: #f2f2f2;
		  overflow: hidden;
		  transition: max-height 0.2s ease-out;
		}
		.preloader {
		  width: 2em;
		  height: 2em;
		  margin: auto;
		  background-image: url(/smartacc/media/preloader-snake@100px.gif);
		  background-repeat: no-repeat;
		  background-position: center;
		  background-size: 1.5em;
		}
		/* --- TODAY REPORTS TABLE --- */
		.today-reports-table {
		  width: 100%;
		  margin: 0.5em auto;
		  /*padding: 0.5em;*/
		  border-spacing: 0;
		  border-collapse: collapse;
		  border: 1px solid #cccccc;
		  background-color: white;
		}
		.today-reports-table thead {
		  background-color: #999999;
		  color: white;
		}
		.today-reports-table tr {
		  border-bottom: 1px solid #cccccc;
		}
		.today-reports-table td {
		  padding: .25em;
		}
		.today-reports-table thead td {
		  text-align: center;
		  text-transform: uppercase;
		  font-weight: 700;
		  text-shadow: 0px 0px 2px #808080;
		}
		.today-reports-table tbody tr:first-of-type td {
		  /*text-align: center;*/
		  font-weight: 700;
		}
		.today-reports-table tbody tr:first-of-type td:last-of-type {
			text-align: right;
		}
		.today-reports-table tbody tr:last-of-type td {
			font-weight: 700;
		}
		.today-reports-table tbody tr:not(:first-of-type) td:last-of-type {
			text-align: right;
		}
		.today-reports-table tbody tr:nth-child(odd):not(:last-of-type):not(:first-of-type) {
			background-color: #d9d9d9;
		}
		/* TABLE ACCOUNT */
		.report-account table {width: 90%;}
		/* TABLE GENERAL */
		.report-general-output table thead {display: none;}


		/* --- TODAY-FOOTER ---*/
		/*footer {
			display: flex;
			justify-content: center;
			align-items: center;
			color: white;
			background-color: #1a1a1a;
			cursor: pointer;
			transition: background-color 0.4s ease-out;
		}
		footer:hover {
			background-color: #333333;
		}
		footer:active {
			background-color: black;
		}*/

		/* --- test-report --- */
		.report-general {
			text-align: center;
			border: none;
		}
		/*
		.tab {
			width: 100%;
			height: 10%;
			min-height: 2em;
		}
		*/
		.report-general-tab {
			width: 50%;
			height: 100%;
			min-height: 2em;
			display: inline-flex;
			justify-content: center;
			align-items: center;
			/*background-color: white;*/
			cursor: pointer;
			color: rgba(215,215,215,0.5);
			transition: color .4s ease-out;
		}
		.report-general-tab:hover {
			color: rgba(215,215,215,0.8);
		}
		.report-general-tab._gentab-active, div.report-tab._sheettab-active {
			color: #f2f2f2;
			font-weight: bold;
		}
		#exTab {float: left;}
		#inTab {float: right;}
		.report-general-output {
			width: 100%;
			height: 90%;
			padding: .5em;
			background-color: #f2f2f2;
		}
		.output-sec {
			width: 100%;
			height: 100%;
			display: none;
		}
		.output-sec > * {
			width: 100%;
		}
		.output-sec > header {
			height: 20%;
			min-height: 4em;
		}
		.output-sec > article {
			height: 80%;
			min-height: 18em;
			position: relative;
			/*
			display: flex;
		  justify-content: center;
		  align-items: center;
		  */
		}
		._output-active {
			display: block;
		}
		.report-folder {
			width: 100%;
			height: 100%;
		}
		.report-tab-wrapper {
			width: 100%;
			height: 10%;
			min-height: 2em;
			display: flex;
		}
		.report-tab {
			width: 25%;
			height: 100%;
			min-height: 2em;
			display: inline-flex;
		  justify-content: center;
		  align-items: center;
		  background-color: #999;
		  color: rgba(215,215,215, 0.5);
		  /*border-top-left-radius: 10px;
		  border-top-right-radius: 10px;*/
		  cursor: pointer;
		  transition: color .4s ease-out;
		}
		.report-tab:hover {
			color: rgba(215,215,215, 0.8);
		}
		.report-sheet-wrapper {
			width: 100%;
			height: 90%;
			position: relative;
		}
		.report-sheet {
			width: 100%;
			height: 100%;
			min-height: 15em;
			position: absolute;
			/*top: 5%;
			left: 5%;*/
			/*background-color: white;*/
			display: none;
		}
		.report-sheet._sheet-active {
			display: block;
		}
	</style>
</head>
<body>
	<?php include("frame1-header.html");?>
	<main style="overflow: hidden;">
		<header class="today-header">
			<section class="today-header-sec-1 today-header-sec">
				<section class="sec-1-left" title="Previous day"></section>
				<section class="sec-1-center">
					<input type="date" name="todayDate" id="todayDate"/>
					<!--label for="todayDate"></label-->
				</section>
				<section class="sec-1-right" title="Next day"></section>
			</section>
			<section class="today-header-sec-2 today-header-sec">
				<section class="sec-2-left _header-active" data-section-name="flow">Flow</section>
				<section class="sec-2-center" data-section-name="budget">Budget</section>
				<section class="sec-2-right" data-section-name="reports">Reports</section>
			</section>
		</header>
		<article class="today-article">
			<section class="today-article-sec-1 _article-active" data-section-name="flow">
			</section>
			<section class="today-article-sec-2" data-section-name="budget">
				BUDGET
			</section>
			<section class="today-article-sec-3" data-section-name="reports">
				<!--div class="report-account">ACCOUNT
				</div-->
				<div class="today-reports-sec report-account">
					<span>Account</span>
					<div class="open-icon"></div>
				</div>
				<div class="today-reports-hidden-detail report-account">
					<div class="preloader"></div>
				</div>
				<!--div class="today-reports-sec reports-general reports-active">
					<span>General</span>
					<div class="open-icon"></div>
				</div-->
				<!--div class="report-general"-->
					<div class="today-reports-sec report-general">
						<div id="exTab" class="report-general-tab _gentab-active" title="Expense report">
							Expense
						</div>
						<div id="inTab" class="report-general-tab" title="Income report">
							Income
						</div>
					</div>
					<div class="report-general-output">
						<div class="output-sec output-ex _output-active">
							<header>
								<span class="output-totalamount">Total amount: <em></em></span><br>
								<span class="output-transactnum">Transaction(s): <em></em></span>
							</header>
							<article>
								<div class="report-folder">
									<div class="report-tab-wrapper">
										<div class="report-tab tab-necessity _sheettab-active">Necessity</div>
										<div class="report-tab tab-category">Category</div>
										<div class="report-tab tab-subcategory">Subcategory</div>
										<div class="report-tab tab-payee">Payee</div>
									</div>
									<div class="report-sheet-wrapper">
										<div class="report-sheet sheet-necessity _sheet-active">
											<div class="preloader"></div>
										</div>
										<div class="report-sheet sheet-category"></div>
										<div class="report-sheet sheet-subcategory"></div>
										<div class="report-sheet sheet-payee"></div>
									</div>
								</div>
							</article>
						</div>
						<div class="output-sec output-in">
							<header>
								<span class="output-totalamount">Total amount: <em></em></span><br>
								<span class="output-transactnum">Transaction(s): <em></em></span>
							</header>
							<article>
								<div class="report-folder">
									<div class="report-tab-wrapper">
										<div class="report-tab tab-necessity _sheettab-active">Income type</div>
										<div class="report-tab tab-category">Category</div>
										<div class="report-tab tab-subcategory">Subcategory</div>
										<div class="report-tab tab-payee">Payer</div>
									</div>
									<div class="report-sheet-wrapper">
										<div class="report-sheet sheet-necessity _sheet-active">
											<div class="preloader"></div>
										</div>
										<div class="report-sheet sheet-category"></div>
										<div class="report-sheet sheet-subcategory"></div>
										<div class="report-sheet sheet-payee"></div>
									</div>
								</div>
							</article>
						</div>
					</div>
				<!--/div-->
				<!--div class="today-reports-sec reports-account reports-active">
					<span>Account</span>
					<div class="open-icon"></div>
				</div>
				<div class="today-reports-hidden-detail reports-account">
					<div class="preloader"></div>
				</div>
				<div class="today-reports-sec reports-general reports-active">
					<span>General</span>
					<div class="open-icon"></div>
				</div>
				<div class="today-reports-hidden-detail reports-general">
					<div class="preloader"></div>
				</div>
				<div class="today-reports-sec reports-deep reports-active">
					<span>Deep</span>
					<div class="open-icon"></div>
				</div>
				<div class="today-reports-hidden-detail reports-deep">
					<div class="preloader"></div>
				</div-->
			</section>
		</article>
	</main>
	<?php include("frame1-footer.html");?>
	<script type="text/javascript">
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
				changeDay(-1);
			});
			nextDayTab.addEventListener("click", function() {
				changeDay(1);
			});

			// --- 1.4 TAB ALTERNATION ON CLICK ---
			var headerSec2Tab = document.querySelectorAll(".today-header-sec-2 > section"),
					articleSec = document.querySelectorAll(".today-article > section");
			tabAndOutputDisplay(headerSec2Tab, articleSec, "click", "_header-active", "_article-active");
			// show account report when user select report tab
			var selectReportCount = 0;
			headerSec2Tab[2].addEventListener("click", function() {
				selectReportCount++;
				if(selectReportCount > 1) {return false;}
				let accHidden = document.querySelector(".today-reports-hidden-detail");
				accHidden.style.maxHeight = accHidden.scrollHeight + "px";
				accHidden.previousElementSibling.classList.add("_report-active");
			});

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
									accHidden = sec.querySelector(".today-reports-hidden-detail"),
									exAmount = document.querySelector(".output-ex .output-totalamount em"),
									inAmount = document.querySelector(".output-in .output-totalamount em"),
									exTransact = document.querySelector(".output-ex .output-transactnum em"),
									inTransact = document.querySelector(".output-in .output-transactnum em"),
									exSheets = document.querySelectorAll(".output-ex .report-sheet"),
									inSheets = document.querySelectorAll(".output-in .report-sheet"),
									i;
							accHidden.innerHTML = json.acc;
							accHidden.style.maxHeight = accHidden.scrollHeight + "px";
							accHidden.previousElementSibling.classList.add("_report-active");
							// Insert expense data
							exAmount.innerHTML = json.totalAmount[0];
							exTransact.innerHTML = json.transact[0];
							for(i = 0; exSheets[i]; i++) {
								exSheets[i].innerHTML = json.ex[i];
							}
							// Insert income data
							inAmount.innerHTML = json.totalAmount[1];
							inTransact.innerHTML = json.transact[1];
							for(i = 0; inSheets[i]; i++) {
								inSheets[i].innerHTML = json.in[i];
							}
						}
					}
				}
				xhr.send(fd);
			}
			//---
			function changeDay(dayNum) {
				console.log("--- changeDay(dayNum) ---");
				if(typeof dayNum !== "number") {
					return false;
				}
				let dateElem = document.querySelector("input[type=date][name=todayDate]"),
						dMS = Date.parse(dateElem.value),
						oneDay = 84000000,
						d;
				if(dayNum === 1) {
					d = new Date(dMS + oneDay).toLocaleDateString();
				} else if(dayNum === -1) {
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
				for(let i = 0; menuActive[i]; i++) {
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
				for(let i = 0; transactItems[i]; i++) {
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
			// --- 2.3.1 OPEN & CLOSE REPORT-ACCOUNT PANEL ---
			var accSec = document.querySelector(".report-account");
			accSec.addEventListener("click", function() {
				console.log("--- accSec.onclick() ---");
				// Unclickable until successfully fetched data.
				if(this.nextElementSibling.children.length <= 1) {
					return false;
				}
				let hiddenDetail = this.nextElementSibling,
						mh = hiddenDetail.style.maxHeight;
				if(mh == "0px"
						&& !this.classList.contains("_report-active")) {
					hiddenDetail.style.maxHeight = hiddenDetail.scrollHeight + "px";
				} else if(mh != "0px"
						&& this.classList.contains("_report-active")) {
					hiddenDetail.style.maxHeight = 0;
				}
				this.classList.toggle("_report-active");
			});
			// --- 2.3.2 INCOME/EXPENSE REPORT TAB SELECTION ---
			var genTabs = document.querySelectorAll(".report-general-tab"),
					genOutputs = document.querySelectorAll(".output-sec");
			tabAndOutputDisplay(genTabs, genOutputs, "click", "_gentab-active", "_output-active");
			// --- 2.3.3 DATA SHEET TAB AND OUTPUT DISPLAYING ---
			var exSheetTabs = document.querySelectorAll(".output-ex .report-tab"),
					exSheets = document.querySelectorAll(".output-ex .report-sheet");
			tabAndOutputDisplay(exSheetTabs, exSheets, "click", "_sheettab-active", "_sheet-active");
			var inSheetTabs = document.querySelectorAll(".output-in .report-tab"),
					inSheets = document.querySelectorAll(".output-in .report-sheet");
			tabAndOutputDisplay(inSheetTabs, inSheets, "click", "_sheettab-active", "_sheet-active");

		function tabAndOutputDisplay(tabs, outputs, eventListener, tabClass, outputClass) {
			console.log("--- tabAndOutputDisplay() ---");
			if(typeof tabs != 'object'
					|| typeof outputs != 'object'
					|| typeof eventListener != 'string'
					|| typeof tabClass != 'string'
					|| typeof outputClass != 'string') {
				console.log("Error: incorrect argument!");
				return false;
			}
			for(let i = 0; tabs[i]; i++) {
				tabs[i].addEventListener(eventListener, function() {
					console.log("--- tabs[i].onclick ---");
					// remove unselected tab and its output class
					var i;
					for(i = 0; tabs[i]; i++) {
						tabs[i].classList.remove(tabClass);
						outputs[i].classList.remove(outputClass);
					}
					// find index of tab and its output
					let x = this;
					for(i = 0; (x = x.previousElementSibling); i++);
					// add class to selected tab and its output
					this.classList.add(tabClass);
					outputs[i].classList.add(outputClass);
				});
			}
		}
		});
	</script>
</body>
</html>