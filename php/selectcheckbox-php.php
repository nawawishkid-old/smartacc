<?php
	$con = mysqli_connect("localhost", "root", "", "smartacc")
		or die ("Error: Could not connect to database, " . mysqli_error($con));
	mysqli_query($con, "SET character_set_results=utf8")
		or die ("Error: Could not set character set results, " . mysqli_error($con));
	mysqli_query($con, "SET character_set_client=utf8")
		or die ("Error: Could not set character set client, " . mysqli_error($con));
	mysqli_query($con, "SET character_set_connection=utf8")
		or die ("Error: Could not set character set connection, " . mysqli_error($con));

	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$filter = $_POST["ovCashflowFilterSelect"];

		for($i = 0; @$_POST["ovCashflowThForm" . ($i + 1) . "Checkbox"]; $i++) {
			$x = $_POST["ovCashflowThForm" . ($i + 1) . "Checkbox"];

			switch ($i) {
				case 0:
					for($j = 0; @$x[$j]; $j++) {
						if($j > 0) {
							$year .= ", {$x[$j]}";
						} else {
							$year = $x[$j];
						}
					}
					break;

				case 1:
					for($j = 0; @$x[$j]; $j++) {
						if($j > 0) {
							$month .= ", {$x[$j]}";
						} else {
							$month = $x[$j];
						}
					}
					break;

				case 2:
					for($j = 0; @$x[$j]; $j++) {
						if($j > 0) {
							$week .= ", {$x[$j]}";
						} else {
							$week = $x[$j];
						}
					}
					break;

				case 3:
					for($j = 0; @$x[$j]; $j++) {
						if($j > 0) {
							$day .= ", {$x[$j]}";
						} else {
							$day = $x[$j];
						}
					}
			}
		}
	}

	$month_list = array("January", "February", "March", 
									"April", "May", "June", "July",
									"August", "September", "October",
									"November", "December");

	switch ($filter) {
		case "year":
			// --- SHOW RESULT IN DATA TABLE --- //
			$str = "SELECT year, total_income, total_expense
							FROM cashflow_yearly
							WHERE year IN ({$year})
							ORDER BY year DESC;";

			$query = mysqli_query($con, $str)
				or die ("Error: could not send query YEAR, " . mysqli_error($con));
			$result = mysqli_fetch_all($query, MYSQLI_ASSOC)
				or die ("Error: could not fetch YEAR, " . mysqli_error($con));

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
			$str = "SELECT year, month, total_income, total_expense
							FROM cashflow_monthly
							WHERE year IN ({$year}) AND month IN ({$month})
							ORDER BY year DESC,
								month DESC;";

			$query = mysqli_query($con, $str)
				or die ("Error: could not send query MONTH, " . mysqli_error($con));
			$result = mysqli_fetch_all($query, MYSQLI_ASSOC)
				or die ("Error: could not fetch MONTH, " . mysqli_error($con));

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

			// --- SET INPUT CHECKBOX IN TABLE HEAD --- //
			/*$str = "SELECT month FROM cashflow_monthly WHERE year IN ({$year});";
			$query = mysqli_query($con, $str)
				or die ("Error: could not send query MONTH for creating input[type=checkbox], " . mysqli_error($con));
			$result = mysqli_fetch_all($query, MYSQLI_ASSOC)
				or die ("Error: could not fetch MONTH for creating input[type=checkbox], " . mysqli_error($con));

			if($result) {
				for($i = 0; @$result[$i]; $i++) {
					$x = ($i + 1);
					$month = $month_list[$result[$i]['month'] - 1];
					@$checkbox_month .= "<label class='ov-cashflow-th-checkbox-label' for='ovCashflowThForm2Checkbox{$x}' data-set='2'>
							    							<input type='checkbox' name='ovCashflowThForm2Checkbox[]' id='ovCashflowThForm2Checkbox{$x}' class='ov-cashflow-th-checkbox-input-2' form='ovCashflowThForm' value='{$result[$i]['month']}' data-set='2' checked>{$month}
							    						</label>";
				}
				echo $checkbox_month;
			}*/

			mysqli_close($con);
			break;

		case "week":
			$str = "SELECT year, month, week, total_income, total_expense
							FROM cashflow_weekly
							WHERE year IN ({$year})
							AND month IN ({$month})
							AND week IN ({$week})
							ORDER BY year DESC,
								month DESC,
								week DESC;";

			$query = mysqli_query($con, $str)
				or die ("Error: could not send query WEEK, " . mysqli_error($con));
			$result = mysqli_fetch_all($query, MYSQLI_ASSOC)
				or die ("Error: could not fetch WEEK, " . mysqli_error($con));

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
			$str = "SELECT year, month, day, total_income, total_expense
							FROM cashflow_daily
							WHERE year IN ({$year})
							AND month IN ({$month})
							AND day IN ({$day})
							ORDER BY year DESC,
								month DESC,
								day DESC;";

			$query = mysqli_query($con, $str)
				or die ("Error: could not send query DAY, " . mysqli_error($con));
			$result = mysqli_fetch_all($query, MYSQLI_ASSOC)
				or die ("Error: could not fetch DAY, " . mysqli_error($con));

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