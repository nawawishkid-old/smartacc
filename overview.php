<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/style-overview.css">
</head>
<body>
	<?php
		$con = mysqli_connect("localhost", "root", "", "smartacc")
		or die ("Error: connection failed, " . mysqli_connect_error());
		mysqli_query($con, "SET character_set_results=utf8")
		or die ("Error: Cannot set character set result.");
		mysqli_query($con, "SET character_set_client=utf8")
		or die ("Error: Cannot set character set client.");
		mysqli_query($con, "SET character_set_connection=utf8")
		or die ("Error: Cannot set character set connection.");

		// === SET DEFAULT <TH> CHECKBOX ===
		// --- YEAR ---
		$str1 = "SELECT DISTINCT year
							FROM cashflow_yearly
							ORDER BY year DESC;";
		$query1 = mysqli_query($con, $str1) or die ("Error: could not send query1, " . mysqli_error($con));
		// --- MONTH ---
		$str2 = "SELECT DISTINCT month
							FROM cashflow_monthly
							ORDER BY month ASC;";
		$query2 = mysqli_query($con, $str2) or die ("Error: could not send query2, " . mysqli_error($con));
		$month_list = array("January", "February", "March", 
											"April", "May", "June", "July",
											"August", "September", "October",
											"November", "December");
		// --- WEEK ---
		$str3 = "SELECT DISTINCT week
							FROM cashflow_weekly
							ORDER BY week ASC;";
		$query3 = mysqli_query($con, $str3) or die ("Error: could not send query3, " . mysqli_error($con));
		// --- DAY ---
		$str4 = "SELECT DISTINCT day
							FROM cashflow_daily
							ORDER BY day ASC;";
		$query4 = mysqli_query($con, $str4) or die ("Error: could not send query4, " . mysqli_error($con));
	?>
	<section id="overviewWrapper" class="ov-wrapper">
		<section id="ovCashflowDiv" class="ov-section ov-cashflow-section">
			<form id="ovCashflowForm" method='post'></form>
			<header class="ov-cashflow-header">
				<section class="ov-header-left">
					<h3>Cashflow of the
						<select id="ovCashflowFilterSelect" name="ovCashflowFilterSelect" form="ovCashflowForm">
							<option class="ov-cashflow-filter-option" value="year">year</option>
							<option class="ov-cashflow-filter-option" value="month">month</option>
							<option class="ov-cashflow-filter-option" value="week">week</option>
							<option class="ov-cashflow-filter-option" value="day">day</option>
						</select>
					</h3>
				</section>
				<section id="ovCashflowViewModeDiv" class="ov-header-right">
					<span>View mode:</span>
					<select id="ovCashflowViewModeSelect">
						<option class="ov-cashflow-viewmode-option" value="table">table</option>
						<option class="ov-cashflow-viewmode-option" value="graph">graph</option>
					</select>
				</section>
			</header>
			<article id="ovCashflowOutputDiv">
				<form style="display:none" id="ovCashflowThForm" class="ovCashflowThForm"></form>
				<table id="ovCashflowTable">
					<thead id="ovCashflowTHead">
						<tr>
							<th class="ov-cashflow-th">
									<div class="ov-cashflow-th-select-div" data-set="1">
									  <div class="ov-cashflow-th-select-box" data-set="1">
									    <select class="ov-cashflow-th-select" data-set="1">
									      <option class="ov-cashflow-th-option" data-set="1">YEAR</option>
									    </select>
									    <div class="ov-cashflow-th-select-prevent" data-set="1"></div>
									  </div>
									  <div class="ov-cashflow-th-checkbox-div" data-set="1">
									    <label class="ov-cashflow-th-checkbox-label" for="ovCashflowThCheckboxAll1" data-set="1">
									      <input type="checkbox" id="ovCashflowThCheckboxAll1" class="ov-cashflow-th-checkbox-input-1 ov-cashflow-th-checkbox-input-all" form="ovCashflowThForm" data-set="1" checked>All
									    </label>
									  	<div class="ov-cashflow-th-checkbox-wrapper" data-set="1">
										    <?php
										    	$result1 = mysqli_fetch_all($query1, MYSQLI_NUM) or die ("Error: could not fetch data from query1, " . mysqli_error($con));
										    	if($result1) {
										    		for($i = 0; @$result1[$i]; $i++) {
										    			$x = ($i + 1);
										    			$year = $result1[$i][0];

										    			echo "<label class='ov-cashflow-th-checkbox-label' for='ovCashflowThForm1Checkbox{$x}' data-set='1'>
										    							<input type='checkbox' name='ovCashflowThForm1Checkbox[]' id='ovCashflowThForm1Checkbox{$x}' class='ov-cashflow-th-checkbox-input-1' form='ovCashflowThForm' value='{$year}' data-set='1' checked>{$year}
										    						</label>";
										    		}
										    	}
										    ?>
										  </div>
										  <button type="button" form="ovCashflowThForm" class="ov-cashflow-th-form-submit" data-set="1">submit</button>
									  </div>
									</div>
							</th>
							<th class="ov-cashflow-th hide">
								  <div class="ov-cashflow-th-select-div" data-set="2">
								    <div class="ov-cashflow-th-select-box" data-set="2">
								      <select class="ov-cashflow-th-select" data-set="2">
								        <option class="ov-cashflow-th-option" data-set="2">MONTH</option>
								      </select>
								      <div class="ov-cashflow-th-select-prevent" data-set="2"></div>
								    </div>
								    <div class="ov-cashflow-th-checkbox-div" data-set="2">
								      <label class="ov-cashflow-th-checkbox-label" for="ovCashflowThCheckboxAll2" data-set="2">
								        <input type="checkbox" id="ovCashflowThCheckboxAll2" class="ov-cashflow-th-checkbox-input-2 ov-cashflow-th-checkbox-input-all" form="ovCashflowThForm" data-set="2" checked>All
								      </label>
									  	<div class="ov-cashflow-th-checkbox-wrapper" data-set="2">
										    <?php
										    	$result2 = mysqli_fetch_all($query2, MYSQLI_NUM) or die ("Error: could not fetch data from query2, " . mysqli_error($con));
										    	if($result2) {
										    		for($i = 0; @$result2[$i]; $i++) {
										    			$x = ($i + 1);

										    			echo "<label class='ov-cashflow-th-checkbox-label' for='ovCashflowThForm2Checkbox{$x}' data-set='2'>
										    							<input type='checkbox' name='ovCashflowThForm2Checkbox[]' id='ovCashflowThForm2Checkbox{$x}' class='ov-cashflow-th-checkbox-input-2' form='ovCashflowThForm' value='{$result2[$i][0]}' data-set='2' checked>{$month_list[$result2[$i][0] - 1]}
										    						</label>";
										    		}
										    	}
										    ?>
									    </div>
									  	<button type="button"  form="ovCashflowThForm" class="ov-cashflow-th-form-submit" data-set="2">submit</button>
								    </div>
								  </div>
							</th>
							<th class="ov-cashflow-th hide">
								  <div class="ov-cashflow-th-select-div" data-set="3">
								    <div class="ov-cashflow-th-select-box" data-set="3">
								      <select class="ov-cashflow-th-select" data-set="3">
								        <option class="ov-cashflow-th-option" data-set="3">WEEK</option>
								      </select>
								      <div class="ov-cashflow-th-select-prevent" data-set="3"></div>
								    </div>
								    <div class="ov-cashflow-th-checkbox-div" data-set="3">
								      <label class="ov-cashflow-th-checkbox-label" for="ovCashflowThCheckboxAll3" data-set="3">
								        <input type="checkbox" id="ovCashflowThCheckboxAll3" class="ov-cashflow-th-checkbox-input-3 ov-cashflow-th-checkbox-input-all" form="ovCashflowThForm" data-set="3" checked>All
								      </label>
									  	<div class="ov-cashflow-th-checkbox-wrapper" data-set="3">
										    <?php
										    	$result3 = mysqli_fetch_all($query3, MYSQLI_NUM) or die ("Error: could not fetch data from query3, " . mysqli_error($con));
										    	if($result3) {
										    		for($i = 0; @$result3[$i]; $i++) {
										    			$x = ($i + 1);
										    			$week = $result3[$i][0];

										    			echo "<label class='ov-cashflow-th-checkbox-label' for='ovCashflowThForm3Checkbox{$x}' data-set='3'>
										    							<input type='checkbox' name='ovCashflowThForm3Checkbox[]' id='ovCashflowThForm3Checkbox{$x}' class='ov-cashflow-th-checkbox-input-3' form='ovCashflowThForm' value='{$week}' data-set='3' checked>{$week}
										    						</label>";
										    		}
										    	}
										    ?>
									    </div>
									    <button type="button"  form="ovCashflowThForm" class="ov-cashflow-th-form-submit" data-set="3">submit</button>
								    </div>
								  </div>
							</th>
							<th class="ov-cashflow-th hide">
								  <div class="ov-cashflow-th-select-div" data-set="4">
								    <div class="ov-cashflow-th-select-box" data-set="4">
								      <select class="ov-cashflow-th-select" data-set="4">
								        <option class="ov-cashflow-th-option" data-set="4">DAY</option>
								      </select>
								      <div class="ov-cashflow-th-select-prevent" data-set="4"></div>
								    </div>
								    <div class="ov-cashflow-th-checkbox-div" data-set="4">
								      <label class="ov-cashflow-th-checkbox-label" for="ovCashflowThCheckboxAll4" data-set="4">
								        <input type="checkbox" id="ovCashflowThCheckboxAll4" class="ov-cashflow-th-checkbox-input-4 ov-cashflow-th-checkbox-input-all" form="ovCashflowThForm" data-set="4" checked>All
								      </label>
									  	<div class="ov-cashflow-th-checkbox-wrapper" data-set="4">
										    <?php
										    	$result4 = mysqli_fetch_all($query4, MYSQLI_NUM) or die ("Error: could not fetch data from query4, " . mysqli_error($con));
										    	if($result4) {
										    		for($i = 0; @$result4[$i]; $i++) {
										    			$x = ($i + 1);
										    			$day = $result4[$i][0];

										    			echo "<label class='ov-cashflow-th-checkbox-label' for='ovCashflowThForm4Checkbox{$x}' data-set='4'>
										    							<input type='checkbox' name='ovCashflowThForm4Checkbox[]' id='ovCashflowThForm4Checkbox{$x}' class='ov-cashflow-th-checkbox-input-4' form='ovCashflowThForm' value='{$day}' data-set='4' checked>{$day}
										    						</label>";
										    		}
										    	}
										    ?>
									    </div>
									    <button type="button"  form="ovCashflowThForm" class="ov-cashflow-th-form-submit" data-set="4">submit</button>
								    </div>
								  </div>
							</th>
							<th class="ov-cashflow-th">Total Income</th>
							<th class="ov-cashflow-th">Total Expense</th>
						</tr>
					</thead>
					<tbody id="ovCashflowTBody">
						<?php
							$str = "SELECT year, total_income, total_expense
											FROM cashflow_yearly
											ORDER BY year DESC;";
							$query = mysqli_query($con, $str) or die ("Error: could not send LAST query, " . mysqli_error($con));
							$result = mysqli_fetch_all($query, MYSQLI_ASSOC) or die ("Error: could not fetch LAST data, " . mysqli_error($con));

							if($result) {
								for($i = 0; @$result[$i]; $i++) {
									echo "<tr>
													<td class='ov-td-key'>{$result[$i]['year']}</td>
													<td class='ov-td-value plus'>{$result[$i]['total_income']}</td>
													<td class='ov-td-value minus'>{$result[$i]['total_expense']}</td>
												</tr>";
								}
							}

							mysqli_close($con);
						?>
					</tbody>
				</table>
				<svg id="ovCashflowSvg" class="hide" width="100%" height="100%">
				</svg>
			</article>
		</section>
	</section>

	<script src="js/script-overview.js"></script>
</body>
</html>
