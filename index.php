<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Home | SMARTACC</title>
	<link rel="stylesheet" type="text/css" href="css/index-style.css">
	<link rel="stylesheet" type="text/css" href="css/today-style.css">
</head>
<body>
	<header class="index-header col-12 col-m-12">
    <section class="header-left">
     	<img class='icon' src="media/icon-menu@48px-fff.png" alt="Menu icon"/>
    </section>
    <section class="header-center">
      <a href="index.php"><h1>Smartacc</h1></a>
    </section>
    <section class="header-right">
      <a href="new-record.php">
      	<img class="icon" width="30px" src="media/icon-add@48px-fff.png" alt="Add icon"/>
      </a>
    </section>
	</header>
  <aside class="sidebar sidebar-maxwidth sidebar-minwidth">
    <header>
    	<section class="sidebar-header-left">
      	<h1>Menu</h1>
      </section>
      <section class="sidebar-header-right">
      	<span>&#10005</span>
      </section>
    </header>
    <nav>
	    <ul>
	      <li>Today</li>
	      <a href="report.php"><li>Reports</li></a>
	      <li>Budget</li>
	      <li>Export/Import</li>
	      <li>Setting</li>
	      <li>About</li>
	      <li>Help</li>
	      <a href="icon-attr.html"><li>Icon attribution</li></a>
	      <output></output>
	    </ul>
	   </nav>
  </aside>
  <div class="sidebar-background"></div>
	<main style="width:100%; height:80%; overflow: hidden;">
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
				<section class="sec-2-left header-active" data-section-name="flow">Flow</section>
				<section class="sec-2-center" data-section-name="budget">Budget</section>
				<section class="sec-2-right" data-section-name="reports">Reports</section>
			</section>
		</header>
		<article class="today-article">
			<section class="today-article-sec-1 article-active" data-section-name="flow">
			</section>
			<section class="today-article-sec-2" data-section-name="budget">
				BUDGET
			</section>
			<section class="today-article-sec-3" data-section-name="reports">
				<div class="report-account">ACCOUNT
				</div>
				<div class="report-general">
					<div class="tab">
						<div id="exTab" class="tab-sec" title="Expense report">
							<span>Expense</span>
						</div>
						<div id="inTab" class="tab-sec" title="Income report">
							<span>Income</span>
						</div>
					</div>
					<div class="output">
						<div id="exOutput" class="output-sec">
							<header>
								<p>Total amount: ###</p>
								<p>Transaction(s): ###</p>
							</header>
							<article>
								<div class="report-sheet"></div>
								<div class="report-sheet"></div>
								<div class="report-sheet"></div>
								<div class="report-sheet"></div>
							</article>
						</div>
						<div id="inOutput" class="output-sec"></div>
					</div>
				</div>
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
	<a href="new-record.php">
		<footer style="width: 100%;height: 10%; min-height: 3em;">New record</footer>
	</a>
	<script src="js/index-script.js"></script>
	<script src="js/today-script.js"></script>
</body>
</html>