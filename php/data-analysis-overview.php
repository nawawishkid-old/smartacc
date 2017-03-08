<?php
	$con = mysqli_connect("localhost", "root", "", "smartacc")
		or die ("Error: Could not connect to database, " . mysqli_error($con));
	mysqli_query($con, "SET character_set_results=utf8")
		or die ("Error: Could not set character set results, " . mysqli_error($con));
	mysqli_query($con, "SET character_set_client=utf8")
		or die ("Error: Could not set character set client, " . mysqli_error($con));
	mysqli_query($con, "SET character_set_connection=utf8")
		or die ("Error: Could not set character set connection, " . mysqli_error($con));

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if($_POST['ovCashflowFilterSelect']) {
			$postdata = $_POST['ovCashflowFilterSelect'];
			$month_list = array("January", "February", "March", 
											"April", "May", "June", "July",
											"August", "September", "October",
											"November", "December");
		}
	}
	// ==================== //
	// ===== CASHFLOW ===== //
	// ==================== //
	// === SET TABLE HEAD COULUMNS DEPEND ON WHAT USER SELECTED. === //
	switch($postdata) {
		case "year":
			// --- OF THE YEAR --- //
			$str = "SELECT year, total_income, total_expense
							FROM cashflow_yearly
							ORDER BY year DESC;";
			$query = mysqli_query($con, $str)
				or die ("Error: could not send LAST query, " . mysqli_error($con));
			$result = mysqli_fetch_all($query, MYSQLI_ASSOC)
				or die ("Error: could not fetch LAST data, " . mysqli_error($con));

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

			break;

		case "month":
			// --- OF THE MONTH --- //
			$str = "SELECT year, month, total_income, total_expense
							FROM cashflow_monthly
							ORDER BY year DESC,
								month DESC;";
			$query = mysqli_query($con, $str)
				or die ("Error: could not send LAST query, " . mysqli_error($con));
			$result = mysqli_fetch_all($query, MYSQLI_ASSOC)
				or die ("Error: could not fetch LAST data, " . mysqli_error($con));

			if($result) {
				for($i = 0; @$result[$i]; $i++) {
					echo "<tr>
									<td class='ov-td-key'>{$result[$i]['year']}</td>
									<td class='ov-td-key'>{$month_list[$result[$i]['month'] - 1]}</td>
									<td class='ov-td-value plus'>{$result[$i]['total_income']}</td>
									<td class='ov-td-value minus'>{$result[$i]['total_expense']}</td>
								</tr>";
				}
			}

			mysqli_close($con);
			
			break;

		case "week":
			// --- OF THE WEEK --- //
			$str = "SELECT year, month, week, total_income, total_expense
							FROM cashflow_weekly
							ORDER BY year DESC,
								month DESC,
								week DESC;";
			$query = mysqli_query($con, $str)
				or die ("Error: could not send LAST query, " . mysqli_error($con));
			$result = mysqli_fetch_all($query, MYSQLI_ASSOC)
				or die ("Error: could not fetch LAST data, " . mysqli_error($con));

			if($result) {
				for($i = 0; @$result[$i]; $i++) {
					echo "<tr>
									<td class='ov-td-key'>{$result[$i]['year']}</td>
									<td class='ov-td-key'>{$month_list[$result[$i]['month'] - 1]}</td>
									<td class='ov-td-key'>{$result[$i]['week']}</td>
									<td class='ov-td-value plus'>{$result[$i]['total_income']}</td>
									<td class='ov-td-value minus'>{$result[$i]['total_expense']}</td>
								</tr>";
				}
			}

			mysqli_close($con);
			
			break;

		case "day":
			// --- OF THE DAY --- //
			$str = "SELECT year, month, day, total_income, total_expense
							FROM cashflow_daily
							ORDER BY year DESC,
								month DESC,
								day DESC;";
			$query = mysqli_query($con, $str)
				or die ("Error: could not send LAST query, " . mysqli_error($con));
			$result = mysqli_fetch_all($query, MYSQLI_ASSOC)
				or die ("Error: could not fetch LAST data, " . mysqli_error($con));

			if($result) {
				for($i = 0; @$result[$i]; $i++) {
					echo "<tr>
									<td class='ov-td-key'>{$result[$i]['year']}</td>
									<td class='ov-td-key'>{$month_list[$result[$i]['month'] - 1]}</td>
									<td class='ov-td-key'>{$result[$i]['day']}</td>
									<td class='ov-td-value plus'>{$result[$i]['total_income']}</td>
									<td class='ov-td-value minus'>{$result[$i]['total_expense']}</td>
								</tr>";
				}
			}

			mysqli_close($con);
			
	}

?>