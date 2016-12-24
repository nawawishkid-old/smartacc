<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style-overview.css">
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
	?>
	<div id="overviewWrapper" class="wrapper">
		<div id="ovCashflowDiv" class="ovDiv">
			<form id="ovCashflowForm" method='post'>
				<h3>Cashflow of the 
					<select id="ovCashflowSelect" name="ovCashflowSelect">
						<option class="ov-cashflow-filter-option" value="year">year</option>
						<option class="ov-cashflow-filter-option" value="month">month</option>
						<option class="ov-cashflow-filter-option" value="week">week</option>
						<option class="ov-cashflow-filter-option" value="day">day</option>
					</select>
				</h3>
			</form>
			<div id="ovCashflowViewModeDiv">
				<span>View mode:</span>
				<select id="ovCashflowViewModeSelect">
					<option class="ov-cashflow-viewmode-option" value="table">table</option>
					<option class="ov-cashflow-viewmode-option" value="graph">graph</option>
				</select>
			</div>
			<div id="ovCashflowOutputDiv">
				<?php
					// === INCOME AND EXPENSE OF THE YEAR === //
					$str1 = "SELECT DISTINCT year FROM record;";

					$query = mysqli_query($con, $str1)
						or die ("Error: Could not send query, " . mysqli_error($con));
					$result = mysqli_fetch_all($query, MYSQLI_ASSOC)
						or die ("Error: Could not fetch data, " . mysqli_error($con));

					//print_r($result);

					for($i = 0; @$result[$i]; $i++) {
						$str2 = "SELECT DISTINCT
											year,
											(
								        SELECT SUM(amount)
								        FROM record
								        WHERE transaction_type = 'in' AND year = {$result[$i]['year']}
									    ) AS TotalIncome,
									    (
								        SELECT SUM(amount)
								        FROM record
								        WHERE transaction_type = 'ex' AND year = {$result[$i]['year']}
									    ) AS TotalExpense
										FROM record
										GROUP BY year;";

						$query = mysqli_query($con, $str2)
							or die ("Error: Could not send query, " . mysqli_error($con));
						$result = mysqli_fetch_all($query, MYSQLI_ASSOC)
							or die ("Error: Could not fetch data, " . mysqli_error($con));

						$year = $result[$i]['year'];
						$in = $result[$i]['TotalIncome'];
						$ex = $result[$i]['TotalExpense'];
						$in = ($in == "" ? 0 : $in);
						$ex = ($ex == "" ? 0 : $ex);
				?>
				<table id="ovCashflowTable" class="">
					<thead id="ovCashflowTHead">
						<tr>
							<th>Year</th>
							<th>Total income</th>
							<th>Total expense</th>
						</tr>
					</thead>
					<tbody id="ovCashflowTBody">
						<?php
							echo "<tr>
											<td class='ov-cashflow-table-key table-key col-3'>{$year}</td>
											<td class='ov-cashflow-table-val table-val col-3 plus'>{$in}</td>
											<td class='ov-cashflow-table-val table-val col-3 minus'>{$ex}</td>
										</tr>";
							}

							mysqli_close($con);
						?>
					</tbody>
				</table>
				<svg id="ovCashflowSvg" class="hide" width="100%" height="100%">
				</svg>
			</div>
		</div>
	</div>

	<script src="script-overview.js"></script>
</body>
</html>
