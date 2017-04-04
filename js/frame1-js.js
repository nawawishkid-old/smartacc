// --- newindex-script.js ---
window.addEventListener("load", function() {
	console.log("=== window.onload ===");
	// ----- 1. HEADER -----
	// --- 1.1 SIDEBAR NAVIGATION ---
	var navicon = document.querySelector("section.header-left img");
	var sidebarBackground = document.querySelector(".sidebar-background");
	// --- 1.1.1 SHOW SIDEBAR NAVIGATION ---
	navicon.onclick = function() {
		console.log("--- navicon.onclick ---");
		var sidebar = document.querySelector(".sidebar");
		sidebar.style.left = "-" + sidebar.clientWidth + "px";
		var i = sidebar.clientWidth;
		var runtime = (i > 200 ? 3 : 6);
		sidebarBackground.style.display = "block";
		var animate = setInterval(function() {
			if(i <= 0) {
				clearInterval(animate);
				sidebar.style.left = "0px";
			} else {
				i = i - 10;
				sidebar.style.left = "-" + i + "px";
			}
		}, runtime);
	}
	// --- 1.1.2 HIDE SIDEBAR NAVIGATION ---
	// --- 1.1.2.1 HIDE BY CLICKING CLOSE ICON ---
	var closeIcon = document.querySelector(".sidebar header span");
	closeIcon.onclick = function() {
		console.log("--- closeIcon.onclick ---");
		hideSidebar();
	}
	// --- 1.1.2.2 HIDE BY CLICKING BACKGROUND ---
	var sidebarBackground = document.querySelector(".sidebar-background");
	sidebarBackground.onclick = function() {
		console.log("--- sidebarBackground.onclick ---");
		hideSidebar();
	}
	// --- 1.1.FUNCTION ---
	function hideSidebar() {
		console.log("--- hideSidebar() ---");
		var sidebar = document.querySelector(".sidebar");
		var i = 0;
		var runtime = (sidebar.clientWidth > 200 ? 3 : 6);
		sidebarBackground.style.display = "none";
		var animate = setInterval(function() {
			if(i >= sidebar.clientWidth) {
				clearInterval(animate);
				sidebar.style.left = "-100%";
			} else {
				i = i + 10;
				sidebar.style.left = "-" + i + "px";
			}
		}, runtime);
	}
});